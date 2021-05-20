function Gerencias() {
    var tableContent, conteoInterval;
    this.init = function () {
        tableContent = obj.table();

        setInterval(function () {
            tableContent.ajax.reload();
        }, 3500);
    }


    this.table = function () {

        return $('#tbl').DataTable({
            dom:
                    "R<'row'<'col-sm-4'l><'col-sm-2 toolbar text-right'><'col-sm-3'B><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12't>>" +
                    "<'row'<'col-xs-3 col-sm-3 col-md-3 col-lg-3'i><'col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center'p><'col-xs-3 col-sm-3 col-md-3 col-lg-3'>>",
            processing: true,
            processing: true,
            serverSide: true,

            ajax: {
                url: "../reportes/getRegistrosFast",
                method: "POST",
            },
            columns: [
                {data: "id"},
                {data: "nombre"},
                {data: "fecha"},
                {data: "registros"},
                {data: "conteo"},
                {data: "porcentaje", render: function (data, type, row) {
                        var color = 'progress-bar-warning';
                        if (row.porcentajen > 60 && row.porcentajen < 100) {
                            color = 'progress-bar-info'
                        } else if (row.porcentajen == 100) {
                            color = 'progress-bar-success'
                        }

                        return `
                                <div class="progress">
                                    <div class="progress-bar ${color}" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: ${row.porcentaje};">
                                      ${row.porcentaje}
                                    </div>
                                  </div>`;

                    }
                },
            ],
            order: [[1, 'ASC']],
            aoColumnDefs: [
                {
                    aTargets: [1],
                    mRender: function (data, type, full) {
                        return '<a href="#" onclick="obj.show(' + full.id + ')">' + data + '</a>';
                    }
                }
            ],
            buttons: [
                {
                    text: 'Recargar',
                    action: function (e, dt, node, config) {
//                        clearInterval(conteoInterval)
                        tableContent.ajax.reload();

                    }
                }
            ],
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });
    }

    this.downloadExcel = function () {
        window.open('getGerenciasExcel');
    }
}

var obj = new Gerencias();
obj.init();
