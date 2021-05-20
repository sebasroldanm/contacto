jQuery.fn.cargarCampos = function (data, estado) {
    estado = (estado == undefined || estado == true) ? true : false;
    this.each(function () {
        var elem = $(this);
        $.each(data, function (i, val) {

            if (elem.attr("name") == i) {
                if (elem.attr('type') == 'checkbox') {
                    (val == 1) ? elem.prop('checked', true) : elem.prop('checked', false);
                } else if (elem.get(0).tagName == 'IMG') {
                    elem.attr("src", val);
                } else if (elem.attr("type") == 'file') {
                    elem.attr("disabled", true);
                } else {
                    elem.val(val);
                }
            }
            elem.removeClass("obligatoriobuscador");
            elem.removeClass("obligatorio");
            elem.removeClass("error2");
            elem.removeClass("error");
        })
        elem.attr("disabled", estado);
    })

}

jQuery.fn.fechaActual = function (min) {

    this.each(function () {
        min = min || false;
        var elem = $(this), d = new Date(), fecha = '', mes = d.getMonth() + 1, minutos = 0;
        minutos = (d.getMinutes() <= 9) ? '0' + d.getMinutes() : d.getMinutes();
        mes = (mes <= 9) ? '0' + mes : mes;

        fecha = d.getFullYear() + "-" + mes + "-" + d.getDate();
        fecha += (min == false) ? " " + d.getHours() + ':' + minutos : '';
        elem.val(fecha);
    })
    return this;
}

jQuery.fn.cargarSelect = function (data) {
    var html = '';
    this.each(function () {
        var elem = $(this);
        html = "<option value='0'>Seleccione</option>";
        $.each(data, function (i, val) {
            html += "<option value='" + val.valor + "'>" + val.texto + "</option>";
        })
        elem.html(html);
    });
}

jQuery.fn.limpiarCampos = function (estado) {
    estado = estado || false;
    var id;
    this.each(function () {
        var elem = $(this);
        $(elem).attr("disabled", false);


        if (elem.get(0).tagName == 'SELECT') {
            if (elem.attr("obligatorio")) {
                elem.addClass("obligatorio");
            }

            elem.val("0");
            elem.removeClass("ok").removeClass("error");
        } else if (elem.get(0).tagName == 'IMG') {
            elem.attr("src", '');
            elem.attr("alt", 'foto');
        } else if (elem.attr("type") == 'checkbox') {
            elem.attr("checked", false);
        } else {
            if (elem.hasClass("select2-offscreen")) {

                if (elem.attr("obligatorio")) {
                    elem.addClass("obligatoriobuscador");
                }

                id = elem.attr("id");
                $("#" + id).select2('data', {id: 0, text: 'Seleccione una Opcion...'});
                elem.removeClass("error2");
            } else if (elem.hasClass("fechahora")) {
                elem.fechaActual();
            } else {
                if (elem.attr("obligatorio")) {
                    elem.addClass("obligatorio");
                }
                elem.val("");
                elem.removeClass("ok").removeClass("error");
            }

        }
        elem.attr("disabled", estado);
    });
    return this;
}

$.filterMSG = function () {
    console.log("asd");
    var specialChars = "!@#$^&%*()+=-[]\/{}|:<>?,.";
    var cadena = "";
    this.each(function () {
        var elem = $(this);
        for (var i = 0; i < specialChars.length; i++) {
            cadena = (elem.val()).replace(new RegExp("\\" + specialChars[i], 'gi'), '');
        }

        cadena = cadena.replace(/á/gi, "a");
        cadena = cadena.replace(/é/gi, "e");
        cadena = cadena.replace(/í/gi, "i");
        cadena = cadena.replace(/ó/gi, "o");
        cadena = cadena.replace(/ú/gi, "u");
        cadena = cadena.replace(/ñ/gi, "n");

        console.log(cadena)
        elem.val(cadena);
    })
}


