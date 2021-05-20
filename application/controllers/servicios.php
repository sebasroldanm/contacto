<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Servicios extends MY_Controller {

    private $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
        $this->tabla = 'servicios';
    }

    /**
     * metodo para agregar y editar servicio
     */
    public function gestion() {
        $data = $this->input->post();
        if ($data["id"] == null) {
            $data["maximo"] = filter_var($data["maximo"], FILTER_SANITIZE_NUMBER_INT);
            unset($data["id"]);
            $id = $this->AdministradorModel->insert($this->tabla, $data);
            echo json_encode($id);
        } else {
            $id = $data["id"];
            $where = "id=" . $id;
            unset($data["id"]);
            $data["acumula"] = (isset($data["acumula"])) ? 1 : 0;
            $idres = $this->AdministradorModel->update($this->tabla, $id, $data);
            $this->cargaTabla();
        }
    }

    /**
     * Metodo para obtener datos segun el id pasado por post
     */
    public function cargaDatos($idext = NULL) {
        $id = ($idext == NULL) ? $this->input->post("id") : $idext;
        $where = "id=" . $id;
        $datos = $this->AdministradorModel->buscar($this->tabla, "*", $where, 'row');
        echo json_encode($datos);
    }

    /**
     * Metodo para borrar segun el id pasando por POST
     */
    public function borrar() {
        $id = $this->input->post("id");
        $this->AdministradorModel->delete($this->tabla, $id);
    }

    /**
     * metodo para obtener servicios
     */
    public function obtenerServicios() {
        $datos = $this->AdministradorModel->datosServicios();
        echo json_encode($datos);
    }

    public function cargaTabla() {
        $draw = 1;
        $campos = "*";
        $datos = $this->AdministradorModel->buscar('servicios order by nombre ASC', $campos);
        $respuesta = $this->dataTable($datos);
        $respuesta["draw"] = 1;
        echo json_encode($respuesta);
    }

}
