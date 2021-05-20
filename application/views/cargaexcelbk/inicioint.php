<style>

    .wrapper-file-input {
        position: relative;
        overflow: hidden;
        float: left;
    }
    .wrapper-file-input input[type="file"] {
        /** Posicionamos de forma absoluta **/
        position: absolute;
        top: 0;
        right: 0;
        margin: 0;
        /** Cambiamos los bordes **/
        border: solid transparent;
        border-width: 0 0 100px 200px;
        /** Lo hacemos transparente **/
        opacity: 0;
        filter: alpha(opacity=0);
    }
    .fake-file-input{
        /** Lo que necesitamos **/
        cursor: pointer;
        /*background: url('../img/fondo-input-file.png') no-repeat;*/
    }
</style>
<script>

</script>
<script src="<?php base_url() ?>public/js/sistema/fechas.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="<?php echo base_url() ?>public/js/sistema/cargaexcelint.js"></script>
<div class="container">
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>Carga Destinos Internacionales</b></h3>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <div class="row">
                            <form action="" method="POST" id="cargar" name="cargar" enctype="multipart/form-data">

                                <div class="col-lg-12 col-md-12 col-sm-12">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-3 col-sm-3"><b>Archivo (.xls)</b></div>    
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="wrapper-file-input">
                                                <span class="fake-file-input">
                                                    <button type="button" class="btn btn-default btn-sm">
                                                        <span class="glyphicon glyphicon-upload"></span> Seleccione el Archivo
                                                    </button>

                                                </span>
                                                <input type="file" class="file-input" name="archivo" id="archivo"/>
                                            </div>

                                        </div>    

                                        <div class="col-lg-5 col-md-5 col-sm-5">
                                            <span id="spandatos"></span>
                                        </div>    
                                    </div>

                                    <div class="espacio"></div>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <button type="button" id="subir" class="btn btn-success ">subir</button>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2">
                                            <button type="button" id="nuevo" class="btn btn-primary ">Nuevo</button>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4">
                                            <div class="cargando hidden">
                                                <img src="<?php echo base_url(); ?>imagenes/subiendo.gif" width="50%">
                                                <!--<progress id="progressbar2" value="0" max="100" style="width:300px;"></progress>-->
                                                <!--<h3 id="estado2"></h3>-->
                                                <!--<p id="total2"></p>-->
                                            </div>
                                        </div>
                                        <div class="col-lg-5 col-md-2 col-sm-5">
                                            <input type="hidden" id="idbase">
                                            <a href="#" id="descargaExcel" class="hidden">Descargar los XLS con errores</a>
                                        </div>
                                    </div>
                                </div>    

                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>    

    </div>



    <div class="espacio"></div>

    <div class="row">
        <div class="col-lg-3 col-center hidden">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Opciones avanzadas 
                        <button type="button" class="btn btn-default btn-lg" id="configuracion">
                            <span class="glyphicon glyphicon-cog"></span>
                        </button>
                    </h3>

                </div>
                <div class="panel-body" style="display:none" id="panelconfig">
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="checkbox" id="prueba">&nbsp;Envios de Prueba
                        </div>
                    </div>

                    <div class="espacio"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            <input type="checkbox" id="habilitafecha">&nbsp;Fecha Programada
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="input-group">
                                <input type="text" class="form-control fechas" disabled id="fechaprogramada" name="fechaprogramada" placeholder="dd-md-YYYY">

                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </button>
                                </span>


                            </div><!-- /input-group -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="row ">

        <div class="col-lg-6 col-md-6 col-center informacioncarga hidden">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Información Carga</h3>
                </div>
                <div class="panel-body">
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
                            <span id="codigorevision"></span>
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <b>Subidas con Exito:</b>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <span id="regbuenos"></span>
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <b>Subidas con Errores:</b>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <span id="regerrores"></span>
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6">
                            <b>Registro con SMS dobles:</b>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <span id="regdobles"></span>
                        </div>
                    </div>

                    <div class="espacio10"></div>
                    <div class="row errorcupos">
                        <div class="col-lg-6 col-md-6">
                            <b>Envio efectivo:</b>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <span id="regcupo"></span>
                        </div>
                    </div>
                    <div class="espacio10"></div>
                    <div class="row errorcupos">
                        <div class="col-lg-6 col-md-6">
                            <b>Cupo:</b>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <span id="cupo"></span>
                        </div>
                    </div>

                    <div class="espacio10"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12">
                            <div class="alert alert-success alertaconfimacion hidden"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>


<div class="modal fade modalaviso">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" style="color:red"><b>Aviso Importante</b></h4>
            </div>
            <div class="modal-body">
                <form id="frmsubida">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <img src="<?php echo base_url(); ?>imagenes/ejemplo.png" >
                            <input id="rutaarchivo" type="hidden">
                            <input id="idarchivo" type="hidden">
                            <input id="idbase" type="hidden">
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h3 id="estado"></h3>
                            <p id="total"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <progress id="progressbar" value="0" max="100" style="width:300px;"></progress>

                        </div>
                    </div>
                    <div class="espacio"></div>
                    <div class="espacio"></div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="alert alert-danger alerta hidden"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Información de Carga</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered table-condensed" id="tablainfo">
                                        <tbody align="center"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h3 class="panel-title rojo">Información de Anterior</h3>
                                </div>
                                <div class="panel-body">
                                    <table class="table table-bordered table-condensed" id="tablaanterior">
                                        <tbody align="center"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-center">
                            <p id="msjcontinuar"></p>
                        </div>
                    </div>

                    <div class="row hidden">
                        <input type="hidden" id="infobase">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default nomodal" data-dismiss="modal" onclick="trash()">No</button>
                <button type="button" class="btn btn-primary" id="continuar" disabled="">Si</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

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


<div class="modal fade modalaviso2">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Aviso</h4>
            </div>
            <div class="modal-body">
                <p>La opción de envio de prueba esta Activo, usted solo podra subir 3 mensajes</p>
                <p>Desea Continuar?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" id="continuar2">Si</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->




