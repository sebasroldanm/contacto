<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require_once APPPATH . 'controllers/cargaexcel.php';

class Desarrollador extends MY_Controller {

    private $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
    }

    /**
     * Metodo para cargar la vista principal de administrador
     */
    public function index() {

        $this->load->view('desarrollador/index');
    }

    public function prueba() {
        echo $this->LimpiaMensaje($prueba);
    }

    /**
     * Metodo que destruye la session de usuario y lo registra como evento en la tabla se SESSION
     */
    public function procesaArchivo() {
        $data = $this->input->post();
        $obj = new CargaExcel();
        $datos = $this->AdministradorModel->buscar("archivos", 'idbase', 'id=' . $data["idarchivo"], 'row');
        $data["idbase"] = $datos->idbase;

        $obj->cargaExcel($data);
    }

    public function correo() {
        $this->load->view('desarrollador/correo');
    }

    public function gestionCorreo() {
        $data = $this->input->post();
        $id = $data["id"];
        unset($data["id"]);
        if ($id == '') {
            $this->AdministradorModel->update("correos", '1', $data);
            $this->cargaCorreo('1');
        } else {
            $this->AdministradorModel->update("correos", $id, $data);
            $this->cargaCorreo("1");
        }
    }

    public function cargaCorreo($idext = null) {
        $id = ($idext == null) ? $this->input->post("id") : $idext;
        $where = "id=" . $id;
        $datos = $this->AdministradorModel->buscar("correos", "*", $where, 'row');
        echo json_encode($datos);
    }

    public function tablaCorreo() {
        $datos = $this->AdministradorModel->buscar("correos", "id,protocolo,puerto,usuario,host,clave", 'id=1', 'row');
        echo json_encode($datos);
    }

    public function pruebaCorreo() {
        $data = $this->input->post();
        $correo = $this->AdministradorModel->buscar("correos", '*', 'id=1', 'row');
        $config['protocol'] = $correo->protocolo;
        $config['smtp_host'] = $correo->host;
        $config['smtp_port'] = $correo->puerto;
        $config['smtp_user'] = $correo->usuario;
        $config['smtp_pass'] = $correo->clave;
        $config['smtp_timeout'] = '7';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not

        $this->load->library("email");
        $this->email->initialize($config);
        $this->email->from('Test Correo');
        $this->email->to($data["pruebacorreo"]);
        $this->email->subject('Test Correo');
        $this->email->message($data["pruebamensaje"]);
        $this->email->send();
        print_r($this->email->print_debugger());
    }

}
