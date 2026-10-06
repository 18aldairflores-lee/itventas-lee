var tabla;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true
});

function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    $("#imagen").change(function() {
        mostrarPrevia(this);
    });

    // Cargar permisos iniciales desmarcados
    $.post("../ajax/usuario.php?op=permisos&id=", function(r) {
        $("#permisos").html(r);
    });
}

function mostrarPrevia(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $("#imagenmuestra").attr("src", e.target.result).show();
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function limpiar() {
    $("#idusuario").val("");
    $("#nombre").val("");
    $("#num_documento").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#cargo").val("");
    $("#login").val("");
    $("#clave").val("");
    $("#claveactual").val("");
    $("#imagenmuestra").attr("src", "").hide();
    $("#imagenactual").val("");
    $("#imagen").val("");
    $("#tipo_documento").val("DNI");
}

function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    } else {
        $("#listadoregistros").show();
        $("#formularioregistros").hide();
        $("#btnagregar").show();
    }
}

function cancelarform() {
    limpiar();
    mostrarform(false);
}

function listar() {
    tabla = $('#tbllistado').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            { extend: 'copyHtml5', className: 'btn btn-default btn-sm' },
            { extend: 'excelHtml5', className: 'btn btn-default btn-sm' },
            { extend: 'pdf', className: 'btn btn-default btn-sm' }
        ],
        "ajax": {
            url: '../ajax/usuario.php?op=listar',
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 6,
        "order": [[0, "desc"]]
    }).DataTable();
}

function guardaryeditar(e) {
    e.preventDefault();
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/usuario.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos) {
            Toast.fire({
                icon: 'success',
                title: datos
            });
            mostrarform(false);
            tabla.ajax.reload();
        },
        error: function(xhr) {
            Swal.fire({
                icon: 'error',
                title: 'Error de servidor',
                text: xhr.responseText
            });
            $("#btnGuardar").prop("disabled", false);
        }
    });
}

function mostrar(idusuario) {
    $.post("../ajax/usuario.php?op=mostrar", { idusuario: idusuario }, function(data) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#nombre").val(data.nombre);
        $("#tipo_documento").val(data.tipo_documento);
        $("#num_documento").val(data.num_documento);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);
        $("#cargo").val(data.cargo);
        $("#login").val(data.login);
        $("#claveactual").val(data.clave);
        $("#clave").val("");
        $("#imagenactual").val(data.imagen);
        $("#idusuario").val(data.idusuario);

        if (data.imagen != "") {
            $("#imagenmuestra").attr("src", "../files/usuarios/" + data.imagen).show();
        } else {
            $("#imagenmuestra").hide();
        }

        // Cargar permisos marcando los que posee el usuario
        $.post("../ajax/usuario.php?op=permisos&id=" + idusuario, function(r) {
            $("#permisos").html(r);
        });
    });
}

function desactivar(idusuario) {
    Swal.fire({
        title: '¿Desactivar este usuario?',
        text: "El usuario ya no podrá iniciar sesión en el sistema.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/usuario.php?op=desactivar", { idusuario: idusuario }, function(e) {
                Toast.fire({
                    icon: 'info',
                    title: e
                });
                tabla.ajax.reload();
            });
        }
    });
}

function activar(idusuario) {
    Swal.fire({
        title: '¿Activar este usuario?',
        text: "El usuario recuperará el acceso al sistema.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/usuario.php?op=activar", { idusuario: idusuario }, function(e) {
                Toast.fire({
                    icon: 'success',
                    title: e
                });
                tabla.ajax.reload();
            });
        }
    });
}

init();