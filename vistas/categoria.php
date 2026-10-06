<?php
require 'header.php';
require 'sidebar.php';
?>

<style>
    /* Tarjetas KPI Modernas */
    .kpi-wrapper {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px 22px;
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
        position: relative;
        overflow: hidden;
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
    .kpi-icon.purple { background: #faf5ff; color: #7c3aed; }
    
    .kpi-value {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .kpi-progress {
        height: 6px;
        border-radius: 6px;
        background: #f1f5f9;
        margin-top: 14px;
        overflow: hidden;
    }
    .kpi-progress-bar {
        height: 100%;
        background: linear-gradient(90deg, #2563eb, #3b82f6);
        border-radius: 6px;
        transition: width .6s ease;
    }

    /* Caja contenedora */
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
    
    /* Chips de filtro rápido */
    .filter-chips {
        display: flex;
        gap: 8px;
    }
    .chip {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        color: #64748b;
        transition: all .2s ease;
    }
    .chip:hover, .chip.active {
        background: #2563eb;
        color: #ffffff;
        border-color: #2563eb;
        box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
    }

    /* Botón Crear */
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

    /* Tabla Estilizada */
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
        padding: 15px 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-size: 13.5px;
    }
    .table-modern tbody tr:hover {
        background: #f8fafc;
    }

    /* Badges */
    .badge-pill-modern {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 700;
    }
    .badge-pill-success {
        background: #dcfce7;
        color: #166534;
    }
    .badge-pill-danger {
        background: #fee2e2;
        color: #991b1b;
    }
    .dot-indicator {
        width: 7px;
        height: 7px;
        border-radius: 50%;
    }
    .badge-pill-success .dot-indicator { background: #22c55e; }
    .badge-pill-danger .dot-indicator { background: #ef4444; }

    /* Botones de acción circulares */
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
    .btn-edit:hover { background: #fde68a; transform: scale(1.08); }
    .btn-deactivate { background: #fee2e2; color: #b91c1c; }
    .btn-deactivate:hover { background: #fecaca; transform: scale(1.08); }
    .btn-activate { background: #dcfce7; color: #15803d; }
    .btn-activate:hover { background: #bbf7d0; transform: scale(1.08); }

    /* Modal Glassmorphism */
    .modal-content-custom {
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
        overflow: hidden;
    }
    .modal-header-custom {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        color: #ffffff;
        padding: 20px 26px;
        border: none;
    }
    .modal-header-custom .modal-title {
        font-weight: 800;
        font-size: 18px;
        color: #ffffff;
    }
    .modal-body-custom {
        padding: 26px;
        background: #ffffff;
    }
    .modal-footer-custom {
        padding: 16px 26px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
        gap: 10px;
    }

    /* Inputs del Modal */
    .input-wrapper label {
        font-size: 13px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 7px;
    }
    .input-field-custom {
        height: 46px;
        border-radius: 10px;
        border: 1.5px solid #cbd5e1;
        background: #f8fafc;
        padding: 10px 14px;
        font-size: 14px;
        width: 100%;
        color: #0f172a;
        transition: all .2s;
    }
    .input-field-custom:focus {
        border-color: #2563eb;
        background: #ffffff;
        outline: none;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }
</style>

<div class="content-wrapper">
    <section class="content">

        <!-- Encabezado -->
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title">Gestión de Categorías</h1>
                <p class="page-subtitle">Organiza y segmenta el catálogo comercial de tu negocio en tiempo real.</p>
            </div>
        </div>

        <br>

        <!-- Métricas KPI Dinámicas -->
        <div class="row">
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Total Categorías</span>
                        <div class="kpi-icon blue"><i class="fa fa-th-large"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-total">0</h3>
                    <div class="kpi-progress">
                        <div class="kpi-progress-bar" id="kpi-bar-total" style="width: 100%;"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Categorías Activas</span>
                        <div class="kpi-icon green"><i class="fa fa-check-circle"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-activas" style="color: #059669;">0</h3>
                    <div class="kpi-progress">
                        <div class="kpi-progress-bar" id="kpi-bar-activas" style="width: 0%; background: #10b981;"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-sm-12 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Índice Operativo</span>
                        <div class="kpi-icon purple"><i class="fa fa-line-chart"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-porcentaje" style="color: #7c3aed;">0%</h3>
                    <div class="kpi-progress">
                        <div class="kpi-progress-bar" id="kpi-bar-porcentaje" style="width: 0%; background: #8b5cf6;"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenedor Principal de la Tabla -->
        <div class="card-shell">
            <div class="card-shell-header">
                <div>
                    <h3 class="card-shell-title">
                        <i class="fa fa-folder-open text-primary"></i> 
                        <span>Catálogo de Categorías</span>
                    </h3>
                </div>

                <!-- Filtros rápidos por estado -->
                <div class="filter-chips">
                    <span class="chip active" onclick="filtrarEstado('')">Todos</span>
                    <span class="chip" onclick="filtrarEstado('Activo')"><i class="fa fa-check text-green"></i> Activos</span>
                    <span class="chip" onclick="filtrarEstado('Inactivo')"><i class="fa fa-times text-red"></i> Inactivos</span>
                </div>

                <button class="btn-create-glow" onclick="abrirModal()">
                    <i class="fa fa-plus"></i> Nueva Categoría
                </button>
            </div>

            <div class="card-shell-body" style="padding: 24px;">
                <div class="table-responsive">
                    <table id="tbllistado" class="table table-modern table-hover" style="width:100%">
                        <thead>
                            <th style="width: 14%;">Acciones</th>
                            <th style="width: 30%;">Categoría</th>
                            <th style="width: 40%;">Descripción</th>
                            <th style="width: 16%;">Estado</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>

<!-- ========================================================
     MODAL MODERNO DE REGISTRO / EDICIÓN
========================================================= -->
<div class="modal fade" id="modalCategoria" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content modal-content-custom">
            
            <div class="modal-header modal-header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: .8;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalTitulo">
                    <i class="fa fa-tag" style="margin-right: 8px;"></i> Nueva Categoría
                </h4>
            </div>

            <form name="formulario" id="formulario" method="POST">
                <div class="modal-body modal-body-custom">
                    <input type="hidden" name="idcategoria" id="idcategoria">

                    <div class="form-group input-wrapper">
                        <label for="nombre">Nombre de la Categoría (*)</label>
                        <input type="text" class="input-field-custom" name="nombre" id="nombre" maxlength="50" placeholder="Ej. Laptops, Smartphones, Teclados" required>
                    </div>

                    <div class="form-group input-wrapper" style="margin-top: 18px;">
                        <label for="descripcion">Descripción</label>
                        <textarea class="input-field-custom" name="descripcion" id="descripcion" rows="3" maxlength="256" style="height: auto; resize: vertical;" placeholder="Breve descripción del alcance de esta categoría..."></textarea>
                    </div>
                </div>

                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 10px; font-weight: 600; padding: 9px 18px;">Cancelar</button>
                    <button type="submit" id="btnGuardar" class="btn-create-glow">
                        <i class="fa fa-check"></i> Guardar Categoría
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php
require 'footer.php';
?>

<!-- Script JS del módulo -->
<script type="text/javascript" src="scripts/categoria.js"></script>