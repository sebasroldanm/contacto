function Password() {
    this.init = function () {
        $("#btnUpdate").click(this.save);
    }

    this.save = function () {
        $("#btnUpdate").attr("disabled",true);
        var obj = {};
        var data = $("#formpasswd").serialize();
        if ($("#password").val() == $("#confirmation").val()) {
            $.ajax({
                url: "updatePassword",
                method: 'post',
                data: data,
                dataType: 'JSON',
                success: function (data) {
                    if (data.success == true) {
                        toastr.success(data.msg);
                    } else {
                        toastr.error(data.msg);
                    }
                    $("#btnUpdate").attr("disabled",false);


                }, error: function (xhr, ajaxOptions, thrownError) {
                    toastr.error(xhr.responseJSON.msg);
                },
            })
        } else {
            toastr.error("la nueva Clave con la confirmación deben ser iguales!");
        }
    }

}

var obj = new Password();
obj.init();