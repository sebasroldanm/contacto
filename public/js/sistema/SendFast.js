var valida, path = "", intervalo;
$(function () {
    var smslong = 0, cupo = 0, concatena = 0, next = 0;
    $("#message").cuentaPalabras("#contentMessage");
    $("#btnClean").click(function () {
        $("#destination,#message,#note").val("");
        $("#contentMessage").html("");
        $("#txtcodigo").html("");
        $(".progress-bar").css("width", "0%");
        $("#txtprocess").html("0%");
    })

    $("#btnConsolidate").click(function () {
        var msg = '';
        valida = [];
        $("#txtcodigo").html("");
        $("#btnSendMsg").attr("disabled", false);
        valida = $("#destination").createSend($("#message").val());

        if (($("#destination").val()).length != 0 || ($("#message").val()).length != 0) {
            if ((valida.errados).length > 0) {
                toastr.error("Se encontraron errores con los numeros, se enviaran los que no tiene error!");
            }

            $("#message,#destination").removeClass("error");
            if ($("#message").val() != '' && $("#note").val() != '') {
                $.ajax({
                    url: 'SendFast/getCupo',
                    type: "POST",
                    dataType: 'JSON',
                    async: false,
                    success: (function (data) {
                        concatena = data.concatena;
                        next = data.next;
                        cupo = data.cupo.disponible;
                    })
                })

                if (concatena != "1" && ($("#message").val()).length > 160) {
                    toastr.error("No tiene permitido enviar mas de 160 caracteres, favor contactarse con el área de soporte de CONTACTOSMS");
                    return false;
                }
                $("#modalSms").modal("show");
                msg = $("#message").val();
                $("#txtInformation").empty();
                $("#txtInformation").html("Cantidad Total: <b>"
                        + ((valida.numeros).length + (valida.errados).length)
                        + "</b><br>Longitud Mensaje: " + (($("#message").val()).length)
                        + "</b><br>Total Envio: " + ((valida.numeros).length)
                        + "</b><br>Total Errores: " + ((valida.errados == '') ? 0 : valida.errados.length)
                        + "<br>Cupo: " + cupo);
                // $("#txtMensaje").html("<p>Mensaje: " + msg + "</p>")
            } else {
                $("#message,#note").addClass("error");
                toastr.error("El Mensaje y la nota no pueden estar vacios!");
            }
        } else {
            $("#message,#destination").addClass("error");
            toastr.error("No hay destinatarios disponibles!");
        }
    })

    $("#btnSendMsg").click(function () {
        var obj = {};
        obj.data = valida.numeros;
        $(this).attr("disabled", true);
        $("#close").attr("disabled", true);
        valida = $("#destination").validaTextarea();
        obj.path = path;
        obj.quantitynumbers = (valida.numeros).length;
        obj.note = $("#note").val();
        $("#loading").removeClass("hidden");
        $.ajax({
            url: 'SendFast/createFile',
            type: "post",
            data: obj,
            async: true,
            dataType: 'JSON',
            error: function () {
                alert("Hubo problemas");
            },
            success: function (data) {
//                $("#counter").html("Procesando...");
                path = data.path;
                idbase = data.idbase;
                processData();
//                intervalo = setInterval("processData()", 10000);
                $("#btnConsolidate,#btnClean").attr("disabled", true);
                
            }
        })
    })

});


function processData() {
    var obj = {};
    obj.path = path;
    obj.idbase = idbase;
    $.ajax({
        url: 'SendFast/receiveNumbers',
        type: "POST",
        async: true,
        data: obj,
        dataType: 'JSON',
        success: function (data) {
            $("#modalSms").modal("hide");
//            $(".progress-bar").css("width", data.porcentaje + "%");
//            $("#txtprocess").html((data.porcentaje).toFixed(1) + "%");
//            $("#counter").html("<b>" + (data.totalnumbers - data.current) + "</b> de " + data.totalnumbers);

            if (data.status == true) {
                toastr.success("proceso Realizado!");
                $("#txtcodigo").empty().html("Codigo Verificación: <b>" + data.idbase + "</b>");
//                clearInterval(intervalo);
                $("#btnConsolidate,#btnClean").attr("disabled", false);
                $("#close").attr("disabled", false);
                $("#loading").addClass("hidden");
//                $("#counter").html("0 de 0");
            }

        }
    });
}