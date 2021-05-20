<script src="<?php echo base_url() ?>public/js/propias/plugin.js"></script>
<script src="<?php echo base_url() ?>librerias/jquery/toastr.js"></script>
<link rel="stylesheet" href="<?php echo base_url() ?>librerias/css/toastr.css">

<script src="<?php echo base_url() ?>public/js/sistema/SendFast.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-9 col-center">
            <div class="row">
                <div class="col-lg-1">
                    <button id="btnConsolidate" type="button" class="btn btn-success">Validar</button>
                </div>
                <div class="col-lg-1">
                    <button id="btnClean" type="button" class="btn btn-warning">Limpiar</button>
                </div>
<!--                <div class="col-lg-7 col-center">
                    <div class="row">
                        <div class="col-lg-9">
                            <div id="movebar" class="progress progress-striped active">
                                <div class="progress-bar" role="progressbar"  aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%" id="valueprocess">
                                    <span id="txtprocess">0%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <span id='counter'>0/0</span>
                        </div>
                    </div>

                </div>-->
            </div>
        </div>
    </div>
    <div class="espacio"></div>
    <div class="row">
        <div class="col-lg-9 col-center">
            <div class="row">
                <div class="col-lg-3">
                    Números Destino:
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-12">
                            Contenido Mensaje
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-3">
                    <textarea class="form-control" rows="14" id="destination" name="destination" placeholder="Contenidor Números" style="resize: none"></textarea>    
                </div>
                <div class="col-lg-9">
                    <div class="row">
                        <div class="col-lg-12">
                            <textarea class="form-control" id="message" rows="3" name="message" placeholder="Escribe aqui el Mensaje" style="resize: none"></textarea>
                        </div>
                    </div>
                    <div class="espacio"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            Nota:
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <input class="form-control" id="note" name="note" placeholder="Nota obligatoria para el sistema">
                        </div>
                    </div>
                    <div class="espacio"></div>
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
            </div>

        </div>

    </div>
</div>


<div class="modal fade" tabindex="-1" role="dialog" id="modalSms">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Números Validados<span id="loading" class="hidden"><img src="<?php echo base_url()?>public/images/loading_circle.gif" width="10%"></span></h4>
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
