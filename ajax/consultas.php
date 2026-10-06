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

    case 'ventasfechacliente':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];
        $idcliente = isset($_REQUEST["idcliente"]) ? $_REQUEST["idcliente"] : "";

        $rspta = $consulta->ventasFechaCliente($fecha_inicio, $fecha_fin, $idcliente);
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => '<strong>' . $reg->usuario . '</strong>',
                "2" => $reg->cliente,
                "3" => '<span class="badge-cat">' . $reg->tipo_comprobante . ': ' . $reg->serie_comprobante . '-' . $reg->num_comprobante . '</span>',
                "4" => '<span style="font-weight:700; color:#0f172a;">S/ ' . number_format($reg->total_venta, 2) . '</span>',
                "5" => 'S/ ' . number_format($reg->impuesto, 2),
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

    case 'comprasfecha':
        $fecha_inicio = $_REQUEST["fecha_inicio"];
        $fecha_fin = $_REQUEST["fecha_fin"];

        $rspta = $consulta->comprasFecha($fecha_inicio, $fecha_fin);
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => $reg->fecha,
                "1" => '<strong>' . $reg->usuario . '</strong>',
                "2" => $reg->proveedor,
                "3" => '<span class="badge-cat">' . $reg->tipo_comprobante . ': ' . $reg->serie_comprobante . '-' . $reg->num_comprobante . '</span>',
                "4" => '<span style="font-weight:700; color:#0f172a;">S/ ' . number_format($reg->total_compra, 2) . '</span>',
                "5" => 'S/ ' . number_format($reg->impuesto, 2),
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