function BlackList() {
    var table;
    this.init = function () {
        table = this.table();

        $("#btnAdd").click(this.save);
        $("#btnNew").click(this.new);
        $("#subirExcel").click(this.addBlacklist);
        $("#btnConfirmation").click(this.confirmation);
    }

    this.confirmation = function () {
        var param = {};
        param.archivo_id = $("#archivo_id").val();
        $.ajax({
            url: "blacklist/confirmation",
            type: "POST",
            data: param,
            dataType: "JSON",
            success: function (data) {
                toastr.success("Operacion realizada");
                table.ajax.reload();
            }
        });
    }

    this.addBlacklist = function () {
        $("#archivo_id").val("");
        var formData = new FormData($("#frmExcel")[0]);
        $.ajax({
            url: "blacklist/uploadExcel",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (data) {
                //toastr.success("Operacion realizada");
                $("#archivo_id").val(data.archivo_id);
                $("#btnConfirmation").attr("disabled", false);
                obj.tableExcel(data.data);
            }
        });
    }

    this.tableExcel = function (detail) {
        var html = "";
        $("#tblResult tbody").empty();
        $.each(detail, function (i, val) {
            html += "<tr><td>" + val.numero + "</td>";
            html += '<td><button class="btn btn-warning btn-xs" type="button" onclick=obj.deleteItem(' + val.id + ',' + val.archivo_id + ')> <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>';
        })
        $("#tblResult tbody").html(html);
    }

    this.new = function () {
        $("#id").val("");
        $("#numero").val("");
    }

    this.save = function () {
        var obj = {};
        obj.numero = $("#frm #numero").val();
        obj.id = $("#id").val();
        obj.idusuario = $("#frm #idusuario").val();
        if (obj.numero != '' && !isNaN(obj.numero) && (obj.numero).length == 10) {
            $.ajax({
                url: "blacklist/add",
                method: 'post',
                data: obj,
                dataType: 'JSON',
                success: function (data) {
                    if (data.success == true) {
                        toastr.success("Operacion realizada");
                        table.ajax.reload();
                    }
                }
            })
        } else {
            alert("Problemas con el numero ingresado!");
        }
    }
    this.show = function (id) {
        $.ajax({
            url: "blacklist/getNumber/" + id,
            method: 'GET',
            dataType: 'JSON',
            success: function (data) {
                $("#id").val(data.id);
                $("#numero").val(data.numero);
            }
        })
    }

    this.delete = function (id) {

        $.ajax({
            url: "blacklist/deleteNumber/" + id,
            method: 'delete',
            dataType: 'JSON',
            success: function () {
                toastr.warning("Operacion realizada");
                table.ajax.reload();
            }

        })

    }
    this.deleteItem = function (id, archivo_id) {
        var data = {};
        data.archivo_id = archivo_id;
        data.id = id;
        $.ajax({
            url: "blacklist/deleteNumberItem/",
            method: 'POST',
            data: data,
            dataType: 'JSON',
            success: function (data) {
                obj.tableExcel(data.data);
                toastr.warning("Operacion realizada");
            }

        })

    }

    this.table = function () {
        return $('#tbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "blacklist/getList",
                method: "POST"
            },
            columns: [
                {data: "id", searchable: false, },
                {data: "numero"},
                {data: "usuario"},
                {data: "date_insert", searchable: false},
            ],
            order: [[1, 'ASC']],
            aoColumnDefs: [
                {
                    aTargets: [0, 1, 2, 3],
                    mRender: function (data, type, full) {
                        return '<a href="#" onclick="obj.show(' + full.id + ')">' + data + '</a>';
                    }
                },
                {
                    targets: [4],
                    searchable: false,
                    mData: null,
                    mRender: function (data, type, full) {
                        return '<button class="btn btn-danger btn-xs" onclick="obj.delete(' + data.id + ')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>';
                    }
                }
            ],
        });
    }
}

var obj = new BlackList();
obj.init();