$(function () {
    var obj = {};
    obj.url = "descargaarchivo";
    obj.col = 1;
    obj.idtabla = "tabladescarga";
    cargaTabla(obj);
})

function descargar(id) {
    var obj = {};
    obj.id = id;
    var ruta = '';
    var res = crud(obj, 'descargaarchivo/obtienelink');
    res.success(function (data) {
        ruta = data.ruta;
        window.open('descargaarchivo/descargar/' + ruta, '_blank');
    })
    
}
