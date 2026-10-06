<?php
if (strlen(session_id()) < 1) {
    session_start();
}
if (!isset($_SESSION["nombre"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>ITVentas Lee | Gestión Comercial ERP</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- Bootstrap 3.3.7 CDN -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Google Fonts Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- AdminLTE Style -->
    <link rel="stylesheet" href="../public/css/AdminLTE.min.css">
    <link rel="stylesheet" href="../public/css/_all-skins.min.css">

    <!-- DATATABLES CDN -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <style>
        body, .main-header, .sidebar-menu, h1, h2, h3, h4, h5, h6 {
            font-family: 'Poppins', sans-serif !important;
        }
        .main-header .navbar {
            background: #ffffff !important;
            border-bottom: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        }
        .main-header .logo {
            background: #1e3a8a !important;
            font-weight: 800;
            letter-spacing: .5px;
        }
        .main-header .sidebar-toggle {
            color: #1e293b !important;
        }
        .main-header .sidebar-toggle:hover {
            background: #f1f5f9 !important;
        }
        .navbar-custom-menu .navbar-nav > li > a {
            color: #334155 !important;
            font-weight: 500;
        }
        .navbar-custom-menu .navbar-nav > li > a:hover {
            background: #f8fafc !important;
        }
        .badge-notification {
            position: absolute;
            top: 9px;
            right: 7px;
            font-size: 10px;
            font-weight: 700;
            background: #ef4444;
            color: #fff;
            border-radius: 50%;
            padding: 2px 6px;
        }
        .notif-dropdown {
            width: 310px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.12);
            border: 1px solid #e2e8f0;
            padding: 0;
            overflow: hidden;
        }
        .notif-header {
            background: #f8fafc;
            padding: 12px 16px;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 700;
            font-size: 13px;
            color: #0f172a;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .notif-item {
            padding: 10px 16px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12.5px;
            color: #334155;
            text-decoration: none;
            transition: background .2s ease;
        }
        .notif-item:hover {
            background: #f8fafc;
            text-decoration: none;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <a href="escritorio.php" class="logo">
      <span class="logo-mini"><b>IT</b>L</span>
      <span class="logo-lg"><b>ITVentas</b> Lee</span>
    </a>

    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

          <!-- Campana de Notificaciones de Stock Bajo -->
          <li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="btnNotificaciones">
              <i class="fa fa-bell-o" style="font-size: 17px;"></i>
              <span class="badge-notification" id="badgeStockBajo">0</span>
            </a>
            <ul class="dropdown-menu notif-dropdown">
              <li class="notif-header">
                <span>Alertas de Stock Crítico</span>
                <span class="label label-danger" id="labelCantBajo">0</span>
              </li>
              <li>
                <ul class="menu" id="listaStockBajo" style="list-style:none; padding:0; margin:0; max-height:220px; overflow-y:auto;">
                  <li style="padding:15px; text-align:center; color:#94a3b8; font-size:12px;">Cargando inventario...</li>
                </ul>
              </li>
              <li style="text-align:center; padding:10px; background:#f8fafc; border-top:1px solid #e2e8f0;">
                <a href="articulo.php" style="font-size:12px; font-weight:700; color:#2563eb; text-decoration:none;">Ver Almacén Completo <i class="fa fa-arrow-right"></i></a>
              </li>
            </ul>
          </li>

          <!-- Menú Usuario Superior -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="display:flex; align-items:center; gap:8px;">
              <img src="../files/usuarios/<?php echo !empty($_SESSION['imagen']) ? $_SESSION['imagen'] : 'defecto.png'; ?>" class="user-image" onerror="this.src='../public/img/logo1.png';" alt="User Image">
              <span class="hidden-xs" style="font-weight:600;"><?php echo $_SESSION['nombre']; ?></span>
            </a>
            <ul class="dropdown-menu" style="border-radius:12px; border:1px solid #e2e8f0; box-shadow:0 8px 24px rgba(0,0,0,0.08);">
              <li class="user-header" style="background:#1e293b; color:#fff;">
                <img src="../files/usuarios/<?php echo !empty($_SESSION['imagen']) ? $_SESSION['imagen'] : 'defecto.png'; ?>" class="img-circle" onerror="this.src='../public/img/logo1.png';" alt="User Image">
                <p>
                  <?php echo $_SESSION['nombre']; ?>
                  <small><?php echo isset($_SESSION['login']) ? '@' . $_SESSION['login'] : 'Usuario'; ?></small>
                </p>
              </li>
              <li class="user-footer" style="padding:10px; display:flex; justify-content:space-between;">
                <a href="usuario.php" class="btn btn-default btn-flat" style="border-radius:6px; font-size:12px;"><i class="fa fa-user"></i> Mi Perfil</a>
                <a href="../ajax/usuario.php?op=salir" class="btn btn-danger btn-flat" style="border-radius:6px; font-size:12px;"><i class="fa fa-power-off"></i> Salir</a>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
  </header>

  <!-- Script para conectar la campana al cargar la página -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
        $.getJSON("../ajax/articulo.php?op=alertaStock", function(data) {
            var cant = data.length;
            $("#badgeStockBajo").text(cant);
            $("#labelCantBajo").text(cant + " productos");

            var html = "";
            if (cant > 0) {
                $.each(data, function(i, item) {
                    html += '<a href="articulo.php" class="notif-item">' +
                              '<span><i class="fa fa-exclamation-circle text-danger" style="margin-right:6px;"></i> ' + item.nombre + '</span>' +
                              '<span class="badge" style="background:#fee2e2; color:#ef4444; font-weight:700;">Stock: ' + item.stock + '</span>' +
                            '</a>';
                });
            } else {
                html = '<li style="padding:15px; text-align:center; color:#10b981; font-size:12px;"><i class="fa fa-check-circle"></i> Todos los artículos tienen stock suficiente.</li>';
                $("#badgeStockBajo").hide();
            }
            $("#listaStockBajo").html(html);
        });
    });
  </script>