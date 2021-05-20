<style>
    .encabezado{
        background-color: #596BB3;
        color:white;

    }
</style>
<script src="<?php echo base_url() ?>public/js/sistema/fechas.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<div class="container">
    <div class="row">
        <div class="col-lg-1 col-md-1 col-sm-1"></div>
        <div class="col-lg-10 col-md-10 col-sm-10">
            <table style="width: 100%;">
                <tr>
                    <td align="center" class="encabezado"><b>Informacion del trafico</b></td>
                </tr>
                <tr>
                    <td align="center"><b>Cantidad de mensajes enviados acumulados</b><hr></td>

                </tr>
                <tr>
                    <td align="center">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                Fecha Inicial
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control inputProgramados" id="inicio" name="inicio" placeholder="dd-md-YYYY">
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
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="input-group">
                                    <input type="text" class="form-control fechas inputProgramados" id="final" name="final" placeholder="dd-md-YYYY">

                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="button">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </button>
                                    </span>


                                </div><!-- /input-group -->
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <button id="btnreporte" class="btn btn-primary" type="buton">Generar</button>
                            </div>
                        </div>

                    </td>
                </tr>
                <tr>
                    <td>
                        <table align="center" style="width: 100%; " class="table table-bordered table-condensed table-hover" id="tablafecha">
                            <tr align="center" style="background-color: #204A87;color:white;">
                                <th><b>Mes</b></th>
                                <th><b>Operador</b></th>
                                <th><b>SMS enviados</b></th>
                            </tr>
                        </table>
                    </td>
                </tr>

            </table>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1"></div>
    </div>
</div>
