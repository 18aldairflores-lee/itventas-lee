<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu">
            <li class="header">MENÚ PRINCIPAL</li>

            <!-- Escritorio / Dashboard -->
            <li id="mEscritorio">
                <a href="escritorio.php">
                    <i class="fa fa-dashboard"></i> <span>Escritorio</span>
                </a>
            </li>

            <!-- Almacén -->
            <li class="treeview" id="mAlmacen">
                <a href="#">
                    <i class="fa fa-cubes"></i> <span>Almacén</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li id="lArticulos"><a href="articulo.php"><i class="fa fa-barcode"></i> Artículos</a></li>
                    <li id="lCategorias"><a href="categoria.php"><i class="fa fa-tags"></i> Categorías</a></li>
                </ul>
            </li>

            <!-- Compras -->
            <li class="treeview" id="mCompras">
                <a href="#">
                    <i class="fa fa-shopping-bag"></i> <span>Compras</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li id="lIngresos"><a href="ingreso.php"><i class="fa fa-truck"></i> Ingresos / Compras</a></li>
                    <li id="lProveedores"><a href="proveedor.php"><i class="fa fa-address-book"></i> Proveedores</a></li>
                </ul>
            </li>

            <!-- Ventas -->
            <li class="treeview" id="mVentas">
                <a href="#">
                    <i class="fa fa-line-chart"></i> <span>Ventas</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li id="lVentas"><a href="venta.php"><i class="fa fa-shopping-cart"></i> Ventas</a></li>
                    <li id="lClientes"><a href="cliente.php"><i class="fa fa-users"></i> Clientes</a></li>
                </ul>
            </li>

            <!-- Seguridad -->
            <li class="treeview" id="mSeguridad">
                <a href="#">
                    <i class="fa fa-shield"></i> <span>Seguridad</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li id="lUsuarios"><a href="usuario.php"><i class="fa fa-user-circle"></i> Usuarios</a></li>
                    <li id="lPermisos"><a href="permiso.php"><i class="fa fa-key"></i> Permisos</a></li>
                </ul>
            </li>

            <li class="header">REPORTES</li>
            <li id="mConsultaCompras">
                <a href="consultacompras.php">
                    <i class="fa fa-pie-chart"></i> <span>Reporte de Compras</span>
                </a>
            </li>
            <li id="mConsultaVentas">
                <a href="consultaventas.php">
                    <i class="fa fa-bar-chart"></i> <span>Reporte de Ventas</span>
                </a>
            </li>

            <li class="header">SISTEMA</li>
            <li>
                <a href="#">
                    <i class="fa fa-question-circle"></i> <span>Ayuda</span>
                    <small class="label pull-right bg-red">PDF</small>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-info-circle"></i> <span>Acerca de</span>
                    <small class="label pull-right bg-blue">IT Lee</small>
                </a>
            </li>
        </ul>
    </section>
</aside>