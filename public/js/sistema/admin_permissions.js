function Permission() {
    var table;
    this.init = function () {
        $.ajax({
            url: 'getPermission',
            type: "POST",
            dataType: 'JSON',
            success: function (data) {
                $('#jstree_demo_div').jstree(data.data);
            }
        })

        $("#btnSave").click(this.save);

        $('#jstree_demo_div').on("select_node.jstree", function (e, data) {
            var param = {};
            param.node_id = data.node.id;
            $.ajax({
                url: 'getNode',
                type: "POST",
                data: param,
                dataType: 'JSON',
                success: function (data) {
                    $(".input-admin").cargarCampos(data.data, false);
                }
            })

        });
    }

    this.save = function () {
        var elem = $(this);
        elem.attr("disabled", true);
        $.ajax({
            url: 'updatePermissionId',
            type: "POST",
            data: $("#formPermission").serialize(),
            dataType: 'JSON',
            success: function (data) {
                toastr.success("Operacion realizada");
                elem.attr("disabled", false);
            }
        })
    }
}


var obj = new Permission();
obj.init();