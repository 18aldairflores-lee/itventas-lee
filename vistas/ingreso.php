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

    .btn-create-glow {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 13.5px;
        border-radius: 11px;
        padding: 10px 20px;
        border: none;
        box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all .25s ease;
        cursor: pointer;
    }
    .btn-create-glow:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 22px rgba(37, 99, 235, 0.4);
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

    .badge-cat {
        background: #f1f5f9;
        color: #334155;
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 12px;
    }
    .barcode-badge {
        background: #e0f2fe;
        color: #0369a1;
        padding: 3px 8px;
        border-radius: 6px;
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

    .action-circle-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
        margin-right: 4px;
        cursor: pointer;
    }
    .btn-view { background: #eff6ff; color: #2563eb; }
    .btn-view:hover { background: #dbeafe; }
    .btn-deactivate { background: #fee2e2; color: #b91c1c; }
    .btn-deactivate:hover { background: #fecaca; }
</style>

<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title">Gestión de Ingresos / Compras</h1>
                <p class="page-subtitle">Reabastece el inventario registrando compras a proveedores.</p>
            </div>
        </div>

        <br>

        <!-- KPI superiores -->
        <div class="row">
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Total Compras</span>
                        <div class="kpi-icon blue"><i class="fa fa-shopping-basket"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-total-compras">0</h3>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Inversión Total</span>
                        <div class="kpi-icon green"><i class="fa fa-money"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-inversion" style="color: #059669;">S/ 0.00</h3>
                </div>
            </div>

            <div class="col-lg-4 col-sm-12 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Compras Aceptadas</span>
                        <div class="kpi-icon orange"><i class="fa fa-check"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-aceptadas" style="color: #ea580c;">0</h3>
                </div>
            </div>
        </div>

        <!-- Contenedor Principal -->
        <div class="card-shell">
            <div class="card-shell-header">
                <div>
                    <h3 class="card-shell-title">
                        <i class="fa fa-truck text-primary"></i> 
                        <span>Historial de Ingresos</span>
                    </h3>
                </div>

                <button class="btn-create-glow" id="btnagregar" onclick="mostrarform(true)">
                    <i class="fa fa-plus"></i> Nueva Compra
                </button>
            </div>

            <div class="card-shell-body" style="padding: 24px;">
                
                <!-- LISTADO -->
                <div class="table-responsive" id="listadoregistros">
                    <table id="tbllistado" class="table table-modern table-hover" style="width:100%">
                        <thead>
                            <th style="width: 10%;">Acciones</th>
                            <th style="width: 12%;">Fecha</th>
                            <th style="width: 25%;">Proveedor</th>
                            <th style="width: 15%;">Usuario</th>
                            <th style="width: 18%;">Documento</th>
                            <th style="width: 10%;">Total</th>
                            <th style="width: 10%;">Estado</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- FORMULARIO DE COMPRA -->
                <div id="formularioregistros" style="display: none;">
                    <form name="formulario" id="formulario" method="POST">
                        <input type="hidden" name="idingreso" id="idingreso">

                        <div class="row">
                            <div class="col-md-8 form-group">
                                <label>Proveedor (*)</label>
                                <select id="idproveedor" name="idproveedor" class="input-field-custom" required></select>
                            </div>

                            <div class="col-md-4 form-group">
                                <label>Fecha (*)</label>
                                <input type="date" class="input-field-custom" name="fecha_hora" id="fecha_hora" required>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-4 form-group">
                                <label>Tipo Comprobante (*)</label>
                                <select name="tipo_comprobante" id="tipo_comprobante" class="input-field-custom" required>
                                    <option value="Factura">Factura</option>
                                    <option value="Boleta">Boleta</option>
                                    <option value="Ticket">Ticket</option>
                                </select>
                            </div>

                            <div class="col-md-2 form-group">
                                <label>Serie</label>
                                <input type="text" class="input-field-custom" name="serie_comprobante" id="serie_comprobante" maxlength="7" placeholder="F001">
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Número (*)</label>
                                <input type="text" class="input-field-custom" name="num_comprobante" id="num_comprobante" maxlength="10" placeholder="0000123" required>
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Impuesto (IGV)</label>
                                <input type="text" class="input-field-custom" name="impuesto" id="impuesto" value="0.18" required>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-primary" onclick="abrirModalArticulos()" style="border-radius: 9px; padding: 9px 18px; font-weight:700;">
                                    <i class="fa fa-plus"></i> Agregar Artículos a la Compra
                                </button>
                            </div>
                        </div>

                        <!-- TABLA DETALLE -->
                        <div class="table-responsive">
                            <table id="detalles" class="table table-modern table-striped" style="width:100%;">
                                <thead style="background-color:#f8fafc; color:#475569; font-size:12px;">
                                    <th style="width: 8%;">Eliminar</th>
                                    <th style="width: 35%;">Artículo</th>
                                    <th style="width: 12%;">Cantidad</th>
                                    <th style="width: 15%;">Precio Compra</th>
                                    <th style="width: 15%;">Precio Venta</th>
                                    <th style="width: 15%;">Subtotal</th>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <th>SUBTOTAL</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="subtotal_mostrado" style="margin:0; font-weight:700;">S/ 0.00</h4></th>
                                </tfoot>
                                <tfoot>
                                    <th>IGV (18%)</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th><h4 id="igv_mostrado" style="margin:0; font-weight:700;">S/ 0.00</h4></th>
                                </tfoot>
                                <tfoot>
                                    <th>TOTAL COMPRA</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                        <h3 id="total_mostrado" style="margin:0; font-weight:800; color:#2563eb;">S/ 0.00</h3>
                                        <input type="hidden" name="total_compra" id="total_compra">
                                    </th>
                                </tfoot>
                            </table>
                        </div>

                        <div style="margin-top: 25px; display: flex; gap: 10px;">
                            <button class="btn-create-glow" type="submit" id="btnGuardar">
                                <i class="fa fa-save"></i> Guardar Compra
                            </button>
                            <button class="btn btn-default" onclick="cancelarform()" type="button" style="border-radius: 10px; font-weight:600; padding:10px 20px;">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </section>
</div>

<!-- ========================================================
     MODAL PARA SELECCIONAR ARTÍCULOS
========================================================= -->
<div class="modal fade" id="modalArticulos" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 18px; overflow:hidden;">
            <div class="modal-header" style="background:#0f172a; color:#fff; padding:18px 24px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:1;">&times;</button>
                <h4 class="modal-title" style="font-weight:700;"><i class="fa fa-cubes"></i> Seleccione los Artículos</h4>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <div class="table-responsive">
                    <table id="tblarticulos" class="table table-modern table-hover" style="width:100%;">
                        <thead>
                            <th>Agregar</th>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Código</th>
                            <th>Stock Actual</th>
                            <th>Imagen</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require 'footer.php';
?>

<script type="text/javascript" src="scripts/ingreso.js"></script>