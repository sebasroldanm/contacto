$(function () {
    $("#btnguardar").click(function () {
        var user = new Array(), cont = 0;
        var pre = new Array();
        var datos = {}

        $(".listadoUsuario").each(function () {
            if ($(this).is(":checked")) {
                user[cont] = $(this).val();
                cont++;
            }
        })

        cont = 0;
        $(".frm-pre").each(function () {
            pre[cont] = $(this).val();
            cont++;
        })

        datos.usuarios = user;
        datos.pre = pre;
        $.ajax({
            url: 'preferencias/gestion',
            type: 'POST',
            data: datos,
            dataType: 'JSON',
            beforeSend: function () {
                $("#alertamensaje").addClass("hidden");
            },
            success: function (data) {
                if (data.msj) {
                    $(".listadoUsuario").prop("checked", false);
                    $("#seleccionar").prop("checked", false);
                    $("#alertamensaje").removeClass("hidden").html(data.msj);
                } else {
                    $("#alertamensaje").addClass("hidden");
                }
            }
        })
    })

    $("#seleccionar").click(function () {

        if ($(this).is(":checked")) {
            $(".listadoUsuario").prop("checked", true);
        } else {
            $(".listadoUsuario").prop("checked", false);
        }
    })

})