<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WsUrl2 extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("CargaexcelModel");
    }

    public function index() {
        print_r($_GET);
        $numero = $_GET["numero"];
        $mensaje = $_GET["mensaje"];

        $errsms = '';
        $errno = '';
        $datos["numero"] = (strlen($numero) == 10) ? $numero : FALSE;
        $mensaje = (strlen($mensaje) < 160) ? $this->LimpiaMensaje($mensaje) : FALSE;

        if ($datos["numero"] == FALSE && !is_numeric($datos["numero"])) {
            $errno = "Error en el numero ";
        }

        if ($mensaje != FALSE) {
            $datos["mensaje"] = $mensaje;
        } else {
            $errsms = "Mensaje largo";
        }

        if ($errno == '' && $errsms == '') {
            $idcarrie = $this->CargaexcelModel->obtenerCamposId('carries', 'id', "prefijos like '%" . substr($datos["numero"], 0, 2) . "%'");
            $datos["nota"] = $_GET["nota"];
            $datos["estado"] = 2;
            $datos["orden"] = 1;
            $datos["cargue"] = 'url';
            $datos["idcarrie"] = $idcarrie->id;
            $this->CargaexcelModel->insert('registros', $datos);
            print_r($datos);
        } else {
            $error["numero"] = $errno;
            $error["mensaje"] = $errsms;
            $error["tam"] = strlen($errsms);
            print_r($error);
        }
    }

}
