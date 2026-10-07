<aside class="main-sidebar">
    <section class="sidebar">
        <!-- Panel de usuario -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="../files/usuarios/<?php echo $_SESSION['imagen']; ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo $_SESSION['nombre']; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> En línea</a>
            </div>
        </div>

        <!-- Menú de navegación -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENÚ PRINCIPAL</li>

            <?php if (isset($_SESSION['escritorio']) && $_SESSION['escritorio'] == 1) { ?>
            <li id="mEscritorio">
                <a href="escritorio.php">
                    <i class="fa fa-dashboard"></i> <span>Escritorio</span>
                </a>
            </li>
            <?php } ?>

            <?php if (isset($_SESSION['almacen']) && $_SESSION['almacen'] == 1) { ?>
            <li class="treeview" id="mAlmacen">
                <a href="#">
                    <i class="fa fa-laptop"></i> <span>Almacén</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id="lArticulos"><a href="articulo.php"><i class="fa fa-circle-o"></i> Artículos</a></li>
                    <li id="lCategorias"><a href="categoria.php"><i class="fa fa-circle-o"></i> Categorías</a></li>
                </ul>
            </li>
            <?php } ?>

            <?php if (isset($_SESSION['compras']) && $_SESSION['compras'] == 1) { ?>
            <li class="treeview" id="mCompras">
                <a href="#">
                    <i class="fa fa-th"></i> <span>Compras</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id="lIngresos"><a href="ingreso.php"><i class="fa fa-circle-o"></i> Ingresos</a></li>
                    <li id="lProveedores"><a href="proveedor.php"><i class="fa fa-circle-o"></i> Proveedores</a></li>
                </ul>
            </li>
            <?php } ?>

            <?php if (isset($_SESSION['ventas']) && $_SESSION['ventas'] == 1) { ?>
            <li class="treeview" id="mVentas">
                <a href="#">
                    <i class="fa fa-shopping-cart"></i> <span>Ventas</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id="lVentas"><a href="venta.php"><i class="fa fa-circle-o"></i> Ventas</a></li>
                    <li id="lClientes"><a href="cliente.php"><i class="fa fa-circle-o"></i> Clientes</a></li>
                </ul>
            </li>
            <?php } ?>

            <?php if (isset($_SESSION['acceso']) && $_SESSION['acceso'] == 1) { ?>
            <li class="treeview" id="mAcceso">
                <a href="#">
                    <i class="fa fa-folder"></i> <span>Seguridad</span>
                    <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                </a>
                <ul class="treeview-menu">
                    <li id="lUsuarios"><a href="usuario.php"><i class="fa fa-circle-o"></i> Usuarios</a></li>
                    <li id="lPermisos"><a href="permiso.php"><i class="fa fa-circle-o"></i> Permisos</a></li>
                </ul>
            </li>
            <?php } ?>

            <li class="header">REPORTES</li>

            <?php if (isset($_SESSION['consultac']) && $_SESSION['consultac'] == 1) { ?>
            <li id="mConsultaC">
                <a href="comprasfecha.php">
                    <i class="fa fa-pie-chart"></i> <span>Reporte de Compras</span>
                </a>
            </li>
            <?php } ?>

            <?php if (isset($_SESSION['consultav']) && $_SESSION['consultav'] == 1) { ?>
            <li id="mConsultaV">
                <a href="ventasfechacliente.php">
                    <i class="fa fa-bar-chart"></i> <span>Reporte de Ventas</span>
                </a>
            </li>
            <?php } ?>

            <li class="header">SISTEMA</li>
            <li>
                <a href="../ajax/usuario.php?op=salir">
                    <i class="fa fa-sign-out text-red"></i> <span>Cerrar Sesión</span>
                </a>
            </li>
        </ul>
    </section>
</aside>