<?php 
require_once "../modelos/Consultas.php";

$consulta = new Consultas();

switch ($_GET["op"]) {
    case 'kpis':
        $ventas = $consulta->totalVentas();
        $compras = $consulta->totalCompras();
        $clientes = $consulta->totalClientes();
        $articulos = $consulta->totalArticulos();

        $total_ventas = isset($ventas["total_venta"]) ? (float)$ventas["total_venta"] : 0;
        $total_compras = isset($compras["total_compra"]) ? (float)$compras["total_compra"] : 0;
        $balance = $total_ventas - $total_compras;

        $data = array(
            "total_ventas" => number_format($total_ventas, 2),
            "total_compras" => number_format($total_compras, 2),
            "balance" => number_format($balance, 2),
            "total_clientes" => isset($clientes["total_clientes"]) ? $clientes["total_clientes"] : 0,
            "total_articulos" => isset($articulos["total_articulos"]) ? $articulos["total_articulos"] : 0
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

    case 'productosMasVendidos':
        $rspta = $consulta->productosMasVendidos();
        $articulos = array();
        $cantidades = array();

        while ($reg = $rspta->fetch_object()) {
            array_push($articulos, $reg->nombre);
            array_push($cantidades, (int)$reg->cantidad);
        }

        $data = array(
            "articulos" => $articulos,
            "cantidades" => $cantidades
        );
        echo json_encode($data);
        break;

    case 'consultaCompras':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];

        $rspta = $consulta->consultaCompras($fecha_inicio, $fecha_fin);
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => '<strong>' . $reg->proveedor . '</strong>',
                "3" => '<span class="badge-cat">' . $reg->tipo_comprobante . ': ' . $reg->serie_comprobante . '-' . $reg->num_comprobante . '</span>',
                "4" => '<span style="font-weight:700; color:#0f172a;">S/ ' . number_format($reg->total_compra, 2) . '</span>',
                "5" => ($reg->impuesto * 100) . ' %',
                "6" => ($reg->estado == 'Aceptado') ? 
                    '<span class="badge-pill-modern badge-pill-success"><span class="dot-indicator"></span> Aceptado</span>' : 
                    '<span class="badge-pill-modern badge-pill-danger"><span class="dot-indicator"></span> Anulado</span>'
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;

    case 'consultaVentas':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idcliente = $_REQUEST["idcliente"];

        $rspta = $consulta->consultaVentas($fecha_inicio, $fecha_fin, $idcliente);
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => '<strong>' . $reg->cliente . '</strong>',
                "3" => '<span class="badge-cat">' . $reg->tipo_comprobante . ': ' . $reg->serie_comprobante . '-' . $reg->num_comprobante . '</span>',
                "4" => '<span style="font-weight:700; color:#0f172a;">S/ ' . number_format($reg->total_venta, 2) . '</span>',
                "5" => ($reg->impuesto * 100) . ' %',
                "6" => ($reg->estado == 'Aceptado') ? 
                    '<span class="badge-pill-modern badge-pill-success"><span class="dot-indicator"></span> Aceptado</span>' : 
                    '<span class="badge-pill-modern badge-pill-danger"><span class="dot-indicator"></span> Anulado</span>'
            );
        }

        $results = array(
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        );
        echo json_encode($results);
        break;
}
?>