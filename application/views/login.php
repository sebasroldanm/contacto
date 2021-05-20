<html>
    <head>
        <title>ContactoSMS</title>
        <meta charset="utf-8">
        <script src="<?php echo base_url() ?>librerias/jquery/jquery-1.11.0.min.js"></script>

        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="shortcut icon" href="<?php echo base_url() ?>imagenes/logo.png" />

        <link href="<?php echo base_url() ?>librerias/css/bootstrap/less/bootstrap.css" rel="stylesheet">
        <!--<link href="<?php echo base_url() ?>librerias/css/bootstrap/less/bootstrap.less" rel="stylesheet/less" type="text/css">-->
        <script src="<?php // echo base_url()   ?>librerias/css/bootstrap/js/less.js"></script>
        <link href="<?php echo base_url() ?>public/css/estiloform.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>librerias/css/bootstrap/dist/js/bootstrap.js"></script>
        <script src="<?php echo base_url() ?>public/js/propias/funciones.js"></script>
        <script src="<?php echo base_url() ?>librerias/jquery/datetimepicker/jquery.datetimepicker.js"></script>
        <!--<link href="<?php echo base_url() ?>librerias/jquery/upload/uploadfile.css" rel="stylesheet">-->
        <!--<script src="<?php echo base_url() ?>librerias/jquery/upload/jquery.uploadfile.js"></script>-->
        <script src="<?php echo base_url() ?>librerias/jquery/upload.js"></script>

        <script>
            $(function () {
                $("#login").focus();
            })
        </script>
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12" style="height: 100px;">
                    <div class="row">
                        <div class="col-lg-5 col-md-5 col-sm-5"></div>
                        <div class="col-lg-5 col-md-5 col-sm-5">
                            <img class="img-responsive" ssrc="<?php echo base_url() ?>imagenes/LogoContacto.jpg" width="150px">
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2"></div>
                    </div>

                </div>
            </div>
            <form action="login/valida" method="POST" name="form" class='responsive-utilities'>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5  col-center">
                        <p class="text-center" style="font-size:20px;font-weight: bold">PLATAFORMA DE ENVIO MASIVO SMS CONTACTO SMS</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5  col-center" style="border:1px solid #000;border-radius: 6px ">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 titulologin">
                                <div class="center-block">
                                    <label class="text-center" style="padding:0px;">Inicio Sesión</label>
                                </div>

                            </div>
                        </div>
                        <div class="espacio"></div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3"><label>Usuario</label></div>
                            <div class="col-lg-6 col-md-6 col-sm-6"><input type='text' name="login" id="login" placeholder="Ingrese su Usuario" class="form-control"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8"></div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <img src="<?php echo base_url() ?>imagenes/help.png" title="Ingrese su Usuario">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="espacioinput"></div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3"><label>Clave</label></div>
                            <div class="col-lg-6 col-md-6 col-sm-6"><input type='password' name="pass" placeholder="Ingrese su Clave" class="form-control"></div>
                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8"></div>
                                    <div class="col-lg-2 col-md-2 col-sm-2">
                                        <img src="<?php echo base_url() ?>imagenes/help.png" title="Ingrese su Password">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="espacioinput"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <p class="text-center"><button type="submit" class="btn btn-primary">Login</button></p>
                            </div>

                        </div>
                        <div class="espacioinput"></div>
                        <div class="row">

                             <?php 
                                if($this->session->flashdata('error')){ ?>
                                <div class="col-lg-12 col-md-12 col-sm-12 error">
                                <div class="alert alert-danger">
                                    <a href="#" class="close" data-dismiss="alert">&times;</a>
                                    <strong>Error!</strong> <?php echo $this->session->flashdata('error'); ?>
                                </div>
                                </div>
                                <?php 
                                    }
                                ?> 
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-5 col-md-5 col-sm-5 col-center">
                        <p class="text-center" style="color:#999999;font-weight: bold;">Ingrese su (usuario/clave) asignado, si no ha recibido uno favor comunicarse con Contacto SMS</p>
                    </div>
                </div>
            </form>
        </div>

    </body>
</html>
