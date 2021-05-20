<script src="<?php echo base_url()?>librerias/datetimepicker/js/jquery.datetimepicker.full.js"></script>
<link href="<?php echo base_url()?>librerias/datetimepicker/css/jquery.datetimepicker.css" rel="stylesheet">
<style>
    .encabezado{
        background-color: #596BB3;
        color:white;

    }
</style>

<div class="container">
    <div class="row">
        <div class="col-lg-2 col-lg-offset-3">
            <button id="btnBuscar" class="btn btn-success" type="button">Buscar</button>
        </div>
    </div>
   
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <div class="form-group">
                <label for="email">Fecha Final</label>
                <input type="text" class="form-control input-courses" id="ffinal" name='ffinal' value="<?php echo date("Y-m-d")?>">
            </div>
        </div>
    </div>
    
    <div class="row">

        <div class="col-lg-6 col-lg-offset-3">
            <table class="table" id="tbl">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Nit</th>
                        <th>Consumo</th>
                        <th>Cupo</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<script src="<?php echo base_url() ?>public/js/sistema/consumo.js"></script>
