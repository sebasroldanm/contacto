<script src="<?php echo base_url() ?>public/js/sistema/servicios.js"></script>

<div class="container">
    <div class="espacio"></div>

    <div class="row">
        <div class="alert alertamensaje hidden"></div>
    </div>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-4"></div>
        <form name="form" id="formServicios">

            <div class="col-lg-4 col-md-4 col-sm-4">
                <div class="hidden">
                    <input type="hidden" name="id" id="id" class="datosservicios">
                    <input type="hidden"  class="accion" id="accion" value="">
                    <input type="hidden" id="estado" value="">
                </div>


                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">Nombre</div>
                    <div class="col-lg-8 col-md-8 col-sm-8">    
                        <input type="text" name='nombre' class="form-control datosservicios obligatorio" placeholder="Nombre">

                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">Servicio</div>
                    <div class="col-lg-8 col-md-8 col-sm-8">    
                        <select name="tiposervicio" id="tiposervicio" class="form-control datosservicios obligatorio">
                            <option value="0">Seleccione</option>
                            <option value="<?php echo "1" ?>"><?php echo "BOLSA LIMITE" ?></option>
                            <option value="<?php echo "2" ?>"><?php echo "MENSUALIDAD" ?></option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">Máximo</div>
                    <div class="col-lg-8 col-md-8 col-sm-8">    
                        <input type="text" name='maximo' id="maximo" class="form-control datosservicios obligatorio" placeholder="Maximo">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4">Acumula</div>
                    <div class="col-lg-8 col-md-8 col-sm-8">    
                        <input type="checkbox" name="acumula" id="acumula" value="1" class="form-control datosservicios">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-4 col-sm-4"></div>
                    <div class="col-lg-8 col-md-8 col-sm-8">
                        <div class="alert alert-danger hidden" id="mensaje"></div>
                    </div>

                </div>
                <div class="espacio"></div>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5"><div id="alertar"></div>

                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3"><button type="button" id="registrarservicios" class="btn btn-primary">Registrar</button></div>
                    <div class="col-lg-3 col-md-3 col-sm-3"><button type="button" id="nuevoservicio" class="btn btn-primary">Nuevo</button></div>
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

            <table class="table table-bordered table-condensed" id="tablaservicios">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Maximo</th>
                        <th>Acumula</th>

                    </tr>
                </thead>
                <tbody align="center"></tbody>



            </table>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>



