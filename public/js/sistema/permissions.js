function Permission() {
    var table;
    this.init = function () {
        $('#btnSave').on("click", function () {
            var param = {};
            param.user_id = $('#frmusuarios #id').val();
            param.ids = $('#jstree_demo_div').jstree(true).get_selected();

            $.ajax({
                url: 'usuarios/updatePermission',
                type: "POST",
                data: param,
                dataType: 'JSON',
                success: function (data) {
                    if (data.success == true) {
                        toastr.success("Operacion realizada");
                    }
                }
            })

        });
    }
}

var obj = new Permission();
obj.init();