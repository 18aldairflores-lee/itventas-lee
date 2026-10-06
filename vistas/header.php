<?php 
if (strlen(session_id()) < 1) {
    session_start();
}

// Si no existe la sesión de usuario, expulsar directamente al login
if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITVentas | Sistema de Gestión</title>

    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../public/css/font-awesome.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">

    <!-- DATATABLES ESTILOS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap.min.css">

    <!-- Tipografía Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="shortcut icon" href="../public/img/favicon.ico">

    <style>
        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background: #f4f7fb; color: #1f2937; }
        
        /* HEADER */
        .main-header { 
            background: #ffffff !important; 
            border-bottom: 1px solid #e5e7eb;
            max-height: 50px;
        }
        .main-header .navbar { 
            background: #ffffff !important; 
            margin-left: 230px;
            min-height: 50px;
        }
        .main-header .logo {
            background: linear-gradient(135deg, #2563eb, #1d4ed8) !important;
            color: #ffffff !important; 
            font-weight: 800; 
            font-size: 20px; 
            border: none;
            height: 50px;
            line-height: 50px;
        }
        .main-header .sidebar-toggle { 
            color: #334155 !important; 
            padding: 15px;
            line-height: 20px;
        }
        .main-header .sidebar-toggle:hover { background: #eff6ff !important; }
        
        /* USUARIO */
        .user-menu > a { 
            color: #334155 !important; 
            display: flex !important;
            align-items: center;
            height: 50px;
            padding: 10px 15px !important;
        }
        .user-menu .user-image { 
            width: 32px !important;
            height: 32px !important;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            margin-top: 0 !important;
            border: 2px solid #2563eb;
        }
        .user-header img.img-circle {
            width: 90px !important;
            height: 90px !important;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.7);
        }
        .user-header { 
            background: linear-gradient(135deg, #1d4ed8, #2563eb) !important; 
            padding: 20px;
            text-align: center;
        }
        .user-header p {
            color: #ffffff;
            font-size: 15px;
            margin-top: 10px;
        }

        /* SIDEBAR */
        .main-sidebar { 
            background: linear-gradient(180deg, #0f172a, #111827) !important; 
            padding-top: 50px;
        }
        .sidebar { padding-top: 10px; }
        .sidebar-menu > li.header { 
            background: transparent !important; 
            color: #64748b !important; 
            font-size: 11px; 
            font-weight: 700; 
            text-transform: uppercase; 
            padding: 15px 20px 8px; 
        }
        .sidebar-menu > li > a { 
            margin: 4px 10px; 
            border-radius: 10px; 
            color: #cbd5e1 !important; 
            padding: 13px 15px; 
            transition: all .25s ease; 
        }
        .sidebar-menu > li > a:hover { 
            background: rgba(37, 99, 235, .18) !important; 
            color: #ffffff !important; 
            transform: translateX(3px); 
        }
        .sidebar-menu > li.active > a { 
            background: linear-gradient(135deg, #2563eb, #1d4ed8) !important; 
            color: #ffffff !important; 
            box-shadow: 0 6px 18px rgba(37, 99, 235, .25); 
        }
        .sidebar-menu > li > a > .fa { width: 25px; text-align: center; font-size: 16px; }
        .treeview-menu { background: rgba(0,0,0,.12) !important; }
        .treeview-menu > li > a { color: #94a3b8 !important; padding: 10px 10px 10px 45px; }
        .treeview-menu > li > a:hover { color: #ffffff !important; background: rgba(255,255,255,.05); }
        
        /* CONTENEDOR */
        .content-wrapper { background: #f4f7fb !important; }
        .content { padding: 25px; }
        .page-title { font-size: 28px; font-weight: 800; color: #0f172a; margin: 0; }
        .page-subtitle { color: #64748b; margin-top: 6px; font-size: 14px; }

        /* TARJETAS Y CONTENEDORES */
        .modern-box { background: #ffffff; border-radius: 16px; border: 1px solid #e5e7eb; box-shadow: 0 5px 20px rgba(15,23,42,.05); overflow: hidden; margin-bottom: 25px; }
        .modern-box-header { padding: 20px 22px; border-bottom: 1px solid #eef2f7; display: flex; justify-content: space-between; align-items: center; }
        .modern-box-title { font-weight: 800; font-size: 17px; color: #0f172a; }
        .modern-box-body { padding: 22px; }
        .btn-modern { border: none; border-radius: 9px; padding: 10px 18px; font-weight: 600; transition: .25s; }
        .btn-primary-modern { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #ffffff; }
        .btn-primary-modern:hover { color: #ffffff; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,.25); }
        .table-modern { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-modern thead th { background: #f8fafc; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; padding: 14px; border-bottom: 1px solid #e5e7eb; }
        .table-modern tbody td { padding: 15px 14px; border-bottom: 1px solid #f1f5f9; font-size: 13px; }
        .table-modern tbody tr:hover { background: #f8fafc; }
        .status { display: inline-block; padding: 5px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
        .status-success { background: #dcfce7; color: #15803d; }
        .status-warning { background: #fef3c7; color: #b45309; }
        .main-footer { background: #ffffff !important; border-top: 1px solid #e5e7eb; color: #64748b; padding: 18px 25px; }
        .main-footer a { color: #2563eb; font-weight: 700; }
    </style>
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

    <header class="main-header">
        <a href="escritorio.php" class="logo">
            <span class="logo-mini"><b>IT</b></span>
            <span class="logo-lg"><b>IT</b>Ventas</span>
        </a>

        <nav class="navbar navbar-static-top">
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Navegación</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- NOTIFICACIONES -->
                    <li class="dropdown notifications-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning">3</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">Tienes 3 notificaciones</li>
                            <li>
                                <ul class="menu">
                                    <li><a href="#"><i class="fa fa-shopping-cart text-aqua"></i> Nueva venta registrada</a></li>
                                    <li><a href="#"><i class="fa fa-warning text-yellow"></i> Producto con stock bajo</a></li>
                                    <li><a href="#"><i class="fa fa-user text-green"></i> Nuevo cliente registrado</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    <!-- USUARIO CONECTADO VÍA SESIÓN -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="../files/usuarios/<?php echo !empty($_SESSION['imagen']) ? $_SESSION['imagen'] : 'mifoto.png'; ?>" class="user-image" alt="Usuario" onerror="this.src='../public/dist/img/user2-160x160.jpg'">
                            <span class="hidden-xs"><?php echo $_SESSION['nombre']; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="../files/usuarios/<?php echo !empty($_SESSION['imagen']) ? $_SESSION['imagen'] : 'mifoto.png'; ?>" class="img-circle" alt="Usuario" onerror="this.src='../public/dist/img/user2-160x160.jpg'">
                                <p>
                                    <?php echo $_SESSION['nombre']; ?>
                                    <small><?php echo $_SESSION['cargo']; ?></small>
                                </p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat"><i class="fa fa-user"></i> Perfil</a>
                                </div>
                                <div class="pull-right">
                                    <a href="../ajax/usuario.php?op=salir" class="btn btn-default btn-flat"><i class="fa fa-sign-out"></i> Salir</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>