<style>
    .encabezado{
        background-color: #596BB3;
        color:white;

    }
</style>

<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-10 col-center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><b>Respuestas</b></h3>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <form id="form-responses">
                            <div class="row">

                                <div class="col-lg-1">Fecha Inicial</div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control fechas frmenviocanales" id="inicio" name="inicio" value='<?php echo date("Y-m") . "-01" ?>'>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-lg-1">Fecha Final</div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control fechas frmenviocanales" id="final" name="final" value='<?php echo date("Y-m-d"); ?>'>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-1">Canal</div>
                                <div class="col-lg-3">
                                    <select id="idcanal" name="idcanal" class="form-control frmenviocanales"> 
                                        <option value="0">Seleccione</option>
                                        <?php
                                        foreach ($canales as $value) {
                                            ?>
                                            <option value="<?php echo $value["id"] ?>"><?php echo $value["nombre"] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="espacio"></div>
                            <div class="row">
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    <button id="btnSearch" class="btn btn-primary " type="button">Generar</button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-10 col-center">
            <div class="row">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table table-bordered table-condensed" id="tblResponses">
                            <thead>
                                <tr>
                                    <th>Canal</th>
                                    <th>Source</th>
                                    <th>Numero</th>
                                    <th>Mensaje</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>public/js/sistema/responses.js"></script>