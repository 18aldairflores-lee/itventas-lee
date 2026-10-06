<?php 
require "../config/Conexion.php";

Class Venta
{
    public function __construct() {}

    // Insertar venta con su detalle y descontar stock automáticamente
    public function insertar($idcliente, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_venta, $idarticulo, $cantidad, $precio_venta, $descuento)
    {
        $sql = "INSERT INTO venta (idcliente, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, total_venta, estado) 
                VALUES ('$idcliente', '$idusuario', '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$fecha_hora', '$impuesto', '$total_venta', 'Aceptado')";
        
        $idventanew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;

        if ($idventanew > 0 && is_array($idarticulo)) {
            while ($num_elementos < count($idarticulo)) {
                $subtotal = ($cantidad[$num_elementos] * $precio_venta[$num_elementos]) - $descuento[$num_elementos];

                $sql_detalle = "INSERT INTO detalle_venta (idventa, idarticulo, cantidad, precio_venta, descuento) 
                                VALUES ('$idventanew', '$idarticulo[$num_elementos]', '$cantidad[$num_elementos]', '$precio_venta[$num_elementos]', '$descuento[$num_elementos]')";
                ejecutarConsulta($sql_detalle) or $sw = false;

                // Descontar stock del almacén
                $sql_stock = "UPDATE articulo SET stock = stock - '$cantidad[$num_elementos]' WHERE idarticulo = '$idarticulo[$num_elementos]'";
                ejecutarConsulta($sql_stock) or $sw = false;

                $num_elementos = $num_elementos + 1;
            }
        } else {
            $sw = false;
        }

        return $sw;
    }

    // Anular venta y devolver stock al inventario
    public function anular($idventa)
    {
        $sql_detalles = "SELECT idarticulo, cantidad FROM detalle_venta WHERE idventa='$idventa'";
        $detalles = ejecutarConsulta($sql_detalles);

        while ($reg = $detalles->fetch_object()) {
            $sql_reposicion = "UPDATE articulo SET stock = stock + '$reg->cantidad' WHERE idarticulo = '$reg->idarticulo'";
            ejecutarConsulta($sql_reposicion);
        }

        $sql = "UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    // Mostrar cabecera de la venta
    public function mostrar($idventa)
    {
        $sql = "SELECT v.idventa, DATE(v.fecha_hora) as fecha, v.idcliente, p.nombre as cliente, p.num_documento, u.idusuario, u.nombre as usuario, v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, v.total_venta, v.impuesto, v.estado 
                FROM venta v 
                INNER JOIN persona p ON v.idcliente = p.idpersona 
                INNER JOIN usuario u ON v.idusuario = u.idusuario 
                WHERE v.idventa='$idventa'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Listar productos comprados de una venta
    public function listarDetalle($idventa)
    {
        $sql = "SELECT dv.iddetalle_venta, dv.idventa, dv.idarticulo, a.nombre, dv.cantidad, dv.precio_venta, dv.descuento, (dv.cantidad * dv.precio_venta - dv.descuento) as subtotal 
                FROM detalle_venta dv 
                INNER JOIN articulo a ON dv.idarticulo = a.idarticulo 
                WHERE dv.idventa='$idventa'";
        return ejecutarConsulta($sql);
    }

    // Listado general de ventas
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