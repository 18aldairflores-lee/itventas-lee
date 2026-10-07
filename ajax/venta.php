<?php 
ob_start();
if (strlen(session_id()) < 1){
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
$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "0";
$total_venta = isset($_POST["total_venta"]) ? limpiarCadena($_POST["total_venta"]) : "0";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idventa)){
            $idarticulo = isset($_POST["idarticulo"]) ? $_POST["idarticulo"] : array();
            $cantidad = isset($_POST["cantidad"]) ? $_POST["cantidad"] : array();
            $precio_venta = isset($_POST["precio_venta"]) ? $_POST["precio_venta"] : array();
            $descuento = isset($_POST["descuento"]) ? $_POST["descuento"] : array();

            $rspta = $venta->insertar($idcliente, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_venta, $idarticulo, $cantidad, $precio_venta, $descuento);
            echo $rspta ? "Venta registrada exitosamente" : "No se pudieron registrar todos los datos de la venta";
        }
        break;

    case 'anular':
        $rspta = $venta->anular($idventa);
        echo $rspta ? "Venta anulada correctamente" : "No se pudo anular la venta";
        break;

    case 'mostrar':
        $rspta = $venta->mostrar($idventa);
        echo json_encode($rspta);
        break;

    case 'listarDetalle':
        $id = $_GET['id'];
        $rspta = $venta->listarDetalle($id);
        $total = 0;
        echo '<thead style="background-color:#A9D0F5">
                <th>Opciones</th>
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Precio Venta</th>
                <th>Descuento</th>
                <th>Subtotal</th>
            </thead>';

        while ($reg = $rspta->fetch_object()){
            echo '<tr class="filas">
                    <td></td>
                    <td>'.$reg->nombre.'</td>
                    <td>'.$reg->cantidad.'</td>
                    <td>S/ '.number_format($reg->precio_venta, 2).'</td>
                    <td>S/ '.number_format($reg->descuento, 2).'</td>
                    <td>S/ '.number_format(($reg->cantidad * $reg->precio_venta - $reg->descuento), 2).'</td>
                </tr>';
            $total = $total + ($reg->cantidad * $reg->precio_venta - $reg->descuento);
        }
        echo '<tfoot>
                <th>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><h4 id="total">S/ '.number_format($total, 2).'</h4></th>
            </tfoot>';
        break;

    case 'listar':
        $rspta = $venta->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()){
            $url = '../reportes/exTicket.php?id=';

            $data[] = array(
                "0" => (($reg->estado == 'Aceptado') ? 
                    '<button class="action-circle-btn btn-view" onclick="mostrar('.$reg->idventa.')" title="Ver Detalle"><i class="fa fa-eye"></i></button> '.
                    '<a target="_blank" href="'.$url.$reg->idventa.'" class="action-circle-btn btn-print" title="Imprimir Ticket QR"><i class="fa fa-print"></i></a> '.
                    '<button class="action-circle-btn btn-cancel" onclick="anular('.$reg->idventa.')" title="Anular Venta"><i class="fa fa-close"></i></button>' :
                    '<button class="action-circle-btn btn-view" onclick="mostrar('.$reg->idventa.')" title="Ver Detalle"><i class="fa fa-eye"></i></button> '.
                    '<a target="_blank" href="'.$url.$reg->idventa.'" class="action-circle-btn btn-print" title="Imprimir Ticket QR"><i class="fa fa-print"></i></a>'),
                "1" => $reg->fecha,
                "2" => '<strong>'.$reg->cliente.'</strong>',
                "3" => $reg->usuario,
                "4" => '<span class="doc-badge">'.$reg->tipo_comprobante.': '.$reg->serie_comprobante.'-'.$reg->num_comprobante.'</span>',
                "5" => '<strong>S/ '.number_format($reg->total_venta, 2).'</strong>',
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
        require_once "../modelos/Persona.php";
        $persona = new Persona();
        $rspta = $persona->listarC();

        echo '<option value="">-- Seleccione un Cliente --</option>';
        while ($reg = $rspta->fetch_object()){
            $doc = !empty($reg->num_documento) ? " (".$reg->tipo_documento.": ".$reg->num_documento.")" : "";
            echo '<option value="'.$reg->idpersona.'">'.$reg->nombre.$doc.'</option>';
        }
        break;

    case 'listarArticulosVenta':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listarActivosVenta();
        $data = Array();

        while ($reg = $rspta->fetch_object()){
            $foto_mostrar = !empty($reg->imagen) ? $reg->imagen : 'defecto.png';

            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\',\''.$reg->precio_venta.'\')"><span class="fa fa-plus"></span></button>',
                "1" => $reg->nombre,
                "2" => $reg->categoria,
                "3" => '<code style="background:#e0f2fe; color:#0369a1; padding:2px 6px; border-radius:4px;">'.$reg->codigo.'</code>',
                "4" => ($reg->stock > 5) ? '<span class="badge" style="background:#dcfce7; color:#166534;">'.$reg->stock.'</span>' : '<span class="badge" style="background:#fee2e2; color:#991b1b;">'.$reg->stock.'</span>',
                "5" => 'S/ '.number_format($reg->precio_venta, 2),
                "6" => "<img src='../files/articulos/".$foto_mostrar."' width='38px' height='38px' style='border-radius:8px; object-fit:cover;' onerror=\"this.src='../files/articulos/defecto.png';\">"
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

    case 'kpisVenta':
        require_once "../config/Conexion.php";

        $sql_total = "SELECT COUNT(*) as total FROM venta";
        $res_total = ejecutarConsultaSimpleFila($sql_total);

        $sql_ingresos = "SELECT IFNULL(SUM(total_venta), 0) as total_soles FROM venta WHERE estado = 'Aceptado'";
        $res_ingresos = ejecutarConsultaSimpleFila($sql_ingresos);

        $sql_aceptadas = "SELECT COUNT(*) as total_aceptadas FROM venta WHERE estado = 'Aceptado'";
        $res_aceptadas = ejecutarConsultaSimpleFila($sql_aceptadas);

        $data = array(
            "total" => $res_total["total"],
            "ingresos" => number_format((float)$res_ingresos["total_soles"], 2, '.', ''),
            "aceptadas" => $res_aceptadas["total_aceptadas"]
        );
        echo json_encode($data);
        break;
}
ob_end_flush();
?>