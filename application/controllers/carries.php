<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Carries extends MY_Controller {

    private $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
        $this->tabla = 'carries';
    }

    /**
     * Metodo para registrar carriers validando si existe
     */
    public function gestion() {
        $data = $this->input->post();
        $id = $data["id"];
        unset($data["id"]);

        if ($id == '') {
            $where = "prefijos ILIKE '%{$data["prefijos"]}%'";
            $valida = $this->AdministradorModel->buscar($this->tabla, "*", $where, 'row');

            if (empty($valida)) {
                $id = $this->AdministradorModel->insert($this->tabla, $data);
                echo json_encode($id);
            } else {
                $respuesta["error"] = 'Carrier ya existe';
                echo json_encode($respuesta);
            }
        } else {
            $where = "id=" . $id;
            $idres = $this->AdministradorModel->update($this->tabla, $id, $data);

            if ($idres == $id) {
                $data["datos"] = $this->AdministradorModel->datosCarries($parametros = '', $carries = '', 'id=' . $id);
                echo json_encode($data);
            }
        }
    }

    /**
     * Metodo para obtener los datos segun el id pasado por POST
     */
    public function cargaDatos() {
        $id = $this->input->post("id");
        $where = "id=" . $id;
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


    public function cargaTabla() {
        $draw = 1;
        $campos = "*";
        $datos = $this->AdministradorModel->buscar('carries', $campos);
        $respuesta = $this->dataTable($datos);
        $respuesta["draw"] = 1;
        echo json_encode($respuesta);
    }

}
