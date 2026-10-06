<?php
require 'header.php';
require 'sidebar.php';
?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title">Gestión de Artículos</h1>
                <p class="page-subtitle">Administra los productos del inventario.</p>
            </div>
        </div>

        <br>

        <!-- Contenedor con estilo moderno -->
        <div class="modern-box">
            <div class="modern-box-header">
                <div class="modern-box-title">Listado de Artículos</div>
                <button class="btn btn-modern btn-primary-modern">
                    <i class="fa fa-plus"></i> Agregar Artículo
                </button>
            </div>
            <div class="modern-box-body">
                <!-- Aquí va tu tabla Datatable o contenido -->
            </div>
        </div>
    </section>
</div>

<?php
require 'footer.php';
?>