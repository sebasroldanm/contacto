function Portados() {
    var table;
    this.init = function () {
        table = this.table();

        $("#btnAdd").click(this.save);
        $("#btnNew").click(this.new);
        $("#subirExcel").click(this.addBlacklist);
        $("#subirExcelbases").click(this.validateBases);
        $("#btnConfirmation").click(this.confirmation);
    }

    this.confirmation = function () {
        toastr.remove();
        var param = {};
        param.archivo_id = $("#archivo_id").val();
        $.ajax({
            url: "portados/confirmation",
            type: "POST",
            data: param,
            dataType: "JSON",
            success: function (data) {
                toastr.success("Numero confirmado");
                table.ajax.reload();
            }
        });
    }

    this.validateBases = function () {
        var elem = $(this);
        $("#link_download").addClass("hidden");
        elem.attr("disabled", true);
        $("#frmExcelBases #archivo_id").val("");
        var formData = new FormData($("#frmExcelBases")[0]);
        $.ajax({
            url: "portados/uploadExcelBases",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (data) {
                toastr.success("Operacion realizada");
                $("#link_download").attr("href", $("#link_download").attr("href") + data.archivo_id).removeClass("hidden");
//                $("#frmExcelBases #archivo_id").val(data.archivo_id);
                elem.attr("disabled", false);
            }
        });
    }

    this.addBlacklist = function () {
        $("#frmExcel #archivo_id").val("");
        var formData = new FormData($("#frmExcel")[0]);
        $.ajax({
            url: "portados/uploadExcel",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "JSON",
            success: function (data) {
                toastr.success("Operacion realizada");
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
            html += "<td>" + val.previuos + "</td>";
            html += "<td>" + val.current_carrie + "</td>";
            html += '<td><button class="btn btn-warning btn-xs" type="button" onclick=obj.deleteItem(' + val.id + ',' + val.archivo_id + ')> <span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td></tr>';
        })
        $("#tblResult tbody").html(html);
    }

    this.new = function () {
        $(".input-portados").limpiarCampos();
    }

    this.save = function () {
        var elem = $(this);
        elem.attr("disabled", true);
        var obj = {};
        var data = $("#frm").serialize();
        if ($("#numero").val() != '' && !isNaN($("#numero").val()) && ($("#numero").val()).length == 10) {
            $.ajax({
                url: "portados/add",
                method: 'post',
                data: data,
                dataType: 'JSON',
                success: function (data) {
                    if (data.success == true) {
                        toastr.success("Operacion realizada");
                        table.ajax.reload();
                        elem.attr("disabled", false);
                    }
                }
            })
        } else {
            alert("Problemas con el numero ingresado!");
        }
    }
    this.show = function (id) {
        $.ajax({
            url: "portados/getNumber/" + id,
            method: 'GET',
            dataType: 'JSON',
            success: function (data) {
                $(".input-portados").cargarCampos(data, false);
            }
        })
    }

    this.delete = function (id) {

        $.ajax({
            url: "portados/deleteNumber/" + id,
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
            url: "portados/deleteNumberItem/",
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
                url: "portados/getList",
                method: "POST"
            },
            columns: [
                {data: "id", searchable: false, },
                {data: "numero"},
                {data: "previuos"},
                {data: "current_carrie"},
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
                    targets: [5],
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

var obj = new Portados();
obj.init();