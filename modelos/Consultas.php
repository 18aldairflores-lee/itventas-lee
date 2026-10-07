<?php 
ob_start();
if (strlen(session_id()) < 1){
    session_start();
}
require_once "../config/Conexion.php";

switch ($_GET["op"]){
    case 'comprasfecha':
        $fecha_inicio = !empty($_REQUEST["fecha_inicio"]) ? limpiarCadena($_REQUEST["fecha_inicio"]) : date('Y-m-d');
        $fecha_fin = !empty($_REQUEST["fecha_fin"]) ? limpiarCadena($_REQUEST["fecha_fin"]) : date('Y-m-d');

        $sql = "SELECT DATE(i.fecha_hora) as fecha, u.nombre as usuario, p.nombre as proveedor, 
                       i.tipo_comprobante, i.serie_comprobante, i.num_comprobante, 
                       i.total_compra, i.impuesto, i.estado 
                FROM ingreso i 
                INNER JOIN persona p ON i.idproveedor = p.idpersona 
                INNER JOIN usuario u ON i.idusuario = u.idusuario 
                WHERE DATE(i.fecha_hora) >= '$fecha_inicio' AND DATE(i.fecha_hora) <= '$fecha_fin'
                ORDER BY i.idingreso DESC";

        $rspta = ejecutarConsulta($sql);
        $data = Array();

        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => '<strong>'.$reg->proveedor.'</strong>',
                "3" => $reg->tipo_comprobante.': '.$reg->serie_comprobante.'-'.$reg->num_comprobante,
                "4" => '<strong>S/ '.number_format($reg->total_compra, 2).'</strong>',
                "5" => $reg->impuesto.'%',
                "6" => ($reg->estado == 'Aceptado') ? 
                    '<span class="badge" style="background:#dcfce7; color:#166534; padding:5px 10px; border-radius:12px;">Aceptado</span>' : 
                    '<span class="badge" style="background:#fee2e2; color:#991b1b; padding:5px 10px; border-radius:12px;">Anulado</span>'
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

    case 'kpisComprasFecha':
        $fecha_inicio = !empty($_REQUEST["fecha_inicio"]) ? limpiarCadena($_REQUEST["fecha_inicio"]) : date('Y-m-d');
        $fecha_fin = !empty($_REQUEST["fecha_fin"]) ? limpiarCadena($_REQUEST["fecha_fin"]) : date('Y-m-d');

        $sql = "SELECT IFNULL(SUM(total_compra), 0) as total_rango, COUNT(*) as cantidad 
                FROM ingreso 
                WHERE DATE(fecha_hora) >= '$fecha_inicio' AND DATE(fecha_hora) <= '$fecha_fin' 
                AND estado = 'Aceptado'";

        $res = ejecutarConsultaSimpleFila($sql);
        $total = (float)$res["total_rango"];
        $cantidad = (int)$res["cantidad"];
        $promedio = ($cantidad > 0) ? ($total / $cantidad) : 0.0;

        echo json_encode(array(
            "total" => number_format($total, 2, '.', ''),
            "cantidad" => $cantidad,
            "promedio" => number_format($promedio, 2, '.', '')
        ));
        break;

    case 'ventasfechacliente':
        $fecha_inicio = !empty($_REQUEST["fecha_inicio"]) ? limpiarCadena($_REQUEST["fecha_inicio"]) : date('Y-m-d');
        $fecha_fin = !empty($_REQUEST["fecha_fin"]) ? limpiarCadena($_REQUEST["fecha_fin"]) : date('Y-m-d');
        $idcliente = !empty($_REQUEST["idcliente"]) ? limpiarCadena($_REQUEST["idcliente"]) : "";

        $sql = "SELECT DATE(v.fecha_hora) as fecha, u.nombre as usuario, p.nombre as cliente, 
                       v.tipo_comprobante, v.serie_comprobante, v.num_comprobante, 
                       v.total_venta, v.impuesto, v.estado 
                FROM venta v 
                INNER JOIN persona p ON v.idcliente = p.idpersona 
                INNER JOIN usuario u ON v.idusuario = u.idusuario 
                WHERE DATE(v.fecha_hora) >= '$fecha_inicio' AND DATE(v.fecha_hora) <= '$fecha_fin'";

        if (!empty($idcliente)) {
            $sql .= " AND v.idcliente = '$idcliente'";
        }

        $sql .= " ORDER BY v.idventa DESC";

        $rspta = ejecutarConsulta($sql);
        $data = Array();

        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                "0" => $reg->fecha,
                "1" => $reg->usuario,
                "2" => '<strong>'.$reg->cliente.'</strong>',
                "3" => $reg->tipo_comprobante.': '.$reg->serie_comprobante.'-'.$reg->num_comprobante,
                "4" => '<strong>S/ '.number_format($reg->total_venta, 2).'</strong>',
                "5" => $reg->impuesto.'%',
                "6" => ($reg->estado == 'Aceptado') ? 
                    '<span class="badge" style="background:#dcfce7; color:#166534; padding:5px 10px; border-radius:12px;">Aceptado</span>' : 
                    '<span class="badge" style="background:#fee2e2; color:#991b1b; padding:5px 10px; border-radius:12px;">Anulado</span>'
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

    case 'kpisVentasFecha':
        $fecha_inicio = !empty($_REQUEST["fecha_inicio"]) ? limpiarCadena($_REQUEST["fecha_inicio"]) : date('Y-m-d');
        $fecha_fin = !empty($_REQUEST["fecha_fin"]) ? limpiarCadena($_REQUEST["fecha_fin"]) : date('Y-m-d');
        $idcliente = !empty($_REQUEST["idcliente"]) ? limpiarCadena($_REQUEST["idcliente"]) : "";

        $filtroCliente = !empty($idcliente) ? " AND idcliente = '$idcliente'" : "";

        $sql = "SELECT IFNULL(SUM(total_venta), 0) as total_rango, COUNT(*) as cantidad 
                FROM venta 
                WHERE DATE(fecha_hora) >= '$fecha_inicio' AND DATE(fecha_hora) <= '$fecha_fin' 
                AND estado = 'Aceptado' $filtroCliente";

        $res = ejecutarConsultaSimpleFila($sql);
        $total = (float)$res["total_rango"];
        $cantidad = (int)$res["cantidad"];
        $promedio = ($cantidad > 0) ? ($total / $cantidad) : 0.0;

        echo json_encode(array(
            "total" => number_format($total, 2, '.', ''),
            "cantidad" => $cantidad,
            "promedio" => number_format($promedio, 2, '.', '')
        ));
        break;
}
ob_end_flush();
?>