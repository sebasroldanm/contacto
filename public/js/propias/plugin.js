jQuery.fn.cuentaPalabras = function (id) {
    var cont = 0;
    var espacios;
    var palabras = '';
    var sms = 1;
    this.each(function () {
        var elem = $(this);
        elem.keyup(function (e) {

            espacios = elem.val().split(" ");
            palabras = espacios.length;
            sms = Math.ceil(parseInt(elem.val().length) / 160);
            $(id).html(elem.val().length + " de 160 caracteres <br>" + palabras + " palabras | " + sms + " parte(s)");
        })

    });
}


jQuery.fn.validaTextarea = function () {
    var sms = [];
    sms.numeros = [];
    sms.errados = [];
    var accepted = ["300", "301", "302", "303", "304", "305", "310", "311", "312", "313", "314", "320", "321", "322", "323",
        "315", "316", "317", "318", "319", "350", "351", "352"];
    this.each(function () {

        var elem = $(this);
        var cont = 0;
        var ok = 0;
        var num = '';
        var numeros = elem.val();
        numeros = numeros.split(/\n/g);
        if (numeros.length > 0 && numeros != '') {
            for (var i = 0; i < numeros.length; i++) {
                if (numeros[i] != '') {
                    if (isNaN(numeros[i])) {
                        sms.errados.push({number: numeros[i], reason: "number contain letter"});
                    } else if (numeros[i].length != 10) {
                        sms.errados.push({number: numeros[i], reason: "size number"});
                    } else if ($.inArray((numeros[i]).substring(0, 3), accepted) == -1) {
                        sms.errados.push({number: numeros[i], reason: "Number dont exist"});
                    } else {
                        sms.numeros.push(numeros[i]);
                    }
                }
            }
        } else {
            sms.errados.push({number: "n/a", reason: "Destinity empty"});
        }

    });
    return sms;
}

jQuery.fn.createSend = function (msg) {
    var sms = [];
    sms.numeros = [];
    sms.errados = [];
    var accepted = ["300", "301", "302", "303", "304", "305", "310", "311", "312", "313", "314", "320", "321", "322", "323",
        "315", "316", "317", "318", "319", "350", "351", "352"];
    this.each(function () {
        var elem = $(this);
        var cont = 0;
        var ok = 0;
        var num = '';
        var numeros = elem.val();
        numeros = numeros.split(/\n/g);
        if (numeros.length > 0 && numeros != '') {
            for (var i = 0; i < numeros.length; i++) {
                if (numeros[i] != '') {
                    if (isNaN(numeros[i])) {
                        sms.errados.push({number: numeros[i], reason: "number contain letter"});
                    } else if (numeros[i].length != 10) {
                        sms.errados.push({number: numeros[i], reason: "size number"});
                    } else if ($.inArray((numeros[i]).substring(0, 3), accepted) == -1) {
                        sms.errados.push({number: numeros[i], reason: "Number dont exist"});
                    } else {

                        var tam = Math.ceil((msg.length) / 160);
                        var anterior = 0, largo = 0, j = 0;
                        for (j = 1; j <= tam; ++j) {
                            largo = j * 160;
                            sms.numeros.push({number: numeros[i], message: msg.substr(anterior, 160), typeLoad: 'web', order: j});
                            anterior = largo;
                        }
                    }
                }
            }
        } else {
            sms.errados.push({number: "n/a", reason: "Destinity empty"});
        }

    });

    return sms;
}

jQuery.fn.filter = function () {
    var specialChars = "!@#$^&%*()+=-[]\/{}|:<>?,.";
    var cadena = "";
    this.each(function () {
        var elem = $(this);
        for (var i = 0; i < specialChars.length; i++) {
            cadena = (elem.val()).replace(new RegExp("\\" + specialChars[i], 'gi'), '');
            cadena = cadena.replace('&nbsp;', '');
        }
        cadena = cadena.replace(/á/gi, "a");
        cadena = cadena.replace(/á/gi, "a");
        cadena = cadena.replace(/é/gi, "e");
        cadena = cadena.replace(/í/gi, "i");
        cadena = cadena.replace(/ó/gi, "o");
        cadena = cadena.replace(/ú/gi, "u");
        cadena = cadena.replace(/ñ/gi, "n");
        elem.val(cadena);
    })
}