jQuery.fn.valida = function (debug) {
    debug = debug || '';
    var cont = 0;
    this.each(function () {
        var elem = $(this);
        if (elem.attr("obligatorio") == 'alfanumerico') {
            if (elem.val() == '') {
                ++cont;
                elem.removeClass("obligatorio");
                elem.addClass("error");
                if (debug == 'debug') {
                    log(elem.attr("name"));
                }

            } else {
                elem.removeClass("error");
            }

        } else if (elem.attr("obligatorio") == 'numero') {

            if (elem.val() == '' || elem.val() == 0) {

                if (elem.hasClass("select2-offscreen")) {
                    elem.removeClass("obligatoriobuscador");
                    elem.addClass("error2");
                } else {
                    elem.removeClass("obligatorio");
                    elem.addClass("error");
                }
                ++cont;
                if (debug == 'debug') {
                    log(elem.attr("name"));
                }
            } else {
                if (elem.val() != null) {
                    if (!elem.val().match(/^[0-9]+$/)) {
                        if (debug == 'debug') {
                            log(elem.attr("name"));
                        }

                        ++cont;
                        elem.removeClass("obligatorio");
                        elem.addClass("error");
                    } else {
                        elem.removeClass("error");
                        if (elem.hasClass("error2")) {
                            elem.removeClass("error2");
                        } else {
                            elem.removeClass("error");
                        }
                    }
                } else {
                    ++cont;
                    elem.addClass("error");
                    if (debug == 'debug') {
                        log(elem.attr("name"));
                    }

                }

            }
        } else if (elem.attr("obligatorio") == 'alfa') {
            if (elem.val() == '') {
                ++cont;
                elem.addClass("error");
                if (debug == 'debug') {
                    log(elem.attr("name"));
                }
            } else {
                elem.removeClass("error");
            }
        } else if (elem.attr("tipodato") == 'numero') {

            if (elem.val() != null && elem.val() != '') {
                if (!elem.val().match(/^[0-9]+$/)) {
                    if (debug == 'debug') {
                        log(elem.attr("name"));
                    }

                    ++cont;
                    elem.removeClass("obligatorio");
                    elem.addClass("error");
                } else {
                    elem.removeClass("error");
                    if (elem.hasClass("error2")) {
                        elem.removeClass("error2");
                    } else {
                        elem.removeClass("error");
                    }
                }
            } else {
                elem.removeClass("error");
            }
        }

    })
    return cont;
}



jQuery.fn.datosTabla = function (datos) {

    var opciones = {}, filas = '', tabla = $(this).attr("id"), evento = '', arreglo = [];
    var cont = 0, ocultar = [], footer = '';
    opciones.method = (datos.method != "undefined") ? 'POST' : datos.method;
    $(tabla + " tbody").empty();
    arreglo = datos.filas[0].columnas;
    ocultar = datos.filas[1].columna;
    $.ajax({
        url: datos.ajax,
        dataType: datos.type,
        data: datos.parametros,
        type: opciones.method,
        success: function (data) {

            $.each(data["data"], function (i, val) {
                filas += "<tr>";
                $.each(val, function (j, valor) {
                    if ($.inArray(parseInt(j), ocultar) != 0) {
                        if ($.inArray(parseInt(j), arreglo) >= 0) {
                            filas += "<td>" + datos.filas[0].evento(valor, val) + "</td>";
                        } else {
                            filas += '<td>' + valor + "</td>";
                        }
                    }
                    cont++;
                })


                filas += "</tr>";
                $("#" + tabla + " tbody").html(filas);
            })
            log(data);
            footer = '<div class="row">'
            footer += '<div class="col-lg-5">Mostrando ' + data["result"].mostrar + ' de ' + data["result"].cantidad + ' Registros</div>';
            footer += '<div class="col-lg-4">'
            footer += '<ul class="pagination pagination-xs">'
            footer += '<li><a href="#">&laquo;</a></li>'
            for (var i = 1; i < 5; i++) {
                footer += '<li><a href="lineas/obtener_tabla/' + i + '" >' + i + '</a></li>'
            }
            footer += '<li><a href="#">&raquo;</a></li>'
            footer += '</ul></div>';
            footer += '<div class="col-lg-4"></div>';
            footer += '</div>';
            $("#" + tabla).after(footer);
        }
    })
}


$.capital = function (str) {
    return str.replace(/^(.)|\s(.)/g, function ($1) {
        return $1.toUpperCase();
    });
}

