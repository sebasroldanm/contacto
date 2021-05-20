<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronenvio extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
        $this->load->library('smpp2');
    }

    public function index() {
        $smpphost = '192.168.32.4';
        $smppport = '7903';
        $systemid = 'CoreTPCA';
        $password = 'C0nt4c'; 
        $from = '87462';        
        
         $numero = $_GET["numero"];
         $msj = $_GET["mensaje"];        

        $this->benchmark->mark('codigo_inicio');

        $smpp = new SMPP($smpphost,$smppport);
        $conectado = $smpp->bindTransceiver($systemid,$password);

        print_r($conectado);
        
        if($numero and $from and $msj)
            $resultado = $smpp->sendSMS($from,$numero, $msj);
        echo "<hr>" . $resultado . "<hr>";
        
        $this->benchmark->mark('codigo_fin');
        echo $this->benchmark->elapsed_time('codigo_inicio', 'codigo_fin')."<br>";

        /*$systemid = 'CoreTalk1';

        $smpp = new SMPP($smpphost,$smppport);
        $conectado = $smpp->bindTransceiver($systemid,$password);

        print_r($conectado);

        $resultado = $smpp->sendSMS($from,"3112578441", "PRUEBA NUMERO CORTOOOO");
        echo "<hr>" . $resultado . "<hr>";

        $systemid = 'CoreTPCA';
        $smpp = new SMPP($smpphost,$smppport);
        $conectado = $smpp->bindTransceiver($systemid,$password);

        print_r($conectado);

        $resultado = $smpp->sendSMS($from,"3112578441", "PRUEBA NUMERO CORTOOOO");
        echo "<hr>" . $resultado . "<hr>";

        $systemid = 'ColTrade';
        $password = 'S1gn4';
        $smpp = new SMPP($smpphost,$smppport);
        $conectado = $smpp->bindTransceiver($systemid,$password);

        print_r($conectado);

        $resultado = $smpp->sendSMS($from,"3112578441", "PRUEBA NUMERO CORTOOOO- final");
        echo "<hr>" . $resultado . "<hr>";

        /*$smpp = new SMPPClass();
        $smpp->SetSender($from);
        $conectado = $smpp->Start($smpphost, $smppport, $systemid, $password, "Avantel");
        print_r($conectado);*/
   } 

 
    
}
