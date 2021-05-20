<script src="<?php echo base_url() ?>public/js/sistema/desarrollo.js"></script>

<div class="container-fluid">
    <form id="formcorreo">
        <div class="hidden">
            <input type="text" name="id" id="id" class="correos">
        </div>
        <div class="row">
            <div class="col-lg-1">protocol</div>
            <div class="col-lg-2"><input type="text" name="protocolo" class="correos"></div>
        </div>
        <div class="espacio"></div>
        <div class="row">
            <div class="col-lg-1">Host SMTP</div>
            <div class="col-lg-2"><input type="text" name="host" class="correos"></div>
        </div>
        <div class="espacio"></div>
        <div class="row">
            <div class="col-lg-1">Puerto SMTP</div>
            <div class="col-lg-2"><input type="text" name="puerto" class="correos"></div>
        </div>
        <div class="espacio"></div>
        <div class="row">
            <div class="col-lg-1">Usuario</div>
            <div class="col-lg-2"><input type="text" name="usuario" class="correos"></div>
        </div>
        <div class="espacio"></div>
        <div class="row">
            <div class="col-lg-1">Clave</div>
            <div class="col-lg-2"><input type="password" name="clave" class="correos"></div>
        </div>
        <div class="espacio"></div>
        <div class="row">
            <div class="col-lg-1"><button id="subirconfiguracion" class="btn btn-success"type="button">Subir</button></div>
            <div class="col-lg-1"><button id="testcorreo" class="btn btn-primary"type="button">Test</button></div>

        </div>
        <div class="espacio"></div>

    </form>
    <div class="row enviocorreo hidden">
        <div class="col-lg-2"><input type="text" name="pruebacorreo" id="pruebacorreo" class="pruebacorreo" placeholder="correo@ejemplo.com"></div>
        <div class="col-lg-2"><input type="text" name="pruebamensaje" id="pruebamensaje" class="pruebacorreo" placeholder="Mensaje de ejemplo"></div>
        <div class="col-lg-2"><button id="enviarprueba" type="button" class="btn btn-success">Enviar</button></div>
    </div>
    <div class="espacio"></div>
    <div class="row">
        <div class="col-lg-10 col-center">
            <table id="tablacorreo" class="table table-bordered">
                <thead>
                    <tr>
                        <td>Protocolo</td>
                        <td>Puerto</td>
                        <td>Host</td>
                        <td>Usuario</td>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>