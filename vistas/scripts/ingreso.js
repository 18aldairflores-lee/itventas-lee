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

    // Cargar selector de proveedores
    $.post("../ajax/ingreso.php?op=selectProveedor", function(r) {
        $("#idproveedor").html(r);
    });
}

function limpiar() {
    $("#idingreso").val("");
    $("#idproveedor").val("");
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $("#impuesto").val("0.18");
    $("#total_compra").val("");
    $(".filas").remove();
    $("#total_mostrado").html("S/ 0.00");
    $("#subtotal_mostrado").html("S/ 0.00");
    $("#igv_mostrado").html("S/ 0.00");

    // Fecha actual
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
            url: '../ajax/ingreso.php?op=listar',
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

    var totalCompras = json.aaData.length;
    var inversionTotal = 0;
    var aceptadas = 0;

    for (var i = 0; i < totalCompras; i++) {
        if (json.aaData[i][6] && json.aaData[i][6].indexOf("Aceptado") !== -1) {
            aceptadas++;
            // Extraer el monto numérico
            var textoMonto = json.aaData[i][5].replace(/[^0-9.-]+/g, "");
            inversionTotal += parseFloat(textoMonto) || 0;
        }
    }

    $("#kpi-total-compras").text(totalCompras);
    $("#kpi-inversion").text("S/ " + inversionTotal.toFixed(2));
    $("#kpi-aceptadas").text(aceptadas);
}

function listarArticulos() {
    $('#tblarticulos').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "ajax": {
            url: '../ajax/ingreso.php?op=listarArticulosActivos',
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

function agregarDetalle(idarticulo, articulo) {
    var cantidad = 1;
    var precio_compra = 1;
    var precio_venta = 1;

    if (idarticulo != "") {
        var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger btn-xs" onclick="eliminarDetalle(' + cont + ')"><i class="fa fa-trash"></i></button></td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '"><strong>' + articulo + '</strong></td>' +
            '<td><input type="number" name="cantidad[]" id="cantidad[]" value="' + cantidad + '" min="1" class="input-field-custom" style="height:36px;" onchange="modificarSubtotales()"></td>' +
            '<td><input type="number" step="0.01" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '" min="0.01" class="input-field-custom" style="height:36px;" onchange="modificarSubtotales()"></td>' +
            '<td><input type="number" step="0.01" name="precio_venta[]" value="' + precio_venta + '" min="0.01" class="input-field-custom" style="height:36px;"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '">S/ ' + subtotal.toFixed(2) + '</span></td>' +
            '</tr>';
        cont++;
        detalles++;
        $('#detalles').append(fila);
        modificarSubtotales();
        Toast.fire({ icon: 'success', title: 'Artículo agregado a la lista' });
    }
}

function modificarSubtotales() {
    var cant = document.getElementsByName("cantidad[]");
    var prec = document.getElementsByName("precio_compra[]");
    var sub = document.getElementsByName("subtotal");

    for (var i = 0; i < cant.length; i++) {
        var inpC = cant[i];
        var inpP = prec[i];
        var inpS = sub[i];

        inpS.value = (inpC.value * inpP.value);
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
    $("#total_compra").val(total.toFixed(2));
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
        url: "../ajax/ingreso.php?op=guardaryeditar",
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

function mostrar(idingreso) {
    $.post("../ajax/ingreso.php?op=mostrar", { idingreso: idingreso }, function(data) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idproveedor").val(data.idproveedor);
        $("#tipo_comprobante").val(data.tipo_comprobante);
        $("#serie_comprobante").val(data.serie_comprobante);
        $("#num_comprobante").val(data.num_comprobante);
        $("#fecha_hora").val(data.fecha);
        $("#impuesto").val(data.impuesto);
        $("#idingreso").val(data.idingreso);

        $("#btnGuardar").hide();

        $.post("../ajax/ingreso.php?op=listarDetalle&id=" + idingreso, function(r) {
            $("#detalles").html(r);
        });
    });
}

function anular(idingreso) {
    Swal.fire({
        title: '¿Anular esta compra?',
        text: "El stock de los productos comprados se descontará del inventario.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, anular e invertir stock',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/ingreso.php?op=anular", { idingreso: idingreso }, function(e) {
                Toast.fire({ icon: 'info', title: e });
                tabla.ajax.reload();
            });
        }
    });
}

init();