<?php
require 'header.php';
require 'sidebar.php';

if (isset($_SESSION['almacen']) && $_SESSION['almacen'] == 1) {
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
    .table-modern tbody tr:hover {
        background: #f8fafc;
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
    .stock-badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 12px;
    }
    .stock-ok { background: #dcfce7; color: #166534; }
    .stock-low { background: #fee2e2; color: #991b1b; }

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
    .btn-edit { background: #fef3c7; color: #b45309; }
    .btn-edit:hover { background: #fde68a; }
    .btn-deactivate { background: #fee2e2; color: #b91c1c; }
    .btn-deactivate:hover { background: #fecaca; }
    .btn-activate { background: #dcfce7; color: #15803d; }
    .btn-activate:hover { background: #bbf7d0; }

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
</style>

<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title">Catálogo de Artículos</h1>
                <p class="page-subtitle">Control de inventario, stock disponible y códigos de barras.</p>
            </div>
        </div>

        <br>

        <div class="row">
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Total de Artículos</span>
                        <div class="kpi-icon blue"><i class="fa fa-cubes"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-total-art">0</h3>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>En Stock</span>
                        <div class="kpi-icon green"><i class="fa fa-check-circle"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-stock-art" style="color: #059669;">0</h3>
                </div>
            </div>

            <div class="col-lg-4 col-sm-12 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Stock Bajo (≤ 5)</span>
                        <div class="kpi-icon orange"><i class="fa fa-exclamation-triangle"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-bajo-art" style="color: #ea580c;">0</h3>
                </div>
            </div>
        </div>

        <div class="card-shell">
            <div class="card-shell-header">
                <div>
                    <h3 class="card-shell-title">
                        <i class="fa fa-box text-primary"></i> 
                        <span>Listado de Artículos</span>
                    </h3>
                </div>

                <button class="btn-create-glow" id="btnagregar" onclick="mostrarform(true)">
                    <i class="fa fa-plus"></i> Nuevo Artículo
                </button>
            </div>

            <div class="card-shell-body" style="padding: 24px;">
                
                <div class="table-responsive" id="listadoregistros">
                    <table id="tbllistado" class="table table-modern table-hover" style="width:100%">
                        <thead>
                            <th style="width: 10%;">Acciones</th>
                            <th style="width: 20%;">Nombre</th>
                            <th style="width: 15%;">Categoría</th>
                            <th style="width: 15%;">Código</th>
                            <th style="width: 10%;">Stock</th>
                            <th style="width: 10%;">Imagen</th>
                            <th style="width: 10%;">Estado</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div id="formularioregistros" style="display: none;">
                    <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idarticulo" id="idarticulo">

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Nombre (*)</label>
                                <input type="text" class="input-field-custom" name="nombre" id="nombre" maxlength="100" placeholder="Nombre del artículo" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Categoría (*)</label>
                                <select id="idcategoria" name="idcategoria" class="input-field-custom" required></select>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6 form-group">
                                <label>Stock (*)</label>
                                <input type="number" class="input-field-custom" name="stock" id="stock" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Descripción</label>
                                <input type="text" class="input-field-custom" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripción breve">
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6 form-group">
                                <label>Imagen</label>
                                <input type="file" class="input-field-custom" name="imagen" id="imagen" accept="image/*">
                                <input type="hidden" name="imagenactual" id="imagenactual">
                                <div style="margin-top: 10px;">
                                    <img src="" width="65px" height="65px" id="imagenmuestra" style="border-radius: 12px; object-fit: cover; display: none;">
                                </div>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Código de Barras</label>
                                <input type="text" class="input-field-custom" name="codigo" id="codigo" placeholder="Código de barras">
                                <button class="btn btn-default btn-sm" type="button" onclick="generarbarcode()" style="margin-top: 8px; border-radius: 8px;">
                                    <i class="fa fa-barcode"></i> Generar Barra
                                </button>
                                <div id="print" style="margin-top: 10px;">
                                    <svg id="barcode"></svg>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 25px; display: flex; gap: 10px;">
                            <button class="btn-create-glow" type="submit" id="btnGuardar">
                                <i class="fa fa-save"></i> Guardar
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

<?php
} else {
    require 'noacceso.php';
}
require 'footer.php';
?>

<!-- Librería de códigos de barra JS -->
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script type="text/javascript" src="scripts/articulo.js"></script>