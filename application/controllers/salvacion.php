<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Salvacion extends MY_Controller {

    private $tabla;

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
        
    }

    
    public function index() {
        $where="idbase = 595 and fecha > '2014-05-20 11:00:00'";
        $data = $this->AdministradorModel->obtenerCampos('errores', 'numero,mensaje,nota',$where);
        
        foreach ($data as $value) {
            $nota=(isset($value["nota"]))?$value["nota"]:'';
            $string="http://www.appcontacto.com.co/wsurl?usuario=castmm&clave=CastM3M*$&numero=".$value["numero"]."&mensaje='".trim($value["mensaje"])."'&nota='".$nota."'";
            //$string="http://www.appcontacto.com.co/wsurl?usuario=castmm&clave=CastM3M*$&numero=".$value["numero"]."&mensaje='".trim($value["mensaje"])."'&$nota";
            echo $string."<br>";
//            $pagina = file_get_contents($string);
//            var_dump($pagina);
        }
        
    }
   
}
