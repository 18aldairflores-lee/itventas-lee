<?php 
require "../config/Conexion.php";

Class Permiso
{
    public function __construct() {}

    // Listar todos los permisos del sistema
    public function listar()
    {
        $sql = "SELECT * FROM permiso ORDER BY idpermiso ASC";
        return ejecutarConsulta($sql);
    }
}
?>