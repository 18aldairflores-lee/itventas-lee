<?php 
require "../config/Conexion.php";

Class Consultas
{
    public function __construct() {}

    // Total de compras (Ingresos) acumuladas y aceptadas
    public function totalCompras()
    {
        $sql = "SELECT IFNULL(SUM(total_compra), 0) as total_compra FROM ingreso WHERE estado='Aceptado'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Total de ventas acumuladas y aceptadas
    public function totalVentas()
    {
        $sql = "SELECT IFNULL(SUM(total_venta), 0) as total_venta FROM venta WHERE estado='Aceptado'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Total de clientes registrados
    public function totalClientes()
    {
        $sql = "SELECT COUNT(idpersona) as total_clientes FROM persona WHERE LOWER(tipo_persona)='cliente'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Total de artículos en catálogo
    public function totalArticulos()
    {
        $sql = "SELECT COUNT(idarticulo) as total_articulos FROM articulo WHERE condicion='1'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Compras de los últimos 10 días
    public function comprasUltimos10Dias()
    {
        $sql = "SELECT DATE_FORMAT(fecha_hora, '%d/%m') as fecha, IFNULL(SUM(total_compra), 0) as total 
                FROM ingreso 
                WHERE estado='Aceptado' 
                GROUP BY DATE(fecha_hora) 
                ORDER BY fecha_hora DESC 
                LIMIT 10";
        return ejecutarConsulta($sql);
    }

    // Ventas de los últimos 10 días
    public function ventasUltimos10Dias()
    {
        $sql = "SELECT DATE_FORMAT(fecha_hora, '%d/%m') as fecha, IFNULL(SUM(total_venta), 0) as total 
                FROM venta 
                WHERE estado='Aceptado' 
                GROUP BY DATE(fecha_hora) 
                ORDER BY fecha_hora DESC 
                LIMIT 10";
        return ejecutarConsulta($sql);
    }

    // Top 5 artículos más vendidos
    public function productosMasVendidos()
    {
        $sql = "SELECT a.nombre, IFNULL(SUM(dv.cantidad), 0) as cantidad 
                FROM detalle_venta dv 
                INNER JOIN articulo a ON dv.idarticulo = a.idarticulo 
                INNER JOIN venta v ON dv.idventa = v.idventa 
                WHERE v.estado = 'Aceptado' 
                GROUP BY dv.idarticulo 
                ORDER BY cantidad DESC 
                LIMIT 5";
        return ejecutarConsulta($sql);
    }

    // Consulta de compras por fecha y proveedor
    public function consultaCompras($fecha_inicio, $fecha_fin)
    {
        $sql = "SELECT DATE(i.fecha_hora) as fecha, u.nombre as usuario, p.nombre as proveedor, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.total_compra, i.impuesto, i.estado 
                FROM ingreso i 
                INNER JOIN persona p ON i.idproveedor = p.idpersona 
                INNER JOIN usuario u ON i.idusuario = u.idusuario 
                WHERE DATE(i.fecha_hora) >= '$fecha_inicio' AND DATE(i.fecha_hora) <= '$fecha_fin' 
                ORDER BY i.fecha_hora DESC";
        return ejecutarConsulta($sql);
    }

    // Consulta de ventas por fecha y cliente
    public function consultaVentas($fecha_inicio, $fecha_fin, $idcliente)
    {
        $sql = "SELECT DATE(v.fecha_hora) as fecha, u.nombre as usuario, p.nombre as cliente, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, v.total_venta, v.impuesto, v.estado 
                FROM venta v 
                INNER JOIN persona p ON v.idcliente = p.idpersona 
                INNER JOIN usuario u ON v.idusuario = u.idusuario 
                WHERE DATE(v.fecha_hora) >= '$fecha_inicio' AND DATE(v.fecha_hora) <= '$fecha_fin' AND v.idcliente = '$idcliente' 
                ORDER BY v.fecha_hora DESC";
        return ejecutarConsulta($sql);
    }
}
?>