<?php 
require_once "../modelos/Persona.php";

$persona = new Persona();

$idpersona = isset($_POST["idpersona"]) ? limpiarCadena($_POST["idpersona"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
$num_documento = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idpersona)) {
            $rspta = $persona->insertar("Proveedor", $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email);
            echo $rspta ? "Proveedor registrado con éxito" : "No se pudo registrar al proveedor";
        } else {
            $rspta = $persona->editar($idpersona, "Proveedor", $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email);
            echo $rspta ? "Proveedor actualizado con éxito" : "No se pudo actualizar al proveedor";
        }
        break;

    case 'eliminar':
        $rspta = $persona->eliminar($idpersona);
        echo $rspta ? "Proveedor eliminado correctamente" : "No se pudo eliminar al proveedor (posiblemente vinculado a compras)";
        break;

    case 'mostrar':
        $rspta = $persona->mostrar($idpersona);
        echo json_encode($rspta);
        break;

    case 'listarp':
        $rspta = $persona->listarp();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => '<button class="action-circle-btn btn-edit" onclick="mostrar('.$reg->idpersona.')" title="Editar"><i class="fa fa-pencil"></i></button> '.
                       '<button class="action-circle-btn btn-deactivate" onclick="eliminar('.$reg->idpersona.')" title="Eliminar"><i class="fa fa-trash"></i></button>',
                "1" => '<strong>'.$reg->nombre.'</strong>',
                "2" => '<span class="badge-doc">'.$reg->tipo_documento.': '.$reg->num_documento.'</span>',
                "3" => !empty($reg->telefono) ? '<i class="fa fa-phone text-muted" style="margin-right:5px;"></i>'.$reg->telefono : '<span style="color:#94a3b8;">Sin teléfono</span>',
                "4" => !empty($reg->email) ? '<i class="fa fa-envelope-o text-muted" style="margin-right:5px;"></i>'.$reg->email : '<span style="color:#94a3b8;">Sin email</span>',
                "5" => !empty($reg->direccion) ? $reg->direccion : '<span style="color:#94a3b8; font-style:italic;">Sin dirección</span>'
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