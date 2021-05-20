$(function () {
    
     $("#tabservicios").click(function () {
         mensaje("alertamensaje", 'hidden');
        crearTabla("servicios", 5,'gestionServicios');
        $(".datosservicios").limpiarCampos();
    })

    $("#registrarservicios").click(function () {
        var valido = $(".datosservicios").valida();
        mensaje("alertamensaje", 'hidden');

        var datos = $("#formServicios").serialize();

        if (valido == 0) {
            var res = crud(datos, 'servicios/gestion');
            res.success(function (data) {
                if (data.error) {
                    mensaje("alertamensaje", 'error', data.error);
                } else {
                    mensaje("alertamensaje");
                    crearTabla("servicios", 4);
                }
            })
        } else {
            mensaje("alertamensaje", 'error');
        }

    });
    $("#nuevoempresa").click(function () {
        $(".datosservicios").limpiarCampos();
        mensaje("alertamensaje", 'hidden');
    });

})

function gestionServicios(id) {
    $("#formServicios #id").attr("disabled", false);
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'servicios/cargaDatos');
    res.success(function (data) {
        $(".datosservicios").cargarCampos(data, false);
    })
    crearTabla("servicios", 4);
}

function borrarEmpresa(id, clase) {
    $("#" + id).empty();
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'servicios/borrar');
    mensaje("alertamensaje");
    crearTabla("servicios", 4);
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