<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ITVentas Lee | Acceso Seguro</title>

    <!-- Bootstrap 3.3.5 & FontAwesome -->
    <link rel="stylesheet" href="../public/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/font-awesome.css">

    <!-- Tipografía Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            min-height: 100vh;
            background-color: #0b0f19;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 20px;
        }

        /* Luces de Fondo */
        .ambient-glow {
            position: absolute;
            width: 450px;
            height: 450px;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.45;
            pointer-events: none;
            z-index: 1;
        }
        .glow-1 {
            background: #2563eb;
            top: -10%;
            left: -5%;
        }
        .glow-2 {
            background: #7c3aed;
            bottom: -10%;
            right: -5%;
        }
        .glow-3 {
            background: #0284c7;
            top: 40%;
            left: 55%;
            width: 320px;
            height: 320px;
        }

        /* Tarjeta Principal Glassmorphism */
        .login-box-pro {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            background: rgba(17, 24, 39, 0.75);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 28px;
            padding: 42px 36px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7),
                        inset 0 1px 1px rgba(255, 255, 255, 0.1);
        }

        /* Marca y Cabecera */
        .brand-zone {
            text-align: center;
            margin-bottom: 30px;
        }
        
        /* Contenedor del Logo con Imagen */
        .brand-logo-container {
            width: 84px;
            height: 84px;
            border-radius: 22px;
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.2), rgba(30, 64, 175, 0.3));
            border: 1px solid rgba(255, 255, 255, 0.15);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.35);
            margin-bottom: 16px;
            padding: 8px;
            overflow: hidden;
        }
        .brand-logo-img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            border-radius: 12px;
        }

        .brand-name {
            font-size: 25px;
            font-weight: 800;
            color: #f8fafc;
            letter-spacing: -0.5px;
            margin-bottom: 6px;
        }
        .brand-name span {
            background: linear-gradient(135deg, #60a5fa, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .brand-tagline {
            font-size: 13.5px;
            color: #94a3b8;
            font-weight: 400;
        }

        /* Campos de Entrada */
        .field-group {
            margin-bottom: 22px;
        }
        .field-label {
            display: block;
            font-size: 12.5px;
            font-weight: 600;
            color: #cbd5e1;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .input-shell {
            position: relative;
            display: flex;
            align-items: center;
        }
        .input-shell .icon-lead {
            position: absolute;
            left: 16px;
            color: #64748b;
            font-size: 15px;
            transition: color .2s;
            pointer-events: none;
        }
        .field-input {
            width: 100%;
            height: 50px;
            background: rgba(15, 23, 42, 0.6);
            border: 1.5px solid rgba(255, 255, 255, 0.1);
            border-radius: 14px;
            padding: 0 45px 0 44px;
            color: #f8fafc;
            font-size: 14px;
            font-weight: 500;
            transition: all .25s ease;
        }
        .field-input:focus {
            outline: none;
            background: rgba(15, 23, 42, 0.9);
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.2);
        }
        .field-input:focus ~ .icon-lead {
            color: #3b82f6;
        }

        /* Botón Mostrar Contraseña */
        .toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 6px;
            font-size: 15px;
            transition: color .2s;
        }
        .toggle-password:hover {
            color: #cbd5e1;
        }

        /* Botón Ingresar */
        .btn-submit-pro {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            border: none;
            border-radius: 14px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.4);
            transition: all .25s ease;
            margin-top: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .btn-submit-pro:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px rgba(37, 99, 235, 0.55);
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        }
        .btn-submit-pro:active {
            transform: translateY(1px);
        }

        /* Pie */
        .card-footer-info {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: #64748b;
        }
        .badge-secure {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #10b981;
            font-weight: 600;
        }
        .badge-secure span.dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
            box-shadow: 0 0 8px #10b981;
        }
    </style>
</head>
<body>

    <!-- Orbes luminosos de fondo -->
    <div class="ambient-glow glow-1"></div>
    <div class="ambient-glow glow-2"></div>
    <div class="ambient-glow glow-3"></div>

    <!-- Contenedor del Formulario -->
    <div class="login-box-pro">
        <div class="brand-zone">
            <!-- Logo personalizado con logo1.png -->
            <div class="brand-logo-container">
                <img src="../files/logo1.png" class="brand-logo-img" alt="Logo" onerror="this.onerror=null; this.src='../public/img/logo1.png'; this.style.display=this.src ? 'block' : 'none';">
            </div>
            <h1 class="brand-name">ITVentas <span>Lee</span></h1>
            <p class="brand-tagline">Sistema de Gestión Comercial y Almacén</p>
        </div>

        <form method="post" id="frmAcceso">
            <div class="field-group">
                <label class="field-label" for="logina">Usuario de Acceso</label>
                <div class="input-shell">
                    <input type="text" class="field-input" id="logina" name="logina" placeholder="Ej. roy" required autocomplete="off">
                    <i class="fa fa-user-circle-o icon-lead"></i>
                </div>
            </div>

            <div class="field-group">
                <label class="field-label" for="clavea">Contraseña</label>
                <div class="input-shell">
                    <input type="password" class="field-input" id="clavea" name="clavea" placeholder="••••••••••••" required>
                    <i class="fa fa-lock icon-lead"></i>
                    <button type="button" class="toggle-password" id="btnTogglePass" onclick="alternarPassword()">
                        <i class="fa fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit-pro" id="btnIngresar">
                <span>Ingresar al Sistema</span>
                <i class="fa fa-arrow-right"></i>
            </button>
        </form>

        <div class="card-footer-info">
            <div class="badge-secure">
                <span class="dot"></span>
                <span>Conexión cifrada</span>
            </div>
            <span>v3.0 &copy; 2026</span>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function alternarPassword() {
            var inputPass = document.getElementById("clavea");
            var icon = document.getElementById("eyeIcon");
            if (inputPass.type === "password") {
                inputPass.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                inputPass.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>

    <!-- Script de autenticación AJAX -->
    <script src="scripts/login.js"></script>
</body>
</html>