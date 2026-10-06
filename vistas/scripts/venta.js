var tabla;

function init() {
    mostrarform(false);
    listar();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    // Cargar selector de clientes
    cargarClientes();

    // Fecha actual predeterminada
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);
}

function cargarClientes() {
    $.post("../ajax/venta.php?op=selectCliente", function(r) {
        $("#idcliente").html(r);
    });
}

function limpiar() {
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

    cargarClientes();
}

function mostrarform(flag) {
    limpiar();
    if (flag) {
        $("#listadoregistros").hide();
        $("#formularioregistros").fadeIn(300);
        $("#btnagregar").hide();
        listarArticulos();

        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        detalles = 0;
    } else {
        $("#listadoregistros").fadeIn(300);
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
        "order": [[1, "desc"]]
    }).DataTable();
}

function listarArticulos() {
    $('#tblarticulos').dataTable({
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
        "iDisplayLength": 6,
        "order": [[1, "asc"]]
    }).DataTable();
}

function abrirModalArticulos() {
    listarArticulos();
    $('#modalArticulos').modal('show');
}

function guardaryeditar(e) {
    e.preventDefault();

    if ($("#idcliente").val() == "") {
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: 'Debe seleccionar un cliente antes de emitir la venta.'
        });
        return false;
    }

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
                title: '¡Operación Exitosa!',
                text: datos,
                confirmButtonColor: '#2563eb'
            });
            mostrarform(false);
            listar();
        }
    });
}

function mostrar(idventa) {
    $.post("../ajax/venta.php?op=mostrar", { idventa: idventa }, function(data, status) {
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
        $("#btnCancelar").show();
    });

    $.post("../ajax/venta.php?op=listarDetalle&id=" + idventa, function(r) {
        $("#detalles").html(r);
    });
}

function anular(idventa) {
    Swal.fire({
        title: '¿Está seguro de anular la venta?',
        text: "Esta acción devolverá los productos al stock del almacén.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, anular venta',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/venta.php?op=anular", { idventa: idventa }, function(e) {
                Swal.fire({
                    icon: 'success',
                    title: 'Venta Anulada',
                    text: e,
                    confirmButtonColor: '#2563eb'
                });
                tabla.ajax.reload();
            });
        }
    });
}

var cont = 0;
var detalles = 0;

function agregarDetalle(idarticulo, articulo, precio_venta, stock) {
    var cantidad = 1;
    var descuento = 0;

    if (idarticulo != "") {
        var subtotal = cantidad * precio_venta;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger btn-xs" onclick="eliminarDetalle(' + cont + ')"><i class="fa fa-close"></i></button></td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '"><strong>' + articulo + '</strong></td>' +
            '<td><input type="number" class="input-field-custom" name="cantidad[]" id="cantidad[]" min="1" max="' + stock + '" value="' + cantidad + '" onchange="modificarSubotales()" style="width:85px; height:36px; padding:4px 8px;"></td>' +
            '<td><input type="number" step="0.01" class="input-field-custom" name="precio_venta[]" id="precio_venta[]" value="' + precio_venta + '" onchange="modificarSubotales()" style="width:105px; height:36px; padding:4px 8px;"></td>' +
            '<td><input type="number" step="0.01" class="input-field-custom" name="descuento[]" value="' + descuento + '" onchange="modificarSubotales()" style="width:95px; height:36px; padding:4px 8px;"></td>' +
            '<td><span name="subtotal" id="subtotal' + cont + '" style="font-weight:700;">' + subtotal.toFixed(2) + '</span></td>' +
            '</tr>';
        cont++;
        detalles++;
        $('#detalles').append(fila);
        modificarSubotales();
        $('#modalArticulos').modal('hide');
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
        document.getElementsByName("subtotal")[i].innerHTML = "S/ " + Number(inpS.value).toFixed(2);
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
    var subtotal_sin_igv = total / (1 + parseFloat(impuesto));
    var igv = total - subtotal_sin_igv;

    $("#subtotal_mostrado").html("S/ " + subtotal_sin_igv.toFixed(2));
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

init();