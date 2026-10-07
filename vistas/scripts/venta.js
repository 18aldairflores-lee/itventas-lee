var tabla;

function init() {
    mostrarform(false);
    listar();
    cargarKPIsVenta();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    $.post("../ajax/venta.php?op=selectCliente", function(r) {
        $("#idcliente").html(r);
        if ($.fn.selectpicker) {
            $('#idcliente').selectpicker('refresh');
        }
    });

    $('#mCompras').addClass("treeview active");
    $('#lVentas').addClass("active");
}

function cargarKPIsVenta() {
    $.getJSON("../ajax/venta.php?op=kpisVenta", function(data) {
        $("#kpi-total-ventas").text(data.total);
        $("#kpi-ingresos-ventas").text("S/ " + parseFloat(data.ingresos).toFixed(2));
        $("#kpi-aceptadas-ventas").text(data.aceptadas);
    });
}

function limpiar() {
    $("#idcliente").val("");
    if ($.fn.selectpicker) {
        $('#idcliente').selectpicker('refresh');
    }
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $("#impuesto").val("18");
    $("#total_venta").val("");
    $(".filas").remove();
    $("#total").html("S/ 0.00");

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);

    $("#tipo_comprobante").val("Boleta");
}

function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").fadeIn(250);
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
        listarArticulos();

        $("#btnGuardar").show();
        $("#btnCancelar").show();
        detalles = 0;
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
            url: '../ajax/venta.php?op=listar',
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

function listarArticulos() {
    tabla = $('#tblarticulos').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [],
        "ajax": {
            url: '../ajax/venta.php?op=listarArticulosVenta',
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 5,
        "order": [[0, "desc"]]
    }).DataTable();
}

function guardaryeditar(e) {
    e.preventDefault();

    if ($("#idcliente").val() == "" || $("#idcliente").val() == null) {
        Swal.fire({
            icon: 'warning',
            title: 'Cliente Requerido',
            text: 'Seleccione un cliente válido para emitir la venta.'
        });
        return false;
    }

    if (detalles == 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin artículos',
            text: 'Debe agregar al menos un artículo a la venta.'
        });
        return false;
    }

    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/venta.php?op=guardaryeditar",
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
            listar();
            cargarKPIsVenta();
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

function mostrar(idventa) {
    $.post("../ajax/venta.php?op=mostrar", { idventa: idventa }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcliente").val(data.idcliente);
        if ($.fn.selectpicker) {
            $('#idcliente').selectpicker('refresh');
        }
        $("#tipo_comprobante").val(data.tipo_comprobante);
        $("#serie_comprobante").val(data.serie_comprobante);
        $("#num_comprobante").val(data.num_comprobante);
        $("#fecha_hora").val(data.fecha);
        $("#impuesto").val(data.impuesto);
        $("#idventa").val(data.idventa);

        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
    });

    $.post("../ajax/venta.php?op=listarDetalle&id=" + idventa, function(r) {
        $("#detalles").html(r);
    });
}

function anular(idventa) {
    Swal.fire({
        title: '¿Anular esta venta?',
        text: 'La venta pasará a estado Anulado y no sumará en los ingresos.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/venta.php?op=anular", { idventa: idventa }, function(e) {
                Swal.fire('Completado', e, 'success');
                tabla.ajax.reload();
                cargarKPIsVenta();
            });
        }
    });
}

var cont = 0;
var detalles = 0;

function agregarDetalle(idarticulo, articulo, precio_venta) {
    var cantidad = 1;
    var descuento = 0;

    if (idarticulo != "") {
        var subtotal = cantidad * precio_venta;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger btn-xs" onclick="eliminarDetalle(' + cont + ')"><i class="fa fa-close"></i></button></td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
            '<td><input type="number" name="cantidad[]" id="cantidad[]" value="' + cantidad + '" min="1" onchange="modificarSubotales()"></td>' +
            '<td><input type="number" name="precio_venta[]" id="precio_venta[]" value="' + precio_venta + '" step="0.01" onchange="modificarSubotales()"></td>' +
            '<td><input type="number" name="descuento[]" value="' + descuento + '" step="0.01" onchange="modificarSubotales()"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">' + subtotal + '</span></td>' +
            '</tr>';
        cont++;
        detalles = detalles + 1;
        $('#detalles').append(fila);
        modificarSubotales();
    } else {
        alert("Error al ingresar el detalle");
    }
}

function modificarSubotales() {
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_venta[]");
    var desc = document.getElementsByName("descuento[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpD = desc[i];
        var inpS = sub[i];

        inpS.value = (inpC.value * inpP.value) - inpD.value;
        document.getElementsByName("subtotal")[i].innerHTML = "S/ " + inpS.value.toFixed(2);
    }
    calcularTotales();
}

function calcularTotales() {
    var sub = document.getElementsByName("subtotal");
    var total = 0.0;

    for (var i = 0; i < sub.length; i++) {
        total += document.getElementsByName("subtotal")[i].value;
    }
    $("#total").html("S/ " + total.toFixed(2));
    $("#total_venta").val(total.toFixed(2));
    evaluar();
}

function evaluar() {
    if (detalles > 0) {
        $("#btnGuardar").show();
    } else {
        $("#btnGuardar").hide();
        cont = 0;
    }
}

function eliminarDetalle(indice) {
    $("#fila" + indice).remove();
    calcularTotales();
    detalles = detalles - 1;
    evaluar();
}

init();