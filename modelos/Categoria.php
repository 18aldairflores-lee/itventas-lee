<?php 
// Incluimos la conexión a la base de datos
require "../config/Conexion.php";

Class Categoria
{
    // Constructor
    public function __construct()
    {
    }

    // Método para insertar registros
    public function insertar($nombre, $descripcion)
    {
        $sql = "INSERT INTO categoria (nombre, descripcion, condicion)
                VALUES ('$nombre', '$descripcion', '1')";
        return ejecutarConsulta($sql);
    }

    // Método para editar registros
    public function editar($idcategoria, $nombre, $descripcion)
    {
        $sql = "UPDATE categoria 
                SET nombre='$nombre', descripcion='$descripcion' 
                WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }

    // Método para desactivar categorías
    public function desactivar($idcategoria)
    {
        $sql = "UPDATE categoria 
                SET condicion='0' 
                WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }

    // Método para activar categorías
    public function activar($idcategoria)
    {
        $sql = "UPDATE categoria 
                SET condicion='1' 
                WHERE idcategoria='$idcategoria'";
        return ejecutarConsulta($sql);
    }

    // Método para mostrar los datos de un registro a modificar
    public function mostrar($idcategoria)
    {
        $sql = "SELECT * FROM categoria 
                WHERE idcategoria='$idcategoria'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Método para listar los registros
    public function listar()
    {
        $sql = "SELECT * FROM categoria ORDER BY idcategoria DESC";
        return ejecutarConsulta($sql);      
    }

    // Método para listar los registros y mostrar en el select
    public function select()
    {
        $sql = "SELECT * FROM categoria 
                WHERE condicion=1";
        return ejecutarConsulta($sql);      
    }
}
?>