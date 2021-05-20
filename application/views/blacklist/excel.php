<br>

<div class="row">
    <div class="col-lg-2 col-lg-offset-3">
        <a href="<?php echo base_url()?>formatos/formato.xlsx" download="formato.xlsx" class="title">Formato Archivo</a>
    </div>
</div>

<div class="container-fluid">
    <form id="frmExcel" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-1">
                <button id="subirExcel" class="btn btn-success" type="button">Subir</button>
            </div>
            <?php
            if ($this->session->userdata("idperfil") == 1) {
                ?>
                <div class="col-lg-2">
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
                </div>
                <?php
            }
            ?>
            <div class="col-lg-2">
                <div class="form-group">
                    <input type="file" name="file_excel" id="file_excel">
                </div>
            </div>

        </div>
    </form>

    <div class="row">
        <div class="col-lg-4 col-lg-offset-1">
            <div class="panel panel-default">
                <div class="panel-title">
                    <button id="btnConfirmation" class="btn btn-primary btn-xs" type="button" disabled="">Confirmar</button>
                    <input type="hidden" id="archivo_id">
                </div>
                <div class="panel-body">
                    <table class="table table-condensed table-bordered" id="tblResult">
                        <thead>
                            <tr>
                                <th>Numero</th>
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