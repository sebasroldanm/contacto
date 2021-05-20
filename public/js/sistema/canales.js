$(function () {

    $("#tabcanales").click(function () {
        mensaje("alertamensaje", 'hidden');
        crearTabla("canales", 14, 'gestionCanales');
        $(".canales").limpiarCampos();
    })

    $("#registrarCanales").click(function () {
        var valido = $(".canales").valida();
        mensaje("alertamensaje", 'hidden');

        var datos = $("#formCanales").serialize();

        if (valido == 0) {
            var res = crud(datos, 'canales/gestion');
            res.success(function (data) {
                if (data.error) {
                    mensaje("alertamensaje", 'error', data.error);
                } else {
                    mensaje("alertamensaje");
                    crearTabla("canales", 14, 'gestionCanales');
                }
            })
        } else {
            mensaje("alertamensaje", 'error');
        }

    });
    $("#nuevocanal").click(function () {
        $(".datoscarries").limpiarCampos();
        mensaje("canales", 'hidden');
    });

})

function gestionCanales(id) {
    $("#formCanales #id").attr("disabled", false);
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'canales/cargaDatos');
    res.success(function (data) {
        $(".canales").cargarCampos(data, false);
    })
}

function borrarEmpresa(id, clase) {
    $("#" + id).empty();
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'canales/borrar');
    mensaje("alertamensaje");
    crearTabla("canales", 14, 'gestionCanales');
}

/**
 * Funcion para obterner los datos de una tabla pasandole como parametro
 * @param {type} id
 * @param {type} tabla
 * @returns {undefined}
 */
function cargaTablaEmpresa(id, tabla) {
    table.ajax.reload();
}