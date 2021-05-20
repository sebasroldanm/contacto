<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SendFast extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("SendfastModel");
        $this->tabla = 'carries';
    }

    public function index() {

        $data["vista"] = 'send/index';
        $this->load->view('template', $data);
    }

    public function receiveNumbers() {
        $data = $this->input->post();
        $resp = $this->SendfastModel->SaveRecord($data);
        echo json_encode($resp);
    }

    public function createFile() {
        $data = $this->input->post();
        $disp = $this->SendfastModel->CupoActual();

//        echo "<pre>";print_r($data);exit;

        if ($disp["disponible"] > $data["quantitynumbers"]) {
            $where = "estado = 1 AND id='" . $this->session->userdata("idusuario") . "'";
            $user = $this->SendfastModel->Buscar('usuarios', '*', $where, 'row');
            $data["preferences"] = $user["preferencias"];
            $data["note"] = $data["note"];
            $state = ($this->session->userdata("idusuario") == '4') ? 33 : 2;
            $data["state"] = $state;
            $data["idser"] = $this->session->userdata("idusuario");
            $file = FCPATH . 'tmp/carga' . $this->session->userdata("idusuario") . '_' . date("YmdHi") . '.json';
            $resp["path"] = $file;
            $resp["idbase"] = $this->SendfastModel->createBase($data);
            $data["idbase"] = $resp["idbase"];
            $json_string = json_encode($data);
            file_put_contents($file, $json_string);

            $resp["status"] = false;
        } else {
            $resp["mgs"] = "No tienes cupo suficiente";
            $resp["status"] = false;
        }
        echo json_encode($resp);
    }

    public function getCupo() {
        $user = $this->SendfastModel->buscar("usuarios", 'concatena', "id=" . $this->session->userdata("idusuario"), "row");
        $res["concatena"] = $user["concatena"];
        $res["cupo"] = $this->SendfastModel->CupoActual();
        echo json_encode($res);
    }

    public function getQuantity() {
        $data = $this->input->post();
        print_r($data);
//        $res["count"] = $this->SendfastModel->getQuantityBase($data["path"]);
//        $total = $this->SendfastModel->buscar("bases", "registros", 'id=' . $this->session->userdata("base"), 'row');
//        $res["total"] = $total["registros"];
//        if ($res["count"] > 0) {
//            $res["porcentaje"] = ($res["count"] / $res["total"]) * 100;
//        }else{
//            $res["porcentaje"] = 0;
//        }
//        
//        echo json_encode($res);
        echo "llego";
    }

}
