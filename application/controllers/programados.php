<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Programados extends MY_Controller {

    private $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
        $this->tabla = 'carries';
    }

    /**
     * Metodo para registrar carriers validando si existe
     */
    public function index() {
        $data["vista"] = 'programados/index';
        $this->load->view('template', $data);
    }

    public function generarInforme() {
        $data = $this->input->post();
        $idbase = ($data["idbase"] != '') ? "idbase=" . $data["idbase"] : '';
        $fecha = '';

        if ($data["inicio"] != '' && $data["final"] != '') {
            $and = ($idbase == '') ? '' : ' AND ';
            $fecha = $and . " fechaprogramado BETWEEN '" . $data["inicio"] . " 00:00' AND '" . $data["final"] . " 23:59'";
        }

        if ($this->session->userdata("idperfil") != 3) {
            
        }

        $where = $idbase . $fecha . " AND estado='2'";

        $datos = $this->AdministradorModel->buscar("registros", 'id,idbase,numero,mensaje,nota,fechaprogramado', $where, 'xdebug');

        echo json_encode($datos);
    }

    public function cancelarEnvios() {
        $data = $this->input->post();
        $valor["idbase"] = $data["idbase"];
        $update["estado"] = '7';

        $where = "id=" . $this->session->userdata("idusuario");
        $user = $this->AdministradorModel->buscar("usuarios", 'pendientes', $where, 'row');
        $where = "idbase=" . $data["idbase"] . " AND estado='2'";
        $total = $this->AdministradorModel->buscar("registros", 'count(id) total', $where, 'row');

        $pend["pendientes"] = $user["pendientes"] - $total["total"];

        $this->AdministradorModel->update("registros", $valor, $update);

        $this->AdministradorModel->update("usuarios", $this->session->userdata("idusuario"), $pend);
        $respuesta["cancelados"] = $total["total"];
        echo json_encode($respuesta);
    }

    public function CargarTabla() {
        $data = $this->input->post();

        if ($this->session->userdata("idperfil") != 3) {
            
        }

        $this->datatables
                ->select("id,idbase,numero,mensaje,nota,fechaprogramado")
                ->from("registros");


        if ($data["idbase"] != '') {
            $this->datatables->where("idbase", $data["idbase"]);
        }
//
        if ($data["inicio"] != '' && isset($data["final"])) {
            $this->datatables->where("fechaprogramado >", $data["inicio"]);
            $this->datatables->where("fechaprogramado <", $data["final"]);
        }
//      
//        $this->datatables->debug = true;
        $this->datatables->where("estado", "2");

        echo $this->datatables->generate();
    }

}
