$(function () {

    $('.fechas').datetimepicker({
        lang: 'es',
        format: 'd/m/Y H:i',
        timepicker: true
    });
    $('#boton').datetimepicker({
        lang: 'es',
        format: 'Y-m-d',
        timepicker: false
    });
    $("#client_id").change(function () {
        var param = {};
        param.client_id = $(this).val();

        $('#tblCargue').DataTable({
            ajax: {
                url: 'exceltemplate/getTemplate',
                data: param,
                method: "POST"
            },
            destroy: true,
            columns: [
                {data: "id"},
                {data: "phone"},
                {data: "campo1"},
                {data: "campo2"},
                {data: "campo3"},
                {data: "filtro1"},
                {data: "filtro2"},
                {data: "filtro3"},
                {data: "filtro4"},
                {data: "filtro5"},
                {data: "filtro6"},
            ],
        });
    })

    var objexcel = {}, infocarga = {};
    $("#configuracion").click(function () {

        if ($("#panelconfig").hasClass("open")) {
            $("#panelconfig").fadeOut();
            $("#panelconfig").removeClass("open");
        } else {
            $("#panelconfig").addClass("open");
            $("#panelconfig").fadeIn();
        }


    })

    $(":file").change(function () {
        var file = $("#archivo")[0].files[0];
        $("#spandatos").removeClass("hidden");
        if (file.type == "application/vnd.ms-excel") {
            $("#spandatos").html('<b>Archivo</b> : ' + file.name + " <br> <b>Tamaño Aprox:</b> " + file.size + "Kb");
            $("#registrosOk").addClass("hidden");
        } else {
            alert("formato no valido");
        }
    });
    $("#subir").click(function () {

        var string = "", fecha = "", html = '', html2 = '';
        if ($("#archivo").val() != '') {
            if ($("#prueba").is(":checked")) {
                string = "Señor Usuario usted tiene la opcion de envio de prueba, por lo cual solo podra realizar 3 envio de sms";
                $("#mensaje").text(string);
                $(".modalaviso2").modal("show");
            } else if ($("#habilitafecha").is(":checked")) {
                fecha = $("#fechaprogramada").val();
                string = "Señor Usuario usted tiene la opcion de Fecha reprogramado activo, por lo cual se enviara hasta la indicada";
                if (fecha != '') {
                    $("#mensaje").text(string);
                    $(".modalaviso").modal("show");
                } else {
                    alert("Por favor coloque la fecha");
                }

            } else {

                var res = preCarga2("exceltemplate/preCarga");
                res.success(function (data) {
                    $("#tablainfo tbody").empty();
                    $("#tablaanterior tbody").empty();
                    $("#infobase").val(data.idbase);
                    $("#continuar").attr("disabled", false);
                    $("#estado").html("<span style='color:green'>100% Completo</span>");
                    html = "<tr><td><b>Registro a cargar</b></td><td>" + data.filas + "</td><tr>";
                    html += "<tr><td><b>Nombre Archivo</b></td><td>" + data.nombreactual + "</td></tr>";
                    html2 = "<tr><td><b>Registro a cargados</b></td><td>" + data.registrosanterior + "</td><tr>";
                    html2 += "<tr><td><b>Nombre Archivo</b></td><td>" + data.nombreaanterior + "</td></tr>";
                    if (data.nombreactual == data.nombreaanterior) {
                        $(".alerta").removeClass("hidden").html("<b>Probablemente este Subiendo la misma base Anterior!, Desea Continuar?</b>");
                    } else {
                        $("#msjcontinuar").html("<b>Desea Continuar?</b>");
                        $(".alerta").addClass("hidden");
                    }

                    $("#tablainfo tbody").html(html);
                    $("#tablaanterior tbody").html(html2);
                    $("#idarchivo").val(data.idarchivo);
                    $("#frmsubida #idbase").val(data.idbase);
                });
            }

        } else {
            alert("No hay archivos para subir");
        }
    });
    $("#descargaExcel").click(function () {
        var idbase = objexcel.idbase;
        window.open('exceltemplate/excelErrores/' + idbase, '_blank');
    });
    $("#continuar").click(function () {
        var fecha = "", objeto = {}, ruta = $("#frmsubida #rutaarchivo").val();
        var archivo = $("#frmsubida #idarchivo").val(), idbase = $("#frmsubida #idbase").val(), clase;
        $(".modalaviso").modal("hide");
        if ($("#habilitafecha").is(":checked")) {
            fecha = $("#fechaprogramada").val();
            $("#registrosOk").addClass("hidden");
            $("#descargaExcel").addClass("hidden");
            $(".modalaviso").modal("hide");
            subirArchivos("exceltemplate/cargaExcel", fecha);
        } else {
            $("#registrosOk").addClass("hidden");
            $("#descargaExcel").addClass("hidden");
            $(".modalaviso").modal("hide");
            objeto.client_id = $("#cargar #client_id").val();
            objeto.idarchivo = archivo;
            objeto.idbase = idbase;
            objeto.fechaprogramado = $("#frmfecha #fecha").val();
            $("#subir").attr("disabled", true);
            $(".cargando").removeClass("hidden");
            var res = crud(objeto, 'exceltemplate/cargaExcel'), envioefectivo = 0;
            res.success(function (data) {
                if (data != null) {
                    toastr.success("Archivo cargado")
                }

                $('#tblCargue').DataTable({
                    data: data.data,
                    destroy: true,
                    columns: [
                        {data: "id"},
                        {data: "phone"},
                        {data: "campo1"},
                        {data: "campo2"},
                        {data: "campo3"},
                        {data: "filtro1"},
                        {data: "filtro2"},
                        {data: "filtro3"},
                        {data: "filtro4"},
                        {data: "filtro5"},
                        {data: "filtro6"},
                    ],
                });
                $("#subir").attr("disabled", false);
                $(".cargando").addClass("hidden");
            });
        }

    });
    $("#continuar2").click(function () {
        $("#registrosOk").addClass("hidden");
        $("#descargaExcel").addClass("hidden");
        subirArchivosPrueba();
        $(".modalaviso2").modal("hide");
    });
    $(".nomodal").click(function () {
        $("#subir").attr("disabled", false);
    });
    $("#habilitafecha").click(function () {

        if ($(this).is(":checked")) {
            $("#fechaprogramada").attr("disabled", false);
        } else {
            $(this).empty();
            $("#fechaprogramada").attr("disabled", true);
        }

    });
    $("#habilitafecha").click(function () {
        if ($(this).is(":checked")) {
            $("#prueba").attr("disabled", true);
        } else {
            $("#prueba").attr("disabled", false);
        }
    })

    $("#confirmacioncarga").click(function () {
        var obj = {};
        obj.idbase = objexcel.idbase;
        obj.tipo = 'confirmado';
        $("#nuevo").attr("disabled", false);
        var res = crud(obj, 'exceltemplate/procesarCarga');
        res.success(function (data) {

            if (data.errores != undefined) {
                mensaje("alertaconfimacion", 'error', '<b>Sin cupo sucifiente</b>');
            } else {
                $("#confirmacioncarga").attr("disabled", true);
                mensaje("alertaconfimacion", null, "<b>" + data.mensaje + "!</b>");
                $("#cancelarcarga").attr("disabled", true);
            }
            $("#cupo").html(data.cupo.disponible + " SMS");
        })
    })

    $("#cancelarcarga").click(function () {
        var obj = {};
        obj.idbase = objexcel.idbase;
        obj.tipo = "cancelado";
        $("#nuevo").attr("disabled", false);
        var res = crud(obj, 'exceltemplate/procesarCarga');
        res.success(function (data) {
            $("#confirmacioncarga").attr("disabled", true);
            mensaje("alertaconfimacion", 'error', "<b>" + data.mensaje + "!</b>");
        })
    })

    $("#nuevo").click(function () {
        $(this).attr("disabled", false);
        $("#subir").attr("disabled", false);
        $(".informacioncarga").addClass("hidden");
        $(".alertaconfimacion").addClass("hidden");
        $("#spandatos").addClass("hidden");
        $("#confirmacioncarga").attr("disabled", false);
        $("#cargar")[0].reset();
        $("#cancelarcarga").attr("disabled", false);
        $("#fecha").attr("disabled", false);
        $(".cargando").addClass("hidden");
        $("#descargaExcel").addClass("hidden");
        $('#tblCargue').DataTable({
            data: [],
            destroy: true,
            columns: [
                {data: "id"},
                {data: "phone"},
                {data: "campo1"},
                {data: "campo2"},
                {data: "campo3"},
                {data: "filtro1"},
                {data: "filtro2"},
                {data: "filtro3"},
                {data: "filtro4"},
                {data: "filtro5"},
                {data: "filtro6"},
            ],
        });
    })


});
function verErrores(id) {
    var obj = {}, txt = '';
    obj.idbase = id;
    $(".modalbase").modal("show");
    $("#tablabase tbody").empty();
    var res = crud(obj, 'exceltemplate/verErrores');
    res.success(function (data) {
        txt = '';
        $.each(data, function (i, val) {
            txt += '<tr>';
            txt += '<td>' + val["numero"] + '</td>';
            txt += '<td>' + val["mensaje"] + '</td>';
            txt += '<td>' + val["nota"] + '</td>';
            txt += '</tr>';
        })
        $("#tablabase tbody").html(txt);
    })
}


