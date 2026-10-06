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

    $.post("../ajax/articulo.php?op=selectCategoria", function(r) {
        $("#idcategoria").html(r);
    });

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    $("#imagen").change(function() {
        mostrarPrevia(this);
    });

    $("#codigo").keyup(function() {
        generarbarcode();
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

function generarbarcode() {
    var codigo = $("#codigo").val();
    if (codigo.length > 0) {
        JsBarcode("#barcode", codigo, {
            format: "CODE128",
            lineColor: "#0f172a",
            width: 2,
            height: 40,
            displayValue: true
        });
        $("#barcode").show();
    } else {
        $("#barcode").hide();
    }
}

function limpiar() {
    $("#idarticulo").val("");
    $("#codigo").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#stock").val("");
    $("#imagenmuestra").attr("src", "").hide();
    $("#imagenactual").val("");
    $("#imagen").val("");
    $("#barcode").hide();
}

function abrirModal() {
    limpiar();
    $("#modalTitulo").html('<i class="fa fa-plus-circle text-primary"></i> Nuevo Artículo');
    $("#btnGuardar").prop("disabled", false);
    $("#modalArticulo").modal('show');
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
            url: '../ajax/articulo.php?op=listar',
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
    var activos = 0;
    var stockBajo = 0;

    for (var i = 0; i < total; i++) {
        if (json.aaData[i][6] && json.aaData[i][6].indexOf("Activo") !== -1) {
            activos++;
        }
        if (json.aaData[i][4] && json.aaData[i][4].indexOf("stock-low") !== -1) {
            stockBajo++;
        }
    }

    $("#kpi-total-art").text(total);
    $("#kpi-activos-art").text(activos);
    $("#kpi-stock-bajo").text(stockBajo);
}

function guardaryeditar(e) {
    e.preventDefault();
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/articulo.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos) {
            $("#modalArticulo").modal('hide');
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

function mostrar(idarticulo) {
    $.post("../ajax/articulo.php?op=mostrar", { idarticulo: idarticulo }, function(data) {
        data = JSON.parse(data);
        $("#modalTitulo").html('<i class="fa fa-edit text-warning"></i> Editar Artículo');
        $("#idcategoria").val(data.idcategoria);
        $("#codigo").val(data.codigo);
        $("#nombre").val(data.nombre);
        $("#stock").val(data.stock);
        $("#descripcion").val(data.descripcion);
        $("#imagenactual").val(data.imagen);
        $("#idarticulo").val(data.idarticulo);

        if (data.imagen != "") {
            $("#imagenmuestra").attr("src", "../files/articulos/" + data.imagen).show();
        } else {
            $("#imagenmuestra").hide();
        }

        generarbarcode();
        $("#modalArticulo").modal('show');
    });
}

function desactivar(idarticulo) {
    Swal.fire({
        title: '¿Desactivar artículo?',
        text: "El artículo no estará disponible para nuevas ventas.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/articulo.php?op=desactivar", { idarticulo: idarticulo }, function(e) {
                Toast.fire({
                    icon: 'info',
                    title: e
                });
                tabla.ajax.reload();
            });
        }
    });
}

function activar(idarticulo) {
    Swal.fire({
        title: '¿Activar artículo?',
        text: "El artículo volverá a estar disponible en el inventario.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/articulo.php?op=activar", { idarticulo: idarticulo }, function(e) {
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