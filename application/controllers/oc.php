<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class oc extends MY_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
    }

    public function index() {
        //print_r($_GET);
echo "estoy aqui";
$query = "INSERT INTO `sms_queue` (`sender`, `recipient`, `message`, `status`) VALUES ('55913', '3118207588', 'COLMEDICA te recuerda que antes de iniciar tu jornada laboral, debes realizar el chequeo de sintomas de COVID-19 aqui: https://bit.ly/3enMNfC', 0)";

 $datos_my["sender"] = '123';
 $datos_my["recipient"] = '3134333382';
 $datos_my["message"] = 'algo';
 $datos_my["status"] = 0;


 $this->AdministradorModel->insert('sms_queue', $datos_my, null ,'mysql');

    }
    
    function buscarBase($idempresa,$idusuario){
        $codigo = "url_{$idempresa}_{$idusuario }_".date("Y-m-d");
        
        $where="nombre like '{$codigo}%'";
        //echo $where;
        
	    $base = $this->AdministradorModel->buscar('bases','*',$where,'row');
        //var_dump($base);
        if(!isset($base["nombre"]))
        {
            $datos["idempresa"] = $idempresa;
            $datos["idusuario"] = $idusuario;
            $datos["nombre"] = $codigo." 00:00:00";
            $datos["fecha"] = date("Y-m-d H:i:s");
            $datos["registros"] = 0;
            $datos["errores"] = 0;
            $datos["ip"] = $_SERVER["REMOTE_ADDR"];
            //echo "aqui";
            $id = $this->AdministradorModel->insert('bases', $datos);
        }
        else
            $id = $base["id"];
        return $id;
    }

    function actualizaBase($idbase){
        
        $where="id = '{$idbase}'";
	    $base = $this->AdministradorModel->buscar('bases','*',$where,'row');
        
        $nuevo = $base["registros"] + 1;
        $cambios = array("registros" => $nuevo);
        $this->AdministradorModel->update('bases',$idbase, $cambios);
    }
    /**
     * Valida formato dd-mm-yyyy
     * @param type $fecha
     * @return type
     */
   function validarFecha($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        $rta[0] = $d;
        if($d != false) 
            $rta[1] = $d->format($format) == $date;
        
        if($d == FALSE)
        {
            $rta[0] = false; 
            $rta[1] = "FORMATO NO VALIDO";
        }else
        {
            $hoy = date('Y-m-d')." 00:00:00";
            $futura = date('Y-m-d H:i:s', strtotime ( '+1 year' , strtotime ( $hoy ) )) ;

            if($date < $hoy)
            {
                $rta[0] = false; 
                $rta[1] = "FECHA ANTIGUA";
            }elseif($date > $futura)
            {
                $rta[0] = false; 
                $rta[1] = "FECHA FUTURA";
            }
        }
        return $rta;
    }
}
