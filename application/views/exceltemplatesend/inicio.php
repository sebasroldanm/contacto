<script src="<?php echo base_url() ?>public/js/propias/plugin.js"></script>
<script src="<?php echo base_url() ?>librerias/jquery/toastr.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>librerias/css/toastr.css">


<div class="container-fluid">
    <form id="frmtempĺateSend" action="exceltemplatesend/sendMenssage" method="post">
        <div class="row">
            <div class="col-lg-3">
                <div class="row">
                    <div class="col-lg-3">
                        <button id="btnConsolidate" type="button" class="btn btn-success">Enviar</button>
                    </div>
                    <div class="col-lg-3">
                        <button id="btnClean" type="button" class="btn btn-warning">Limpiar</button>
                    </div>
                </div>
                <div class="espacio"></div>
                <div class="row">
                    <div class="col-lg-12">
                        Clientes
                    </div>
                </div>
                <div class="espacio10"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <select id="client_id" name="client_id" class="form-control">
                            <option value="0">Seleccione</option>
                            <?php
                            foreach ($client as $value) {
                                ?>
                                <option value="<?php echo $value["id"] ?>"><?php echo $value["nombre"] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="espacio10"></div>
                <div class="row">
                    <div class="col-lg-12">
                        Contenido Mensaje
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <textarea class="form-control" id="message" rows="3" name="message" placeholder="Escribe aqui el Mensaje" style="resize: none"></textarea>
                    </div>   
                </div>   
                <div class="row">
                    <div class="col-lg-12">
                        <span id="contentMessage"></span>
                    </div>
                </div>
                <div class="espacio"></div>
                <div class="row">
                    <div class="col-lg-12">
                        <span id="txtcodigo"></span>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <span id="txtquantity">Contactos filtados:0</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <table class="table table-bordered table-condensed" id="preMessage">
                                    <thead>
                                        <tr>
                                            <th>Numero</th>
                                            <th>Mensaje</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="row">

                            <div class="col-lg-12 col-md-12 col-center informacioncarga hidden">

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-lg-4"><h3 class="panel-title">Información Carga</h3></div>
                                            <div class="col-lg-6 col-right">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-3">
                                                        <b>Cupo:</b>
                                                    </div>
                                                    <div class="col-lg-4 col-md-4">
                                                        <span id="cupo"></span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="panel-body">

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12">
                                                <div class="alert alert-success alertaconfimacion hidden"></div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <button type="button" id="confirmacioncarga" class="btn btn-primary">Confirmar carga</button>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <button type="button" id="cancelarcarga" class="btn btn-danger">Cancelar carga</button>
                                            </div>
                                        </div>
                                        <div class="espacio"></div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <b>Codigo Revisión:</b>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="alert alert-info " id="codigorevision"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <b>Subidas con Exito:</b>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="alert alert-info " id="regbuenos"></div>
                                            </div>
                                        </div>
                                        <div class="espacio10"></div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <b>Subidas con Errores:</b>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="alert alert-danger " id="regerrores"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <b>Registro con SMS dobles:</b>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="alert alert-warning " id="regdobles"></div>
                                            </div>
                                        </div>

                                        <div class="row errorcupos">
                                            <div class="col-lg-6 col-md-6">
                                                <b>Envio efectivo:</b>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="alert alert-success " id="regcupo"></div>
                                                <span id="regcupo"></span>
                                            </div>
                                        </div>


                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-2">
                        Filtro 1
                    </div>
                    <div class="col-lg-2">
                        Filtro 2
                    </div>
                    <div class="col-lg-2">
                        Filtro 3
                    </div>
                    <div class="col-lg-2">
                        Filtro 4
                    </div>
                    <div class="col-lg-2">
                        Filtro 5
                    </div>
                    <div class="col-lg-2">
                        Filtro 6
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2">
                        <ul class="list-group" id="list-filter-1">
                        </ul>
                    </div>
                    <div class="col-lg-2">
                        <ul class="list-group" id="list-filter-2">
                        </ul>
                    </div>
                    <div class="col-lg-2">
                        <ul class="list-group" id="list-filter-3">
                        </ul>
                    </div>
                    <div class="col-lg-2">
                        <ul class="list-group" id="list-filter-4">
                        </ul>
                    </div>
                    <div class="col-lg-2">
                        <ul class="list-group" id="list-filter-5">
                        </ul>
                    </div>
                    <div class="col-lg-2">
                        <ul class="list-group" id="list-filter-6">
                        </ul>
                    </div>

                </div>

            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-lg-12">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<div class="modal fade modalbase">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="color:red"><b>3 Primeros Registros del Archivo</b></h4>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table class="table table-bordered table-condensed" id="tablabase">
                                <thead>
                                    <tr align="center">
                                        <td>Numero</td>
                                        <td>Mensaje</td>
                                        <td>Nota</td>
                                    </tr>
                                </thead>
                                <tbody align="center"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" >Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" tabindex="-1" role="dialog" id="modalSms">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Números Validados<span id="loading" class="hidden"><img src="<?php echo base_url() ?>public/images/loading_circle.gif" width="10%"></span></h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <p id="txtInformation"></p>
                        <p id="txtMensaje"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" id="close">Cerrar</button>
                <button type="button" class="btn btn-success " id="btnSendMsg" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Procesando..">Enviar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script src="<?php echo base_url() ?>public/js/sistema/exceltemplatesend.js"></script>