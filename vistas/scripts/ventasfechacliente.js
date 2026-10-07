var tabla;

function init() {
    // Configurar fechas por defecto si vienen vacías
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

    // Cargar select de clientes
    $.post("../ajax/venta.php?op=selectCliente", function(r) {
        $("#idcliente").html(r);
        $("#idcliente").prepend('<option value="" selected>-- Todos los Clientes --</option>');
        if ($.fn.selectpicker) {
            $('#idcliente').selectpicker('refresh');
        }
    });

    listar();

    // Evento de clic en el botón Filtrar
    $("#btnFiltrar").on("click", function(e) {
        e.preventDefault();
        listar();
    });

    $('#mConsultaV').addClass("treeview active");
    $('#lConsulatav').addClass("active");
}

function listar() {
    var fecha_inicio = $("#fecha_inicio").val();
    var fecha_fin = $("#fecha_fin").val();
    var idcliente = $("#idcliente").val();

    // 1. Actualizar KPIs del reporte
    $.getJSON("../ajax/consultas.php?op=kpisVentasFecha", {
        fecha_inicio: fecha_inicio,
        fecha_fin: fecha_fin,
        idcliente: idcliente
    }, function(kpis) {
        $("#kpi-total-facturado").text("S/ " + parseFloat(kpis.total).toFixed(2));
        $("#kpi-comprobantes").text(kpis.cantidad);
        $("#kpi-ticket-promedio").text("S/ " + parseFloat(kpis.promedio).toFixed(2));
    }).fail(function() {
        console.error("Error al cargar los KPIs del reporte de ventas");
    });

    // 2. Cargar DataTable
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
            data: { 
                fecha_inicio: fecha_inicio, 
                fecha_fin: fecha_fin, 
                idcliente: idcliente 
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