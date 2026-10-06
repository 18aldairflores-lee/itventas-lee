var tabla;
var cont = 0;
var detalles = 0;

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

    // Cargar selector de clientes
    $.post("../ajax/venta.php?op=selectCliente", function(r) {
        $("#idcliente").html(r);
    });
}

function limpiar() {
    $("#idventa").val("");
    $("#idcliente").val("");
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $("#impuesto").val("0.18");
    $("#total_venta").val("");
    $(".filas").remove();
    $("#total_mostrado").html("S/ 0.00");
    $("#subtotal_mostrado").html("S/ 0.00");
    $("#igv_mostrado").html("S/ 0.00");

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);
}

function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").show();
        $("#btnGuardar").prop("disabled", false);
        $("#btnagregar").hide();
        listarArticulos();
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
            url: '../ajax/venta.php?op=listar',
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

    var totalVentas = json.aaData.length;
    var ingresos = 0;
    var aceptadas = 0;

    for (var i = 0; i < totalVentas; i++) {
        if (json.aaData[i][6] && json.aaData[i][6].indexOf("Aceptado") !== -1) {
            aceptadas++;
            var textoMonto = json.aaData[i][5].replace(/[^0-9.-]+/g, "");
            ingresos += parseFloat(textoMonto) || 0;
        }
    }

    $("#kpi-total-ventas").text(totalVentas);
    $("#kpi-ingresos-ventas").text("S/ " + ingresos.toFixed(2));
    $("#kpi-ventas-aceptadas").text(aceptadas);
}

function listarArticulos() {
    $('#tblarticulos').dataTable({
        "aProcessing": true,
        "aServerSide": true,
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
        "order": [[1, "asc"]]
    }).DataTable();
}

function abrirModalArticulos() {
    $("#modalArticulos").modal('show');
}

function agregarDetalle(idarticulo, articulo, precio_venta, stock) {
    var cantidad = 1;
    var descuento = 0;

    if (idarticulo != "") {
        var subtotal = cantidad * precio_venta;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger btn-xs" onclick="eliminarDetalle(' + cont + ')"><i class="fa fa-trash"></i></button></td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '"><strong>' + articulo + '</strong> <small style="color:#64748b;">(Stock: ' + stock + ')</small></td>' +
            '<td><input type="number" name="cantidad[]" id="cantidad[]" value="' + cantidad + '" min="1" max="' + stock + '" class="input-field-custom" style="height:36px;" onchange="modificarSubtotales()"></td>' +
            '<td><input type="number" step="0.01" name="precio_venta[]" id="precio_venta[]" value="' + precio_venta + '" min="0.01" class="input-field-custom" style="height:36px;" onchange="modificarSubtotales()"></td>' +
            '<td><input type="number" step="0.01" name="descuento[]" value="' + descuento + '" min="0" class="input-field-custom" style="height:36px;" onchange="modificarSubtotales()"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">S/ ' + subtotal.toFixed(2) + '</span></td>' +
            '</tr>';
        cont++;
        detalles++;
        $('#detalles').append(fila);
        modificarSubtotales();
        Toast.fire({ icon: 'success', title: 'Producto añadido a la venta' });
    }
}

function modificarSubtotales() {
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

    var impuesto = $("#impuesto").val() || 0.18;
    var subtotalSinIgv = total / (1 + parseFloat(impuesto));
    var igv = total - subtotalSinIgv;

    $("#subtotal_mostrado").html("S/ " + subtotalSinIgv.toFixed(2));
    $("#igv_mostrado").html("S/ " + igv.toFixed(2));
    $("#total_mostrado").html("S/ " + total.toFixed(2));
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

function guardaryeditar(e) {
    e.preventDefault();
    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/venta.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(datos) {
            Toast.fire({ icon: 'success', title: datos });
            mostrarform(false);
            tabla.ajax.reload();
        },
        error: function(xhr) {
            Swal.fire({ icon: 'error', title: 'Error', text: xhr.responseText });
            $("#btnGuardar").prop("disabled", false);
        }
    });
}

function mostrar(idventa) {
    $.post("../ajax/venta.php?op=mostrar", { idventa: idventa }, function(data) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idcliente").val(data.idcliente);
        $("#tipo_comprobante").val(data.tipo_comprobante);
        $("#serie_comprobante").val(data.serie_comprobante);
        $("#num_comprobante").val(data.num_comprobante);
        $("#fecha_hora").val(data.fecha);
        $("#impuesto").val(data.impuesto);
        $("#idventa").val(data.idventa);

        $("#btnGuardar").hide();

        $.post("../ajax/venta.php?op=listarDetalle&id=" + idventa, function(r) {
            $("#detalles").html(r);
        });
    });
}

function anular(idventa) {
    Swal.fire({
        title: '¿Anular esta venta?',
        text: "Los productos vendidos regresarán automáticamente al stock disponible en almacén.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, anular venta',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/venta.php?op=anular", { idventa: idventa }, function(e) {
                Toast.fire({ icon: 'info', title: e });
                tabla.ajax.reload();
            });
        }
    });
}

init();