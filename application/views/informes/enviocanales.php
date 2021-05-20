<style>
    .encabezado{
        background-color: #596BB3;
        color:white;

    }
</style>
<script src="<?php echo base_url() ?>public/js/sistema/fechas.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-10 col-center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><b>Informacion Envio por canales</b></h3>
                </div>
                <div class="panel-body">
                    <div class="container-fluid">
                        <form id="form-canales">
                            <div class="row">

                                <div class="col-lg-1">Fecha Inicial</div>
                                <div class="col-lg-3">
                                    <div class="input-group">
                                        <input type="text" class="form-control fechas frmenviocanales" id="inicio" name="inicio" placeholder="dd-md-YYYY">
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
                                        <input type="text" class="form-control fechas frmenviocanales" id="final" name="final" placeholder="dd-md-YYYY">
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-1">Operador</div>
                                <div class="col-lg-3">
                                    <select id="idcarrier" name="idcarrier" class="form-control frmenviocanales"> 
                                        <option value="0">Seleccione</option>
                                        <?php
                                        foreach ($carrier as $value) {
                                            ?>
                                            <option value="<?php echo $value["codigo"] ?>"><?php echo $value["nombre"] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="espacio"></div>
                            <div class="row">
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    Canal
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3">
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
                                <div class="col-lg-1 col-md-1 col-sm-1">
                                    <button id="btnenviocanal" class="btn btn-primary " type="button">Generar</button>
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
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2">
                                    Clientes
                                </div>
                                <div class="col-lg-10 col-md-10 col-sm-10" >
                                    <table class="table table-striped table-hover">
                                        <tr>
                                            <td><input type="checkbox" id="seleccionar"></td>
                                            <td>Seleccionar Todos</td>
                                        </tr>
                                        <?php
                                        foreach ($empresas as $value) {
                                            ?>
                                            <tr>
                                                <td><input type="checkbox" class="listadoEmpresa" value="<?php echo $value["id"] ?>"></td>
                                                <td><?php echo $value["nombre"] ?></td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 ">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <table align="center"  class="table" id="tablacanales">
                                <thead>
                                    <tr align="center" style="background-color: #204A87;color:white;">
                                        <td><b>Canal</b></td>
                                        <td><b>SMS enviados</b></td>
                                    </tr>
                                </thead>
                                <tbody align="center">
                                </tbody>
                                <tfoot align="center">
                                    <tr class="hidden" id="cargando">
                                        <td colspan="2"><img src="<?php echo base_url() ?>imagenes/loading.gif" width=" 70px"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
