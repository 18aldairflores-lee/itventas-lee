var tabla;

function init() {
    var now = new Date();
    var day = ("0" + now.getDate()).slice(-2);
    var month = ("0" + (now.getMonth() + 1)).slice(-2);
    var today = now.getFullYear() + "-" + (month) + "-" + (day);

    if (!$("#fecha_inicio").val()) {
        $("#fecha_inicio").val(today);
    }
    if (!$("#fecha_fin").val()) {
        $("#fecha_fin").val(today);
    }

    listar();

    $("#btnFiltrar").on("click", function(e) {
        e.preventDefault();
        listar();
    });

    $('#mConsultaC').addClass("treeview active");
    $('#lConsultac').addClass("active");
}

function listar() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();

    // Actualizar KPIs de compras por rango de fechas
    $.getJSON("../ajax/consultas.php?op=kpisComprasFecha", {
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin
    }, function(kpis) {
        $("#kpi-total-comprado").text("S/ " + parseFloat(kpis.total).toFixed(2));
        $("#kpi-total-registros").text(kpis.cantidad);
        $("#kpi-compra-promedio").text("S/ " + parseFloat(kpis.promedio).toFixed(2));
    }).fail(function(xhr, status, error) {
        console.error("Error al cargar KPIs de compras: " + error);
    });

    // Cargar DataTable
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
            data: { 
                fecha_inicio: fecha_inicio, 
                fecha_fin: fecha_fin 
            },
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

$(document).ready(function() {
    init();
});