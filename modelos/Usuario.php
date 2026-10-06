<?php 
require "../config/Conexion.php";

Class Usuario
{
    public function __construct() {}

    // Insertar un nuevo usuario con permisos
    public function insertar($nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos)
    {
        $sql = "INSERT INTO usuario (nombre, tipo_documento, num_documento, direccion, telefono, email, cargo, login, clave, imagen, condicion) 
                VALUES ('$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$email', '$cargo', '$login', '$clave', '$imagen', '1')";
        $idusuarionew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;

        if (!empty($permisos)) {
            while ($num_elementos < count($permisos)) {
                $sql_detalle = "INSERT INTO usuario_permiso (idusuario, idpermiso) VALUES ('$idusuarionew', '$permisos[$num_elementos]')";
                ejecutarConsulta($sql_detalle) or $sw = false;
                $num_elementos++;
            }
        }

        return $sw;
    }

    // Editar datos y actualizar permisos
    public function editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos)
    {
        $sql = "UPDATE usuario 
                SET nombre='$nombre', tipo_documento='$tipo_documento', num_documento='$num_documento', direccion='$direccion', telefono='$telefono', email='$email', cargo='$cargo', login='$login', clave='$clave', imagen='$imagen' 
                WHERE idusuario='$idusuario'";
        ejecutarConsulta($sql);

        // Eliminar permisos anteriores y reinsertar los seleccionados
        $sqldel = "DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
        ejecutarConsulta($sqldel);

        $num_elementos = 0;
        $sw = true;

        if (!empty($permisos)) {
            while ($num_elementos < count($permisos)) {
                $sql_detalle = "INSERT INTO usuario_permiso (idusuario, idpermiso) VALUES ('$idusuario', '$permisos[$num_elementos]')";
                ejecutarConsulta($sql_detalle) or $sw = false;
                $num_elementos++;
            }
        }

        return $sw;
    }

    // Desactivar usuario
    public function desactivar($idusuario)
    {
        $sql = "UPDATE usuario SET condicion='0' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    // Activar usuario
    public function activar($idusuario)
    {
        $sql = "UPDATE usuario SET condicion='1' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    // Mostrar un usuario específico
    public function mostrar($idusuario)
    {
        $sql = "SELECT * FROM usuario WHERE idusuario='$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Listar todos los usuarios (Método requerido)
    public function listar()
    {
        $sql = "SELECT * FROM usuario ORDER BY idusuario DESC";
        return ejecutarConsulta($sql);
    }

    // Listar permisos marcados que ya tiene asignados el usuario
    public function listarmarcados($idusuario)
    {
        $sql = "SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    // Verificar credenciales para el inicio de sesión
    public function verificar($login, $clave)
    {
        $sql = "SELECT idusuario, nombre, tipo_documento, num_documento, telefono, email, cargo, imagen, login 
                FROM usuario 
                WHERE login='$login' AND clave='$clave' AND condicion='1'";
        return ejecutarConsulta($sql);
    }
}
?>