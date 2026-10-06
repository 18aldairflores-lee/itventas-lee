var tabla;

function init() {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);
    var firstDay = now.getFullYear() + "-" + (month) + "-01";

    $("#fecha_inicio").val(firstDay);
    $("#fecha_fin").val(today);

    listar();
}

function listar() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();

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
            url: '../ajax/consultas.php?op=comprasfecha',
            data: { fecha_inicio: fecha_inicio, fecha_fin: fecha_fin },
            type: "get",
            dataType: "json",
            error: function(e) {
                console.log(e.responseText);
            }
        },
        "initComplete": function(settings, json) {
            calcularMetricasCompras(json);
        },
        "drawCallback": function(settings) {
            var api = this.api();
            var json = api.ajax.json();
            if (json) {
                calcularMetricasCompras(json);
            }
        },
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
    }).DataTable();
}

function calcularMetricasCompras(json) {
    if (!json || !json.aaData) return;

    var totalComprobantes = json.aaData.length;
    var totalComprado = 0;

    for (var i = 0; i < totalComprobantes; i++) {
        if (json.aaData[i][6] && json.aaData[i][6].indexOf("Aceptado") !== -1) {
            var valorLimpio = json.aaData[i][4].replace(/[^0-9.-]+/g, "");
            totalComprado += parseFloat(valorLimpio) || 0;
        }
    }

    var promedio = totalComprobantes > 0 ? (totalComprado / totalComprobantes) : 0;

    $("#kpi_monto_compras").text("S/ " + totalComprado.toFixed(2));
    $("#kpi_comprobantes_compras").text(totalComprobantes);
    $("#kpi_promedio_compras").text("S/ " + promedio.toFixed(2));
}

init();