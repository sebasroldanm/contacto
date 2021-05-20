<script src="<?php echo base_url() ?>librerias/toastr/toastr.min.js"></script>
<link href="<?php echo base_url() ?>librerias/toastr/toastr.min.css" rel="stylesheet">
<link href="<?php echo base_url() ?>librerias/jstree/themes/default/style.css" rel="stylesheet">
<script src="<?php echo base_url() ?>librerias/jstree/jstree.js"></script>
<div class="container">
    <form name="form" id="formPermission">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-8">
                <button id="btnSave" type="button" class="btn btn-success">Guardar</button>

            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-lg-5 col-md-8 col-sm-8">
                <div id="jstree_demo_div"></div>
            </div>
            <input type="hidden" id="id" name="id" class="input-admin">
            <div class="col-lg-5">
                <div class="row">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Titulo</label>
                        <input type="email" class="form-control input-admin" id="title" placeholder="titulo" name="title">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Url</label>
                        <input type="email" class="form-control input-admin" id="url" placeholder="Url" name="url">
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nivel</label>
                        <select id="nivel" name="nivel" class="form-control input-admin">
                            <option value="0">Seleccione</option>
                            <option value="1">Principal</option>
                            <option value="2">Sub Menu</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Nodo</label>
                        <select id="node_id" name="node_id" class="form-control input-admin">
                            <option value="0">Seleccione</option>
                            <?php
                            foreach ($main as $i => $val) {
                                ?>
                                <option value = "<?php echo $val["id"] ?>"><?php echo $val["title"]; ?></option>
                                <?php
                            }
                            ?>


                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="<?php echo base_url() ?>public/js/sistema/admin_permissions.js"></script>
