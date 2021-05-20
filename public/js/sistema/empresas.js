$(function () {
    crearTabla("administrador", 7, 'gestionEmpresa');
    mensaje("alertamensaje", 'hidden');

    $("#tabempresas").click(function () {
        crearTabla("administrador", 7, 'gestionEmpresa');
        mensaje("alertamensaje", 'hidden');
        $(".datosempresa").limpiarCampos();
    })


    $("#registrarempresa").click(function () {
        var valido = $(".datosempresa").valida();
        mensaje("alertamensaje", 'hidden');

        var datos = $("#formEmpresas").serialize();

        if (valido == 0) {
            var res = crud(datos, 'empresas/gestion');
            res.success(function (data) {
                if (data.error) {
                    mensaje("alertamensaje", 'error', data.error);
                } else {
                    mensaje("alertamensaje");
                    crearTabla("administrador", 7, 'gestionEmpresa');
                }
            })
        } else {
            mensaje("alertamensaje", 'error');
        }

    });
    $("#nuevoempresa").click(function () {
        $(".datosempresa").limpiarCampos();
        mensaje("alertamensaje", 'hidden');
    });

})

function gestionEmpresa(id) {
    $("#formEmpresas #id").attr("disabled", false);
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'empresas/cargaDatos');
    res.success(function (data) {
        $(".datosempresa").cargarCampos(data, false);
    })
}

function borrarEmpresa(id, clase) {
    $("#" + id).empty();
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'empresas/borrar');
    crearTabla("administrador", 7, 'gestionEmpresa');

    mensaje("alertamensaje");
}