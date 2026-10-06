<?php 
require "../config/Conexion.php";

Class Consultas
{
    public function __construct() {}

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

    public function totalClientes()
    {
        $sql = "SELECT COUNT(idpersona) as total_clientes FROM persona WHERE tipo_persona='Cliente'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function totalArticulos()
    {
        $sql = "SELECT COUNT(idarticulo) as total_articulos FROM articulo WHERE condicion='1'";
        return ejecutarConsultaSimpleFila($sql);
    }

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

    // Consulta de Ventas filtrada por rango de fechas y/o cliente
    public function ventasFechaCliente($fecha_inicio, $fecha_fin, $idcliente)
    {
        $filtro_cliente = (!empty($idcliente)) ? " AND v.idcliente='$idcliente' " : "";

        $sql = "SELECT DATE(v.fecha_hora) as fecha, u.nombre as usuario, p.nombre as cliente, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, v.total_venta, v.impuesto, v.estado 
                FROM venta v 
                INNER JOIN persona p ON v.idcliente = p.idpersona 
                INNER JOIN usuario u ON v.idusuario = u.idusuario 
                WHERE DATE(v.fecha_hora) >= '$fecha_inicio' AND DATE(v.fecha_hora) <= '$fecha_fin' $filtro_cliente 
                ORDER BY v.idventa DESC";
        return ejecutarConsulta($sql);
    }

    // Consulta de Compras filtrada por rango de fechas
    public function comprasFecha($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT DATE(i.fecha_hora) as fecha, u.nombre as usuario, p.nombre as proveedor, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.total_compra, i.impuesto, i.estado 
                FROM ingreso i 
                INNER JOIN persona p ON i.idproveedor = p.idpersona 
                INNER JOIN usuario u ON i.idusuario = u.idusuario 
                WHERE DATE(i.fecha_hora) >= '$fecha_inicio' AND DATE(i.fecha_hora) <= '$fecha_fin' 
                ORDER BY i.idingreso DESC";
        return ejecutarConsulta($sql);
    }
}
?>