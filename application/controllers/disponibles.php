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
    <div class="espacio"></div>
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-9 col-center">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title text-center"><b>Informacion Cupos</b></h3>
                </div>
                <div class="panel-body">
                    <form id="form-dispo">
                        <div class="row">
                            <div class="col-lg-1">Usuarios</div>
                            <div class="col-lg-5 col-md-5">
                                <select id="idusuario" name="idusuario[]" class="form-control frmcupos" multiple size="10">
                                    <option value='0' style="font-weight:bold">TODOS</option>
                                    <?php
                                    foreach ($usuarios as $value) {
                                        ?>
                                        <option value='<?php echo $value["id"] ?>'><?php echo $value["nombre"] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2">
                                <button id="btndisponibles" class="btn btn-primary" type="button">Generar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9 col-md-9 col-sm-9 col-center">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <table align="center" style="width: 100%; " class="table table-bordered table-condensed table-hover" id="tablaestado">
                                <thead>
                                    <tr align="center">
                                        <td><b>Usuario</b></td>
                                        <td><b>Plan</b></td>
                                        <td><b>Cupo Total</b></td>
                                        <td><b>Consumido</b></td>
                                        <td><b>Disponible</b></td>
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