function preCarga2(controlador) {

    var formData = new FormData($("#cargar")[0]);
    return $.ajax({
        url: controlador,
        type: "POST",
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        dataType: "JSON",
        beforeSend: function () {
            $("#cargar .cargando").removeClass("hidden");
        },
        xhr: function () {
            myXhr = $.ajaxSettings.xhr();
            if (myXhr.upload) {
                myXhr.upload.addEventListener('progress', function (evt) {
                    if (evt.lengthComputable) {

                        var porcentaje = (evt.loaded / evt.total) * 100;
                        $('#progressbar').attr(
                                {
                                    value: evt.loaded,
                                    max: evt.total
                                }
                        );
                        $(".modalaviso").modal("show");
                        $(".cargando").addClass("hidden");
                        $("#total").html("Subido: " + evt.loaded + " Tamaño total:" + evt.total);
                        $("#estado").html(Math.round(porcentaje) + "% subido espere...");
//                        $("#informacion").html("<b>Cantidad de Registros en el Archivo: " + data.filas + "</b>");
                    }
                }, false); // for handling the progress of the upload
            }
            return myXhr;
        }
    })
}



function CargaExcel(objeto) {
    var texto = '';
    $.ajax({
        url: 'exceltemplate/cargaExcel',
        type: "POST",
        data: objeto,
        dataType: "JSON",
        sucess: function (data) {
            log(data);
            $(".cargando").addClass("hidden");
            if (data > 0) {
                texto = "Los <b>" + data + "</b> registros se subieron con exito";
                $("#cargar #registrosOk").removeClass("hidden").html(texto);
                $("#cargar #alerta").addClass("hidden");
                if (!$("#cargar #error").hasClass("hidden")) {
                    $("#cargar #error").addClass("hidden");
                }
            } else if (data.errores != '' && data.errores != null) {
                $("#cargar #idbase").val(data.idbase);
                texto = "Registros correctos :" + data.ok + ', Errores: ' + data.errores;
                $("#cargar #alerta").removeClass("hidden").html(texto);
                $("#cargar #descargaExcel").removeClass("hidden");
            } else {
                texto = "<b>La extensión del archivo no es valida para procesar</b>";
                $("#cargar #error").removeClass("hidden").html(texto);
            }
            $('#cargar #archivo').val("");
            $("#cargar #subir").attr("disabled", false);
            $("#cargar #spandatos").empty();
            $('#cargar #subir').attr("disabled", true);
        }
    })


}

function preCarga(ruta, fecha) {
    var formData = new FormData($("#cargar")[0]);
    fecha = (fecha == undefined) ? '' : fecha;
    formData.append("fecha", fecha);
    var texto = "";
    return $.ajax({
        url: ruta,
        type: 'POST',
        data: formData,
        processData: false,
        cache: false,
        contentType: false,
        dataType: 'JSON',
        beforeSend: function () {
            $(".cargando").removeClass("hidden");
        }
    })
}



    