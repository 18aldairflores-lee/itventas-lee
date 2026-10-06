<?php 
require "../config/Conexion.php";

Class Consultas
{
    public function __construct() {}

    // Suma total de compras aceptadas
    public function totalCompraHoy()
    {
        $sql = "SELECT IFNULL(SUM(total_compra), 0) as total_compra FROM ingreso WHERE DATE(fecha_hora) = CURDATE() AND estado='Aceptado'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function totalCompra()
    {
        $sql = "SELECT IFNULL(SUM(total_compra), 0) as total_compra FROM ingreso WHERE estado='Aceptado'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Suma total de ventas aceptadas
    public function totalVentaHoy()
    {
        $sql = "SELECT IFNULL(SUM(total_venta), 0) as total_venta FROM venta WHERE DATE(fecha_hora) = CURDATE() AND estado='Aceptado'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function totalVenta()
    {
        $sql = "SELECT IFNULL(SUM(total_venta), 0) as total_venta FROM venta WHERE estado='Aceptado'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Cantidad total de clientes
    public function totalClientes()
    {
        $sql = "SELECT COUNT(idpersona) as total_clientes FROM persona WHERE tipo_persona='Cliente'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Cantidad total de artículos
    public function totalArticulos()
    {
        $sql = "SELECT COUNT(idarticulo) as total_articulos FROM articulo WHERE condicion='1'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Gráfico: Compras de los últimos 10 días
    public function comprasUltimos10Dias()
    {
        $sql = "SELECT DATE_FORMAT(fecha_hora, '%d/%m') as fecha, SUM(total_compra) as total 
                FROM ingreso 
                WHERE estado='Aceptado' 
                GROUP BY DATE(fecha_hora) 
                ORDER BY fecha_hora DESC 
                LIMIT 10";
        return ejecutarConsulta($sql);
    }

    // Gráfico: Ventas de los últimos 10 días
    public function ventasUltimos10Dias()
    {
        $sql = "SELECT DATE_FORMAT(fecha_hora, '%d/%m') as fecha, SUM(total_venta) as total 
                FROM venta 
                WHERE estado='Aceptado' 
                GROUP BY DATE(fecha_hora) 
                ORDER BY fecha_hora DESC 
                LIMIT 10";
        return ejecutarConsulta($sql);
    }
}
?>