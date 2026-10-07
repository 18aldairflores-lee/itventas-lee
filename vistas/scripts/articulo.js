var tabla;

function init() {
    mostrarform(false);
    listar();
    cargarKPIs();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    cargarCategorias();
    $("#imagenmuestra").hide();

    // Redibujar código de barras al escribir
    $("#codigo").on("input", function() {
        generarbarcode();
    });
}

function cargarKPIs() {
    $.getJSON("../ajax/articulo.php?op=kpisArticulo", function(data) {
        $("#kpi-total-art").text(data.total);
        $("#kpi-stock-art").text(data.stock);
        $("#kpi-bajo-art").text(data.bajo);
    });
}

function cargarCategorias() {
    $.post("../ajax/articulo.php?op=selectCategoria", function(r) {
        $("#idcategoria").html(r);
    });
}

function limpiar() {
    $("#idarticulo").val("");
    $("#codigo").val("");
    $("#nombre").val("");
    $("#descripcion").val("");
    $("#stock").val("0");
    $("#imagenmuestra").attr("src", "").hide();
    $("#imagenactual").val("");
    $("#imagen").val("");
    $("#print").hide();
    cargarCategorias();
}

function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").fadeIn(250);
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
    } else {
        $("#listadoregistros").fadeIn(250);
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
            { extend: 'copyHtml5', text: '<i class="fa fa-copy"></i> Copiar', className: 'btn btn-default btn-sm' },
            { extend: 'excelHtml5', text: '<i class="fa fa-file-excel-o"></i> Excel', className: 'btn btn-default btn-sm' },
            { extend: 'pdf', text: '<i class="fa fa-file-pdf-o"></i> PDF', className: 'btn btn-default btn-sm' }
        ],
        "ajax": {
            url: '../ajax/articulo.php?op=listar',
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    }).DataTable();
}

function guardaryeditar(e) {
    e.preventDefault();

    if ($("#nombre").val().trim() == "") {
        Swal.fire({
            icon: 'warning',
            title: 'Nombre requerido',
            text: 'Debe ingresar el nombre del artículo.'
        });
        return false;
    }

    if ($("#idcategoria").val() == "" || $("#idcategoria").val() == null) {
        Swal.fire({
            icon: 'warning',
            title: 'Categoría requerida',
            text: 'Seleccione una categoría válida.'
        });
        return false;
    }

    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/articulo.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos) {
            Swal.fire({
                icon: 'success',
                title: 'Operación Exitosa',
                text: datos,
                confirmButtonColor: '#2563eb'
            });
            mostrarform(false);
            tabla.ajax.reload();
            cargarKPIs();
        },
        error: function(xhr, status, error) {
            Swal.fire({
                icon: 'error',
                title: 'Error de servidor',
                text: 'No se pudo conectar con el servidor: ' + error
            });
            $("#btnGuardar").prop("disabled", false);
        }
    });
}

function mostrar(idarticulo) {
    $.post("../ajax/articulo.php?op=mostrar", { idarticulo: idarticulo }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idarticulo").val(data.idarticulo);
        $("#idcategoria").val(data.idcategoria);
        $("#codigo").val(data.codigo);
        $("#nombre").val(data.nombre);
        $("#stock").val(data.stock);
        $("#descripcion").val(data.descripcion);

        if (data.imagen && data.imagen != "") {
            $("#imagenmuestra").show();
            $("#imagenmuestra").attr("src", "../files/articulos/" + data.imagen);
            $("#imagenactual").val(data.imagen);
        } else {
            $("#imagenmuestra").hide();
            $("#imagenactual").val("");
        }

        generarbarcode();
    });
}

function desactivar(idarticulo) {
    Swal.fire({
        title: '¿Desactivar este artículo?',
        text: 'El artículo pasará a estado inactivo.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/articulo.php?op=desactivar", { idarticulo: idarticulo }, function(e) {
                Swal.fire('Completado', e, 'success');
                tabla.ajax.reload();
                cargarKPIs();
            });
        }
    });
}

function activar(idarticulo) {
    Swal.fire({
        title: '¿Activar este artículo?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, activar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/articulo.php?op=activar", { idarticulo: idarticulo }, function(e) {
                Swal.fire('Completado', e, 'success');
                tabla.ajax.reload();
                cargarKPIs();
            });
        }
    });
}

function generarCodigoAleatorio() {
    var codigoRandom = Math.floor(10000000 + Math.random() * 90000000).toString();
    $("#codigo").val(codigoRandom);
    generarbarcode();
}

function generarbarcode() {
    var codigo = $("#codigo").val();
    if (codigo && codigo.trim() != "") {
        try {
            JsBarcode("#barcode", codigo, {
                format: "CODE128",
                lineColor: "#0f172a",
                width: 2,
                height: 42,
                displayValue: true
            });
            $("#print").fadeIn(200);
        } catch(e) {
            console.log(e);
        }
    } else {
        $("#print").hide();
    }
}

init();