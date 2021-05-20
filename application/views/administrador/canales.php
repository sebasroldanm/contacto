<script src="<?php echo base_url() ?>public/js/sistema/canales.js"></script>
<div class="container">
    <div class="espacio"></div>
    <div class="row">
        <div class="alert alertamensaje hidden"></div>
        <div class="col-lg-2 col-md-2 col-sm-2"></div>
        <form name="form" id="formCanales">

            <div class="col-lg-8 col-md-8 col-sm-8">
                <div class="hidden">
                    <input type="hidden" name="id" id="id" class="canales">
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">Nomenclatura</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='nomenclatura' class="form-control canales " placeholder="Nomenclatura">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">Nombre</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='nombre' class="form-control canales" placeholder="Nombre">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">Host</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='host' class="form-control canales" placeholder="Host">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">Puerto</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='port' class="form-control canales" placeholder="Puerto">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">User</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='usr' class="form-control canales" placeholder="User" obligatorio="letra">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">Password</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='password' class="form-control canales" placeholder="Password" obligatorio="letra">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">Source Address</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='sourceaddress' class="form-control canales" placeholder="Source Address">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">System Type</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='systemtype' class="form-control canales" placeholder="System Type">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">Address NPI</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='addressnpi' class="form-control canales" placeholder="Address NPI">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">Adress Range</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='addressrange' class="form-control canales" placeholder="Adress Range">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">Delay</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='delay' class="form-control canales" placeholder="Delay">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">Packet Limit</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='packetlimit' class="form-control canales" placeholder="Packet Limit">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">Adress Ton</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='addresston' class="form-control canales" placeholder="Adrreston">
                    </div>

                </div>
                <div class="espacio"> </div>
                <div class="espacio"></div>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5"><div id="alertar2"></div></div>
                    <div class="col-lg-3 col-md-3 col-sm-3"><button type="button" id="registrarCanales" class="btn btn-primary">Registrar</button></div>
                    <div class="col-lg-3 col-md-3 col-sm-3"><button type="button" id="nuevocanal" class="btn btn-primary">Nuevo</button></div>
                    <div class="col-lg-1 col-md-1 col-sm-1"></div>
                </div>
            </div>
        </form>
    </div>
    <div class="espacio"></div>
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12">

            <table class="table table-bordered table-condensed" id="tablacanales">
                <thead>
                    <tr>
                        <th>id</th>
                        <th>Nomenclatura-view</th>
                        <th>Nombre</th>
                        <th>Host</th>
                        <th>Port</th>
                        <th>User</th>
                        <th>Password</th>
                        <th>Source Adress</th>
                        <th>System Type</th>
                        <th>Adress ON</th>
                        <th>Adress NPI</th>
                        <th>Adress Range</th>
                        <th>Delay</th>
                        <th>Packet Limit</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>


