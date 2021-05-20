<div class="container-fluid"> 
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <div class="panel panel-default">
                <form id="frm">
                    <div class="panel-body">
                        <input id="id" name="id" type="hidden">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Numero</label>
                                    <input type="text" class="form-control input-category" id="numero" name='numero' required="">
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($this->session->userdata("idperfil") == 1) {
                            ?>
                            <div class="form-group">
                                <label for="email">Usuario</label>
                                <select class="form-control" id="idusuario" name="idusuario">
                                    <option value="0">Seleccione</option>
                                    <?php
                                    foreach ($usuarios as $value) {
                                        ?>
                                        <option value="<?php echo $value["id"] ?>"><?php echo $value["usuario"] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                </form>
                <div class="panel-footer">
                    <button class="btn btn-success" type="button" id="btnNew">Nuevo</button>
                    <button class="btn btn-success" type="button" id="btnAdd">Agregar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <table class="table table-bordered table-condensed" id="tbl">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Numero</th>
                                <th>Usuario</th>
                                <th>Fecha</th>
                                <th>Borrar</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
