<?php 
ob_start();
if (strlen(session_id()) < 1){
    session_start();
}
require_once "../modelos/Ingreso.php";

$ingreso = new Ingreso();

$idingreso = isset($_POST["idingreso"]) ? limpiarCadena($_POST["idingreso"]) : "";
$idproveedor = isset($_POST["idproveedor"]) ? limpiarCadena($_POST["idproveedor"]) : "";
$idusuario = isset($_SESSION["idusuario"]) ? $_SESSION["idusuario"] : "1";
$tipo_comprobante = isset($_POST["tipo_comprobante"]) ? limpiarCadena($_POST["tipo_comprobante"]) : "";
$serie_comprobante = isset($_POST["serie_comprobante"]) ? limpiarCadena($_POST["serie_comprobante"]) : "";
$num_comprobante = isset($_POST["num_comprobante"]) ? limpiarCadena($_POST["num_comprobante"]) : "";
$fecha_hora = isset($_POST["fecha_hora"]) ? limpiarCadena($_POST["fecha_hora"]) : "";
$impuesto = isset($_POST["impuesto"]) ? limpiarCadena($_POST["impuesto"]) : "0";
$total_compra = isset($_POST["total_compra"]) ? limpiarCadena($_POST["total_compra"]) : "0";

switch ($_GET["op"]){
    case 'guardaryeditar':
        if (empty($idingreso)){
            $idarticulo = isset($_POST["idarticulo"]) ? $_POST["idarticulo"] : array();
            $cantidad = isset($_POST["cantidad"]) ? $_POST["cantidad"] : array();
            $precio_compra = isset($_POST["precio_compra"]) ? $_POST["precio_compra"] : array();
            $precio_venta = isset($_POST["precio_venta"]) ? $_POST["precio_venta"] : array();

            $rspta = $ingreso->insertar($idproveedor, $idusuario, $tipo_comprobante, $serie_comprobante, $num_comprobante, $fecha_hora, $impuesto, $total_compra, $idarticulo, $cantidad, $precio_compra, $precio_venta);
            echo $rspta ? "Ingreso registrado exitosamente" : "No se pudieron registrar todos los datos del ingreso";
        }
        break;

    case 'anular':
        $rspta = $ingreso->anular($idingreso);
        echo $rspta ? "Ingreso anulado correctamente" : "No se pudo anular el ingreso";
        break;

    case 'mostrar':
        $rspta = $ingreso->mostrar($idingreso);
        echo json_encode($rspta);
        break;

    case 'listarDetalle':
        $id = $_GET['id'];
        $rspta = $ingreso->listarDetalle($id);
        $total = 0;
        echo '<thead style="background-color:#A9D0F5">
                <th>Opciones</th>
                <th>Artículo</th>
                <th>Cantidad</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Subtotal</th>
            </thead>';

        while ($reg = $rspta->fetch_object()){
            echo '<tr class="filas">
                    <td></td>
                    <td>'.$reg->nombre.'</td>
                    <td>'.$reg->cantidad.'</td>
                    <td>S/ '.number_format($reg->precio_compra, 2).'</td>
                    <td>S/ '.number_format($reg->precio_venta, 2).'</td>
                    <td>S/ '.number_format(($reg->cantidad * $reg->precio_compra), 2).'</td>
                </tr>';
            $total = $total + ($reg->cantidad * $reg->precio_compra);
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
        $rspta = $ingreso->listar();
        $data = Array();

        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                "0" => (($reg->estado == 'Aceptado') ? 
                    '<button class="action-circle-btn btn-view" onclick="mostrar('.$reg->idingreso.')" title="Ver Detalle"><i class="fa fa-eye"></i></button> '.
                    '<button class="action-circle-btn btn-cancel" onclick="anular('.$reg->idingreso.')" title="Anular Ingreso"><i class="fa fa-close"></i></button>' :
                    '<button class="action-circle-btn btn-view" onclick="mostrar('.$reg->idingreso.')" title="Ver Detalle"><i class="fa fa-eye"></i></button>'),
                "1" => $reg->fecha,
                "2" => '<strong>'.$reg->proveedor.'</strong>',
                "3" => $reg->usuario,
                "4" => '<span class="doc-badge">'.$reg->tipo_comprobante.': '.$reg->serie_comprobante.'-'.$reg->num_comprobante.'</span>',
                "5" => '<strong>S/ '.number_format($reg->total_compra, 2).'</strong>',
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
        require_once "../modelos/Persona.php";
        $persona = new Persona();
        $rspta = $persona->listarP();

        echo '<option value="">-- Seleccione un Proveedor --</option>';
        while ($reg = $rspta->fetch_object()){
            $doc = !empty($reg->num_documento) ? " (".$reg->tipo_documento.": ".$reg->num_documento.")" : "";
            echo '<option value="'.$reg->idpersona.'">'.$reg->nombre.$doc.'</option>';
        }
        break;

    case 'listarArticulos':
        require_once "../modelos/Articulo.php";
        $articulo = new Articulo();
        $rspta = $articulo->listarActivos();
        $data = Array();

        while ($reg = $rspta->fetch_object()){
            $data[] = array(
                "0" => '<button class="btn btn-warning btn-xs" onclick="agregarDetalle('.$reg->idarticulo.',\''.$reg->nombre.'\')"><span class="fa fa-plus"></span></button>',
                "1" => $reg->nombre,
                "2" => $reg->categoria,
                "3" => '<code style="background:#e0f2fe; color:#0369a1; padding:2px 6px; border-radius:4px;">'.$reg->codigo.'</code>',
                "4" => $reg->stock,
                "5" => "<img src='../files/articulos/".$reg->imagen."' width='38px' height='38px' style='border-radius:8px; object-fit:cover;' onerror=\"this.src='../files/articulos/defecto.png';\">"
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

    case 'kpisIngreso':
        require_once "../config/Conexion.php";

        // Total de transacciones de compra
        $sql_total = "SELECT COUNT(*) as total FROM ingreso";
        $res_total = ejecutarConsultaSimpleFila($sql_total);

        // Inversion total acumulada (solo de ingresos Aceptados)
        $sql_inversion = "SELECT IFNULL(SUM(total_compra), 0) as total_inversion FROM ingreso WHERE estado = 'Aceptado'";
        $res_inversion = ejecutarConsultaSimpleFila($sql_inversion);

        // Compras activas aceptadas
        $sql_aceptadas = "SELECT COUNT(*) as total_aceptadas FROM ingreso WHERE estado = 'Aceptado'";
        $res_aceptadas = ejecutarConsultaSimpleFila($sql_aceptadas);

        $data = array(
            "total" => (int)$res_total["total"],
            "inversion" => number_format((float)$res_inversion["total_inversion"], 2, '.', ''),
            "aceptadas" => (int)$res_aceptadas["total_aceptadas"]
        );
        echo json_encode($data);
        break;
}
ob_end_flush();
?>