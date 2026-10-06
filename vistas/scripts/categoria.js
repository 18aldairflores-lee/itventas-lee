var tabla;

// Inicialización de SweetAlert2 Toast flotante
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer);
        toast.addEventListener('mouseleave', Swal.resumeTimer);
    }
});

function init() {
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });
}

function limpiar() {
    $("#idcategoria").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
}

function abrirModal() {
    limpiar();
    $("#modalTitulo").html('<i class="fa fa-plus-circle text-primary"></i> Nueva Categoría');
    $("#btnGuardar").prop("disabled", false);
    $("#modalCategoria").modal('show');
}

function filtrarEstado(estado) {
    $(".filter-chips .chip").removeClass("active");
    $(event.target).addClass("active");
    tabla.column(3).search(estado).draw();
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
            url: '../ajax/categoria.php?op=listar',
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
    var activas = 0;

    for (var i = 0; i < total; i++) {
        if (json.aaData[i][3] && json.aaData[i][3].indexOf("Activo") !== -1) {
            activas++;
        }
    }

    var porcentaje = total > 0 ? Math.round((activas / total) * 100) : 0;

    // Actualiza valores
    $("#kpi-total").text(total);
    $("#kpi-activas").text(activas);
    $("#kpi-porcentaje").text(porcentaje + "%");

    // Actualiza barras de progreso
    $("#kpi-bar-activas").css("width", (total > 0 ? (activas / total) * 100 : 0) + "%");
    $("#kpi-bar-porcentaje").css("width", porcentaje + "%");
}

function guardaryeditar(e) {
    e.preventDefault();
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/categoria.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos) {
            $("#modalCategoria").modal('hide');
            Toast.fire({
                icon: 'success',
                title: datos
            });
            tabla.ajax.reload();
            limpiar();
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error de servidor',
                text: xhr.responseText
            });
            $("#btnGuardar").prop("disabled", false);
        }
    });
}

function mostrar(idcategoria) {
    $.post("../ajax/categoria.php?op=mostrar", { idcategoria: idcategoria }, function(data, status) {
        data = JSON.parse(data);
        $("#modalTitulo").html('<i class="fa fa-edit text-warning"></i> Editar Categoría');
        $("#nombre").val(data.nombre);
        $("#descripcion").val(data.descripcion);
        $("#idcategoria").val(data.idcategoria);
        $("#modalCategoria").modal('show');
    });
}

function desactivar(idcategoria) {
    Swal.fire({
        title: '¿Desactivar categoría?',
        text: "Los productos vinculados a esta categoría podrían verse afectados.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/categoria.php?op=desactivar", { idcategoria: idcategoria }, function(e) {
                Toast.fire({
                    icon: 'info',
                    title: e
                });
                tabla.ajax.reload();
            });
        }
    });
}

function activar(idcategoria) {
    Swal.fire({
        title: '¿Activar categoría?',
        text: "Esta categoría volverá a estar disponible para el inventario.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/categoria.php?op=activar", { idcategoria: idcategoria }, function(e) {
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