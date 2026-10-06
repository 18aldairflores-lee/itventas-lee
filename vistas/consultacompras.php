<?php
require 'header.php';
require 'sidebar.php';
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
    .kpi-icon.orange { background: #fff7ed; color: #ea580c; }
    
    .kpi-value {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }

    .filter-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 22px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
        margin-bottom: 25px;
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

    .btn-filter {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff;
        border: none;
        height: 44px;
        border-radius: 10px;
        padding: 0 22px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all .25s ease;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.25);
    }
    .btn-filter:hover {
        transform: translateY(-2px);
        color: #ffffff;
        box-shadow: 0 8px 20px rgba(37, 99, 235, 0.35);
    }

    .badge-cat {
        background: #f1f5f9;
        color: #334155;
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
    }
    .badge-pill-modern {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
    }
    .badge-pill-success { background: #dcfce7; color: #166534; }
    .badge-pill-danger { background: #fee2e2; color: #991b1b; }
    .dot-indicator { width: 7px; height: 7px; border-radius: 50%; }
    .badge-pill-success .dot-indicator { background: #22c55e; }
    .badge-pill-danger .dot-indicator { background: #ef4444; }
</style>

<div class="content-wrapper">
    <section class="content">

        <!-- Encabezado -->
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title">Reporte y Auditoría de Compras</h1>
                <p class="page-subtitle">Consulta de compras realizadas por período, proveedores asociados y exportación de auditoría.</p>
            </div>
        </div>

        <br>

        <!-- Filtros de Búsqueda -->
        <div class="filter-card">
            <div class="row">
                <div class="col-md-5 form-group">
                    <label style="font-weight:700; color:#475569;">Fecha Inicial</label>
                    <input type="date" class="input-field-custom" name="fecha_inicio" id="fecha_inicio">
                </div>

                <div class="col-md-5 form-group">
                    <label style="font-weight:700; color:#475569;">Fecha Final</label>
                    <input type="date" class="input-field-custom" name="fecha_fin" id="fecha_fin">
                </div>

                <div class="col-md-2 form-group" style="padding-top: 25px;">
                    <button class="btn-filter" onclick="listar()" style="width: 100%; justify-content: center;">
                        <i class="fa fa-search"></i> Filtrar
                    </button>
                </div>
            </div>
        </div>

        <!-- Tarjetas de Resumen del Período -->
        <div class="row">
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Inversión Total en Rango</span>
                        <div class="kpi-icon blue"><i class="fa fa-money"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi_monto_compras" style="color: #2563eb;">S/ 0.00</h3>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Ingresos / Compras</span>
                        <div class="kpi-icon green"><i class="fa fa-truck"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi_comprobantes_compras">0</h3>
                </div>
            </div>

            <div class="col-lg-4 col-sm-12 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Inversión Promedio</span>
                        <div class="kpi-icon orange"><i class="fa fa-calculator"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi_promedio_compras" style="color: #ea580c;">S/ 0.00</h3>
                </div>
            </div>
        </div>

        <!-- Tabla de Datos -->
        <div class="card-shell">
            <div class="card-shell-header">
                <div>
                    <h3 class="card-shell-title">
                        <i class="fa fa-pie-chart text-primary"></i> 
                        <span>Detalle de Compras del Período</span>
                    </h3>
                </div>
            </div>

            <div class="card-shell-body" style="padding: 24px;">
                <div class="table-responsive">
                    <table id="tbllistado" class="table table-modern table-hover" style="width:100%">
                        <thead>
                            <th style="width: 12%;">Fecha</th>
                            <th style="width: 18%;">Usuario/Registrador</th>
                            <th style="width: 25%;">Proveedor</th>
                            <th style="width: 20%;">Comprobante</th>
                            <th style="width: 12%;">Total Compra</th>
                            <th style="width: 8%;">Impuesto</th>
                            <th style="width: 10%;">Estado</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>

<?php
require 'footer.php';
?>

<script type="text/javascript" src="scripts/consultacompras.js"></script>