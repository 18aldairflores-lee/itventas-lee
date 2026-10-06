<?php 
if (strlen(session_id()) < 1) {
    session_start();
}

require_once "../modelos/Venta.php";

$venta = new Venta();

$idventa = isset($_POST["idventa"]) ? limpiarCadena($_POST["idventa"]) : "";
$idcliente = isset($_POST["idcliente"]) ? limpiarCadena($_POST["idcliente"]) : "";
$idusuario = isset($_SESSION["idusuario"]) ? $_SESSION["idusuario"] : "1";
$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
$serie_comprobante = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
$num_comprobante = isset($_POST["num_comprobante"]) ? limpiarCadena($_POST["num_comprobante"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : "";
$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "";
$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idventa)) {
            $rspta = $venta->insertar($idcliente, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_venta, $_POST["idarticulo"], $_POST["cantidad"], $_POST["precio_venta"], $_POST["descuento"]);
            echo $rspta ? "Venta registrada exitosamente" : "No se pudieron guardar todos los detalles de la venta";
        }
        break;

    case 'anular':
        $rspta = $venta->anular($idventa);
        echo $rspta ? "Venta anulada y stock devuelto a almacén" : "No se pudo anular la venta";
        break;

    case 'mostrar':
        $rspta = $venta->mostrar($idventa);
        echo json_encode($rspta);
        break;

    case 'listarDetalle':
        $id = $_GET['id'];
        $rspta = $venta->listarDetalle($id);
        $total = 0;
        echo '<thead style="background-color:#f8fafc; color:#475569; font-size:12px;">
                <th>Opciones</th>
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Precio Venta</th>
                <th>Descuento</th>
                <th>Subtotal</th>
              </thead>';

        while ($reg = $rspta->fetch_object()) {
            $total += $reg->subtotal;
            echo '<tr class="filas">
                    <td></td>
                    <td><strong>'.$reg->nombre.'</strong></td>
                    <td>'.$reg->cantidad.'</td>
                    <td>S/ '.number_format($reg->precio_venta, 2).'</td>
                    <td>S/ '.number_format($reg->descuento, 2).'</td>
                    <td><strong>S/ '.number_format($reg->subtotal, 2).'</strong></td>
                  </tr>';
        }
        echo '<tfoot>
                <th>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><h4 style="color:#2563eb; font-weight:800; margin:0;">S/ '.number_format($total, 2).'</h4></th>
              </tfoot>';
        break;

    case 'listar':
        $rspta = $venta->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $url = '../reportes/exTicket.php?id=' . $reg->idventa;

            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ? 
                    '<button class="action-circle-btn btn-view" onclick="mostrar('.$reg->idventa.')" title="Ver Detalles"><i class="fa fa-eye"></i></button> '.
                    '<a target="_blank" href="'.$url.'" class="action-circle-btn" title="Imprimir Ticket" style="background:#0284c7; color:#fff; display:inline-flex; align-items:center; justify-content:center; text-decoration:none;"><i class="fa fa-print"></i></a> '.
                    '<button class="action-circle-btn btn-deactivate" onclick="anular('.$reg->idventa.')" title="Anular"><i class="fa fa-close"></i></button>' :
                    '<button class="action-circle-btn btn-view" onclick="mostrar('.$reg->idventa.')" title="Ver Detalles"><i class="fa fa-eye"></i></button> '.
                    '<a target="_blank" href="'.$url.'" class="action-circle-btn" title="Imprimir Ticket" style="background:#0284c7; color:#fff; display:inline-flex; align-items:center; justify-content:center; text-decoration:none;"><i class="fa fa-print"></i></a>',
                "1" => $reg->fecha,
                "2" => '<strong>'.$reg->cliente.'</strong>',
                "3" => $reg->usuario,
                "4" => '<span class="badge-cat">'.$reg->tipo_comprobante.': '.$reg->serie_comprobante.'-'.$reg->num_comprobante.'</span>',
                "5" => '<span style="font-weight:700; color:#0f172a;">S/ '.number_format($reg->total_venta, 2).'</span>',
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

    case 'selectCliente':
        require_once "../config/Conexion.php";
        $sql = "SELECT idpersona, nombre FROM persona WHERE tipo_persona='Cliente'";
        $rspta = ejecutarConsulta($sql);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value="' . $reg->idpersona . '">' . $reg->nombre . '</option>';
        }
        break;

    case 'listarArticulosVenta':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            if ($reg->condicion == 1) {
                $sql_pv = "SELECT precio_venta FROM detalle_ingreso WHERE idarticulo='$reg->idarticulo' ORDER BY iddetalle_ingreso DESC LIMIT 1";
                $pv_query = ejecutarConsultaSimpleFila($sql_pv);
                $precio_venta_sugerido = isset($pv_query["precio_venta"]) ? $pv_query["precio_venta"] : "10.00";

                $btn_agregar = ($reg->stock > 0) ? 
                    '<button class="btn btn-primary-modern btn-xs" onclick="agregarDetalle('.$reg->idarticulo.', \''.addslashes($reg->nombre).'\', '.$precio_venta_sugerido.', '.$reg->stock.')"><i class="fa fa-plus"></i></button>' :
                    '<span class="badge" style="background:#ef4444;">Sin stock</span>';

                $data[] = array(
                    "0" => $btn_agregar,
                    "1" => '<strong>'.$reg->nombre.'</strong>',
                    "2" => $reg->categoria,
                    "3" => '<code class="barcode-badge">'.$reg->codigo.'</code>',
                    "4" => ($reg->stock > 5) ? '<span class="stock-badge stock-ok">'.$reg->stock.'</span>' : '<span class="stock-badge stock-low">'.$reg->stock.'</span>',
                    "5" => "<img src='../files/articulos/".$reg->imagen."' width='38px' height='38px' style='border-radius:8px;' onerror=\"this.src='../public/img/logo1.png';\">"
                );
            }
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