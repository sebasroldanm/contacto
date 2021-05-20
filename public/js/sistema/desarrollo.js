$(function() {
    cargaCorreo();
    $("#subirconfiguracion").click(function() {
        var datos = $("#formcorreo").serialize();
        var res = crud(datos, 'desarrollador/gestionCorreo', 'JSON');
        res.success(function(data) {
            Tabla(data);
        })
    })



    $("#testcorreo").click(function() {
        $(".enviocorreo").toggleClass('hidden');
    });

    $("#enviarprueba").click(function() {
        var obj = $(".pruebacorreo");
        var res = crud(obj, 'desarrollador/pruebaCorreo', 'JSON');
        res.success(function(data) {

        })
    })
})

function gestion(id) {
    var obj = {};
    obj.id = id;
    var res = crud(obj, 'desarrollador/cargaCorreo', 'JSON');
    res.success(function(data) {
        cargaCampos(data, 'correos');
    })
}

function Tabla(data) {
    var html = '';
    html += "<tr>";
    html += "<td><a href='#' onclick=gestion(" + data.id + ")>" + data.protocolo + "</td>";
    html += "<td><a href='#' onclick=gestion(" + data.id + ")>" + data.puerto + "</td>";
    html += "<td><a href='#' onclick=gestion(" + data.id + ")>" + data.host + "</td>";
    html += "<td><a href='#' onclick=gestion(" + data.id + ")>" + data.usuario + "</td>";
    html += "<td><a href='#' onclick=gestion(" + data.id + ")>" + data.clave + "</td>";
    html += "<tr>";
    $("#tablacorreo tbody").html(html);
}


function cargaCorreo() {
    var res = crud(null, 'desarrollador/tablaCorreo', 'JSON'), html = '';
    $("#tablacorreo tbody").empty();
    res.success(function(data) {
        Tabla(data);
    })
}