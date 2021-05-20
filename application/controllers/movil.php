<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Movil extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
			$this->load->model("LoginModel");
        
    }
    /**
     * Index para cargar el documento pdf
     */
    public function index(){

	        $campos = "usuarios.id,usuarios.usuario,usuarios.nombre,emp.id as idempresa,usuarios.idperfil,usuarios.concatena,usuarios.idservicio";
        $where = " usuarios.usuario = '{$_GET["usuario"]}' AND usuarios.clave='" . base64_encode($_GET["clave"]) . "' AND usuarios.estado=1 AND emp.activo=1";
        $datos = $this->LoginModel->Buscar('usuarios JOIN empresas as emp ON usuarios.idempresa=emp.id', $campos, $where, 'row');
				if(isset($datos["id"])){
						$resultadosJson = json_encode($datos);
				}else{
						$respuesta["error"]='Usuario o clave invalido';
						$resultadosJson = json_encode($respuesta);
				}


		echo $_GET["jsoncallback"] . "(" . $resultadosJson . ");";
			
    }
    

    
}
