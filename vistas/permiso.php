<?php
require 'header.php';
require 'sidebar.php';
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
    .dot-indicator { width: 7px; height: 7px; border-radius: 50%; background: #22c55e; }
</style>

<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title">Permisos del Sistema</h1>
                <p class="page-subtitle">Listado maestro de privilegios disponibles para asignar a los usuarios.</p>
            </div>
        </div>

        <br>

        <div class="card-shell">
            <div class="card-shell-header">
                <h3 class="card-shell-title">
                    <i class="fa fa-key text-primary"></i> 
                    <span>Catálogo de Privilegios</span>
                </h3>
            </div>

            <div class="card-shell-body" style="padding: 24px;">
                <div class="table-responsive">
                    <table id="tbllistado" class="table table-modern table-hover" style="width:100%">
                        <thead>
                            <th style="width: 15%;">Identificador</th>
                            <th style="width: 60%;">Nombre del Permiso / Módulo</th>
                            <th style="width: 25%;">Estado</th>
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

<script type="text/javascript" src="scripts/permiso.js"></script>