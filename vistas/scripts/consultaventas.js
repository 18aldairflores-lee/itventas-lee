var tabla;

function init() {
    // Definir fechas predeterminadas: Primer día del mes actual hasta hoy
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    var firstDay = now.getFullYear() + "-" + (month) + "-01";

    $("#fecha_inicio").val(firstDay);
    $("#fecha_fin").val(today);

    // Cargar selector de clientes
    $.post("../ajax/venta.php?op=selectCliente", function(r) {
        $("#idcliente").html('<option value="">Todos los Clientes</option>' + r);
    });

    listar();
}

function listar() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idcliente = $("#idcliente").val();

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
            url: '../ajax/consultas.php?op=ventasfechacliente',
            data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, idcliente: idcliente },
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "initComplete": function(settings, json) {
            calcularMetricasPeriodo(json);
        },
        "drawCallback": function(settings) {
            var api = this.api();
            var json = api.ajax.json();
            if (json) {
                calcularMetricasPeriodo(json);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    }).DataTable();
}

function calcularMetricasPeriodo(json) {
    if (!json || !json.aaData) return;

    var totalComprobantes = json.aaData.length;
    var totalFacturado = 0;

    for (var i = 0; i < totalComprobantes; i++) {
        if (json.aaData[i][6] && json.aaData[i][6].indexOf("Aceptado") !== -1) {
            var valorLimpio = json.aaData[i][4].replace(/[^0-9.-]+/g, "");
            totalFacturado += parseFloat(valorLimpio) || 0;
        }
    }

    var ticketPromedio = totalComprobantes > 0 ? (totalFacturado / totalComprobantes) : 0;

    $("#kpi_monto_rango").text("S/ " + totalFacturado.toFixed(2));
    $("#kpi_comprobantes_rango").text(totalComprobantes);
    $("#kpi_ticket_promedio").text("S/ " + ticketPromedio.toFixed(2));
}

init();