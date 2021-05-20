<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Canales extends MY_Controller {

    private $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
        $this->tabla = 'canales';
    }

    /**
     * Metodo para insertar canales
     */
    public function gestion() {
        /**
         * Se obtienen los datos que llegan por post
         */
        $data = $this->input->post();

        /**
         * Si no llega el id es un canal nuevo, de lo contrario es una canal existente
         */
        if ($data["id"] == null) {
            unset($data["id"]);
            $id = $this->AdministradorModel->insert($this->tabla, $data);
            echo json_encode($id);
        } else {

            $id = $data["id"];
            $where = "id=" . $id;
            unset($data["id"]);
            $data = $this->asignaNull($data);
            $this->AdministradorModel->update($this->tabla, $id, $data);
            echo json_decode($id);
        }
    }

    /**
     * Metodo para obtener los datos segun el id pasado por POST
     */
    public function cargaDatos() {
        $id = $this->input->post("id");
        $where = "id=" . $id . " ORDER BY id";
        $datos = $this->AdministradorModel->buscar($this->tabla, "*", $where,'row');
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
    public function cargaTabla() {
        
        $draw = 1;
        $campos = "*";
        $datos = $this->AdministradorModel->buscar("canales ORDER BY nomenclatura", '*');
        $respuesta = $this->dataTable($datos);
        $respuesta["draw"] = 1;
        echo json_encode($respuesta);
    }

}
