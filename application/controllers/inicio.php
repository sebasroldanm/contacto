<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inicio extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("LoginModel");
    }

    public function index() {

        /**
         * Se obtiene los datos necesarios para mostrar en la pantalla principal
         */
        $cantidad = $this->LoginModel->CupoActual();
        $empresa = $this->session->userdata("idempresa");
        $data["usuario"] = $this->session->userdata("usuario");
        $datos = $this->LoginModel->buscar('empresas', 'nombre', 'id=' . $empresa, 'row');
        $data["empresa"] = $datos["nombre"];
        $menu = $this->LoginModel->buscar("perfiles", '*', 'id=' . $this->session->userdata("idperfil"), 'row');
        $data["menu"] = $this->cargaMenu($menu["menu"]);
        $data["disponible"] = (isset($cantidad["disponible"])) ? $cantidad["disponible"] : 0;

        /**
         * Se carga la vista
         */
        $data["vista"] = "home/index";
        $this->load->view("template", $data);
    }

    /**
     * metodo para cargar el menu que tiene asignado cada perfil
     * @param string $ruta
     * @return type
     */
}
