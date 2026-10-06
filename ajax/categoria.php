<?php 
require_once "../modelos/Categoria.php";

$categoria = new Categoria();

$idcategoria = isset($_POST["idcategoria"]) ? limpiarCadena($_POST["idcategoria"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$descripcion = isset($_POST["descripcion"]) ? limpiarCadena($_POST["descripcion"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idcategoria)) {
            $rspta = $categoria->insertar($nombre, $descripcion);
            echo $rspta ? "Categoría registrada con éxito" : "No se pudo registrar la categoría";
        } else {
            $rspta = $categoria->editar($idcategoria, $nombre, $descripcion);
            echo $rspta ? "Categoría actualizada con éxito" : "No se pudo actualizar la categoría";
        }
        break;

    case 'desactivar':
        $rspta = $categoria->desactivar($idcategoria);
        echo $rspta ? "Categoría desactivada" : "No se pudo desactivar la categoría";
        break;

    case 'activar':
        $rspta = $categoria->activar($idcategoria);
        echo $rspta ? "Categoría activada" : "No se pudo activar la categoría";
        break;

    case 'mostrar':
        $rspta = $categoria->mostrar($idcategoria);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $categoria->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->condicion) ? 
                    '<button class="btn btn-warning btn-xs" style="border-radius: 8px; padding: 5px 10px; margin-right: 5px;" onclick="mostrar('.$reg->idcategoria.')" title="Editar"><i class="fa fa-pencil"></i></button> '.
                    '<button class="btn btn-danger btn-xs" style="border-radius: 8px; padding: 5px 10px;" onclick="desactivar('.$reg->idcategoria.')" title="Desactivar"><i class="fa fa-trash"></i></button>' :
                    '<button class="btn btn-warning btn-xs" style="border-radius: 8px; padding: 5px 10px; margin-right: 5px;" onclick="mostrar('.$reg->idcategoria.')" title="Editar"><i class="fa fa-pencil"></i></button> '.
                    '<button class="btn btn-primary btn-xs" style="border-radius: 8px; padding: 5px 10px;" onclick="activar('.$reg->idcategoria.')" title="Activar"><i class="fa fa-check"></i></button>',
                "1" => '<strong>'.$reg->nombre.'</strong>',
                "2" => !empty($reg->descripcion) ? $reg->descripcion : '<span style="color: #94a3b8; font-style: italic;">Sin descripción</span>',
                "3" => ($reg->condicion) ? 
                    '<span class="badge-modern badge-active"><i class="fa fa-check"></i> Activo</span>' : 
                    '<span class="badge-modern badge-inactive"><i class="fa fa-times"></i> Inactivo</span>'
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
}
?>