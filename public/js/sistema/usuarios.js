$(function () {
    $("#tabusuarios").click(function () {
        crearTabla("usuarios", 7, 'gestionUsuarios');
        mensaje("alertamensaje", 'hidden');
        $(".registro").limpiarCampos();
    })

    $("#btnregistro").click(function () {
        var valido = $(".registro").valida();
        mensaje("alertamensaje", 'hidden');

        if (valido == 0) {
            var datos = $("#frmusuarios").serialize();

            var res = crud(datos, 'usuarios/gestion'), pre;
            res.success(function (data) {

                if (data.error != undefined) {
                    mensaje("alertamensaje", 'error', data.error);
                } else if (data.datos != '') {
                    $(".registro").cargarCampos(data.datos, false);

                    pre = data.datos.preferencias.split(",");
                    $.each(pre, function (i, val) {
                        $(".preferencias").each(function () {
                            if ($(this).attr("id") == i) {
                                $(this).val(val);
                            }
                        })
                    })
                    mensaje("alertamensaje");
                    recargar();
                }
            })
        } else {
            mensaje("alertamensaje", 'error');
        }
    });

    $("#btnnuevousuario").click(function () {
        $(".registro").attr("disabled", false);
        $(".registro").val("");
        mensaje("alertamensaje", 'hidden');
        $("#ver").attr("disabled", true);
    });

    $("#ver").click(function () {

        if ($("#ver").is(':checked')) {

            var id = $("#frmusuarios #id").val();
            if ($("#clave").val() != '') {
                var obj = {};
                obj.id = id;
                obj.ver = "ok";

                var res = crud(obj, 'usuarios/cargaDatos');
                res.success(function (data) {
                    $("#clave").attr("type", 'text');
                    $("#clave").val(data["datos"].clave);
                })
            } else {
                $(this).attr("checked", false);
            }
        } else {
            $("#clave").attr("type", 'password');
        }
    })

})


function gestionUsuarios(id) {
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'usuarios/cargaDatos');
    res.success(function (data) {
        $(".registro").cargarCampos(data.datos, false);
        $.each(data.preferencia, function (i, val) {
            $(".preferencias").each(function () {
                if (($(this).attr("id")).trim() == i.trim()) {
                    $(this).val(val);
                }
            })
        })
        mensaje("alertamensaje", 'hidden');

        
        $('#jstree_demo_div').jstree(data.permissions);

        $("#tabpermissions").removeClass("hidden");
        recargar();
    })
}

function borrar(id, clase) {
    $("#" + id).empty();
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'usuarios/borrar');
    recargar();
    mensaje("alertamensaje");
}


