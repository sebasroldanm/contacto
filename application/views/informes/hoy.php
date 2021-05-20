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
                    <td align="center" class="encabezado"><b>Informacion del trafico</b></td>
                </tr>
                <tr>
                    <td align="center"><b>Cantidad de mensajes enviados acumulados</b></td>
                </tr>
                <tr>
                    <td>
                        <table align="center" style="width: 100%; " class="table table-bordered table-condensed table-hover">
                            <tr align="center" style="background-color: #204A87;color:white;">
                                <th><b>Mes</b></th>
                                <th><b>Operador</b></th>
                                <th><b>SMS enviados</b></th>
                            </tr>
                            <?php
                            foreach ($hoy as $value) {
                                ?>
                                <tr align="center">
                                    <td><?php echo $value["fecha"]?></td>
                                    <td><?php echo $value["operador"]?></td>
                                    <td><?php echo $value["cantidad"]?></td>
                                </tr>
                                <?php
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
