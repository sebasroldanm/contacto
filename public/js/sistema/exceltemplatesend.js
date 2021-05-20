var valida, path = "", intervalo, objexcel = {};
$(function () {
    var smslong = 0, cupo = 0, concatena = 0, next = 0;
    $("#message").cuentaPalabras("#contentMessage");
    $("#btnClean").click(function () {
        $("#destination,#message,#note").val("");
        $("#contentMessage").html("");
        $("#txtcodigo").html("");
        $(".progress-bar").css("width", "0%");
        $("#txtprocess").html("0%");
        $(".informacioncarga").addClass("hidden");
        $("#txtquantity").html("Contactos filtados: 0");

        $(".filter-1,.filter-2,.filter-3,.filter-4,filter-5,.filter-6").prop("checked", false);

        getFilter();

    })



    $("#btnConsolidate").click(function () {
        var msg = '';

        $("#btnSendMsg").attr("disabled", false);

        if ($("#message").val() != '') {
            $.ajax({
                url: 'exceltemplatesend/getCupo',
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


            msg = $("#message").val();

            processData();


        } else {
            $("#message,#note").addClass("error");
            toastr.error("El Mensaje");
        }

    })

    $("#cancelarcarga").click(function () {
        var obj = {};
        obj.idbase = objexcel.idbase;
        obj.tipo = "cancelado";
        $("#nuevo").attr("disabled", false);
        var res = crud(obj, 'exceltemplatesend/procesarCarga');
        res.success(function (data) {
            $("#confirmacioncarga").attr("disabled", true);
            mensaje("alertaconfimacion", 'error', "<b>" + data.mensaje + "!</b>");
        })
    })

    $("#confirmacioncarga").click(function () {

        var obj = {};
        obj.idbase = objexcel.idbase;
        obj.tipo = 'confirmado';
        $("#nuevo").attr("disabled", false);

        var res = crud(obj, 'exceltemplatesend/procesarCarga');
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


    $("#client_id").change(function () {
        getFilter();

    })
});

function getFilter() {
    var form = {}, html = '', checked = '';
    form.client_id = $("#client_id").val();

    $.ajax({
        url: 'exceltemplatesend/getFilter',
        type: "POST",
        async: true,
        data: form,
        dataType: 'JSON',
        success: function (data) {
            $("#txtquantity").html("Contactos filtados: " + data.quantity);
            var html = '';



            if (data.filter.filter1 != undefined) {
                html = '';
                $("#list-filter-1").empty();
                $.each(data.filter.filter1, function (i, j) {
                    html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-1" onclick=obj.countData("filter1",' + form.client_id + ') value="' + j.filtro1 + '" name="filter1[]">' + j.filtro1 + '</li>';
                    checked = '';
                })
                $("#list-filter-1").html(html);
            } else {
                $("#list-filter-1").html("");
            }

            if (data.filter.filter2 != undefined) {
                html = '';
                $("#list-filter-2").empty();
                $.each(data.filter.filter2, function (i, j) {
                    html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-2" onclick=obj.countData("filter2",' + form.client_id + ') value="' + j.filtro2 + '" name="filter2[]">' + j.filtro2 + '</li>';
                    checked = '';
                })
                $("#list-filter-2").html(html);
            } else {
                $("#list-filter-2").html("");
            }



            if (data.filter.filter3 != undefined) {
                html = '';
                $("#list-filter-3").empty();
                $.each(data.filter.filter3, function (i, j) {
                    html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-3" onclick=obj.countData("filter3",' + form.client_id + ') value="' + j.filtro3 + '" name="filter3[]">' + j.filtro3 + '</li>';
                    checked = '';
                })
                $("#list-filter-3").html(html);
            } else {
                $("#list-filter-3").html("");
            }


            if (data.filter.filter4 != undefined) {
                html = '';
                $("#list-filter-4").empty();
                $.each(data.filter.filter4, function (i, j) {

                    html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-4" onclick=obj.countData("filter4",' + form.client_id + ') value="' + j.filtro4 + '" name="filter4[]">' + j.filtro4 + '</li>';
                    checked = '';
                })
                $("#list-filter-4").html(html);
            } else {
                $("#list-filter-4").html("");
            }


            if (data.filter.filter5 != undefined) {
                html = '';
                $("#list-filter-5").empty();
                $.each(data.filter.filter5, function (i, j) {
                    html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-5" onclick=obj.countData("filter5",' + form.client_id + ') value="' + j.filtro5 + '" name="filter5[]">' + j.filtro5 + '</li>';
                    checked = '';
                })
                $("#list-filter-5").html(html);
            } else {
                $("#list-filter-5").html("");
            }



            if (data.filter.filter6 != undefined) {
                html = '';
                $("#list-filter-6").empty();
                $.each(data.filter.filter6, function (i, j) {
                    html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-6" onclick=obj.countData("filter6",' + form.client_id + ') value="' + j.filtro6 + '" name="filter6[]">' + j.filtro6 + '</li>';
                })
                $("#list-filter-6").html(html);
            } else {
                $("#list-filter-6").html("");
            }
        }
    });
}

function verBase(id) {
    var obj = {}, txt = '';
    obj.idbase = id;
    $(".modalbase").modal("show");
    var res = crud(obj, 'cargaexcel/verBase');
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


function processData() {
    var form = {};
    var form = obj.getDataFilter();
    form.client_id = $("#client_id").val();
    form.message = $("#message").val();

    $.ajax({
        url: 'exceltemplatesend/cargaExcel',
        type: "POST",
        async: true,
        data: form,
        dataType: 'JSON',
        success: function (data) {
            objexcel = data;
            var envioefectivo = 0, clase = '';
            $("#fecha").attr("disabled", true);
            clase = (data.duplicados == 0) ? '' : 'error';
            $(".cargando").addClass("hidden");

            $(".informacioncarga").removeClass("hidden");
            $("#codigorevision").html(data.idbase);
            $("#regbuenos").html('<strong><a href="#" onclick=verBase(' + data.idbase + ')>Vista Previa ' + data.ok + ' SMS</a></string>');
//                $("#regerrores").html(data.errores + " SMS");
            $("#regerrores").html('<strong><a href="#" onclick=verErrores(' + data.idbase + ')>Vista Errores ' + data.errores + ' SMS</a><br><br><a href="#" onclick=verBlacklist(' + data.idbase + ')>Vista Blacklist ' + data.blacklist + ' SMS</a></string>');
            $("#regdobles").html("<span class='" + clase + "'>" + data.duplicados + " SMS </span>");

            if (data.cupo != "undefined") {
                $(".errorcupos").removeClass("hidden");
                envioefectivo = (parseInt(data.cupo.disponible) >= parseInt(data.ok)) ? data.ok : 0;
                $("#regcupo").html('<b>' + envioefectivo + '</b>');
                $("#cupo").html('<b>' + (data.cupo.disponible) + '</b>');
            }

            if (data.errores > 0 || data.blacklist > 0) {
                $("#descargaExcel").removeClass("hidden");
            } else {
                $("#descargaExcel").addClass("hidden");
            }
        }, error: function (xhr, ajaxOptions, thrownError) {

            alert("Se presento un problema, comunicarse con sistemas");
        }
    });
}




function exceltempleate() {
    this.init = function () {

    }

    this.getDataFilter = function () {
        var form = {};

        var filter1 = [], filter2 = [], filter3 = [], filter4 = [], filter5 = [], filter6 = [];

        $(".filter-1").each(function () {
            if ($(this).is(":checked")) {
                filter1.push($(this).val());
            }
        })
        $(".filter-2").each(function () {
            if ($(this).is(":checked")) {
                filter2.push($(this).val());
            }
        })
        $(".filter-3").each(function () {
            if ($(this).is(":checked")) {
                filter3.push($(this).val());
            }
        })
        $(".filter-4").each(function () {
            if ($(this).is(":checked")) {
                filter4.push($(this).val());
            }
        })
        $(".filter-5").each(function () {
            if ($(this).is(":checked")) {
                filter5.push($(this).val());
            }
        })
        $(".filter-6").each(function () {
            if ($(this).is(":checked")) {
                filter6.push($(this).val());
            }
        })

        form.filter1 = filter1;
        form.filter2 = filter2;
        form.filter3 = filter3;
        form.filter4 = filter4;
        form.filter5 = filter5;
        form.filter6 = filter6;
        return form;
    }

    this.countData = function (type, client_id) {
        var form = {}, html = '', checked = '';
        form = obj.getDataFilter();
        form.type = type;
        form.client_id = client_id;
        form.message = $("#frmtempĺateSend #message").val();

        $.ajax({
            url: $("#ruta").val() + 'exceltemplatesend/countFilter',
            type: "POST",
            data: form,
            dataType: 'JSON',
            success: function (data) {
                $("#txtquantity").html("Contactos filtados: " + data.quantity);

                $.each(data.messages, function (i, val) {
                    html += "<tr><td>" + val.phone + "</td><td>" + val.message + "</td></tr>"
                })

                $("#preMessage tbody").html(html);

                if (data.mark.type != "filter1") {
                    if (data.filter.filter1 != undefined) {
                        html = '';
                        $("#list-filter-1").empty();
                        $.each(data.filter.filter1, function (i, j) {
                            if (data.mark.filter1 != undefined) {
                                $.each(data.mark.filter1, function (a, val) {
                                    if (val == j.filtro1) {
                                        checked = 'checked'
                                    }
                                });
                            }
                            html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-1" onclick=obj.countData("filter1",' + client_id + ') value="' + j.filtro1 + '" name="filter1[]">' + j.filtro1 + '</li>';
                            checked = '';
                        })
                        $("#list-filter-1").html(html);
                    }
                }

                if (data.mark.type != "filter2") {
                    if (data.filter.filter2 != undefined) {
                        html = '';
                        $("#list-filter-2").empty();
                        $.each(data.filter.filter2, function (i, j) {
                            if (data.mark.filter2 != undefined) {

                                $.each(data.mark.filter2, function (a, val) {
                                    if (val == j.filtro2) {
                                        checked = 'checked'
                                    }
                                });
                            }
                            html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-2" onclick=obj.countData("filter2",' + client_id + ') value="' + j.filtro2 + '" name="filter2[]">' + j.filtro2 + '</li>';
                            checked = '';
                        })
                        $("#list-filter-2").html(html);
                    }
                }

                if (data.mark.type != "filter3") {
                    if (data.filter.filter3 != undefined) {
                        html = '';
                        $("#list-filter-3").empty();
                        $.each(data.filter.filter3, function (i, j) {

                            if (data.mark.filter3 != undefined) {

                                $.each(data.mark.filter3, function (a, val) {
                                    if (val == j.filtro3) {
                                        checked = 'checked'
                                    }
                                });
                            }

                            html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-3" onclick=obj.countData("filter3",' + client_id + ') value="' + j.filtro3 + '" name="filter3[]">' + j.filtro3 + '</li>';
                            checked = '';
                        })
                        $("#list-filter-3").html(html);
                    }
                }
                if (data.mark.type != "filter4") {
                    if (data.filter.filter4 != undefined) {
                        html = '';
                        $("#list-filter-4").empty();
                        $.each(data.filter.filter4, function (i, j) {

                            if (data.mark.filter4 != undefined) {

                                $.each(data.mark.filter4, function (a, val) {
                                    if (val == j.filtro4) {
                                        checked = 'checked'
                                    }
                                });
                            }

                            html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-4" onclick=obj.countData("filter4",' + client_id + ') value="' + j.filtro4 + '" name="filter4[]">' + j.filtro4 + '</li>';
                            checked = '';
                        })
                        $("#list-filter-4").html(html);
                    }
                }

                if (data.mark.type != "filter5") {
                    if (data.filter.filter5 != undefined) {
                        html = '';
                        $("#list-filter-5").empty();
                        $.each(data.filter.filter5, function (i, j) {

                            if (data.mark.filter5 != undefined) {

                                $.each(data.mark.filter5, function (a, val) {
                                    if (val == j.filtro5) {
                                        checked = 'checked'
                                    }
                                });
                            }

                            html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-5" onclick=obj.countData("filter5",' + client_id + ') value="' + j.filtro5 + '" name="filter5[]">' + j.filtro5 + '</li>';
                            checked = '';
                        })
                        $("#list-filter-5").html(html);
                    }
                }

                if (data.mark.type != "filter6") {
                    if (data.filter.filter6 != undefined) {
                        html = '';
                        $("#list-filter-6").empty();
                        $.each(data.filter.filter6, function (i, j) {

                            if (data.mark.filter6 != undefined) {

                                $.each(data.mark.filter6, function (a, val) {
                                    if (val == j.filtro6) {
                                        checked = 'checked'
                                    }
                                });
                            }

                            html += '<li class="list-group-item"><input type="checkbox" ' + checked + ' class="filter-6" onclick=obj.countData("filter6",' + client_id + ') value="' + j.filtro6 + '" name="filter6[]">' + j.filtro6 + '</li>';
                            checked = '';
                        })
                        $("#list-filter-6").html(html);
                    }
                }
            }
        });
    }
}

var obj = new exceltempleate();
obj.init();