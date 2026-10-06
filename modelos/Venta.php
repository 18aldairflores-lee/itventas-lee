<?php 
require "../config/Conexion.php";

Class Venta
{
    public function __construct() {}

    // Registrar venta con descuento automático de stock
    public function insertar($idcliente, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_venta, $idarticulo, $cantidad, $precio_venta, $descuento)
    {
        $sql = "INSERT INTO venta (idcliente, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, total_venta, estado) 
                VALUES ('$idcliente', '$idusuario', '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$fecha_hora', '$impuesto', '$total_venta', 'Aceptado')";
        
        $idventanew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;

        while ($num_elementos < count($idarticulo)) {
            $sql_detalle = "INSERT INTO detalle_venta (idventa, idarticulo, cantidad, precio_venta, descuento) 
                            VALUES ('$idventanew', '$idarticulo[$num_elementos]', '$cantidad[$num_elementos]', '$precio_venta[$num_elementos]', '$descuento[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            // Descontar del stock en almacén
            $sql_stock = "UPDATE articulo SET stock = stock - '$cantidad[$num_elementos]' WHERE idarticulo='$idarticulo[$num_elementos]'";
            ejecutarConsulta($sql_stock) or $sw = false;

            $num_elementos++;
        }

        return $sw;
    }

    // Anular venta y reponer el stock
    public function anular($idventa)
    {
        $sql = "UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
        $sw = ejecutarConsulta($sql);

        // Devolver el stock de los productos vendidos
        $sql_det = "SELECT idarticulo, cantidad FROM detalle_venta WHERE idventa='$idventa'";
        $detalles = ejecutarConsulta($sql_det);

        while ($reg = $detalles->fetch_object()) {
            $sql_sumar = "UPDATE articulo SET stock = stock + '$reg->cantidad' WHERE idarticulo='$reg->idarticulo'";
            ejecutarConsulta($sql_sumar);
        }

        return $sw;
    }

    // Cabecera de venta
    public function mostrar($idventa)
    {
        $sql = "SELECT v.idventa, DATE(v.fecha_hora) as fecha, v.idcliente, p.nombre as cliente, u.idusuario, u.nombre as usuario, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, v.total_venta, v.impuesto, v.estado 
                FROM venta v 
                INNER JOIN persona p ON v.idcliente = p.idpersona 
                INNER JOIN usuario u ON v.idusuario = u.idusuario 
                WHERE v.idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Detalle de ítems vendidos
    public function listarDetalle($idventa)
    {
        $sql = "SELECT dv.idventa, dv.idarticulo, a.nombre, dv.cantidad, dv.precio_venta, dv.descuento, (dv.cantidad*dv.precio_venta - dv.descuento) as subtotal 
                FROM detalle_venta dv 
                INNER JOIN articulo a ON dv.idarticulo = a.idarticulo 
                WHERE dv.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    // Listar historial de ventas
    public function listar()
    {
        $sql = "SELECT v.idventa, DATE(v.fecha_hora) as fecha, v.idcliente, p.nombre as cliente, u.idusuario, u.nombre as usuario, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, v.total_venta, v.impuesto, v.estado 
                FROM venta v 
                INNER JOIN persona p ON v.idcliente = p.idpersona 
                INNER JOIN usuario u ON v.idusuario = u.idusuario 
                ORDER BY v.idventa DESC";
        return ejecutarConsulta($sql);
    }
}
?>