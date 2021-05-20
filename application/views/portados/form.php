<div class="container-fluid"> 
    <div class="row">
        <div class="col-lg-4 col-lg-offset-4">
            <div class="panel panel-default">
                <form id="frm">
                    <div class="panel-body">
                        <input id="id" name="id" type="hidden" class="input-portados">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Numero</label>
                                    <input type="text" class="form-control input-portados" id="numero" name='numero' required="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Operador Anterior</label>
                                    <select class="form-control input-portados" id="previous_carrie_id" name="previous_carrie_id">
                                        <option value="0">Seleccione</option>
                                        <?php
                                        foreach ($carries as $value) {
                                            ?>
                                            <option value="<?php echo $value["id"] ?>"><?php echo trim($value["nombre"]) ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="email">Operador actual</label>
                                    <select class="form-control input-portados" id="current_carrie_id" name="current_carrie_id">
                                        <option value="0">Seleccione</option>
                                        <?php
                                        foreach ($carries as $value) {
                                            ?>
                                            <option value="<?php echo $value["id"] ?>"><?php echo trim($value["nombre"]) ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>


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
                                <th>Operador Anterior</th>
                                <th>Operador Actual</th>
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
