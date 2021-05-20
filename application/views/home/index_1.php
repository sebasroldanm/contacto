<html>
    <head>
        <title>ContactoSMS</title>
        <meta charset="utf-8">
        <script src="<?php echo base_url() ?>librerias/jquery/jquery-1.11.0.min.js"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

        <link rel="shortcut icon" href="<?php echo base_url() ?>imagenes/logo.png" />
        <link href="<?php echo base_url() ?>librerias/css/bootstrap/less/bootstrap.css" rel="stylesheet">
        <!--<link href="<?php echo base_url() ?>librerias/css/bootstrap/less/bootstrap.less" rel="stylesheet/less" type="text/css">-->
        <script src="<?php echo base_url() ?>librerias/css/bootstrap/js/less.js"></script>
        <link href="<?php echo base_url() ?>public/css/estiloform.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>librerias/css/bootstrap/dist/js/bootstrap.js"></script>
        <script src="<?php echo base_url() ?>public/js/propias/funciones.js"></script>

        <link href="<?php echo base_url() ?>librerias/jquery/upload/uploadfile.css" rel="stylesheet">
        <!--<link href="<?php echo base_url() ?>librerias/jquery/datepicker/css/datepicker.css" rel="stylesheet">-->
        <link href="<?php echo base_url() ?>librerias/jquery/datetimepicker/jquery.datetimepicker.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>librerias/jquery/upload/jquery.uploadfile.js"></script>
        <script src="<?php echo base_url() ?>librerias/jquery/number.js"></script>
        <!--<script src="<?php echo base_url() ?>librerias/jquery/datepicker/js/bootstrap-datepicker.js"></script>-->
        <script src="<?php echo base_url() ?>librerias/jquery/datetimepicker/jquery.datetimepicker.js"></script>
        <script src="<?php echo base_url() ?>librerias/jquery/upload.js"></script>
        <script src="<?php echo base_url() ?>librerias/jquery/validCampoFranz.js"></script>
        <script src="<?php echo base_url() ?>public/js/sistema/documentos.js"></script>
        <script src="<?php base_url() ?>public/js/sistema/descargaarchivo.js"></script>
        <link rel="stylesheet" href="<?php echo base_url() ?>librerias/css/jquery-ui.css">
        <script src="<?php echo base_url() ?>librerias/jquery/jquery-ui.js"></script>
        <style>
            .sidebar-nav {
                padding: 9px 0;
            }

            .dropdown-menu .sub-menu {
                left: 100%;
                position: absolute;
                top: 0;
                visibility: hidden;
                margin-top: -1px;
            }

            .dropdown-menu li:hover .sub-menu {
                visibility: visible;
            }

            .dropdown:hover .dropdown-menu {
                display: block;
            }

            .nav-tabs .dropdown-menu, .nav-pills .dropdown-menu, .navbar .dropdown-menu {
                margin-top: 0;
            }

            .navbar .sub-menu:before {
                border-bottom: 7px solid transparent;
                border-left: none;
                border-right: 7px solid rgba(0, 0, 0, 0.2);
                border-top: 7px solid transparent;
                left: -7px;
                top: 10px;
            }
            .navbar .sub-menu:after {
                border-top: 6px solid transparent;
                border-left: none;
                border-right: 6px solid #fff;
                border-bottom: 6px solid transparent;
                left: 10px;
                top: 11px;
                left: -6px;
            }

        </style>
    </head>

    <body >
        <div class="wrap">
            <div class="container" id="todo" style="width: 100%">
                <div class="row filas">
                    <div class="col-lg-2">
                        <img src="<?php echo base_url() ?>imagenes/logo.png" width="30px">
                    </div>
                    <div class="col-lg-10">

                    </div>
                </div>
                <div class="row fondomenu">
                    <?php $this->load->view("menu") ?>
                </div>
            </div>

            <div class="container-fluid container-tabs">
                <div class="row">

                    <div class="col-lg-12"><p class="text-right"><b>User</b>: <?php echo ucwords($usuario) . '(' . strtoupper($empresa) . ')' ?></p></div>
                </div>
                <div class="espacio"></div>
                <div class="row">
                    <div class="col-lg-12"><p class="text-center"><b>Bienvenido <?php echo strtoupper($empresa) ?> tienes <?php echo $disponible ?> mensajes restantes</b></p></div>
                </div>
            </div>
        </div>

        <div id="footer" class="navbar-fixed-bottom">
            <div class="container containermenu ">
                <p class="text-muted credit text-center">Tienes dudas o necesitas soporte? <br>servicioalcliente@contactosms.com.co, telefono: 2369921, celular: 3183011111</p>
            </div>
        </div>

    </body>
</html>

<?php
//print_r($_SESSION)
?>


