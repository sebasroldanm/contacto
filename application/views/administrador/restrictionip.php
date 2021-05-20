<script src="<?php echo base_url() ?>librerias/toastr/toastr.min.js"></script>
<link href="<?php echo base_url() ?>librerias/toastr/toastr.min.css" rel="stylesheet">
<div class="espacio"></div>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
        <div class="alert alertamensaje hidden"></div>
    </div>
    
</div>
<div class="row">

    <form name="formEmpresas" id="formEmpresas">
        <div class="col-lg-4 col-md-4 col-sm-4 col-lg-offset-4 col-md-offset-4 col-sm-offset-4">
            <div class="hidden">
                <input type="hidden" name="id" id="id" class="datosempresa">
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">Usuario</div>
                <div class="col-lg-8 col-md-8 col-sm-8">    
                    <select class="form-control datosempresa" id="idusuario" name="idusuario">
                        <option>Seleccion</option>
                        <?php
                        foreach($users as $val){
                        ?>
                            <option value="<?php echo $val["id"]?>"><?php echo $val["nombre"]. " | ".$val["usuario"]?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="espacio"></div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">Ip</div>
                <div class="col-lg-8 col-md-8 col-sm-8">    
                    <input type="text" name='ip' id="ip" class="form-control datosempresa" placeholder="Ip autorizado">
                </div>
            </div>
            <div class="espacio"></div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-lg-offset-3">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3"><button type="button" id="btn_register" class="btn btn-success">Registrar</button></div>
                        <div class="col-lg-3 col-md-3 col-sm-3"><button type="button" id="btn_new" class="btn btn-primary">Nuevo</button></div>
                        <div class="col-lg-1 col-md-1 col-sm-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <div class="col-lg-4 col-md-4 col-sm-4"></div>
</div>
<div class="espacio"></div>
<div class="row">
    <div class="col-lg-10 col-md-10 col-sm-10 col-lg-offset-1 col-md-offset-1 col-sm-offset-1">

        <table class="table table-bordered table-condensed" id="tbl">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Ip</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody>
            </tbody>


        </table>
    </div>
</div>
<script src="<?php echo base_url() ?>public/js/sistema/restrictionip.js"></script>