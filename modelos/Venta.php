<?php 
require "../config/Conexion.php";

Class Usuario
{
    public function __construct() {}

    // Insertar usuario junto con sus permisos asignados
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

    // Editar usuario y actualizar permisos
    public function editar($idusuario, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email, $cargo, $login, $clave, $imagen, $permisos)
    {
        $sql = "UPDATE usuario 
                SET nombre='$nombre', tipo_documento='$tipo_documento', num_documento='$num_documento', direccion='$direccion', telefono='$telefono', email='$email', cargo='$cargo', login='$login', clave='$clave', imagen='$imagen' 
                WHERE idusuario='$idusuario'";
        ejecutarConsulta($sql);

        // Eliminar permisos previos y reinsertar los nuevos
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

    public function desactivar($idusuario)
    {
        $sql = "UPDATE usuario SET condicion='0' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    public function activar($idusuario)
    {
        $sql = "UPDATE usuario SET condicion='1' WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idusuario)
    {
        $sql = "SELECT * FROM usuario WHERE idusuario='$idusuario'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT * FROM usuario ORDER BY idusuario DESC";
        return ejecutarConsulta($sql);
    }

    // Listar permisos activos asignados al usuario para marcarlos en el formulario
    public function listarmarcados($idusuario)
    {
        $sql = "SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
        return ejecutarConsulta($sql);
    }

    // Verificación de credenciales para login
    public function verificar($login, $clave)
    {
        $sql = "SELECT idusuario, nombre, tipo_documento, num_documento, telefono, email, cargo, imagen, login 
                FROM usuario 
                WHERE login='$login' AND clave='$clave' AND condicion='1'";
        return ejecutarConsulta($sql);
    }
}
?>