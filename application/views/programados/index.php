<script src="<?php base_url() ?>public/js/sistema/programados.js"></script>
<div class="container">

    <div class="row">
        <div class="col-lg-1"># Base</div>
        <div class="col-lg-2">
            <input type="text" id="idbase" name="idbase" class="form-control inputProgramados" placeholder="# Base">
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            Fecha Inicial
        </div>
        <div class="col-lg-2 col-md-3 col-sm-3">
            <div class="input-group">
                <input type="text" class="form-control fechas inputProgramados" id="inicio" name="inicio" placeholder="dd-md-YYYY">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </button>
                </span>
            </div>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-2">
            Fecha Final
        </div>
        <div class="col-lg-2 col-md-3 col-sm-3">
            <div class="input-group">
                <input type="text" class="form-control fechas inputProgramados" id="final" name="final" placeholder="dd-md-YYYY">

                <span class="input-group-btn">
                    <button class="btn btn-default" type="button">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </button>
                </span>


            </div><!-- /input-group -->
        </div>
        <div class="col-lg-1 col-md-2 col-sm-2">
            <button id="btnreporte" class="btn btn-primary" type="buton">Generar</button>
        </div>
    </div>
    <div class="espacio"></div>
    <div class="row">

        <div class="col-lg-11 col-center">

            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-lg-6"><h3 class="panel-title">Contenido Programados</h3></div>
                        <div class="col-lg-3 col-right"><button id="btncancelar" class="btn btn-danger" type="buton">Cancelar Envio</button></div>
                    </div>

                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="alert alertamensaje hidden"></div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <table class="table table-bordered table-condensed" id="tblprogramados">
                                <thead>
                                    <tr align="center">
                                        <td></td>
                                        <td># Base</td>
                                        <td>Numero</td>
                                        <td>Mensaje</td>
                                        <td>Nota</td>
                                        <td>Fecha envio</td>
                                    </tr>
                                </thead>
                                <tbody align="center"></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>




        </div>
    </div>
</div>