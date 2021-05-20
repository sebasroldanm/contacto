<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class InfoCliente extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
    }

    public function index() {

        /**
         * Se obtiene los datos para mostrar la informacion del cliente
         */
        $data["usuario"] = $this->session->userdata("usuario");
        $data["nombre"] = $this->session->userdata("nombre");

        $where = "id = '" . $this->session->userdata("idusuario")."' ";
        $dato["usuario"] = $this->AdministradorModel->buscar('usuarios', 'idservicio,enviados,pendientes', $where);

         $where = "id = '" . $dato["usuario"][0]["idservicio"]."' ";
         $dato["servicio"] = $this->AdministradorModel->buscar('servicios', 'maximo', $where);

        $data["cupo"] = $dato["servicio"][0]["maximo"];
        $data["restantes"] = $data["cupo"] - ($dato["usuario"][0]["enviados"] + $dato["usuario"][0]["pendientes"]);
        
        /**
         * Se cargan los datos a la vista
         */
        $data["vista"] = "informes/infocliente";
        $this->load->view("template", $data);
    }

}
