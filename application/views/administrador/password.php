<script src="<?php echo base_url() ?>librerias/toastr/toastr.min.js"></script>
<link href="<?php echo base_url() ?>librerias/toastr/toastr.min.css" rel="stylesheet">
<div class="container">
    <form id="formpasswd">
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="email">Clave Actual</label>
                    <input class="form-control" id="password_current" name="password_current" type="password">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="email">Clave Nueva</label>
                    <input class="form-control" id="password" name="password" type="password">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="email">Confirmación</label>
                    <input class="form-control" id="confirmation" name="confirmation" type="password">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <button type="button" class="btn btn-success" id="btnUpdate">Actualizar</button>
            </div>
        </div>
    </form>
</div>

<script src="<?php echo base_url() ?>public/js/sistema/password.js"></script>