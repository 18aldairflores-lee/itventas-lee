<?php 
require "../config/Conexion.php";

Class Articulo
{
    public function __construct() {}

    // Insertar un nuevo artículo garantizando tipos de datos válidos
    public function insertar($idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen)
    {
        // Si no seleccionó categoría, se asigna la categoría general (1)
        $idcategoria = (!empty($idcategoria) && is_numeric($idcategoria)) ? (int)$idcategoria : 1;
        
        // Si no tiene código de barras, se le genera uno único con timestamp
        $codigo = (!empty($codigo)) ? trim($codigo) : (string)time();
        
        $stock = (!empty($stock) && is_numeric($stock)) ? (int)$stock : 0;
        $descripcion = trim($descripcion);
        $imagen = trim($imagen);

        $sql = "INSERT INTO articulo (idcategoria, codigo, nombre, stock, descripcion, imagen, condicion) 
                VALUES ('$idcategoria', '$codigo', '$nombre', '$stock', '$descripcion', '$imagen', '1')";
        return ejecutarConsulta($sql);
    }

    // Editar artículo existente
    public function editar($idarticulo, $idcategoria, $codigo, $nombre, $stock, $descripcion, $imagen)
    {
        $idarticulo = (int)$idarticulo;
        $idcategoria = (!empty($idcategoria) && is_numeric($idcategoria)) ? (int)$idcategoria : 1;
        $codigo = (!empty($codigo)) ? trim($codigo) : (string)time();
        $stock = (!empty($stock) && is_numeric($stock)) ? (int)$stock : 0;
        $descripcion = trim($descripcion);

        // Si no subió imagen nueva, no sobreescribir con vacío
        if (empty($imagen)) {
            $sql = "UPDATE articulo 
                    SET idcategoria='$idcategoria', codigo='$codigo', nombre='$nombre', stock='$stock', descripcion='$descripcion' 
                    WHERE idarticulo='$idarticulo'";
        } else {
            $sql = "UPDATE articulo 
                    SET idcategoria='$idcategoria', codigo='$codigo', nombre='$nombre', stock='$stock', descripcion='$descripcion', imagen='$imagen' 
                    WHERE idarticulo='$idarticulo'";
        }
        return ejecutarConsulta($sql);
    }

    // Desactivar artículo (borrado lógico para no romper historial de ventas/compras)
    public function desactivar($idarticulo)
    {
        $idarticulo = (int)$idarticulo;
        $sql = "UPDATE articulo SET condicion='0' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    // Activar artículo
    public function activar($idarticulo)
    {
        $idarticulo = (int)$idarticulo;
        $sql = "UPDATE articulo SET condicion='1' WHERE idarticulo='$idarticulo'";
        return ejecutarConsulta($sql);
    }

    // Mostrar datos de un solo artículo
    public function mostrar($idarticulo)
    {
        $idarticulo = (int)$idarticulo;
        $sql = "SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Listar todos los artículos con el nombre de su categoría
    public function listar()
    {
        $sql = "SELECT a.idarticulo, a.idcategoria, IFNULL(c.nombre, 'Sin Categoría') as categoria, a.codigo, a.nombre, a.stock, a.descripcion, a.imagen, a.condicion 
                FROM articulo a 
                LEFT JOIN categoria c ON a.idcategoria = c.idcategoria 
                ORDER BY a.idarticulo DESC";
        return ejecutarConsulta($sql);
    }

    // Listar solo activos
    public function listarActivos()
    {
        $sql = "SELECT a.idarticulo, a.idcategoria, IFNULL(c.nombre, 'Sin Categoría') as categoria, a.codigo, a.nombre, a.stock, a.descripcion, a.imagen, a.condicion 
                FROM articulo a 
                LEFT JOIN categoria c ON a.idcategoria = c.idcategoria 
                WHERE a.condicion='1' 
                ORDER BY a.idarticulo DESC";
        return ejecutarConsulta($sql);
    }
}
?>