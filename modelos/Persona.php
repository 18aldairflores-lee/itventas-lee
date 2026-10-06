<?php 
require "../config/Conexion.php";

Class Persona
{
    public function __construct() {}

    // Insertar registro (Proveedor o Cliente)
    public function insertar($tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
    {
        $sql = "INSERT INTO persona (tipo_persona, nombre, tipo_documento, num_documento, direccion, telefono, email) 
                VALUES ('$tipo_persona', '$nombre', '$tipo_documento', '$num_documento', '$direccion', '$telefono', '$email')";
        return ejecutarConsulta($sql);
    }

    // Editar registro
    public function editar($idpersona, $tipo_persona, $nombre, $tipo_documento, $num_documento, $direccion, $telefono, $email)
    {
        $sql = "UPDATE persona 
                SET tipo_persona='$tipo_persona', nombre='$nombre', tipo_documento='$tipo_documento', num_documento='$num_documento', direccion='$direccion', telefono='$telefono', email='$email' 
                WHERE idpersona='$idpersona'";
        return ejecutarConsulta($sql);
    }

    // Eliminar registro
    public function eliminar($idpersona)
    {
        $sql = "DELETE FROM persona WHERE idpersona='$idpersona'";
        return ejecutarConsulta($sql);
    }

    // Mostrar un registro por ID
    public function mostrar($idpersona)
    {
        $sql = "SELECT * FROM persona WHERE idpersona='$idpersona'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Listar solo Proveedores
    public function listarp()
    {
        $sql = "SELECT * FROM persona WHERE tipo_persona='Proveedor' ORDER BY idpersona DESC";
        return ejecutarConsulta($sql);
    }

    // Listar solo Clientes
    public function listarc()
    {
        $sql = "SELECT * FROM persona WHERE tipo_persona='Cliente' ORDER BY idpersona DESC";
        return ejecutarConsulta($sql);
    }
}
?>