$("#frmAcceso").on('submit', function(e) {
    e.preventDefault();
    
    var logina = $("#logina").val();
    var clavea = $("#clavea").val();
    var btn = $("#btnIngresar");

    // Efecto de carga en el botón
    btn.prop("disabled", true).html('<i class="fa fa-circle-o-notch fa-spin"></i> <span>Verificando...</span>');

    $.post("../ajax/usuario.php?op=verificar", { "logina": logina, "clavea": clavea }, function(data) {
        if (data != "null" && data != null && data != "") {
            // Éxito: Feedback visual y redirección
            btn.css("background", "linear-gradient(135deg, #059669, #10b981)")
               .html('<i class="fa fa-check"></i> <span>Acceso Autorizado</span>');
            
            setTimeout(function() {
                $(location).attr("href", "escritorio.php");
            }, 600);
        } else {
            // Error en credenciales
            btn.prop("disabled", false).html('<span>Ingresar al Sistema</span> <i class="fa fa-arrow-right"></i>');
            
            Swal.fire({
                icon: 'error',
                title: 'Credenciales inválidas',
                text: 'El usuario o la contraseña ingresados no coinciden.',
                background: '#111827',
                color: '#f8fafc',
                confirmButtonColor: '#2563eb'
            });
        }
    }).fail(function() {
        btn.prop("disabled", false).html('<span>Ingresar al Sistema</span> <i class="fa fa-arrow-right"></i>');
        Swal.fire({
            icon: 'warning',
            title: 'Error de servidor',
            text: 'No se pudo conectar con el servicio de autenticación.',
            background: '#111827',
            color: '#f8fafc',
            confirmButtonColor: '#2563eb'
        });
    });
});