
$(function () {
    var ruta = $("#ruta").val();
    $('.fechas').datetimepicker({
        lang: 'es',
        format: 'Y-m-d',
        timepicker: false
    });
})

function crud(data, controlador, opc) {

    var tipo = '', async = '';
    if (opc != undefined) {
        tipo = (opc.tipo == undefined) ? 'JSON' : opc.tipo;
        async = (opc.async == undefined) ? false : opc.async;
    } else {
        tipo = 'JSON';
        tipo = false;

    }

//    var tipo = (opc.tipo != undefined) ? opc.tipo : 'JSON';


    return $.ajax({
        url: controlador,
        type: 'POST',
        data: data,
        dataType: 'JSON',
        //async: async
    });
}

function log(string) {
    console.log(string);
}


function CrearString(tam) {
    var string = '';
    var arreglo = new Array();
    for (var i = 0; i < tam; i++) {
//        string += (string == '') ? '' : ',';
//        string += i;
        arreglo[i] = i;
    }

//    return string;
    return arreglo;
}

var tabla;
function cargaTabla(config, debug) {

    var info;

    config.col = config.col || 1;
    info = config.url.split("/");
    config.idtabla = (config.idtabla != undefined) ? config.idtabla : info[1];
    config.destruir = (config.destruir != undefined) ? config.destruir : true;
    config.data = (config.data != undefined) ? config.data : null;
    config.metodo = (config.metodo != undefined) ? config.metodo : 'CargarTabla';
    config.funcion = (config.funcion != undefined) ? config.funcion : 'gestion';

    if (config.filtro != undefined) {
        config.filtro = (config.filtro == true) ? 'l' : ''
    } else {
        config.filtro = 'l';
    }

    if (config.exportar != undefined) {
        config.exportar = (config.exportar == true) ? 'T' : ''
    } else {
        config.exportar = 'T';
    }

    if (config.buscar != undefined) {
        config.buscar = (config.buscar == true) ? 'f' : ''
    } else {
        config.buscar = 'f';
    }

    if (config.header != undefined) {

        if (config.header == true) {
            config.filtro = 'l';
            config.exportar = 'T';
            config.buscar = 'f';
        } else {
            config.filtro = '';
            config.exportar = '';
            config.buscar = '';
        }
    }

    var abutton = [], opc = {};

    if (config.xls != undefined) {
        if (config.xls == true) {

            if (config.xlsdescarga != undefined) {
                abutton.push({
                    "sExtends": "select",
                    "sButtonText": "<img src='" + ruta + "librerias/librerias-jquery/DataTables-1.10.0/media/images/excel.png'>",
                    "fnClick": function (nButton, oConfig, oFlash) {
                        window.open('informes/filtros/descarga', '_blank');
                    }
                });
            } else {
                abutton.push({
                    "sExtends": "xls",
                    "sButtonText": "<img src='" + ruta + "librerias/librerias-jquery/DataTables-1.10.4/media/images/xls.png'>"
                });
            }
        }
    }

    if (config.graphic != undefined) {
        if (config.graphic == true) {
            abutton.push({
                "sExtends": "select",
                "sButtonText": "<img src='" + ruta + "librerias/librerias-jquery/DataTables-1.10.4/media/images/excel.png'>",
                "fnClick": function (nButton, oConfig, oFlash) {
                    var res = crud(config.data, 'informes/filtros');

                }
            });
        }
    }

    if (config.pdf != undefined) {
        if (config.pdf == true) {
            abutton.push({
                "sExtends": "pdf",
                "sButtonText": "<img src='" + ruta + "librerias/librerias-jquery/DataTables-1.10.4/media/images/pdf.png'>"
            });
        }
    }

    if (debug != undefined) {
        if (debug == true) {
            log(config);
        }
    }

    var arreglo = CrearString(config.col);
    var ajax = {};
    ajax.url = './' + config.url + "/" + config.metodo;
    ajax.type = "POST";


    if (config.hidden == undefined) {
        config.hidden = {};
        config.hidden.targets = [0];
        config.hidden.visible = false;
        config.hidden.visible = false;
    }

    if (config.data != null) {
        ajax.data = config.data;
    }

    // Setup - add a text input to each footer cell
    $('#' + config.idtabla + ' tfoot th').each(function () {
        var title = $('#' + config.idtabla + ' thead th').eq($(this).index()).text();
        $(this).html('<input type="text" class="text-filter"  placeholder="' + $.capital(title.toLowerCase()) + '" />');
    });

    tabla = $("#" + config.idtabla).DataTable({
        "dom":
                "R<'row'<'col-xs-3 col-lg-4 '" + config.filtro + "><'col-xs-3 col-lg-4'" + config.exportar + "><'col-xs-6 col-lg-4'" + config.buscar + ">r>" +
                "<'row'<'col-xs-12 col-sm-12 col-md-12 col-lg-12't>>" +
                "<'row'<'col-xs-3 col-sm-3 col-md-3 col-lg-3'i><'col-xs-6 col-sm-6 col-md-6 col-lg-6 text-center'p><'col-xs-3 col-sm-3 col-md-3 col-lg-3'>>",
        processing: true,
        serverSide: true,
        ajax: ajax,
        "order": [[1, 'asc']],
        "oLanguage": {
            "sInfo": "Mostrando _START_ de _END_ de _TOTAL_ Entradas",
            "sInfoEmpty": "Mostrando 0 de 0 de 0 Entradas",
            "sLoadingRecords": "Cargando...",
            "sProcessing": "Procesando...",
            "sSearch": "Buscar:",
            "sLengthMenu": "Mostrar _MENU_ Entradas",
            "sZeroRecords": "No hay registros encontrados",
            oPaginate: {
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }

        },
        "sInfo": "Mostrando _START_ to _END_ de _TOTAL_ Registros",
        "destroy": config.destruir,
        "tableTools": {
            "sSwfPath": ruta + "librerias/librerias-jquery/DataTables-1.10.0/extensions/TableTools/swf/copy_csv_xls_pdf.swf",
            "aButtons": abutton

        },
        "columnDefs": [
            {
                "targets": arreglo,
                "render": function (data, type, full) {
                    return '<a href="#" onclick="' + config.funcion + '(' + full[0] + ')">' + data + '</a>';
                }
            }, config.hidden
        ]
    });
    // Apply the filter
//    tabla.columns().eq(0).each(function (colIdx) {
//        $('input', tabla.column(colIdx).footer()).on('keyup change', function () {
//            tabla
//                    .column(colIdx)
//                    .search(this.value)
//                    .draw();
//        });
//    });

    return tabla;
}


