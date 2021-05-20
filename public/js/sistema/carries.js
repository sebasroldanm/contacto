$(function () {

    $("#tabcarrier").click(function () {
        mensaje("alertamensaje", 'hidden');
        crearTabla("carries", 4, 'gestionCarrier');
        $(".datoscarries").limpiarCampos();
    })

    $("#registrarcarries").click(function () {
        var valido = $(".datoscarries").valida();
        mensaje("alertamensaje", 'hidden');

        var datos = $("#formCarries").serialize();

        if (valido == 0) {
            var res = crud(datos, 'carries/gestion');
            res.success(function (data) {
                if (data.error) {
                    mensaje("alertamensaje", 'error', data.error);
                } else {
                    mensaje("alertamensaje");
                    crearTabla("carries", 4);
                }
            })
        } else {
            mensaje("alertamensaje", 'error');
        }

    });
    $("#nuevocarrie").click(function () {
        $(".datoscarries").limpiarCampos();
        mensaje("alertamensaje", 'hidden');
    });

})

function gestionCarrier(id) {
    $("#formCarries #id").attr("disabled", false);
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'carries/cargaDatos');
    res.success(function (data) {
        $(".datoscarries").cargarCampos(data, false);
    })
    crearTabla("carries", 4);
}

function borrarEmpresa(id, clase) {
    $("#" + id).empty();
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'carries/borrar');
    mensaje("alertamensaje");
    crearTabla("carries", 4);
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