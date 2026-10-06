<?php 
if (strlen(session_id()) < 1) {
    session_start();
}

require_once "../modelos/Usuario.php";

$usuario = new Usuario();

$idusuario = isset($_POST["idusuario"]) ? limpiarCadena($_POST["idusuario"]) : "";
$nombre = isset($_POST["nombre"]) ? limpiarCadena($_POST["nombre"]) : "";
$tipo_documento = isset($_POST["tipo_documento"]) ? limpiarCadena($_POST["tipo_documento"]) : "";
$num_documento = isset($_POST["num_documento"]) ? limpiarCadena($_POST["num_documento"]) : "";
$direccion = isset($_POST["direccion"]) ? limpiarCadena($_POST["direccion"]) : "";
$telefono = isset($_POST["telefono"]) ? limpiarCadena($_POST["telefono"]) : "";
$email = isset($_POST["email"]) ? limpiarCadena($_POST["email"]) : "";
$cargo = isset($_POST["cargo"]) ? limpiarCadena($_POST["cargo"]) : "";
$login = isset($_POST["login"]) ? limpiarCadena($_POST["login"]) : "";
$clave = isset($_POST["clave"]) ? limpiarCadena($_POST["clave"]) : "";
$imagen = isset($_POST["imagen"]) ? limpiarCadena($_POST["imagen"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (!file_exists($_FILES['imagen']['tmp_name']) || !is_uploaded_file($_FILES['imagen']['tmp_name'])) {
            $imagen = isset($_POST["imagenactual"]) ? $_POST["imagenactual"] : "";
        } else {
            $ext = explode(".", $_FILES["imagen"]["name"]);
            if ($_FILES['imagen']['type'] == "image/jpg" || $_FILES['imagen']['type'] == "image/jpeg" || $_FILES['imagen']['type'] == "image/png") {
                $imagen = round(microtime(true)) . '.' . end($ext);
                if (!file_exists("../files/usuarios/")) {
                    mkdir("../files/usuarios/", 0777, true);
                }
                move_uploaded_file($_FILES["imagen"]["tmp_name"], "../files/usuarios/" . $imagen);
            }
        }

        if (!empty($clave)) {
            $clavehash = hash("SHA256", $clave);
        } else {
            $clavehash = isset($_POST["claveactual"]) ? $_POST["claveactual"] : "";
        }

        $permisos = isset($_POST['permiso']) ? $_POST['permiso'] : array();

        if (empty($idusuario)) {
            $rspta = $usuario->insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $permisos);
            echo $rspta ? "Usuario registrado exitosamente" : "No se pudo registrar el usuario";
        } else {
            $rspta = $usuario->editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clavehash, $imagen, $permisos);
            echo $rspta ? "Usuario actualizado exitosamente" : "No se pudo actualizar el usuario";
        }
        break;

    case 'desactivar':
        $rspta = $usuario->desactivar($idusuario);
        echo $rspta ? "Usuario desactivado" : "No se pudo desactivar el usuario";
        break;

    case 'activar':
        $rspta = $usuario->activar($idusuario);
        echo $rspta ? "Usuario activado" : "No se pudo activar el usuario";
        break;

    case 'mostrar':
        $rspta = $usuario->mostrar($idusuario);
        echo json_encode($rspta);
        break;

    case 'listar':
        $rspta = $usuario->listar();
        $data = Array();

        if ($rspta) {
            while ($reg = $rspta->fetch_object()) {
                $foto_mostrar = !empty($reg->imagen) ? $reg->imagen : 'defecto.png';

                $data[] = array(
                    "0" => ($reg->condicion) ? 
                        '<button class="action-circle-btn btn-edit" onclick="mostrar('.$reg->idusuario.')" title="Editar"><i class="fa fa-pencil"></i></button> '.
                        '<button class="action-circle-btn btn-deactivate" onclick="desactivar('.$reg->idusuario.')" title="Desactivar"><i class="fa fa-trash"></i></button>' :
                        '<button class="action-circle-btn btn-edit" onclick="mostrar('.$reg->idusuario.')" title="Editar"><i class="fa fa-pencil"></i></button> '.
                        '<button class="action-circle-btn btn-activate" onclick="activar('.$reg->idusuario.')" title="Activar"><i class="fa fa-check"></i></button>',
                    "1" => '<strong>'.$reg->nombre.'</strong>',
                    "2" => '<span class="badge-cat">'.(!empty($reg->cargo) ? $reg->cargo : 'General').'</span>',
                    "3" => '<code class="barcode-badge">'.$reg->login.'</code>',
                    "4" => !empty($reg->telefono) ? $reg->telefono : '<span style="color:#94a3b8;">S/N</span>',
                    "5" => "<img src='../files/usuarios/".$foto_mostrar."' class='img-table' onerror=\"this.src='../public/img/logo1.png';\" />",
                    "6" => ($reg->condicion) ? 
                        '<span class="badge-pill-modern badge-pill-success"><span class="dot-indicator"></span> Activo</span>' : 
                        '<span class="badge-pill-modern badge-pill-danger"><span class="dot-indicator"></span> Inactivo</span>'
                );
            }
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'permisos':
        require_once "../modelos/Permiso.php";
        $permiso = new Permiso();
        $rspta = $permiso->listar();

        $id = isset($_GET['id']) ? $_GET['id'] : "";
        $valores = array();

        if (!empty($id)) {
            $marcados = $usuario->listarmarcados($id);
            while ($per = $marcados->fetch_object()) {
                array_push($valores, $per->idpermiso);
            }
        }

        if ($rspta) {
            while ($reg = $rspta->fetch_object()) {
                $sw = in_array($reg->idpermiso, $valores) ? 'checked' : '';
                echo '<li style="list-style:none; margin-bottom:8px;">
                        <label style="cursor:pointer; font-weight:500;">
                            <input type="checkbox" ' . $sw . ' name="permiso[]" value="' . $reg->idpermiso . '" style="margin-right:8px;">' . $reg->nombre . '
                        </label>
                      </li>';
            }
        }
        break;

    case 'verificar':
        $logina = $_POST['logina'];
        $clavea = $_POST['clavea'];

        $clavehash = hash("SHA256", $clavea);
        $rspta = $usuario->verificar($logina, $clavehash);
        $fetch = $rspta->fetch_object();

        if (isset($fetch)) {
            $_SESSION['idusuario'] = $fetch->idusuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['login'] = $fetch->login;

            // Obtener permisos marcados del usuario
            $marcados = $usuario->listarmarcados($fetch->idusuario);
            $valores = array();

            while ($per = $marcados->fetch_object()) {
                array_push($valores, $per->idpermiso);
            }

            // Asignar variables de sesión según los permisos asignados
            in_array(1, $valores) ? $_SESSION['escritorio'] = 1 : $_SESSION['escritorio'] = 0;
            in_array(2, $valores) ? $_SESSION['almacen'] = 1 : $_SESSION['almacen'] = 0;
            in_array(3, $valores) ? $_SESSION['compras'] = 1 : $_SESSION['compras'] = 0;
            in_array(4, $valores) ? $_SESSION['ventas'] = 1 : $_SESSION['ventas'] = 0;
            in_array(5, $valores) ? $_SESSION['acceso'] = 1 : $_SESSION['acceso'] = 0;
            in_array(6, $valores) ? $_SESSION['consultac'] = 1 : $_SESSION['consultac'] = 0;
            in_array(7, $valores) ? $_SESSION['consultav'] = 1 : $_SESSION['consultav'] = 0;
        }
        echo json_encode($fetch);
        break;

    case 'salir':
        // Limpiar todas las variables de sesión
        $_SESSION = array();

        // Destruir la cookie de sesión si existe
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }

        // Destruir la sesión
        session_destroy();

        // Redirigir de inmediato al login
        header("Location: ../vistas/login.php");
        exit();
        break;
}
?>