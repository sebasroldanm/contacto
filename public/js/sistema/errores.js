$(function () {
    $("#btnerrores").click(function () {
        var inicio = $("#inicio").val(), final = $("#final").val();
        var obj = {};
        if (inicio != '' && final != '') {
            obj.inicio = inicio;
            obj.final = final;
            obj.idbase = $("#idbase").val();

            var res = crud(obj, '../reportes/getErrores');

            res.success(function (data) {
                $("#tablaerrores tbody").empty();
                if (data != '') {
                    var cuerpo = "";
                    $.each(data, function (i, val) {
                        cuerpo += "<tr><td>" + val.idbase + "</td><td>" + val.numero + "</td><td>" + val.nota + "</td><td>" + val.error + "</td></tr>";
                    })
                    $("#tablaerrores tbody").append(cuerpo);
                } else {

                }
            })
        } else {
            alert("datos vacios");
        }
    })
})