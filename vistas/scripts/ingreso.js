var tabla;

function init() {
    mostrarform(false);
    listar();
    cargarKPIsIngreso();

    $("#formulario").on("submit", function(e) {
        guardaryeditar(e);
    });

    $.post("../ajax/ingreso.php?op=selectProveedor", function(r) {
        $("#idproveedor").html(r);
        if ($.fn.selectpicker) {
            $('#idproveedor').selectpicker('refresh');
        }
    });

    $('#mCompras').addClass("treeview active");
    $('#lIngresos').addClass("active");
}

function cargarKPIsIngreso() {
    $.getJSON("../ajax/ingreso.php?op=kpisIngreso", function(data) {
        $("#kpi-total-ingresos").text(data.total);
        $("#kpi-inversion-ingresos").text("S/ " + parseFloat(data.inversion).toFixed(2));
        $("#kpi-aceptadas-ingresos").text(data.aceptadas);
    });
}

function limpiar() {
    $("#idproveedor").val("");
    if ($.fn.selectpicker) {
        $('#idproveedor').selectpicker('refresh');
    }
    $("#serie_comprobante").val("");
    $("#num_comprobante").val("");
    $("#impuesto").val("18");
    $("#total_compra").val("");
    $(".filas").remove();
    $("#total").html("S/ 0.00");

    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    $('#fecha_hora').val(today);

    $("#tipo_comprobante").val("Factura");
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
            url: '../ajax/ingreso.php?op=listar',
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
            url: '../ajax/ingreso.php?op=listarArticulos',
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

    if ($("#idproveedor").val() == "" || $("#idproveedor").val() == null) {
        Swal.fire({
            icon: 'warning',
            title: 'Proveedor Requerido',
            text: 'Seleccione un proveedor válido para registrar el ingreso.'
        });
        return false;
    }

    if (detalles == 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Sin artículos',
            text: 'Debe agregar al menos un artículo a la compra.'
        });
        return false;
    }

    $("#btnGuardar").prop("disabled", true);
    var formData = new FormData($("#formulario")[0]);

    $.ajax({
        url: "../ajax/ingreso.php?op=guardaryeditar",
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
            cargarKPIsIngreso();
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

function mostrar(idingreso) {
    $.post("../ajax/ingreso.php?op=mostrar", { idingreso: idingreso }, function(data, status) {
        data = JSON.parse(data);
        mostrarform(true);

        $("#idproveedor").val(data.idproveedor);
        if ($.fn.selectpicker) {
            $('#idproveedor').selectpicker('refresh');
        }
        $("#tipo_comprobante").val(data.tipo_comprobante);
        $("#serie_comprobante").val(data.serie_comprobante);
        $("#num_comprobante").val(data.num_comprobante);
        $("#fecha_hora").val(data.fecha);
        $("#impuesto").val(data.impuesto);
        $("#idingreso").val(data.idingreso);

        $("#btnGuardar").hide();
        $("#btnCancelar").show();
        $("#btnAgregarArt").hide();
    });

    $.post("../ajax/ingreso.php?op=listarDetalle&id=" + idingreso, function(r) {
        $("#detalles").html(r);
    });
}

function anular(idingreso) {
    Swal.fire({
        title: '¿Anular este ingreso?',
        text: 'El ingreso pasará a estado Anulado y se revertirá el balance.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sí, anular',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../ajax/ingreso.php?op=anular", { idingreso: idingreso }, function(e) {
                Swal.fire('Completado', e, 'success');
                tabla.ajax.reload();
                cargarKPIsIngreso();
            });
        }
    });
}

var cont = 0;
var detalles = 0;

function agregarDetalle(idarticulo, articulo) {
    var cantidad = 1;
    var precio_compra = 1;
    var precio_venta = 1;

    if (idarticulo != "") {
        var subtotal = cantidad * precio_compra;
        var fila = '<tr class="filas" id="fila' + cont + '">' +
            '<td><button type="button" class="btn btn-danger btn-xs" onclick="eliminarDetalle(' + cont + ')"><i class="fa fa-close"></i></button></td>' +
            '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + articulo + '</td>' +
            '<td><input type="number" name="cantidad[]" id="cantidad[]" value="' + cantidad + '" min="1" onchange="modificarSubotales()"></td>' +
            '<td><input type="number" name="precio_compra[]" id="precio_compra[]" value="' + precio_compra + '" step="0.01" onchange="modificarSubotales()"></td>' +
            '<td><input type="number" name="precio_venta[]" value="' + precio_venta + '" step="0.01"></td>' +
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
    $("#total").html("S/ " + total.toFixed(2));
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

init();