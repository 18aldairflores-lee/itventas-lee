<?php 
if (strlen(session_id()) < 1) {
    session_start();
}

require_once "../modelos/Ingreso.php";

$ingreso = new Ingreso();

$idingreso = isset($_POST["idingreso"]) ? limpiarCadena($_POST["idingreso"]) : "";
$idproveedor = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
$idusuario = $_SESSION["idusuario"];
$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
$serie_comprobante = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
$num_comprobante = isset($_POST["num_comprobante"]) ? limpiarCadena($_POST["num_comprobante"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : "";
$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "";
$total_compra = isset($_POST["total_compra"]) ? limpiarCadena($_POST["total_compra"]) : "";

switch ($_GET["op"]) {
    case 'guardaryeditar':
        if (empty($idingreso)) {
            $rspta = $ingreso->insertar($idproveedor, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_compra, $_POST["idarticulo"], $_POST["cantidad"], $_POST["precio_compra"], $_POST["precio_venta"]);
            echo $rspta ? "Ingreso de compra registrado exitosamente" : "No se pudieron registrar todos los datos del ingreso";
        }
        break;

    case 'anular':
        $rspta = $ingreso->anular($idingreso);
        echo $rspta ? "Compra anulada y stock revertido" : "No se pudo anular la compra";
        break;

    case 'mostrar':
        $rspta = $ingreso->mostrar($idingreso);
        echo json_encode($rspta);
        break;

    case 'listarDetalle':
        $id = $_GET['id'];
        $rspta = $ingreso->listarDetalle($id);
        $total = 0;
        echo '<thead style="background-color:#f8fafc; color:#475569; font-size:12px;">
                <th>Opciones</th>
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Subtotal</th>
              </thead>';

        while ($reg = $rspta->fetch_object()) {
            $subtotal = $reg->cantidad * $reg->precio_compra;
            $total += $subtotal;
            echo '<tr class="filas">
                    <td></td>
                    <td><strong>'.$reg->nombre.'</strong></td>
                    <td>'.$reg->cantidad.'</td>
                    <td>S/ '.$reg->precio_compra.'</td>
                    <td>S/ '.$reg->precio_venta.'</td>
                    <td><strong>S/ '.number_format($subtotal, 2).'</strong></td>
                  </tr>';
        }
        echo '<tfoot>
                <th>TOTAL</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><h4 id="total" style="color:#2563eb; font-weight:800; margin:0;">S/ '.number_format($total, 2).'</h4></th>
              </tfoot>';
        break;

    case 'listar':
        $rspta = $ingreso->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            $data[] = array(
                "0" => ($reg->estado == 'Aceptado') ? 
                    '<button class="action-circle-btn btn-view" onclick="mostrar('.$reg->idingreso.')" title="Ver Detalles"><i class="fa fa-eye"></i></button> '.
                    '<button class="action-circle-btn btn-deactivate" onclick="anular('.$reg->idingreso.')" title="Anular"><i class="fa fa-close"></i></button>' :
                    '<button class="action-circle-btn btn-view" onclick="mostrar('.$reg->idingreso.')" title="Ver Detalles"><i class="fa fa-eye"></i></button>',
                "1" => $reg->fecha,
                "2" => '<strong>'.$reg->proveedor.'</strong>',
                "3" => $reg->usuario,
                "4" => '<span class="badge-cat">'.$reg->tipo_comprobante.': '.$reg->serie_comprobante.'-'.$reg->num_comprobante.'</span>',
                "5" => '<span style="font-weight:700; color:#0f172a;">S/ '.number_format($reg->total_compra, 2).'</span>',
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

    case 'selectProveedor':
        require_once "../config/Conexion.php";
        $sql = "SELECT idpersona, nombre FROM persona WHERE tipo_persona='Proveedor'";
        $rspta = ejecutarConsulta($sql);
        while ($reg = $rspta->fetch_object()) {
            echo '<option value="' . $reg->idpersona . '">' . $reg->nombre . '</option>';
        }
        break;

    case 'listarArticulosActivos':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()) {
            if ($reg->condicion == 1) {
                $data[] = array(
                    "0" => '<button class="btn btn-primary-modern btn-xs" onclick="agregarDetalle('.$reg->idarticulo.', \''.addslashes($reg->nombre).'\')"><i class="fa fa-plus"></i></button>',
                    "1" => '<strong>'.$reg->nombre.'</strong>',
                    "2" => $reg->categoria,
                    "3" => '<code class="barcode-badge">'.$reg->codigo.'</code>',
                    "4" => $reg->stock,
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