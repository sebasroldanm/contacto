<script src="<?php echo base_url() ?>public/js/sistema/empresas.js"></script>
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
                <div class="col-lg-4 col-md-4 col-sm-4">Nombre</div>
                <div class="col-lg-8 col-md-8 col-sm-8">    
                    <input type="text" name='nombre' class="form-control datosempresa " obligatorio='letra' placeholder="Nombre" maxlength="50">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">Nit</div>
                <div class="col-lg-8 col-md-8 col-sm-8">    
                    <input type="text" name='nit' class="form-control datosempresa obligatorio" placeholder="Nit">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">Dirección</div>
                <div class="col-lg-8 col-md-8 col-sm-8">    
                    <input type="text" name='direccion' id="direccion" class="form-control datosempresa obligatorio" placeholder="Dirección">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">Telefono</div>
                <div class="col-lg-8 col-md-8 col-sm-8">    
                    <input type="text" name='telefonos' id="telefonos" class="form-control datosempresa obligatorio" placeholder="Telefono">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">Contacto</div>
                <div class="col-lg-8 col-md-8 col-sm-8">    
                    <input type="text" name='contacto' id="contacto" class="form-control datosempresa obligatorio" placeholder="Contacto">
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4">Activo</div>
                <div class="col-lg-8 col-md-8 col-sm-8">    
                    <input type="checkbox" name="activo" id="activo" value="1" checked class="form-control datosempresa">
                </div>
            </div>

            <div class="espacio"></div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-lg-offset-3">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3"><button type="button" id="registrarempresa" class="btn btn-primary">Registrar</button></div>
                        <div class="col-lg-3 col-md-3 col-sm-3"><button type="button" id="nuevoempresa" class="btn btn-primary">Nuevo</button></div>
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

        <table class="table table-bordered table-condensed" id="tablaadministrador">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Nit</th>
                    <th>Dirección</th>
                    <th>Telefonos</th>
                    <th>Contacto</th>
                    <th>Activo</th>

                </tr>
            </thead>
            <tbody>
            </tbody>


        </table>
    </div>
</div>




