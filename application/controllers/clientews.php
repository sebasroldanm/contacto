<?php

/* * *******************************************************************************
 * @AUTOR:            ULTIMINIO RAMOS GALAN.
 * @SISTEMA:          CI_training.
 * @FECHA:            17/07/2010, 05:41:16 PM.
 * @ARCHIVO:          cliente_nusoap.php
 * @DESCRIPCION:      Controlador.
 * @Encoding file:    UTF-8
 * Notas:             Convenciones de nombres de archivos, clases, metodos,
 *                    variables, estructuras de control {}, manejo de operadores,
 *                    etc, son adoptadas segun la guia de referencia de
 *                    CodeIgniter.
 * ****************************************************************************** */
if (!defined('BASEPATH'))
    exit('No se permite el acceso directo a las p&aacute;ginas de este sitio.');

class ClienteWs extends CI_Controller {

    function __construct() {
        parent::__construct();

        $this->load->library('nusoap');
        $this->nusoap_server=new soap_client("http:90.69.200.146/contactosms2");
    }

// end Constructor

    function index() {
         // Same as application/libraries/nusoap/nusoap.php
         $n_params = array('name' => 'My Name', 'email' => 'my@email.adr');
         $client = new nusoap_client('http://190.69.200.1467contactosms2/wsxml');
         $result = $client->call('addnumbers', $n_params);
         echo $result;
    }

}

// end Class
