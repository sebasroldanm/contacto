<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronCanales extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
        $this->load->library('smpp1');
        $this->load->library('smpp2');
        /**
         * Carga de librerias necesarioas para envio de correo
         */
        $this->load->library('Mailer');
    }

    public function index() {  
        $contador = 0;
        $suma = 0;
        $estadoA = $this->canalA('test','',null);
        $estadoE = $this->canalE('test','',null);
        $estadoB = $this->canalB('test','',null);
        
        $celulares = array("3112578441","3203776811");

        if($estadoA == 'CAIDA')
           $this->canalE('envio','POSIBLE CAIDA DEL CANAL A - FAVOR REVISAR',$celulares);
        if($estadoB == 'CAIDA')
           $this->canalE('envio','POSIBLE CAIDA DEL CANAL B - FAVOR REVISAR',$celulares);
        if($estadoE == 'CAIDA')
           $this->canalA('envio','POSIBLE CAIDA DEL CANAL E - FAVOR REVISAR',$celulares);
    }

    function canalA($accion,$mensaje,$cels)
    {
        /**
         * inicializa los parametros de la conexion SMPP
         * y abre la conexion con el servidor SMPP
         */
         $estado = '';
        $where = "id = 1";
        $data["smpp"] = $this->AdministradorModel->buscar('canales', 'host,port,usr,password,systemtype,nomenclatura', $where);

        //print_r($data["smpp"]);

        $smpphost = $data["smpp"][0]["host"];
        $smppport = $data["smpp"][0]["port"];
        $systemid = $data["smpp"][0]["usr"];
        $password = $data["smpp"][0]["password"];
        $system_type = $data["smpp"][0]["systemtype"];
        $from = "contactos";

        $smpp = new SMPPClass();
        $smpp->SetSender($from);

       $conectado = $smpp->Start($smpphost, $smppport, $systemid, $password, $system_type);

        print_r($conectado);

        if($conectado == 1)
        {
           $estado = 'CONECTADO';
           if($accion  == 'envio')
           {
                $this->enviarCorreo($mensaje);
                for($i=0;$i<sizeof($cels);$i++)                    
                    $smpp->Send($cels[$i],$mensaje);
           } 
        }
        else   
           $estado = 'CAIDA';
        
        return $estado;
    }

    function canalB($accion,$mensaje,$cels)
    {
        /**
         * inicializa los parametros de la conexion SMPP
         * y abre la conexion con el servidor SMPP
         */
        $where = "id = 5";
        $data["smpp"] = $this->AdministradorModel->buscar('canales', 'host,port,usr,password,systemtype,nomenclatura,sourceaddress,rango1,rango2,ultimo', $where);

        //print_r($data["smpp"]);

        $smpphost = $data["smpp"][0]["host"];
        $smppport = $data["smpp"][0]["port"];
        $systemid = $data["smpp"][0]["usr"];
        $password = $data["smpp"][0]["password"];
        $system_type = $data["smpp"][0]["systemtype"];
        $from = $data["smpp"][0]["sourceaddress"];
        $smpp = new SMPP($smpphost,$smppport);
        $conectado = $smpp->bindTransceiver($systemid,$password);

         echo "<hr>" ;
          print_r($conectado);
        
        if($conectado)
        {
           $estado = 'CONECTADO';
           if($accion  == 'envio')
           {
                $this->enviarCorreo($mensaje);                    
                for($i=0;$i<sizeof($cels);$i++)                    
                    $smpp->Send($cels[$i],$mensaje);
           } 
        }
        else   
           $estado = 'CAIDA';
        
        return $estado;
    }

    function canalE($accion,$mensaje,$cels)
    {
        /**
         * inicializa los parametros de la conexion SMPP
         * y abre la conexion con el servidor SMPP
         */
        $where = "id = 8";
        $data["smpp"] = $this->AdministradorModel->buscar('canales', 'host,port,usr,password,systemtype,nomenclatura,sourceaddress,rango1,rango2,ultimo', $where);

        //print_r($data["smpp"]);

        $smpphost = $data["smpp"][0]["host"];
        $smppport = $data["smpp"][0]["port"];
        $systemid = $data["smpp"][0]["usr"];
        $password = $data["smpp"][0]["password"];
        $system_type = $data["smpp"][0]["systemtype"];
        $from = 87462;
        
        $smpp = new SMPP($smpphost,$smppport);
        $conectado = $smpp->bindTransceiver($systemid,$password);

         echo "<hr>" ;
          print_r($conectado);
        
        if($conectado)
        {
           $estado = 'CONECTADO';
           if($accion  == 'envio')
           {
                $this->enviarCorreo($mensaje);                    
                for($i=0;$i<sizeof($cels);$i++)
                    $smpp->sendSMS($from,$cels[$i],$mensaje);                    
           } 
        }
        else   
           $estado = 'CAIDA';

        $smpp->close();
        
        return $estado;
    }
    
    function enviarCorreo($mensaje)
    {
        $this->load->library("email");
        /**
         * Se obtiene el correo de todos los usuarios que esten activos y que tenga contenido
         */
        $usuarios = $this->AdministradorModel->buscar("usuarios", 'id,usuario,nombre,correos', "correos!='' OR correos!=NULL");
//        $usuarios = $this->AdministradorModel->buscar("usuarios", '*', "id=21");
        //cargamos la libreria email de ci
//        $this->load->library("email");
        /**
         * Configuracion de la cuenta de correo
         */
        $correo = $this->AdministradorModel->buscar("correos", '*', 'id=1', 'row');
        $config['protocol'] = $correo["protocolo"];
        $config['smtp_host'] = $correo["host"];
        $config['smtp_port'] = $correo["puerto"];
        $config['smtp_user'] = $correo["usuario"];
        $config['smtp_pass'] = $correo["clave"];
        $config['smtp_timeout'] = '7';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not
        
        /**
         * se inicializa la configuracion de correo 
         */
        $this->email->initialize($config);
        /**
         * Nombre de quien envia
         */
        $this->email->from('reportes contactosms');
        /**
         * Agrega los correos para
         */
        //$this->email->to("oskarcuervo@gmail.com,jpinedom@hotmail.com,ojoven@contactosms.com.co,servicioalcliente@contactosms.com.co");
        $this->email->to("oskarcuervo@gmail.com");
        /**
         * El asunto
         */
        $this->email->subject($mensaje);
        $sms = "<br>SE RECOMIENDA REVISAR EL CANAL<br> EN ESTA ITERACION NO FUE POSIBLE LA CONEXION ...<br>";

        /**
         * Agrega el mensaje
         */
        $this->email->message($sms);
        /**
         * Envia el mensaje
         */
        $this->email->send();
    }
}
