<br>
<div class="container-fluid">
    <form id="frmExcelBases" enctype="multipart/form-data">
        <div class="row">
            <div class="col-lg-1">
                <button id="subirExcelbases" class="btn btn-success" type="button">Subir</button>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <input type="file" name="file_excel" id="file_excel">
                </div>
            </div>

        </div>
    </form>

    <div class="row">
        <div class="col-lg-4 col-lg-offset-1">
            <a href="<?php echo base_url() ?>portados/downloadPortados/" id="link_download" class="hidden btn btn-info" target="_blank">Descargar</a>
        </div>
    </div>
</div>