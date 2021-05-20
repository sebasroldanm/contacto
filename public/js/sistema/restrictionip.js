function RestricionIp() {
    var table;
    this.init = function () {
        table = this.table();

        $("#btn_register").click(function () {
            var valido = $(".datosempresa").valida();
            mensaje("alertamensaje", 'hidden');

            let valida_ip = obj.validateIPaddress($("#ip").val());

            if (!valida_ip) {
                mensaje("alertamensaje", 'error');

                return false;
            }

            var datos = $("#formEmpresas").serialize();

            if (valido == 0) {
                var res = crud(datos, 'restrictionip/gestion');
                res.success(function (data) {
                    if (data.error) {
                        mensaje("alertamensaje", 'error', data.error);
                    } else {
                        table.ajax.reload();
                        $(".datosempresa").limpiarCampos()
                        mensaje("alertamensaje");

                    }
                })
            } else {
                mensaje("alertamensaje", 'error');
            }

        });
        $("#btn_new").click(function () {

            $(".datosempresa").limpiarCampos();
            mensaje("alertamensaje", 'hidden');
        });


    }


    this.validateIPaddress = function (ipaddress) {
        if (/^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/.test(ipaddress)) {
            return (true)
        }
        return (false)
    }

    this.show = function (id) {
        $.ajax({
            url: "restrictionip/show",
            method: 'POST',
            data:{id:id},
            dataType: 'JSON',
            success: function (data) {
                $(".datosempresa").cargarCampos(data, false);
            }
        })  
    }



    this.table = function () {
        return $('#tbl').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "restrictionip/cargatabla",
                method: "POST"
            },
            columns: [
                { data: "id", searchable: false, },
                { data: "nombre" },
                { data: "ip" }
            ],
            order: [[1, 'ASC']],
            aoColumnDefs: [
                {
                    aTargets: [0, 1, 2],
                    mRender: function (data, type, full) {
                        return '<a href="#" onclick="obj.show(' + full.id + ')">' + data + '</a>';
                    }
                },
                {
                    targets: [3],
                    searchable: false,
                    mData: null,
                    mRender: function (data, type, full) {
                        return '<button class="btn btn-danger btn-xs" onclick="obj.delete(' + data.id + ')"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>';
                    }
                }

            ],
        });
    }

    this.delete = function (id) {
        if (confirm("¿Estas seguro de eliminar este registro?")) {
            $.ajax({
                url: "restrictionip/delete/" + id,
                method: 'delete',
                dataType: 'JSON',
                success: function (data) {
                    if (data.success == true) {
                        mensaje("alertamensaje", 'hidden');
                        toastr.warning("Operacion realizada");
                        table.ajax.reload();
                    }
                }

            })
        }

    }
}

var obj = new RestricionIp();
obj.init();


// $(function () {
//     crearTabla("restrictionip", 3, 'gestion');
//     mensaje("alertamensaje", 'hidden');

//     $("#tabempresas").click(function () {
//         crearTabla("administrador", 7, 'gestionEmpresa');
//         mensaje("alertamensaje", 'hidden');
//         $(".datosempresa").limpiarCampos();
//     })


//     $("#btn_register").click(function () {
//         var valido = $(".datosempresa").valida();
//         mensaje("alertamensaje", 'hidden');

//         var datos = $("#formEmpresas").serialize();

//         if (valido == 0) {
//             var res = crud(datos, 'restrictionip/gestion');
//             res.success(function (data) {
//                 if (data.error) {
//                     mensaje("alertamensaje", 'error', data.error);
//                 } else {
//                     mensaje("alertamensaje");
//                     crearTabla("administrador", 7, 'gestionEmpresa');
//                 }
//             })
//         } else {
//             mensaje("alertamensaje", 'error');
//         }

//     });
//     $("#btn_new").click(function () {
//         console.log("asd");
//         $(".datosempresa").limpiarCampos();
//         mensaje("alertamensaje", 'hidden');
//     });

// })

// function gestionEmpresa(id) {
//     $("#formEmpresas #id").attr("disabled", false);
//     var obj = {};
//     obj.id = id;
//     var res = crud(obj, 'empresas/cargaDatos');
//     res.success(function (data) {
//         $(".datosempresa").cargarCampos(data, false);
//     })
// }

// function borrarEmpresa(id, clase) {
//     $("#" + id).empty();
//     var obj = {};
//     obj.id = id;
//     var res = crud(obj, 'empresas/borrar');
//     crearTabla("administrador", 7, 'gestionEmpresa');

//     mensaje("alertamensaje");
// }