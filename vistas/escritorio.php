<?php
require 'header.php';
require 'sidebar.php';

if (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1) {
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<style>
    .kpi-stat-card {
        background: #ffffff;
        border-radius: 18px;
        padding: 24px;
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: transform .25s ease, box-shadow .25s ease;
    }
    .kpi-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.08);
    }
    .kpi-stat-info span.title {
        font-size: 12.5px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: .5px;
        display: block;
        margin-bottom: 6px;
    }
    .kpi-stat-info h2 {
        font-size: 26px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
    }
    .kpi-stat-icon {
        width: 54px;
        height: 54px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }
    .icon-blue { background: #eff6ff; color: #2563eb; }
    .icon-emerald { background: #ecfdf5; color: #059669; }
    .icon-indigo { background: #eef2ff; color: #4f46e5; }
    .icon-amber { background: #fffbeb; color: #d97706; }

    .chart-container-card {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 6px 20px rgba(15, 23, 42, 0.04);
        padding: 24px;
        margin-bottom: 30px;
        height: 100%;
    }
    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 14px;
    }
    .chart-title {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
</style>

<div class="content-wrapper">
    <section class="content">

        <div class="row">
            <div class="col-md-12">
                <h1 class="page-title">Panel de Control General</h1>
                <p class="page-subtitle">Monitoreo de ingresos, egresos, balance comercial y rotación de productos en inventario.</p>
            </div>
        </div>

        <br>

        <!-- KPI Cards -->
        <div class="row">
            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="kpi-stat-card">
                    <div class="kpi-stat-info">
                        <span class="title">Ventas Totales</span>
                        <h2 id="kpi_ventas" style="color: #059669;">S/ 0.00</h2>
                    </div>
                    <div class="kpi-stat-icon icon-emerald"><i class="fa fa-shopping-cart"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="kpi-stat-card">
                    <div class="kpi-stat-info">
                        <span class="title">Compras Totales</span>
                        <h2 id="kpi_compras" style="color: #2563eb;">S/ 0.00</h2>
                    </div>
                    <div class="kpi-stat-icon icon-blue"><i class="fa fa-truck"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="kpi-stat-card">
                    <div class="kpi-stat-info">
                        <span class="title">Balance Comercial</span>
                        <h2 id="kpi_balance" style="color: #4f46e5;">S/ 0.00</h2>
                    </div>
                    <div class="kpi-stat-icon icon-indigo"><i class="fa fa-line-chart"></i></div>
                </div>
            </div>

            <div class="col-lg-3 col-sm-6 col-xs-12">
                <div class="kpi-stat-card">
                    <div class="kpi-stat-info">
                        <span class="title">Cartera Clientes</span>
                        <h2 id="kpi_clientes" style="color: #d97706;">0</h2>
                    </div>
                    <div class="kpi-stat-icon icon-amber"><i class="fa fa-users"></i></div>
                </div>
            </div>
        </div>

        <!-- Fila de Gráficos -->
        <div class="row">
            <!-- Gráfico de Ventas Recientes -->
            <div class="col-md-4 col-xs-12">
                <div class="chart-container-card">
                    <div class="chart-header">
                        <h3 class="chart-title"><i class="fa fa-line-chart text-success"></i> Ventas Recientes</h3>
                        <span class="badge" style="background:#ecfdf5; color:#059669; font-weight:700;">Últimos 10 Días</span>
                    </div>
                    <canvas id="ventasChart" height="230"></canvas>
                </div>
            </div>

            <!-- Gráfico de Compras Recientes -->
            <div class="col-md-4 col-xs-12">
                <div class="chart-container-card">
                    <div class="chart-header">
                        <h3 class="chart-title"><i class="fa fa-bar-chart text-primary"></i> Compras / Ingresos</h3>
                        <span class="badge" style="background:#eff6ff; color:#2563eb; font-weight:700;">Últimos 10 Días</span>
                    </div>
                    <canvas id="comprasChart" height="230"></canvas>
                </div>
            </div>

            <!-- Gráfico Top 5 Productos Más Vendidos -->
            <div class="col-md-4 col-xs-12">
                <div class="chart-container-card">
                    <div class="chart-header">
                        <h3 class="chart-title"><i class="fa fa-pie-chart text-warning" style="color:#f59e0b;"></i> Más Vendidos</h3>
                        <span class="badge" style="background:#fffbeb; color:#d97706; font-weight:700;">Top 5 Artículos</span>
                    </div>
                    <canvas id="topProductosChart" height="230"></canvas>
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

<script>
$(document).ready(function() {
    // 1. Cargar KPIs superiores
    $.getJSON("../ajax/consultas.php?op=kpis", function(data) {
        $("#kpi_ventas").text("S/ " + data.total_ventas);
        $("#kpi_compras").text("S/ " + data.total_compras);
        $("#kpi_balance").text("S/ " + data.balance);
        $("#kpi_clientes").text(data.total_clientes);
    });

    // 2. Gráfico de Compras
    $.getJSON("../ajax/consultas.php?op=comprasUltimos10Dias", function(data) {
        var ctxCompras = document.getElementById('comprasChart').getContext('2d');
        new Chart(ctxCompras, {
            type: 'bar',
            data: {
                labels: (data.fechas && data.fechas.length) ? data.fechas : ['Sin datos'],
                datasets: [{
                    label: 'Compras (S/)',
                    data: (data.totales && data.totales.length) ? data.totales : [0],
                    backgroundColor: 'rgba(37, 99, 235, 0.75)',
                    borderColor: '#2563eb',
                    borderWidth: 2,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    });

    // 3. Gráfico de Ventas
    $.getJSON("../ajax/consultas.php?op=ventasUltimos10Dias", function(data) {
        var ctxVentas = document.getElementById('ventasChart').getContext('2d');
        new Chart(ctxVentas, {
            type: 'line',
            data: {
                labels: (data.fechas && data.fechas.length) ? data.fechas : ['Sin datos'],
                datasets: [{
                    label: 'Ventas (S/)',
                    data: (data.totales && data.totales.length) ? data.totales : [0],
                    backgroundColor: 'rgba(5, 150, 105, 0.15)',
                    borderColor: '#059669',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#059669',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    });

    // 4. Gráfico Top 5 Productos Más Vendidos (Doughnut)
    $.getJSON("../ajax/consultas.php?op=productosMasVendidos", function(data) {
        var ctxTop = document.getElementById('topProductosChart').getContext('2d');
        var labels = (data.articulos && data.articulos.length > 0) ? data.articulos : ['Sin ventas registradas'];
        var valores = (data.cantidades && data.cantidades.length > 0) ? data.cantidades : [1];

        new Chart(ctxTop, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: valores,
                    backgroundColor: [
                        '#2563eb',
                        '#059669',
                        '#f59e0b',
                        '#ec4899',
                        '#8b5cf6'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: { size: 11, family: 'Poppins' }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
});
</script>