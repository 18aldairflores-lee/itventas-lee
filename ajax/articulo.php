<?php 
require_once "../modelos/Articulo.php";

$articulo = new Articulo();

$idarticulo = isset($_POST["idarticulo"]) ? limpiarCadena($_POST["idarticulo"]) : "";
$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$codigo = isset($_POST["codigo"]) ? limpiarCadena($_POST["codigo"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$stock = isset($_POST["stock"]) ? limpiarCadena($_POST["stock"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $imagen = $_POST["imagenactual"];
        } else {
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
                $imagen = round(microtime(true)) . '.' . end($ext);
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/articulos/" . $imagen);
            }
        }

        if (empty($idarticulo)) {
            $rspta = $articulo->insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen);
            echo $rspta ? "Artículo registrado con éxito" : "No se pudo registrar el artículo";
        } else {
            $rspta = $articulo->editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen);
            echo $rspta ? "Artículo actualizado con éxito" : "No se pudo actualizar el artículo";
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
            $data[] = array(
                "0" => ($reg->condicion) ? 
                    '<button class="action-circle-btn btn-edit" onclick="mostrar('.$reg->idarticulo.')" title="Editar"><i class="fa fa-pencil"></i></button> '.
                    '<button class="action-circle-btn btn-deactivate" onclick="desactivar('.$reg->idarticulo.')" title="Desactivar"><i class="fa fa-trash"></i></button>' :
                    '<button class="action-circle-btn btn-edit" onclick="mostrar('.$reg->idarticulo.')" title="Editar"><i class="fa fa-pencil"></i></button> '.
                    '<button class="action-circle-btn btn-activate" onclick="activar('.$reg->idarticulo.')" title="Activar"><i class="fa fa-check"></i></button>',
                "1" => '<strong>'.$reg->nombre.'</strong>',
                "2" => '<span class="badge-cat">'.$reg->categoria.'</span>',
                "3" => !empty($reg->codigo) ? '<code class="barcode-badge">'.$reg->codigo.'</code>' : '<span style="color:#94a3b8;">S/C</span>',
                "4" => ($reg->stock > 5) ? '<span class="stock-badge stock-ok">'.$reg->stock.'</span>' : '<span class="stock-badge stock-low">'.$reg->stock.'</span>',
                "5" => "<img src='../files/articulos/".$reg->imagen."' class='img-table' onerror=\"this.src='../public/img/logo1.png';\" />",
                "6" => ($reg->condicion) ? 
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

    case "selectCategoria":
        require_once "../modelos/Categoria.php";
        $categoria = new Categoria();
        $rspta = $categoria->select();

        while ($reg = $rspta->fetch_object()) {
            echo '<option value="' . $reg->idcategoria . '">' . $reg->nombre . '</option>';
        }
        break;
}
?>