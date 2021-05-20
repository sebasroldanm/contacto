<script src="<?php echo base_url() ?>public/js/sistema/preferencias.js"></script>
<div class="espacio"></div>
<form id="frm-preferencias">
    <div class="alert alert-success hidden" id="alertamensaje"></div>
    <div class="row">
        <div class="col-lg-12">
            <button id="btnguardar" class="btn btn-success" type="button">Guardar</button>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="row ">
                                        <div class="col-lg-12">
                                            <table class="table table-striped">
                                                <tr>
                                                        <td><input type="checkbox" id="seleccionar"></td>
                                                        <td>Seleccionar Todos</td>
                                                    </tr>
                                                <?php
                                                foreach ($usuarios as $value) {
                                                    ?>
                                                    <tr>
                                                        <td><input type="checkbox" class="listadoUsuario" value="<?php echo $value["id"] ?>"></td>
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
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <input class="form-control" id="1" name="claro" value="Claro" readonly="">
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <select name="canalClaro" id="canalClaro" class="form-control frm-pre">
                                                                <?php
                                                                foreach ($canales as $i => $value) {
                                                                    ?>
                                                                    <option value="<?php echo $value["id"] ?>"  ><?php echo $value["nombre"] ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <input class="form-control" id="2" name="movistar" value="Movistar" readonly="">
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <select name="canalMov" id="canalMov" class="form-control frm-pre">

                                                                <?php
                                                                foreach ($canales as $i => $value) {
                                                                    ?>
                                                                    <option value="<?php echo $value["id"] ?>" ><?php echo $value["nombre"] ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <input class="form-control" id="3" name="tigo" value="Tigo" readonly="">
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <select name="canalTigo" id="canalTigo" class="form-control frm-pre">
                                                                <?php
                                                                foreach ($canales as $i => $value) {
                                                                    ?>
                                                                    <option value="<?php echo $value["id"] ?>" ><?php echo $value["nombre"] ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <input class="form-control" id="4" name="avantel" value="avantel" readonly="">
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6">
                                                            <select name="canalAvan" id="canalAvan" class="form-control frm-pre">
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
        </div>
    </div>
</form>



