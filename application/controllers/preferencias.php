<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Preferencias extends MY_Controller {

    private $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
        $this->tabla = 'preferencias';
    }

    /**
     * Metodo para insertar canales
     */
    public function gestion() {
        /**
         * Se obtienen los datos que llegan por post
         */
        $data = $this->input->post();
        foreach ($data["usuarios"] as $value) {
            $where = 'id=' . $value;
            $datos = $this->AdministradorModel->Buscar('usuarios', "id", $where, 'row');
            $update["preferencias"] = implode(",", $data["pre"]);
            $this->AdministradorModel->update('usuarios', $datos["id"], $update);
        }
        echo json_encode(array("msj"=>'Operacion realizada con exito'));
    }

    /**
     * Metodo para obtener los datos segun el id pasado por POST
     */
    public function cargaDatos() {
        $id = $this->input->post("id");
        $where = "id=" . $id . " ORDER BY id";
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
    public function obtenerCanales() {
        $data = $this->input->post();
        $datos = $this->AdministradorModel->buscar($data["tabla"] . " ORDER BY id", '*');
        echo json_encode($datos);
    }

}