function recargar(tabla) {
//    tabla.ajax.reload();
    tabla.ajax.reload();
//    tabla.fnDraw();
}





var table;
function crearTabla(id, columnas, metodo) {
    var arreglo = CrearString(columnas);
    metodo = metodo || 'gestion';
    table = $("#tabla" + id).DataTable({
        "sAjaxSource": id + "/cargaTabla",
        "destroy": true,
        "language": {
            "url": '//cdn.datatables.net/plug-ins/be7019ee387/i18n/Spanish.json'
        },
        "aoColumnDefs": [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            },
            {
                "aTargets": arreglo,
                "mRender": function (data, type, full) {
                    return '<a href="#" onclick="' + metodo + '(' + full[0] + ')">' + data + '</a>';
                }
            }
        ]
    });
}

function recargar() {
    table.ajax.reload();
}

function formato_numero(numero, decimales, separador_decimal, separador_miles) { // v2007-08-06
    numero = parseFloat(numero);
    if (isNaN(numero)) {
        return "";
    }

    if (decimales !== undefined) {
        // Redondeamos
        numero = numero.toFixed(decimales);
    }

    // Convertimos el punto en separador_decimal
    numero = numero.toString().replace(".", separador_decimal !== undefined ? separador_decimal : ",");

    if (separador_miles) {
        // Añadimos los separadores de miles
        var miles = new RegExp("(-?[0-9]+)([0-9]{3})");
        while (miles.test(numero)) {
            numero = numero.replace(miles, "$1" + separador_miles + "$2");
        }
    }

    return numero;
}


function mensaje(clase, activo, mensaje) {
    clase = (clase == null) ? 'alertamensaje' : clase;
    activo = activo || true;
    if (activo == true) {
        mensaje = mensaje || '<b><h4>Operacion realizada</h4></b>';
        $("." + clase).removeClass("hidden").removeClass("alert-danger").removeClass("alert-warning").addClass("alert-success").html('<b><h4>' + mensaje + '</h4></b>');
    } else if (activo == 'hidden') {
        $("." + clase).addClass("hidden");
    } else if (activo == 'error') {
        mensaje = mensaje || '<b><h4>Problemas con la ejecucion realizada</h4></b>';
        $("." + clase).removeClass("hidden")
                .removeClass("alert-success")
                .removeClass("alert-warning")
                .addClass("alert-danger")
                .html('<b><h4>' + mensaje + '</h4></b>');
    } else if (activo == 'war') {
        mensaje = mensaje || '<b><h4>Problemas con la operacion realizada</h4></b>';
        $("." + clase)
                .removeClass("hidden")
                .removeClass("alert-success")
                .removeClass("alert-danger")
                .addClass("alert-warning")
                .html('<b><h4>' + mensaje + '</h4></b>');
    }

}