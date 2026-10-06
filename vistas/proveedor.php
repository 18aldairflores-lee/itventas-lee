<?php
require 'header.php';
require 'sidebar.php';
?>

<style>
    /* KPI Cards */
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
    .kpi-icon.purple { background: #faf5ff; color: #7c3aed; }
    
    .kpi-value {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
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

    /* Tabla */
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

    .badge-doc {
        background: #e0f2fe;
        color: #0369a1;
        padding: 4px 10px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 12px;
    }

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

    /* Modal */
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

        <!-- Encabezado -->
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title">Directorio de Proveedores</h1>
                <p class="page-subtitle">Administra los proveedores y empresas asociadas para el abastecimiento comercial.</p>
            </div>
        </div>

        <br>

        <!-- KPI Cards -->
        <div class="row">
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Total Proveedores</span>
                        <div class="kpi-icon blue"><i class="fa fa-truck"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-total-prov">0</h3>
                </div>
            </div>

            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Con Teléfono</span>
                        <div class="kpi-icon green"><i class="fa fa-phone"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-con-tel" style="color: #059669;">0</h3>
                </div>
            </div>

            <div class="col-lg-4 col-sm-12 col-xs-12">
                <div class="kpi-wrapper">
                    <div class="kpi-header">
                        <span>Con Correo Electrónico</span>
                        <div class="kpi-icon purple"><i class="fa fa-envelope"></i></div>
                    </div>
                    <h3 class="kpi-value" id="kpi-con-mail" style="color: #7c3aed;">0</h3>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="card-shell">
            <div class="card-shell-header">
                <div>
                    <h3 class="card-shell-title">
                        <i class="fa fa-address-book text-primary"></i> 
                        <span>Proveedores Registrados</span>
                    </h3>
                </div>

                <button class="btn-create-glow" onclick="abrirModal()">
                    <i class="fa fa-plus"></i> Nuevo Proveedor
                </button>
            </div>

            <div class="card-shell-body" style="padding: 24px;">
                <div class="table-responsive">
                    <table id="tbllistado" class="table table-modern table-hover" style="width:100%">
                        <thead>
                            <th style="width: 10%;">Acciones</th>
                            <th style="width: 25%;">Empresa / Contacto</th>
                            <th style="width: 15%;">Documento</th>
                            <th style="width: 15%;">Teléfono</th>
                            <th style="width: 15%;">Email</th>
                            <th style="width: 20%;">Dirección</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </section>
</div>

<!-- ========================================================
     MODAL DE REGISTRO / EDICIÓN DE PROVEEDOR
========================================================= -->
<div class="modal fade" id="modalProveedor" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-custom">
            
            <div class="modal-header modal-header-custom">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: .8;">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="modalTitulo">
                    <i class="fa fa-truck" style="margin-right: 8px;"></i> Nuevo Proveedor
                </h4>
            </div>

            <form name="formulario" id="formulario" method="POST">
                <div class="modal-body modal-body-custom">
                    <input type="hidden" name="idpersona" id="idpersona">

                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label>Nombre / Razón Social (*)</label>
                            <input type="text" class="input-field-custom" name="nombre" id="nombre" maxlength="100" placeholder="Ej. Distribuidora Tech SAC" required>
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Tipo Documento (*)</label>
                            <select class="input-field-custom" name="tipo_documento" id="tipo_documento" required>
                                <option value="RUC">RUC</option>
                                <option value="DNI">DNI</option>
                                <option value="CEDULA">Cédula</option>
                            </select>
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Número Documento (*)</label>
                            <input type="text" class="input-field-custom" name="num_documento" id="num_documento" maxlength="20" placeholder="20601234567" required>
                        </div>
                    </div>

                    <div class="row" style="margin-top: 15px;">
                        <div class="col-md-6 form-group">
                            <label>Dirección</label>
                            <input type="text" class="input-field-custom" name="direccion" id="direccion" maxlength="70" placeholder="Av. Central 123">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Teléfono</label>
                            <input type="text" class="input-field-custom" name="telefono" id="telefono" maxlength="20" placeholder="987654321">
                        </div>

                        <div class="col-md-3 form-group">
                            <label>Correo Electrónico</label>
                            <input type="email" class="input-field-custom" name="email" id="email" maxlength="50" placeholder="ventas@techsac.com">
                        </div>
                    </div>

                </div>

                <div class="modal-footer modal-footer-custom">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 10px; font-weight: 600; padding: 9px 18px;">Cancelar</button>
                    <button type="submit" id="btnGuardar" class="btn-create-glow">
                        <i class="fa fa-check"></i> Guardar Proveedor
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<?php
require 'footer.php';
?>

<script type="text/javascript" src="scripts/proveedor.js"></script>