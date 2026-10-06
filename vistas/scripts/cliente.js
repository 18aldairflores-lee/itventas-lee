var tabla;

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true
});

function init() {
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });
}

function limpiar() {
    $("#idpersona").val("");
    $("#nombre").val("");
    $("#num_documento").val("");
    $("#direccion").val("");
    $("#telefono").val("");
    $("#email").val("");
    $("#tipo_documento").val("DNI");
}

function abrirModal() {
    limpiar();
    $("#modalTitulo").html('<i class="fa fa-user-plus text-primary"></i> Nuevo Cliente');
    $("#btnGuardar").prop("disabled", false);
    $("#modalCliente").modal('show');
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
            url: '../ajax/cliente.php?op=listarc',
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "initComplete": function(settings, json) {
            actualizarMetricas(json);
        },
        "drawCallback": function(settings) {
            var api = this.api();
            var json = api.ajax.json();
            if (json) {
                actualizarMetricas(json);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 6,
        "order": [[0, "desc"]]
    }).DataTable();
}

function actualizarMetricas(json) {
    if (!json || !json.aaData) return;

    var total = json.aaData.length;
    var conTel = 0;
    var conMail = 0;

    for (var i = 0; i < total; i++) {
        if (json.aaData[i][3] && json.aaData[i][3].indexOf("Sin teléfono") === -1) {
            conTel++;
        }
        if (json.aaData[i][4] && json.aaData[i][4].indexOf("Sin email") === -1) {
            conMail++;
        }
    }

    $("#kpi-total-cli").text(total);
    $("#kpi-con-tel").text(conTel);
    $("#kpi-con-mail").text(conMail);
}

function guardaryeditar(e) {
    e.preventDefault();
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/cliente.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos) {
            $("#modalCliente").modal('hide');
            Toast.fire({
                icon: 'success',
                title: datos
            });
            tabla.ajax.reload();
            limpiar();
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

function mostrar(idpersona) {
    $.post("../ajax/cliente.php?op=mostrar", { idpersona: idpersona }, function(data) {
        data = JSON.parse(data);
        $("#modalTitulo").html('<i class="fa fa-edit text-warning"></i> Editar Cliente');
        $("#idpersona").val(data.idpersona);
        $("#nombre").val(data.nombre);
        $("#tipo_documento").val(data.tipo_documento);
        $("#num_documento").val(data.num_documento);
        $("#direccion").val(data.direccion);
        $("#telefono").val(data.telefono);
        $("#email").val(data.email);

        $("#modalCliente").modal('show');
    });
}

function eliminar(idpersona) {
    Swal.fire({
        title: '¿Eliminar este cliente?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/cliente.php?op=eliminar", { idpersona: idpersona }, function(e) {
                Toast.fire({
                    icon: 'info',
                    title: e
                });
                tabla.ajax.reload();
            });
        }
    });
}

init();