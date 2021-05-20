<style>
    .encabezado{
        background-color: #596BB3;
        color:white;

    }
</style>
<script src="<?php echo base_url() ?>public/js/sistema/fechas.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<div class="container">
    <div class="row">
        <div class="col-lg-1 col-md-1 col-sm-1"></div>
        <div class="col-lg-10 col-md-10 col-sm-10">
            <table style="width: 100%;">
                <tr>
                    <td align="center"><b>Reporte Consolidado<hr></td>

                </tr>
                <tr>
                    <td>
                        <table align="center" style="width: 100%; " class="table table-bordered table-condensed table-hover" id="tablafecha">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Usuario</th>
                                    <th>Enviados</th>
                                    <th>Disponible</th>
                                    <th>Tipo Servicio</th>
                                    <th>Servicio</th>
                                </tr>
                            </thead>
                        </table>
                    </td>
                </tr>

            </table>
        </div>
        <div class="col-lg-1 col-md-1 col-sm-1"></div>
    </div>
</div>


<script src="<?php echo base_url() ?>public/js/sistema/consolidado.js"></script>