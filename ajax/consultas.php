<?php 
require_once "../modelos/Consultas.php";

$consulta = new Consultas();

switch ($_GET["op"]) {
    case 'kpis':
        $compras = $consulta->totalCompra();
        $ventas = $consulta->totalVenta();
        $clientes = $consulta->totalClientes();
        $articulos = $consulta->totalArticulos();

        $data = array(
            "total_compras" => number_format($compras["total_compra"], 2),
            "total_ventas" => number_format($ventas["total_venta"], 2),
            "balance" => number_format($ventas["total_venta"] - $compras["total_compra"], 2),
            "total_clientes" => $clientes["total_clientes"],
            "total_articulos" => $articulos["total_articulos"]
        );
        echo json_encode($data);
        break;

    case 'comprasUltimos10Dias':
        $rspta = $consulta->comprasUltimos10Dias();
        $fechas = array();
        $totales = array();

        while ($reg = $rspta->fetch_object()) {
            array_push($fechas, $reg->fecha);
            array_push($totales, (float)$reg->total);
        }

        // Invertir para mostrar de más antiguo a más reciente
        $data = array(
            "fechas" => array_reverse($fechas),
            "totales" => array_reverse($totales)
        );
        echo json_encode($data);
        break;

    case 'ventasUltimos10Dias':
        $rspta = $consulta->ventasUltimos10Dias();
        $fechas = array();
        $totales = array();

        while ($reg = $rspta->fetch_object()) {
            array_push($fechas, $reg->fecha);
            array_push($totales, (float)$reg->total);
        }

        $data = array(
            "fechas" => array_reverse($fechas),
            "totales" => array_reverse($totales)
        );
        echo json_encode($data);
        break;
}
?>