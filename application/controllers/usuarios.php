<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuarios extends MY_Controller {

    private $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
        $this->tabla = 'usuarios';
    }

    /**
     * Metod para agregar y editar un usuario
     */
    public function gestion() {
        $data = $this->input->post();
//        $ruta = $_SERVER["DOCUMENT_ROOT"] . "/planos/";
        $ruta = $_SERVER["DOCUMENT_ROOT"] . "/contactosms/planos/";
        $rutazip = $_SERVER["DOCUMENT_ROOT"] . "/contactosms/zip/";
//        $rutazip = $_SERVER["DOCUMENT_ROOT"] . "/zip/";

        $carrie = $data["idcarries"];
        $canales = $data["idcanal"];
//
        $data["estado"] = (isset($data["estado"])) ? 1 : 0;
        $data["concatena"] = (isset($data["concatena"])) ? 1 : 0;

//        

        /**
         * Validacion para verifica si es agregar o editar
         */
        if ($data["id"] == '') {
            unset($data["id"]);
            unset($data["idcarries"]);
            unset($data["idcanal"]);
            /**
             * Verifica la tabla de usuarios
             */
            $data["clave"] = base64_encode($data["clave"]);


            $data = $this->asignaNull($data);
            $data["fechainicio"] = date("Y-m-d");
            /**
             * metodo para obtener los datos del usuario
             */
            $valida = $this->AdministradorModel->buscar($this->tabla, '*', "usuario = '{$data["usuario"]}'", 'row');

            if ($valida == FALSE) {
                $ok = $this->AdministradorModel->insert($this->tabla, $data);

                $canal = '';
                /**
                 * Iteracion para obtener las preferencias
                 */
                foreach ($canales as $i => $value) {
                    $canal .= ($canal == '') ? '' : ',';
                    $canal .= $value;
                }

                $preferencias["preferencias"] = $canal;

                $this->AdministradorModel->update($this->tabla, $ok, $preferencias);
                /**
                 * Crear las carpetas necesarias y le da permisos
                 */
                $this->crearRutaCarpeta($ruta . $ok . "/");
                $this->crearRutaCarpeta($rutazip . $ok . "/");

                $respuesta["datos"] = $this->AdministradorModel->buscar($this->tabla, '*', 'id=' . $ok, 'row');
                echo json_encode($respuesta);
            } else {
                $respuesta["error"] = 'usuario ya existe';
                echo json_encode($respuesta);
            }
        } else {
            /**
             * De la contrario edita el registro
             */
            $id = $data["id"];
            unset($data["idcarries"]);
            unset($data["idcanal"]);
            $where = "id=" . $id;
            $datos = $this->AdministradorModel->Buscar($this->tabla, "clave", $where, 'row');
            /**
             * Valida que las claves sean iguales para borrarlo
             */
            if ($data["clave"] === $datos["clave"]) {
                unset($data["clave"]);
            } else {
                $data["clave"] = base64_encode($data["clave"]);
            }

            $data = $this->asignaNull($data);
            /**
             * Iteracion para obtener para preferencas
             */
            $canal = '';
            foreach ($canales as $i => $value) {
                $canal .= ($canal == '') ? '' : ',';
                $canal .= $value;
            }

            $data["preferencias"] = $canal;
            $this->AdministradorModel->update($this->tabla, $id, $data);

            $respuesta["datos"] = $this->AdministradorModel->buscar($this->tabla, '*', 'id=' . $id, 'row');
            $respuesta["preferencias"] = $canal;
            echo json_encode($respuesta);
        }
    }

    /**
     * Metodo para borrar pasandole el id por POST
     */
    public function borrar() {
        $id = $this->input->post("id");
        $this->AdministradorModel->delete($this->tabla, $id);
    }

    /**
     * Lista todos los usuarios
     */
    public function obtenerUsuarios() {
        $datos = $this->AdministradorModel->datosUsuarios();
        echo json_encode($datos);
    }

    public function cargaTabla() {
        $draw = 1;
        $datos = $this->AdministradorModel->datosUsuarios();
        $respuesta = $this->dataTable($datos);
        $respuesta["draw"] = 1;
        echo json_encode($respuesta);
    }

    /**
     * Metodo para listar los datos y las preferencias del usuario
     */
    public function cargaDatos() {
        $data = $this->input->post();

        $ok = ($this->input->post("ver") == 'ok') ? 'ok' : NULL;
        $where = "id=" . $data["id"];
        $datos = $this->AdministradorModel->Buscar($this->tabla, "*", $where, 'row');

        $prefencias = ($datos["preferencias"] != NULL) ? explode(",", $datos["preferencias"]) : NULL;

        foreach ($prefencias as $i => $valor) {
            $prefer[" canal_" . $i] = $valor;
        }

        unset($datos["preferencias"]);
        $datos["clave"] = ($ok == 'ok') ? base64_decode($datos["clave"]) : $datos["clave"];

        $respuesta["datos"] = $datos;
        $respuesta["preferencia"] = $prefer;
        $respuesta["permissions"] = $this->getDataPermission($data["id"]);
        echo json_encode($respuesta);
    }

    public function getDataPermission($user_id = NULL) {

        $campos = "p.id,p.title as text,u.id selected,coalesce(p.node_id::text,'#') parent,
                CASE WHEN p.nivel=2 THEN 'glyphicon glyphicon-lock' ELSE '' END icon,
                CASE WHEN u.id IS NOT NULL THEN 'checked' ELSE '' END as state,p.nivel";
        $join = " LEFT JOIN permission_users u ON u.permission_id=p.id ";

        if ($user_id != null) {
            $join .= " AND u.user_id=" . $user_id;
        }
        $permission["core"]["data"] = $this->AdministradorModel->buscar("permission p  " . $join, $campos);
        $permission["plugins"] = array("checkbox");

        foreach ($permission["core"]["data"] as $i => $value) {
            if ($value["state"] == 'checked' && $value["nivel"] == 2) {
                $permission["core"]["data"][$i]["state"] = array("selected" => true);
            }
        }
        return $permission;
    }

    public function updatePermission() {
        $in = $this->input->post();
        $sql = "DELETE from permission_users  where user_id=" . $in["user_id"] . " and permission_id not in(select id from permission where nivel=1)";
        $this->AdministradorModel->ejecutar($sql, "delete");

        $cont = 0;

        foreach ($in["ids"] as $value) {
            $new["user_id"] = $in["user_id"];
            $new["permission_id"] = $value;
            $this->AdministradorModel->insert("permission_users", $new);
            $cont++;
        }
        
        $ids = implode(",", $in["ids"]);
        

        $node = $this->AdministradorModel->buscar("permission", "node_id", "id IN($ids) and node_id is not null group by node_id");

        
        foreach ($node as $value) {
            $new["user_id"] = $in["user_id"];
            $new["permission_id"] = $value["node_id"];

            $res = $this->AdministradorModel->buscar("permission_users", "*", "user_id=" . $in["user_id"] . " and permission_id=" . $value["node_id"]);
            if ($res == false) {
                $this->AdministradorModel->insert("permission_users", $new);
            }
        }




        echo json_encode(array("success" => true, "new" => $cont));
    }

    public function permisos() {
        $data["vista"] = "administrador/admin_permissions";
        $data["main"] = $this->AdministradorModel->buscar("permission", "*", "nivel=1");

        $this->load->view("template", $data);
    }

    public function getPermission() {
        $campos = "id,title as text, coalesce(node_id::text,'#') parent,CASE WHEN nivel=2 THEN 'glyphicon glyphicon-lock' ELSE '' END icon";
        $permission["core"]["data"] = $this->AdministradorModel->buscar("permission", $campos);
        $permission["plugins"] = array("checkbox");
        echo json_encode(array("success" => true, "data" => $permission));
    }

    public function getNode() {
        $in = $this->input->post();
        $where = "id=" . $in["node_id"];
        $node = $this->AdministradorModel->buscar("permission", '*', $where, "row");
        echo json_encode(array("success" => true, "data" => $node));
    }

    public function updatePermissionId() {
        $in = $this->input->post();
        $id = $in["id"];
        unset($in["id"]);
        $node = $this->AdministradorModel->update("permission", $id, $in);
        echo json_encode(array("success" => true));
    }

}
