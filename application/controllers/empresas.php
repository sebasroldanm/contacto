<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Empresas extends MY_Controller {

    private $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
        $this->tabla = 'empresas';
    }

    /**
     * Metodo para insertar empresas
     */
    public function gestion() {
        /**
         * Se obtienen los datos que llegan por post
         */
        $data = $this->input->post();

        /**
         * Si no llega el id es una empresa nueva, de lo contrario es una empresa existente
         */
        if ($data["id"] == '') {
            unset($data["id"]);
            $where = "nit = '{$data["nit"]}'";

            $datos = $this->AdministradorModel->Buscar($this->tabla, "*", $where, 'row');
            
            if ($datos != FALSE) {
                $error["error"] = 'Nit ya existe';
                echo json_encode($error);
            } else {
                $data["activo"] = (isset($data["activo"])) ? 1 : 0;
                $id = $this->AdministradorModel->insert($this->tabla, $data);
                echo json_encode($id);
            }
        } else {
            /**
             * De lo contrario actualiza el registro
             */
            $id = $data["id"];
            $where = "id=" . $id;
            unset($data["id"]);
            $data["activo"] = (isset($data["activo"])) ? 1 : 0;
            $idres = $this->AdministradorModel->update($this->tabla, $id, $data);
            echo json_encode($id);
        }
    }

    /**
     * Metodo para obtener los datos segun el id pasado por POST
     */
    public function cargaDatos() {
        $id = $this->input->post("id");
        $where = "id=" . $id.' ORDER BY nombre';
        $datos = $this->AdministradorModel->buscar($this->tabla, "*", $where, 'row');
        echo json_encode($datos);
    }

    /**
     * Metodo para borrar segun el "id" pasado por POST
     */
    public function borrar() {
        $id = $this->input->post("id");
        $this->AdministradorModel->delete($this->tabla, $id);
    }

    /**
     * Metodo para listar todas las empresas ordenadas por "ID"
     */
    public function obtenerEmpresas() {
        $data = $this->input->post();
        $datos = $this->AdministradorModel->buscar($data["tabla"] . " ORDER BY id", '*');
        echo json_encode($datos);
    }

}
