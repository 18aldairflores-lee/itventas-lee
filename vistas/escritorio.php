<?php
// Incluye la cabecera y el sidebar
require 'header.php';
require 'sidebar.php';
?>

<div class="content-wrapper">
    <section class="content">
        <!-- Encabezado -->
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title">Panel de control</h1>
                <p class="page-subtitle">
                    Bienvenido al sistema de gestión comercial ITVentas. Aquí tienes un resumen de la actividad de tu negocio.
                </p>
            </div>
        </div>

        <br>

        <!-- Métricas / Estadísticas -->
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon icon-blue"><i class="fa fa-line-chart"></i></div>
                    <div class="stat-title">Ventas del mes</div>
                    <div class="stat-number">S/ 12,850</div>
                    <div class="stat-footer"><span class="positive"><i class="fa fa-arrow-up"></i> 12.5%</span> vs mes anterior</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon icon-orange"><i class="fa fa-shopping-bag"></i></div>
                    <div class="stat-title">Compras del mes</div>
                    <div class="stat-number">S/ 7,420</div>
                    <div class="stat-footer"><span class="positive"><i class="fa fa-arrow-up"></i> 8.2%</span> vs mes anterior</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon icon-green"><i class="fa fa-users"></i></div>
                    <div class="stat-title">Clientes registrados</div>
                    <div class="stat-number">248</div>
                    <div class="stat-footer"><span class="positive">+18</span> nuevos este mes</div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="stat-card">
                    <div class="stat-icon icon-purple"><i class="fa fa-cubes"></i></div>
                    <div class="stat-title">Productos</div>
                    <div class="stat-number">1,284</div>
                    <div class="stat-footer"><span class="positive">96%</span> disponibles</div>
                </div>
            </div>
        </div>

        <!-- Tablas y Accesos Rápidos -->
        <div class="row">
            <div class="col-md-8">
                <div class="modern-box">
                    <div class="modern-box-header">
                        <div class="modern-box-title"><i class="fa fa-clock-o text-primary"></i> Actividad reciente</div>
                        <a href="venta.php" class="btn btn-modern btn-primary-modern"><i class="fa fa-plus"></i> Nueva venta</a>
                    </div>
                    <div class="modern-box-body">
                        <div class="table-responsive">
                            <table class="table-modern">
                                <thead>
                                    <tr>
                                        <th>Operación</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Total</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Venta #00125</strong></td>
                                        <td>Carlos Mendoza</td>
                                        <td>Hoy, 10:35</td>
                                        <td><strong>S/ 450.00</strong></td>
                                        <td><span class="status status-success">Completada</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Venta #00124</strong></td>
                                        <td>María López</td>
                                        <td>Hoy, 09:20</td>
                                        <td><strong>S/ 280.00</strong></td>
                                        <td><span class="status status-success">Completada</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Compra #00089</strong></td>
                                        <td>Distribuidora ABC</td>
                                        <td>Ayer, 16:45</td>
                                        <td><strong>S/ 1,250.00</strong></td>
                                        <td><span class="status status-warning">Pendiente</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Venta #00123</strong></td>
                                        <td>José Torres</td>
                                        <td>Ayer, 14:10</td>
                                        <td><strong>S/ 720.00</strong></td>
                                        <td><span class="status status-success">Completada</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="modern-box">
                    <div class="modern-box-header">
                        <div class="modern-box-title">Accesos rápidos</div>
                    </div>
                    <div class="modern-box-body">
                        <a href="venta.php" class="quick-action">
                            <div class="quick-icon"><i class="fa fa-shopping-cart"></i></div>
                            <div>
                                <div class="quick-title">Registrar venta</div>
                                <div class="quick-description">Crear una nueva venta</div>
                            </div>
                        </a>
                        <a href="articulo.php" class="quick-action">
                            <div class="quick-icon"><i class="fa fa-cube"></i></div>
                            <div>
                                <div class="quick-title">Nuevo producto</div>
                                <div class="quick-description">Agregar artículo al almacén</div>
                            </div>
                        </a>
                        <a href="cliente.php" class="quick-action">
                            <div class="quick-icon"><i class="fa fa-user-plus"></i></div>
                            <div>
                                <div class="quick-title">Nuevo cliente</div>
                                <div class="quick-description">Registrar cliente</div>
                            </div>
                        </a>
                        <a href="consultaventas.php" class="quick-action">
                            <div class="quick-icon"><i class="fa fa-file-text-o"></i></div>
                            <div>
                                <div class="quick-title">Ver reportes</div>
                                <div class="quick-description">Consultar ventas y estadísticas</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
// Incluye el footer
require 'footer.php';
?>