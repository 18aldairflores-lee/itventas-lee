<?php 
session_start();
require_once "../modelos/Usuario.php";

$usuario = new Usuario();

$logina = isset($_POST["logina"]) ? limpiarCadena($_POST["logina"]) : "";
$clavea = isset($_POST["clavea"]) ? limpiarCadena($_POST["clavea"]) : "";

switch ($_GET["op"]) {
    case 'verificar':
        // Encriptar la contraseña ingresada en SHA256
        $clavehash = hash("SHA256", $clavea);

        $rspta = $usuario->verificar($logina, $clavehash);
        $fetch = $rspta->fetch_object();

        if (isset($fetch)) {
            // Guardar variables de sesión del usuario
            $_SESSION['idusuario'] = $fetch->idusuario;
            $_SESSION['nombre'] = $fetch->nombre;
            $_SESSION['imagen'] = $fetch->imagen;
            $_SESSION['login'] = $fetch->login;
            $_SESSION['cargo'] = $fetch->cargo;
        }

        echo json_encode($fetch);
        break;

    case 'salir':
        // Limpiar todas las sesiones
        session_unset();
        session_destroy();
        header("Location: ../vistas/login.php");
        break;
}
?>