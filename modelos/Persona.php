<?php 
require "../config/Conexion.php";

Class Persona
{
    public function __construct() {}

    public function insertar($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
    {
        $sql = "INSERT INTO persona (tipo_persona, nombre, tipo_documento, num_documento, direccion, telefono, email) 
                VALUES ('$tipo_persona', '$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$email')";
        return ejecutarConsulta($sql);
    }

    public function editar($idpersona, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
    {
        $sql = "UPDATE persona 
                SET tipo_persona='$tipo_persona', nombre='$nombre', tipo_documento='$tipo_documento', num_documento='$num_documento', direccion='$direccion', telefono='$telefono', email='$email' 
                WHERE idpersona='$idpersona'";
        return ejecutarConsulta($sql);
    }

    public function eliminar($idpersona)
    {
        $sql = "DELETE FROM persona WHERE idpersona='$idpersona'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idpersona)
    {
        $sql = "SELECT * FROM persona WHERE idpersona='$idpersona'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listarp()
    {
        $sql = "SELECT * FROM persona WHERE LOWER(tipo_persona)='proveedor' ORDER BY idpersona DESC";
        return ejecutarConsulta($sql);
    }

    public function listarc()
    {
        $sql = "SELECT * FROM persona WHERE LOWER(tipo_persona)='cliente' ORDER BY idpersona DESC";
        return ejecutarConsulta($sql);
    }
}
?>