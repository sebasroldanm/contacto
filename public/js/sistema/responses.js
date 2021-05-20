function Responses() {
    var table;
    this.init = function () {
        table = this.table();
        $("#btnSearch").click(this.table);
        console.log("ad");
    }


    this.table = function () {
        var param = {};
        param.inicio = $("#form-responses #inicio").val();
        param.final = $("#form-responses #final").val();
        param.idcanal = $("#form-responses #idcanal").val();

        return $('#tblResponses').DataTable({
            dom:
                    "R<'row'<'col-sm-4'l><'col-sm-2 toolbar text-right'><'col-sm-3'B><'col-sm-3'f>>" +
                    "<'row'<'col-sm-12't>>" +
                    "<'row'<'col-xs-3 col-sm-3 col-md-3 col-lg-3'i><'col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center'p><'col-xs-3 col-sm-3 col-md-3 col-lg-3'>>",
            processing: true,
            serverSide: true,
            destroy: true,
            ajax: {
                url: "getResponses",
                method: "POST",
                data: param
            },
            columns: [
                {data: "canal"},
                {data: "source"},
                {data: "numero"},
                {data: "mensaje"},
                {data: "fecha", searchable: false},
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
            buttons: [
            
                {
                    extend: 'excelHtml5',
                    className: 'btn btn-primary glyphicon glyphicon-download',
                    titleAttr: 'Excel'
                },
            ],
        });
    }
}

var obj = new Responses();
obj.init();