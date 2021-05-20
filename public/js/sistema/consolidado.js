function consolidado() {
    var table;
    this.init = function () {
        table = this.table();
    }

    this.table = function () {
        return $('#tablafecha').DataTable({
            ajax: {
                url: "getConsolidado",
                method: "POST"
            },
            columns: [
                {data: "id"},
                {data: "usuario"},
                {data: "enviados"},
                {data: "disponible"},
                {data: "servicio"},
                {data: "tiposervicio", render: function (data, type, row) {
                        var total = parseInt(row.disponible) + parseInt(row.enviados);
//                        console.log(total)
                        var por = (row.disponible / total) * 100;
                        var html = 'Ok';
                        
                        if (row.enviados != '0') {
                            if (row.tiposervicio == 1) {
                                if (por < 20) {
                                    html = "Saldo disponible bajo. Comuníquese con asistente.comercial@contactosms.com.co o cpineda@contactosms.com.co para adquirir un nuevo plan";
                                }
                            } else {
                                if (por < 10) {
                                    html = "Saldo disponible bajo. Comuníquese con asistente.comercial@contactosms.com.co o cpineda@contactosms.com.co para adquirir un nuevo plan";
                                }
                            }
                        }
                        return html;
                    }
                },
            ],
            order: [[1, 'ASC']],
            aoColumnDefs: [
                {
                    aTargets: [0, 1, 2, 3],
                    mRender: function (data, type, full) {
                        return '<a href="#" onclick="obj.show(' + full.id + ')">' + data + '</a>';
                    }
                }
            ],
        });
    }
}

var obj = new consolidado();
obj.init();