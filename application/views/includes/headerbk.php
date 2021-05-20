<html>
    <head>
        <title>ContactoSMS</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <script src="<?php echo base_url() ?>librerias/jquery/jquery-1.11.0.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <link rel="shortcut icon" href="<?php echo base_url() ?>imagenes/logo.png" />
        <script src="<?php echo base_url() ?>librerias/jquery/jquery-1.11.0.min.js"></script>
        <link href="<?php echo base_url() ?>librerias/css/bootstrap/less/bootstrap.css" rel="stylesheet">
        <!--<link href="<?php echo base_url() ?>librerias/css/bootstrap/less/bootstrap.less" rel="stylesheet/less">-->
        <script src="<?php echo base_url() ?>librerias/css/bootstrap/js/less.js"></script>
        <link href="<?php echo base_url() ?>public/css/estiloform.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>librerias/css/bootstrap/dist/js/bootstrap.js"></script>
        <script src="<?php echo base_url() ?>public/js/propias/funciones.js"></script>

        <link href="<?php echo base_url() ?>librerias/jquery/upload/uploadfile.css" rel="stylesheet">
        <!--<link href="<?php echo base_url() ?>librerias/jquery/datepicker/css/datepicker.css" rel="stylesheet">-->
        <link href="<?php echo base_url() ?>librerias/jquery/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
        <!--<script src="<?php echo base_url() ?>librerias/jquery/upload/jquery.uploadfile.js"></script>-->
        <script src="<?php echo base_url() ?>librerias/jquery/number.js"></script>
        <script src="<?php echo base_url() ?>librerias/jquery/datetimepicker/jquery.datetimepicker.js"></script>
        <script src="<?php echo base_url() ?>librerias/jquery/upload.js"></script>
        <script src="<?php echo base_url() ?>librerias/jquery/validCampoFranz.js"></script>
        <script src="<?php echo base_url() ?>public/js/sistema/documentos.js"></script>
        <!--<script src="<?php base_url() ?>public/js/sistema/descargaarchivo.js"></script>-->
        <link rel="stylesheet" href="<?php echo base_url() ?>librerias/css/jquery-ui.css">
        <script src="<?php echo base_url() ?>librerias/jquery/jquery-ui.js"></script>
        <link href="<?php echo base_url() ?>public/css/menudinamico.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>public/css/jquery.dataTables.min.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>public/css/jquery.dataTables_themeroller.min.css" rel="stylesheet">


        <script src="<?php echo base_url() ?>public/js/plugin/jquery.dataTables.min.js"></script>
        <script src="<?php echo base_url() ?>public/js/plugin/plugins.js"></script>
    </head>

    <body >
        <input type="hidden" id="ruta" value="<?php echo base_url() ?>">
        <div class="container-fluid">
            <div class="row filas">
                <div class="col-lg-12 col-md-12 col-sm-12" >
                    <img src="<?php echo base_url() ?>imagenes/logo.png" width="30px">
                </div>
            </div>
        </div>
        <nav class="navbar navbar-default" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <?php $this->load->view("menu") ?>
                    </ul>

                    <ul class="nav navbar-nav navbar-right">
                        <li class='dropdown' ><a href="<?php echo base_url() ?>inicio">Inicio</a></li>
                        <li class='dropdown'><a href="<?php echo base_url() ?>administrador/cerrarSession">Salir</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <!--<div class="row">-->
        <div id="container-tabs">