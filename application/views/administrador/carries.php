<script src="<?php echo base_url() ?>public/js/sistema/carries.js"></script>
<div class="container">
    <div class="espacio"></div>
    <div class="row">
        <div class="alert alertamensaje hidden"></div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4"></div>
        <form name="form" id="formCarries">

            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="hidden">
                    <input type="hidden" name="id" id="id" class="datoscarries">
                    <input type="hidden"  class="accion" id="accion" value="">
                    <input type="hidden" id="estado" value="">
                </div>

                <div class="row">
                    <div class="alert alert-danger hidden"></div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">Nombre</div>
                    <div class="col-lg-8 col-md-8 col-sm-8">    
                        <input type="text" name='nombre' class="form-control datoscarries " placeholder="Nombre">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">Prefijos</div>
                    <div class="col-lg-8 col-md-8 col-sm-8">    
                        <input type="text" name='prefijos' class="form-control datoscarries" placeholder="Prefijos">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">Codigo</div>
                    <div class="col-lg-8 col-md-8 col-sm-8">    
                        <input type="text" name='codigo' maxlength="1" class="form-control datoscarries" placeholder="Codigo">
                    </div>
                </div>

                <div class="espacio"></div>

                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5"><div id="alertar"></div></div>
                    <div class="col-lg-3 col-md-3 col-sm-3"><button type="button" id="registrarcarries" class="btn btn-primary">Registrar</button></div>
                    <div class="col-lg-3 col-md-3 col-sm-3"><button type="button" id="nuevocarrie" class="btn btn-primary">Nuevo</button></div>
                    <div class="col-lg-1 col-md-1 col-sm-1"></div>
                </div>


            </div>
        </form>
        <div class="col-lg-4 col-md-4 col-sm-4"></div>
    </div>
    <div class="espacio"></div>
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3"></div>
        <div class="col-lg-6 col-md-6 col-sm-6">

            <table class="table table-bordered table-condensed" id="tablacarries">
                <thead>
                    <tr>
                        <td></td>
                        <td>Nombre</td>
                        <td>Prefijos</td>
                        <td>Codigo</td>
                    </tr>
                </thead>
                <tbody align="center"></tbody>
            </table>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>



