<?php
require 'header.php';
require 'sidebar.php';

if (isset($_SESSION['acceso']) && $_SESSION['acceso'] == 1) {
?>

<style>
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
    .img-table {
        width: 42px;
        height: 42px;
        object-fit: cover;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
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

    .permisos-card {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        max-height: 220px;
        overflow-y: auto;
    }
</style>

<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title">Gestión de Usuarios y Accesos</h1>
                <p class="page-subtitle">Administra los usuarios del sistema, credenciales y asignación de permisos por módulo.</p>
            </div>
        </div>

        <br>

        <div class="card-shell">
            <div class="card-shell-header">
                <div>
                    <h3 class="card-shell-title">
                        <i class="fa fa-user-circle text-primary"></i> 
                        <span>Usuarios del Sistema</span>
                    </h3>
                </div>

                <button class="btn-create-glow" id="btnagregar" onclick="mostrarform(true)">
                    <i class="fa fa-user-plus"></i> Nuevo Usuario
                </button>
            </div>

            <div class="card-shell-body" style="padding: 24px;">
                
                <!-- LISTADO -->
                <div class="table-responsive" id="listadoregistros">
                    <table id="tbllistado" class="table table-modern table-hover" style="width:100%">
                        <thead>
                            <th style="width: 10%;">Acciones</th>
                            <th style="width: 20%;">Nombre Completo</th>
                            <th style="width: 15%;">Cargo</th>
                            <th style="width: 15%;">Usuario (Login)</th>
                            <th style="width: 15%;">Teléfono</th>
                            <th style="width: 10%;">Foto</th>
                            <th style="width: 15%;">Estado</th>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <!-- FORMULARIO -->
                <div id="formularioregistros" style="display: none;">
                    <form name="formulario" id="formulario" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idusuario" id="idusuario">

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Nombre Completo (*)</label>
                                <input type="text" class="input-field-custom" name="nombre" id="nombre" maxlength="100" placeholder="Ej. Roy Aldair Flores" required>
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Tipo Documento (*)</label>
                                <select class="input-field-custom" name="tipo_documento" id="tipo_documento" required>
                                    <option value="DNI">DNI</option>
                                    <option value="RUC">RUC</option>
                                    <option value="CEDULA">Cédula</option>
                                </select>
                            </div>

                            <div class="col-md-3 form-group">
                                <label>Número Documento (*)</label>
                                <input type="text" class="input-field-custom" name="num_documento" id="num_documento" maxlength="20" placeholder="70123456" required>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-4 form-group">
                                <label>Cargo / Puesto</label>
                                <input type="text" class="input-field-custom" name="cargo" id="cargo" maxlength="20" placeholder="Ej. Administrador / Cajero">
                            </div>

                            <div class="col-md-4 form-group">
                                <label>Usuario de Acceso (Login) (*)</label>
                                <input type="text" class="input-field-custom" name="login" id="login" maxlength="20" placeholder="Ej. roy" required>
                            </div>

                            <div class="col-md-4 form-group">
                                <label>Contraseña (*)</label>
                                <input type="password" class="input-field-custom" name="clave" id="clave" maxlength="64" placeholder="••••••••">
                                <input type="hidden" name="claveactual" id="claveactual">
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6 form-group">
                                <label>Teléfono</label>
                                <input type="text" class="input-field-custom" name="telefono" id="telefono" maxlength="20" placeholder="987654321">
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Correo Electrónico</label>
                                <input type="email" class="input-field-custom" name="email" id="email" maxlength="50" placeholder="usuario@correo.com">
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px;">
                            <div class="col-md-6 form-group">
                                <label>Foto de Perfil</label>
                                <input type="file" class="input-field-custom" name="imagen" id="imagen" accept="image/*">
                                <input type="hidden" name="imagenactual" id="imagenactual">
                                <div style="margin-top: 10px;">
                                    <img src="" width="65px" height="65px" id="imagenmuestra" style="border-radius: 12px; object-fit: cover; border: 1px solid #e2e8f0; display: none;">
                                </div>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>Permisos de Módulos (*)</label>
                                <div class="permisos-card">
                                    <ul id="permisos" style="padding-left: 0; margin-bottom: 0;"></ul>
                                </div>
                            </div>
                        </div>

                        <div style="margin-top: 25px; display: flex; gap: 10px;">
                            <button class="btn-create-glow" type="submit" id="btnGuardar">
                                <i class="fa fa-save"></i> Guardar Usuario
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

<script type="text/javascript" src="scripts/usuario.js"></script>