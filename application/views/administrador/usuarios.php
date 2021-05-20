<script src="<?php echo base_url() ?>public/js/sistema/usuarios.js"></script>
<div class="container">
    <div class="espacio"></div>
    <div class="row">
        <div class="alert alertamensaje hidden"></div>
    </div>

    <div class="row">
        <form name="frmusuarios" id="frmusuarios">
            <div class="hidden">
                <input type="hidden" name="id" id="id" class="registro">
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">Usuario</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='usuario' class="form-control registro " obligatorio="letra" placeholder="Usuario">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">Clave</div>
                    <div class="col-lg-3 col-md-3 col-sm-3">    
                        <input type="password" name='clave' id="clave" class="form-control registro" obligatorio="letras" placeholder="Clave">

                    </div>
                    <div class="col-lg-1 col-md-1 col-sm-1">
                        <input type='checkbox' id="ver" title="ver clave">
                    </div>    
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">Nombre</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="text" name='nombre' class="form-control registro" obligatorio="letra" placeholder="Nombre Completo">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">Empresa</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <select name="idempresa" id="idempresa" class="form-control registro " obligatorio="numero">
                            <option value="0">Seleccione</option>
                            <?php
                            foreach ($idempresa as $value) {
                                ?>
                                <option value="<?php echo $value["id"] ?>"><?php echo $value["nombre"] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">Servicio</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <select name="idservicio" id="idservicio" class="form-control registro" obligatorio="numero">
                            <option value="0">Seleccione</option>
                            <?php
                            foreach ($idservicio as $value) {
                                ?>
                                <option value="<?php echo $value["id"] ?>"><?php echo $value["nombre"] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-2">Perfil</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <select name="idperfil" id="idperfil" class="form-control registro" obligatorio="numero">
                            <option value="0">Seleccione</option>
                            <?php
                            foreach ($idperfil as $value) {
                                ?>
                                <option value="<?php echo $value["id"] ?>"><?php echo $value["perfil"] ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">Correos</div>
                    <div class="col-lg-4 col-md-2 col-sm-2">    
                        <input type="text" name="correos" id="correos" class="form-control registro" placeholder="Correos separados por ,">
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2">Concatena</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="checkbox" name="concatena" id="concatena" value="1" class="form-control registro">
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2">Activo</div>
                    <div class="col-lg-4 col-md-4 col-sm-4">    
                        <input type="checkbox" name="estado" id="estado" value="1" checked class="form-control registro">
                    </div>
                </div>

                <div class="espacio"></div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-lg-offset-3 col-md-offset-3 col-sm-offset-3">
                        <div class="col-lg-3"><button type="button" id="btnregistro" class="btn btn-primary">Registrar</button></div>
                        <div class="col-lg-3"><button type="button" id="btnnuevousuario" class="btn btn-primary">Nuevo</button></div>
                        <div class="col-lg-1"></div>
                    </div>
                </div>

                <div class="espacio"></div>

            </div>
            <!--Preferencia-->
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2"></div>
                    <div class="col-lg-8 col-md-8 col-sm-8">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <select name="idcarries[]" id="idcarries" class="form-control ">
                                    <option value="1" >Claro</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <select name="idcanal[]" id="canal_0" class="form-control registro preferencias" obligatorio="numero">
                                    <?php
                                    foreach ($canales as $i => $value) {
                                        ?>
                                        <option value="<?php echo $value["id"] ?>"  ><?php echo $value["nombre"] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <select name="idcarries[]" id="idcarries" class="form-control ">
                                    <option value="2" >Movistar</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <select name="idcanal[]" id="canal_1" class="form-control registro preferencias" obligatorio="numero">

                                    <?php
                                    foreach ($canales as $i => $value) {
                                        ?>
                                        <option value="<?php echo $value["id"] ?>" ><?php echo $value["nombre"] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <select name="idcarries[]" id="idcarries" class="form-control ">
                                    <option value="3" >Tigo</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <select name="idcanal[]" id="canal_2" class="form-control registro preferencias" obligatorio="numero" >
                                    <?php
                                    foreach ($canales as $i => $value) {
                                        ?>
                                        <option value="<?php echo $value["id"] ?>" ><?php echo $value["nombre"] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <select name="idcarries[]" id="idcarries" class="form-control ">
                                    <option value="2" >Avantel</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <select name="idcanal[]" id="canal_3" class="form-control registro preferencias registro" obligatorio="numero">
                                    <?php
                                    foreach ($canales as $value) {
                                        ?>
                                        <option value="<?php echo $value["id"] ?>"><?php echo $value["nombre"] ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>


                    </div>
                </div>

            </div>
        </form>
    </div>
    <div class="espacio"></div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-center">

            <table class="table table-bordered table-condensed table-hover" id="tablausuarios">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nombre</th>
                        <th>Empresa</th>
                        <th>Perfil</th>
                        <th>Maximo</th>
                        <th>Enviados</th>
                        <th>Pendientes</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>



