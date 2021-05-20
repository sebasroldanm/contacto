$(function () {
    $("#btnreporte").click(function () {
        var inicio = $("#inicio").val(), final = $("#final").val();
        var obj = {};
        if (inicio != '' && final != '') {
            obj.inicio = inicio;
            obj.final = final;

            var res = crud(obj, 'reportes/datosFecha');

            res.success(function (data) {
                $("#tablafecha tbody").empty();
                if (data != '') {
                    var cuerpo = "";
                    $.each(data, function (i, val) {
                        cuerpo += "<tr><td>" + val.fecha + "</td><td>" + val.operador + "</td><td>" + val.cantidad + "</td></tr>";
                    })
                    $("#tablafecha tbody").append(cuerpo);
                } else {

                }
            })
        } else {
            alert("datos vacios");
        }

    })

    $("#btnenviocanal").click(function () {
        var datos = {}, html = '', link = '', total = 0, empresa = new Array(), cont = 0;
        $("#tablacanales tbody").empty();

        $(".listadoEmpresa").each(function () {
            if ($(this).is(":checked")) {
                empresa[cont] = $(this).val();
                cont++;
            }
        })

        datos.empresa = empresa;
        datos.inicio = $("#inicio").val();
        datos.final = $("#final").val();
        datos.final = $("#final").val();
        datos.idcarrier = $("#idcarrier").val();
        datos.idcanal = $("#idcanal").val();
        if (datos.inicio != '' && datos.final) {


            $.ajax({
                url: '../reportes/getEnvioCanales',
                type: "POST",
                dataType: 'JSON',
                data: datos,
                beforeSend: function () {
                    $("#tablacanales #cargando").removeClass("hidden");
                }, success: function (data) {
                    $("#tablacanales tbody").empty();
                    $("#cargando").addClass("hidden");

                    if (!$.isEmptyObject(data)) {
                        $.each(data, function (i, val) {
                            total += parseInt(val.envio);
                            html += '<tr><td>' + val.canal + '</td><td align="right">' + formato_numero(val.envio, 0, ",", ".") + '</td></tr>';
                        })
                        html += '<tr align="center"><td><b>TOTAL</b></td><td align="right"><b>' + formato_numero(total, 0, ",", ".") + '</b></td></tr>';
                    } else {
                        html = '<tr align="center"><td colspan="2"><b>No se encontraron datos con los filtros de busqueda</b></td></tr>';
                    }

                    $("#tablacanales tbody").html(html);
                }
            })
        }else{
            alert("Por favor ingresar una Fecha!");
        }
    });

    $("#seleccionar").click(function () {

        if ($(this).is(":checked")) {
            $(".listadoEmpresa").prop("checked", true);
        } else {
            $(".listadoEmpresa").prop("checked", false);
        }
    })

    $("#idestado").change(function () {
        var elem = $(this);
        if (elem.val() == '2') {
            $(".fechas").attr("disabled", true);
        } else {
            $(".fechas").attr("disabled", false);
        }
    })

    $("#btnestados").click(function () {
        var inicio = $("#inicio").val(), final = $("#final").val(), html = '', tipo = $("#idestado").val();
        var obj = {};

        obj.inicio = inicio;
        obj.final = final;
        obj.tipo = tipo;

        var res = crud(obj, '../reportes/getEstados');
        res.success(function (data) {
            if (!$.isEmptyObject(data)) {
                $.each(data, function (i, val) {
                    html += '<tr><td>' + val.numero + '</td><td>' + val.mensaje + '</td><td>' + val.fechaprogramado + '</td></tr>';
                })
            } else {
                html = '<tr align="center"><td colspan="2"><b>No se encontraron datos con los filtros de busqueda</b></td></tr>';
            }
            $("#tablaestado tbody").html(html);
        })


    })

    $("#btndisponibles").click(function () {
        var html = '', datos = $("#form-dispo").serialize();
        var res = crud(datos, '../reportes/getDisponibles');
        res.success(function (data) {
            if (!$.isEmptyObject(data)) {
                $.each(data, function (i, val) {
                    html += '<tr><td>' + val.usuario + '</td><td>' + val.plan + '</td><td align="right">' + formato_numero(val.cupototal, 0, ",", '.') + '</td>';
                    html += '<td align="right">' + formato_numero(val.consumido, 0, ",", '.') + '</td><td align="right">' + formato_numero(val.disponible, 0, ",", '.') + '</td></tr>';
                })
            } else {
                html = '<tr align="center"><td colspan="5"><b>No se encontraron datos con los filtros de busqueda</b></td></tr>';
            }

            $("#tablaestado tbody").html(html);
        })
    })
})