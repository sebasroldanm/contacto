<style>
    .encabezado{
        background-color: #596BB3;
        color:white;

    }
</style>
<script src="<?php base_url() ?>public/js/sistema/descargaarchivo.js"></script>
<div class="container">
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <table style="width: 100%;">
                <tr>
                    <td align="center" class="encabezado"><b>Informacion del Usuario</b></td>
                </tr>
                <tr>
                <table style="width: 100%;">
                    <tr>
                        <td><b>Nombre</b></td>
                        <td><p class="text-left"><?php echo ucwords($usuario) ?></p></td>
                        <td><img src="<?php echo base_url() ?>imagenes/help.png"></td>
                    </tr>
                    <tr>
                        <td><b>Login</b></td>
                        <td><p class="text-left"><?php echo ucwords($nombre) ?></p></td>
                        <td><img src="<?php echo base_url() ?>imagenes/help.png"></td>
                    </tr>
                    <tr>
                        <td><b>Cupo mensual</b></td>
                        <td><p class="text-left"><?php echo ucwords($cupo) ?></p></td>
                        <td><img src="<?php echo base_url() ?>imagenes/help.png"></td>
                    </tr>
                    <tr>
                        <td><b>Cantidad de mensaje Restante</b></td>
                        <td><p class="text-left"><?php echo ucwords($restantes) ?></p></td>
                        <td><img src="<?php echo base_url() ?>imagenes/help.png"></td>
                    </tr>
                </table>

                </tr>

            </table>
        </div>
        <div class="col-lg-1"></div>
    </div>
</div>
