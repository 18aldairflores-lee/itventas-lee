<?php 
require "../config/Conexion.php";

Class Ingreso
{
    public function __construct() {}

    // Insertar compra con transacción y aumento de stock
    public function insertar($idproveedor, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_compra, $idarticulo, $cantidad, $precio_compra, $precio_venta)
    {
        $sql = "INSERT INTO ingreso (idproveedor, idusuario, tipo_comprobante, serie_comprobante, num_comprobante, fecha_hora, impuesto, total_compra, estado) 
                VALUES ('$idproveedor', '$idusuario', '$tipo_comprobante', '$serie_comprobante', '$num_comprobante', '$fecha_hora', '$impuesto', '$total_compra', 'Aceptado')";
        
        $idingresonew = ejecutarConsulta_retornarID($sql);

        $num_elementos = 0;
        $sw = true;

        while ($num_elementos < count($idarticulo)) {
            $sql_detalle = "INSERT INTO detalle_ingreso (idingreso, idarticulo, cantidad, precio_compra, precio_venta) 
                            VALUES ('$idingresonew', '$idarticulo[$num_elementos]', '$cantidad[$num_elementos]', '$precio_compra[$num_elementos]', '$precio_venta[$num_elementos]')";
            ejecutarConsulta($sql_detalle) or $sw = false;

            // Aumentar stock del producto en inventario
            $sql_stock = "UPDATE articulo SET stock = stock + '$cantidad[$num_elementos]' WHERE idarticulo='$idarticulo[$num_elementos]'";
            ejecutarConsulta($sql_stock) or $sw = false;

            $num_elementos++;
        }

        return $sw;
    }

    // Anular compra y revertir stock
    public function anular($idingreso)
    {
        $sql = "UPDATE ingreso SET estado='Anulado' WHERE idingreso='$idingreso'";
        $sw = ejecutarConsulta($sql);

        // Disminuir el stock que sumó esta compra
        $sql_det = "SELECT idarticulo, cantidad FROM detalle_ingreso WHERE idingreso='$idingreso'";
        $detalles = ejecutarConsulta($sql_det);

        while ($reg = $detalles->fetch_object()) {
            $sql_restar = "UPDATE articulo SET stock = stock - '$reg->cantidad' WHERE idarticulo='$reg->idarticulo'";
            ejecutarConsulta($sql_restar);
        }

        return $sw;
    }

    // Cabecera del comprobante
    public function mostrar($idingreso)
    {
        $sql = "SELECT i.idingreso, DATE(i.fecha_hora) as fecha, i.idproveedor, p.nombre as proveedor, u.idusuario, u.nombre as usuario, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.total_compra, i.impuesto, i.estado 
                FROM ingreso i 
                INNER JOIN persona p ON i.idproveedor = p.idpersona 
                INNER JOIN usuario u ON i.idusuario = u.idusuario 
                WHERE i.idingreso='$idingreso'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Detalle de productos de la compra
    public function listarDetalle($idingreso)
    {
        $sql = "SELECT di.idingreso, di.idarticulo, a.nombre, di.cantidad, di.precio_compra, di.precio_venta 
                FROM detalle_ingreso di 
                INNER JOIN articulo a ON di.idarticulo = a.idarticulo 
                WHERE di.idingreso='$idingreso'";
        return ejecutarConsulta($sql);
    }

    // Listar historial de compras
    public function listar()
    {
        $sql = "SELECT i.idingreso, DATE(i.fecha_hora) as fecha, i.idproveedor, p.nombre as proveedor, u.idusuario, u.nombre as usuario, i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, i.total_compra, i.impuesto, i.estado 
                FROM ingreso i 
                INNER JOIN persona p ON i.idproveedor = p.idpersona 
                INNER JOIN usuario u ON i.idusuario = u.idusuario 
                ORDER BY i.idingreso DESC";
        return ejecutarConsulta($sql);
    }
}
?>