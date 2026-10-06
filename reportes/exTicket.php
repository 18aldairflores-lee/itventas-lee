<?php
ob_start();
if (strlen(session_id()) < 1) {
    session_start();
}

if (!isset($_SESSION["nombre"])) {
    echo 'Debe iniciar sesión para visualizar este comprobante.';
} else {
    require_once "../modelos/Venta.php";
    $venta = new Venta();

    $idventa = isset($_GET["id"]) ? $_GET["id"] : "";
    $rsptav = $venta->mostrar($idventa);

    if (!$rsptav) {
        die("El comprobante solicitado no existe o fue eliminado.");
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ticket Venta - ITVentas Lee</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: 'Courier New', Courier, monospace;
        }
        body {
            margin: 0;
            padding: 10px;
            background-color: #ffffff;
            color: #000000;
        }
        .ticket-wrapper {
            width: 290px;
            margin: 0 auto;
            border: 1px dashed #cbd5e1;
            padding: 14px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .title { font-size: 16px; margin: 0; }
        .subtitle { font-size: 11px; margin: 2px 0; color: #334155; }
        .divider {
            border-top: 1px dashed #000000;
            margin: 8px 0;
        }
        .info-row {
            font-size: 11px;
            line-height: 1.35;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 6px;
        }
        table th {
            border-bottom: 1px dashed #000000;
            padding: 4px 0;
            text-align: left;
        }
        table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .btn-print {
            background: #2563eb;
            color: #ffffff;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            margin-bottom: 14px;
            font-family: sans-serif;
            font-weight: 700;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        @media print {
            .no-print { display: none !important; }
            .ticket-wrapper { border: none; padding: 0; width: 100%; }
        }
    </style>
</head>
<body onload="window.print();">

    <div class="text-center no-print">
        <button class="btn-print" onclick="window.print();">🖨️ Imprimir Ticket</button>
    </div>

    <div class="ticket-wrapper">
        <div class="text-center">
            <h2 class="title bold">ITVENTAS LEE</h2>
            <p class="subtitle">RUC: 20601234567</p>
            <p class="subtitle">Puno - Perú</p>
            <p class="subtitle">Teléfono: 951234567</p>
        </div>

        <div class="divider"></div>

        <div class="info-row">
            <strong>COMPROBANTE:</strong> <?php echo strtoupper($rsptav['tipo_comprobante']); ?><br>
            <strong>N°:</strong> <?php echo $rsptav['serie_comprobante'] . '-' . $rsptav['num_comprobante']; ?><br>
            <strong>FECHA:</strong> <?php echo $rsptav['fecha']; ?><br>
            <strong>CLIENTE:</strong> <?php echo $rsptav['cliente']; ?><br>
            <strong>ATENDIDO POR:</strong> <?php echo $rsptav['usuario']; ?><br>
        </div>

        <div class="divider"></div>

        <table>
            <thead>
                <tr>
                    <th style="width: 50%;">Cant. / Art.</th>
                    <th class="text-right" style="width: 25%;">P.U.</th>
                    <th class="text-right" style="width: 25%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $rsptad = $venta->listarDetalle($idventa);
                while ($regd = $rsptad->fetch_object()) {
                ?>
                <tr>
                    <td colspan="3" class="bold"><?php echo $regd->nombre; ?></td>
                </tr>
                <tr>
                    <td><?php echo $regd->cantidad; ?> x S/ <?php echo number_format($regd->precio_venta, 2); ?></td>
                    <td class="text-right">Desc. S/ <?php echo number_format($regd->descuento, 2); ?></td>
                    <td class="text-right bold">S/ <?php echo number_format($regd->subtotal, 2); ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="divider"></div>

        <?php 
            $impuesto = $rsptav['impuesto'];
            $subtotal_sin_igv = $rsptav['total_venta'] / (1 + $impuesto);
            $igv = $rsptav['total_venta'] - $subtotal_sin_igv;
        ?>

        <div class="info-row text-right">
            OP. GRAVADA: S/ <?php echo number_format($subtotal_sin_igv, 2); ?><br>
            I.G.V. (18%): S/ <?php echo number_format($igv, 2); ?><br>
            <span class="bold" style="font-size: 13px;">TOTAL A PAGAR: S/ <?php echo number_format($rsptav['total_venta'], 2); ?></span>
        </div>

        <div class="divider"></div>

        <div class="text-center subtitle" style="margin-top: 10px;">
            ¡Gracias por su compra!<br>
            Conserve este comprobante para cualquier cambio.<br>
            <em>Sistema ITVentas Lee</em>
        </div>
    </div>

</body>
</html>
<?php 
}
ob_end_flush();
?>