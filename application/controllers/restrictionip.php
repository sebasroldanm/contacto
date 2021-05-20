<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Restrictionip extends MY_Controller {

    private $tabla;

    public function __construct() {
        parent::__construct();
        
        $this->load->library("reader");
        $this->load->model("AdministradorModel");
        $this->load->model("CargaexcelModel");
        $this->tabla = "";
    }

    /**
     * Metodo para cargar la vista principal de administrador
     */
    public function index() {
        
        /**
         * Metodos que se obtiene para precargar datos en los formularios
         */
        $data["users"] = $this->AdministradorModel->buscar("usuarios ORDER BY 2", 'id,lower(nombre) nombre,usuario', '');
        
        $data["vista"] = 'administrador/restrictionip';
        $this->load->view('template', $data);
    }

    
    public function cargaTabla() {

        echo $this->datatables
                ->select("id,nombre,ip")
                ->from("vusuarios_ips")
                ->generate();
    }


    public function gestion() {
        $data = $this->input->post();
        
        $id = $data["id"];
        unset($data["id"]);
        
        $where = "ip = '" . $data["ip"] . "' and idusuario=".$_SESSION["idusuario"];
        
        $com = $this->AdministradorModel->buscar("usuarios_ips", '*', $where, 'row');

        $data["idautoriza"] = $_SESSION["idusuario"];
        $data["fecha"] = date("Y-m-d");

        if ($id == '' && $com == false) {
            $this->AdministradorModel->insert("usuarios_ips", $data);
        } else {
            $this->AdministradorModel->update("usuarios_ips", $id, $data);
        }

        echo json_encode(["success" => true]);
    }

    public function show(){
        $data = $this->input->post();
        $user = $this->AdministradorModel->buscar("usuarios_ips", '*', 'id='.$data["id"],"row");
        echo json_encode($user);
    }

    public function delete($id) {
        $com = $this->AdministradorModel->borrar("usuarios_ips", $id);
        echo json_encode(["success" => true]);
    }

}
