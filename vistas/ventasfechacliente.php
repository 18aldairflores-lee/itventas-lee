<?php
require 'header.php';
require 'sidebar.php';

if (isset($_SESSION['consultav']) && $_SESSION['consultav'] == 1) {
?>

<style>
    .kpi-wrapper {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px 22px;
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .kpi-wrapper:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
    }
    .kpi-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }
    .kpi-header span {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .4px;
    }
    .kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }
    .kpi-icon.blue { background: #eff6ff; color: #2563eb; }
    .kpi-icon.green { background: #ecfdf5; color: #059669; }
    .kpi-icon.purple { background: #f5f3ff; color: #7c3aed; }
    
    .kpi-value {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .card-shell {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.04);
        margin-bottom: 30px;
    }
    .card-shell-header {
        padding: 22px 26px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
    }
    .card-shell-title {
        font-size: 19px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-card {
        background: #ffffff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.04);
        padding: 22px 26px;
        margin-bottom: 24px;
    }

    .input-field-custom {
        height: 44px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #f8fafc;
        padding: 8px 14px;
        font-size: 14px;
        width: 100%;
        color: #0f172a;
    }
    .input-field-custom:focus {
        border-color: #2563eb;
        background: #ffffff;
        outline: none;
    }

    .btn-filter-glow {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 14px;
        border-radius: 10px;
        padding: 10px 24px;
        border: none;
        height: 44px;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all .25s ease;
        cursor: pointer;
        width: 100%;
    }
    .btn-filter-glow:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(37, 99, 235, 0.4);
    }

    .table-modern thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: .5px;
        font-weight: 700;
        padding: 14px 16px;
        border-bottom: 2px solid #e2e8f0;
    }
    .table-modern tbody td {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 13.5px;
    }
</style>

<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title">Reporte y Auditoría de Ventas</h1>
                <p class="page-subtitle">Consulta de ventas por rango de fechas, clientes y exportación oficial a Excel/PDF.</p>
            </div>
        </div>

        <br>

        <!-- Filtros Superiores -->
        <div class="filter-card">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group">
                    <label style="font-weight: 700; color: #334155; margin-bottom: 6px;">Fecha Inicial</label>
                    <input type="date" class="input-field-custom" name="fecha_inicio" id="fecha_inicio">
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 form-group">
                    <label style="font-weight: 700; color: #334155; margin-bottom: 6px;">Fecha Final</label>
                    <input type="date" class="input-field-custom" name="fecha_fin" id="fecha_fin">
                </div>

                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-12 form-group">
                    <label style="font-weight: 700; color: #334155; margin-bottom: 6px;">Cliente (Opcional)</label>
                    <select name="idcliente" id="idcliente" class="input-field-custom selectpicker" data-live-search="true"></select>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12 form-group">
                    <label style="visibility: hidden; display: block; margin-bottom: 6px;">Filtrar</label>
                    <button class="btn-filter-glow" id="btnFiltrar" type="button">
                        <i class="fa fa-filter"></i> Filtrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Tarjetas KPIs -->
        <div class="row">
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Total Facturado en Rango</span>
                        <div class="kpi-icon green"><i class="fa fa-money"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-total-facturado" style="color: #059669;">S/ 0.00</h3>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Comprobantes Emitidos</span>
                        <div class="kpi-icon blue"><i class="fa fa-file-text-o"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-comprobantes">0</h3>
                </div>
            </div>

            <div class="col-lg-4 col-sm-12 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Ticket Promedio</span>
                        <div class="kpi-icon purple"><i class="fa fa-calculator"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-ticket-promedio" style="color: #7c3aed;">S/ 0.00</h3>
                </div>
            </div>
        </div>

        <!-- Tabla de Datos -->
        <div class="card-shell">
            <div class="card-shell-header">
                <div>
                    <h3 class="card-shell-title">
                        <i class="fa fa-bar-chart text-primary"></i> 
                        <span>Detalle de Ventas del Período</span>
                    </h3>
                </div>
            </div>

            <div class="card-shell-body" style="padding: 24px;">
                <div class="table-responsive" id="listadoregistros">
                    <table id="tbllistado" class="table table-modern table-hover" style="width:100%">
                        <thead>
                            <th style="width: 12%;">Fecha</th>
                            <th style="width: 15%;">Usuario/Cajero</th>
                            <th style="width: 25%;">Cliente</th>
                            <th style="width: 20%;">Comprobante</th>
                            <th style="width: 12%;">Total Venta</th>
                            <th style="width: 8%;">Impuesto</th>
                            <th style="width: 8%;">Estado</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>

<?php
} else {
    require 'noacceso.php';
}
require 'footer.php';
?>

<script type="text/javascript" src="scripts/ventasfechacliente.js?v=<?php echo time(); ?>"></script>