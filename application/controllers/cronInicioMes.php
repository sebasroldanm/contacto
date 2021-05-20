<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronInicioMes extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
    }

    public function index() {
        /**
         * trae todos los usuarios con tipo de servicio mensual
         */
        $where = "idservicio in (select id from servicios where tiposervicio = 2)";
        $data["usuarios"] = $this->AdministradorModel->buscar('usuarios', '*', $where);

        for($i=0;$i<sizeof($data["usuarios"]);$i++)
        {
            $cambios = array("enviados" => 0, "pendientes" => 0, "fechainicio" => date("Y-m-d"));
            $this->AdministradorModel->update('usuarios',$data["usuarios"][$i]["id"], $cambios);    
            
            echo   $data["usuarios"][$i]["id"]." - ok <br>";      
        }//fiin for
        

    }
}
