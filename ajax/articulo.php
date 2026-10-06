<?php 
require_once "../modelos/Articulo.php";

$articulo = new Articulo();

$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$stock = isset($_POST["stock"]) ? limpiarCadena($_POST["stock"]) : "0";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (!isset($_FILES['imagen']['tmp_name']) || !file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $imagen = isset($_POST["imagenactual"]) ? $_POST["imagenactual"] : "";
        } else {
            $ext = explode(".", $_FILES["imagen"]["name"]);
            $extension = strtolower(end($ext));
            if (in_array($extension, ["jpg", "jpeg", "png", "webp"])) {
                $imagen = round(microtime(true)) . '.' . $extension;
                if (!file_exists("../files/articulos/")) {
                    mkdir("../files/articulos/", 0777, true);
                }
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" . $imagen);
            }
        }

        if (empty($codigo)) {
            $codigo = "ART-" . time();
        }

        if (empty($idarticulo)) {
            $rspta = $articulo->insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen);
            echo $rspta ? "Artículo registrado exitosamente" : "No se pudo registrar el artículo en la base de datos";
        } else {
            $rspta = $articulo->editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen);
            echo $rspta ? "Artículo actualizado exitosamente" : "No se pudo actualizar el artículo";
        }
        break;

    case 'desactivar':
        $rspta = $articulo->desactivar($idarticulo);
        echo $rspta ? "Artículo desactivado" : "No se pudo desactivar el artículo";
        break;

    case 'activar':
        $rspta = $articulo->activar($idarticulo);
        echo $rspta ? "Artículo activado" : "No se pudo activar el artículo";
        break;

    case 'mostrar':
        $rspta = $articulo->mostrar($idarticulo);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $articulo->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $ruta_fisica = "../files/articulos/" . $reg->imagen;
            if (!empty($reg->imagen) && file_exists($ruta_fisica)) {
                $html_img = "<img src='../files/articulos/".$reg->imagen."' width='38px' height='38px' style='border-radius:8px; object-fit:cover; border:1px solid #e2e8f0;'>";
            } else {
                $html_img = "<span class='badge' style='background:#f1f5f9; color:#64748b; font-size:11px; padding:6px 8px; border-radius:6px;'><i class='fa fa-cube'></i> Sin foto</span>";
            }

            $data[] = array(
                "0" => ($reg->condicion == 1) ? 
                    '<button class="action-circle-btn btn-edit" onclick="mostrar('.$reg->idarticulo.')" title="Editar"><i class="fa fa-pencil"></i></button> '.
                    '<button class="action-circle-btn btn-deactivate" onclick="desactivar('.$reg->idarticulo.')" title="Desactivar"><i class="fa fa-trash"></i></button>' :
                    '<button class="action-circle-btn btn-edit" onclick="mostrar('.$reg->idarticulo.')" title="Editar"><i class="fa fa-pencil"></i></button> '.
                    '<button class="action-circle-btn btn-activate" onclick="activar('.$reg->idarticulo.')" title="Activar"><i class="fa fa-check"></i></button>',
                "1" => '<strong>'.$reg->nombre.'</strong>',
                "2" => '<span class="badge-cat">'.$reg->categoria.'</span>',
                "3" => '<code class="barcode-badge">'.$reg->codigo.'</code>',
                "4" => ($reg->stock > 5) ? '<span class="stock-badge stock-ok">'.$reg->stock.'</span>' : '<span class="stock-badge stock-low">'.$reg->stock.'</span>',
                "5" => $html_img,
                "6" => ($reg->condicion == 1) ? 
                    '<span class="badge-pill-modern badge-pill-success"><span class="dot-indicator"></span> Activo</span>' : 
                    '<span class="badge-pill-modern badge-pill-danger"><span class="dot-indicator"></span> Inactivo</span>'
            );
        }
        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'selectCategoria':
        require_once "../modelos/Categoria.php";
        $categoria = new Categoria();
        $rspta = $categoria->select();
        echo '<option value="">-- Seleccione una Categoría --</option>';
        if ($rspta) {
            while ($reg = $rspta->fetch_object()) {
                echo '<option value="' . $reg->idcategoria . '">' . $reg->nombre . '</option>';
            }
        }
        break;

    case 'kpisArticulo':
        require_once "../config/Conexion.php";

        // Total de artículos
        $sql_total = "SELECT COUNT(*) as total FROM articulo";
        $res_total = ejecutarConsultaSimpleFila($sql_total);

        // Total de unidades en stock
        $sql_stock = "SELECT IFNULL(SUM(stock), 0) as total_stock FROM articulo WHERE condicion = 1";
        $res_stock = ejecutarConsultaSimpleFila($sql_stock);

        // Artículos con stock bajo (menor o igual a 5)
        $sql_bajo = "SELECT COUNT(*) as total_bajo FROM articulo WHERE stock <= 5 AND condicion = 1";
        $res_bajo = ejecutarConsultaSimpleFila($sql_bajo);

        $data = array(
            "total" => $res_total["total"],
            "stock" => $res_stock["total_stock"],
            "bajo" => $res_bajo["total_bajo"]
        );
        echo json_encode($data);
        break;

    case 'alertaStock':
        require_once "../config/Conexion.php";
        $sql = "SELECT idarticulo, nombre, stock FROM articulo WHERE condicion='1' AND stock <= 5 ORDER BY stock ASC LIMIT 6";
        $rspta = ejecutarConsulta($sql);
        $data = array();
        while ($reg = $rspta->fetch_object()) {
            $data[] = $reg;
        }
        echo json_encode($data);
        break;
}
?>