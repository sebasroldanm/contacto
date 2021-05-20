var tabla;
$(function () {



    $("#btnreporte").click(function () {
        mensaje("alertamensaje", 'hidden');

        var idbase = $("#idbase").val(), inicio, final = '', html = '';
        inicio = $("#inicio").val();
        final = $("#final").val();
        var datos = $(".inputProgramados");
        if (idbase != '' || inicio != '') {


            var obj = {};
            obj.data = {};
            obj.data.idbase = idbase;
            obj.data.inicio = inicio;
            obj.data.final = final;

            obj.url = "programados";
            obj.idtabla = "tblprogramados";
            var tabla = cargaTabla(obj);

        } else {
            alert("Debes Ingresa Datos para realizar la Busqueda");
        }
    });

    $("#btncancelar").click(function () {
        var idbase = $("#idbase").val();

        if (idbase != '') {
            var datos = {};
            datos.idbase = idbase;
            var res = crud(datos, 'programados/cancelarEnvios');
            res.success(function (data) {
                if (data.cancelados > 0) {
                    mensaje("alertamensaje", null, 'Se han Cancelado ' + data.cancelados + " Mensajes programados");
                } else {
                    mensaje("alertamensaje", 'hidden');
                }
                tabla.ajax.reload();
            })
        } else {
            alert("Por favor Ingresa el numero de la base para poderla cancelar!");
        }
    });
});

