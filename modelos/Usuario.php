<?php 
require "../config/Conexion.php";

Class Usuario
{
    public function __construct() {}

    // Función para validar credenciales en el login
    public function verificar($login, $clave)
    {
        $sql = "SELECT idusuario, nombre, tipo_documento, num_documento, telefono, email, cargo, imagen, login 
                FROM usuario 
                WHERE login='$login' AND clave='$clave' AND condicion='1'";
        return ejecutarConsulta($sql);
    }
}
?>