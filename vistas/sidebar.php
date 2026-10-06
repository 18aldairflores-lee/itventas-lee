<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Panel de Usuario -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="../files/usuarios/<?php echo !empty($_SESSION['imagen']) ? $_SESSION['imagen'] : 'defecto.png'; ?>" class="img-circle" onerror="this.src='../public/img/logo1.png';" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Usuario'; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> En línea</a>
            </div>
        </div>

        <!-- Menú de Navegación Dinámico -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENÚ PRINCIPAL</li>

            <!-- 1. ESCRITORIO -->
            <?php if (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1) { ?>
            <li>
                <a href="escritorio.php">
                    <i class="fa fa-dashboard"></i> <span>Escritorio</span>
                </a>
            </li>
            <?php } ?>

            <!-- 2. ALMACÉN -->
            <?php if (isset($_SESSION['almacen']) && $_SESSION['almacen'] == 1) { ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-laptop"></i>
                    <span>Almacén</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="articulo.php"><i class="fa fa-circle-o"></i> Artículos</a></li>
                    <li><a href="categoria.php"><i class="fa fa-circle-o"></i> Categorías</a></li>
                </ul>
            </li>
            <?php } ?>

            <!-- 3. COMPRAS -->
            <?php if (isset($_SESSION['compras']) && $_SESSION['compras'] == 1) { ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-th"></i>
                    <span>Compras</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="ingreso.php"><i class="fa fa-circle-o"></i> Ingresos / Compras</a></li>
                    <li><a href="proveedor.php"><i class="fa fa-circle-o"></i> Proveedores</a></li>
                </ul>
            </li>
            <?php } ?>

            <!-- 4. VENTAS -->
            <?php if (isset($_SESSION['ventas']) && $_SESSION['ventas'] == 1) { ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i>
                    <span>Ventas</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="venta.php"><i class="fa fa-circle-o"></i> Ventas</a></li>
                    <li><a href="cliente.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
                </ul>
            </li>
            <?php } ?>

            <!-- 5. SEGURIDAD / ACCESO -->
            <?php if (isset($_SESSION['acceso']) && $_SESSION['acceso'] == 1) { ?>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Seguridad</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                    <li><a href="permiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
                </ul>
            </li>
            <?php } ?>

            <!-- REPORTES -->
            <?php if ((isset($_SESSION['consultac']) && $_SESSION['consultac'] == 1) || (isset($_SESSION['consultav']) && $_SESSION['consultav'] == 1)) { ?>
            <li class="header">REPORTES</li>
            <?php } ?>

            <!-- 6. CONSULTA COMPRAS -->
            <?php if (isset($_SESSION['consultac']) && $_SESSION['consultac'] == 1) { ?>
            <li>
                <a href="consultacompras.php">
                    <i class="fa fa-pie-chart"></i> <span>Reporte de Compras</span>
                </a>
            </li>
            <?php } ?>

            <!-- 7. CONSULTA VENTAS -->
            <?php if (isset($_SESSION['consultav']) && $_SESSION['consultav'] == 1) { ?>
            <li>
                <a href="consultaventas.php">
                    <i class="fa fa-bar-chart"></i> <span>Reporte de Ventas</span>
                </a>
            </li>
            <?php } ?>

            <li class="header">SISTEMA</li>
            <li><a href="../ajax/usuario.php?op=salir"><i class="fa fa-sign-out text-danger"></i> <span>Cerrar Sesión</span></a></li>
        </ul>
    </section>
</aside>