<style>
    .encabezado{
        background-color: #596BB3;
        color:white;

    }
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-1"></div>
        <div class="col-lg-10">
            <table style="width: 100%;">
                <tr>
                    <td align="center" class="encabezado"><b>Informacion de bases</b></td>
                </tr>
                <tr>
                    <td align="center"><b>Cantidad de mensajes por Bases</b></td>
                </tr>
                <tr>
                    <td>
                        <table align="center" style="width: 100%; " class="table table-bordered table-condensed table-hover">
                            <thead>
                                <tr align="center" style="background-color: #204A87;color:white;">
                                    <th>Usuario</th>
                                    <th>Base</th>
                                    <th>Fecha</th>
                                    <th>Forma carga</th>
                                    <th>Cargados</th>
                                    <th>Enviados</th>
                                    <th>Pendientes</th>
                                    <th>Errores</th>
                                </tr>
                            </thead>
                            <?php
                            if (count($bases) > 0) {
                                foreach ($bases as $value) {
                                    ?>
                                    <tr align="center">
                                        <td><?php echo $value["usuario"] ?></td>
                                        <td><?php echo $value["id"] ?></td>
                                        <td><?php echo $value["fecha"] ?></td>
                                        <td><?php echo $value["nombre"] ?></td>
                                        <td><?php echo $value["registros"] ?></td>
                                        <td><?php echo $value["enviados"] ?></td>
                                        <td><?php echo $value["pendientes"] ?></td>
                                        <td><a target="_blank" href="<?php echo base_url() . 'cargaexcel/excelErrores/' . $value["id"] ?>"><?php echo $value["errores"] ?></a></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </table>
                    </td>
                </tr>

            </table>
        </div>
        <div class="col-lg-1"></div>
    </div>
</div>
