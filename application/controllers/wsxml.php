<?php

/* * *******************************************************************************
 * @AUTOR:            ULTIMINIO RAMOS GALAN.
 * @SISTEMA:          CI_training.
 * @FECHA:            19/07/2010, 01:04:51 PM.
 * @ARCHIVO:          servidor_nusoap.php
 * @DESCRIPCION:      Controlador.
 * @Encoding file:    UTF-8
 * Notas:             Convenciones de nombres de archivos, clases, metodos,
 *                    variables, estructuras de control {}, manejo de operadores,
 *                    etc, son adoptadas segun la guia de referencia de
 *                    CodeIgniter.
 * ****************************************************************************** */
if (!defined('BASEPATH'))
    exit('No se permite el acceso directo a las p&aacute;ginas de este sitio.');

class WsXml extends MY_Controller {

    function __construct() {
        parent::__construct();

        // Libreria personalizada que hicimos previamente para integrar la clase NuSoap con CI
        $this->load->library('nusoap');

        parent::__construct();
        $ns = "http://" . $_SERVER['HTTP_HOST'] . "/index.php/wsxml/";
        $this->nusoap_server = new soap_server(); // create soap server object
        $this->nusoap_server->configureWSDL("SOAP Server Using NuSOAP in CodeIgniter", $ns); // wsdl cinfiguration
        $this->nusoap_server->wsdl->schemaTargetNamespace = $ns; // server namespace
    }

// end Constructor

    function index() {

//        $_SERVER['QUERY_STRING'] = '';
//
//        if ($this->uri->segment(3) == 'wsdl') {
//            $_SERVER['QUERY_STRING'] = 'wsdl';
//        } // endif
//
//        $this->NuSoap_server->service(file_get_contents('php://input'));


        function addnumbers($a, $b) {
            $c = $a + $b;
            return $c;
        }

        $this->nusoap_server->service(file_get_contents("php://input")); // read raw data from request body
//        $HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
//        $this->NuSoap_server->service($HTTP_RAW_POST_DATA);
//        $this->NuSoap_server->service(file_get_contents("php://input"));
    }

// end function

    function obtenerUsuario($id_usuario) {
        $row = array(
            array(
                'id' => $id_usuario
                , 'nombre' => 'Ultiminio'
                , 'apellidos' => 'Ramos Galan'
            )
        );

        /* Este bloque muestra como se puede trabajar con un modelo de CodeIgniter */
        /*
          // Obtener el superobjeto de CodeIgniter, get_instance() sirve para tener acceso a todos los métodos del framework
          $CI =& get_instance();

          $CI->load->model('mod_cruds');

          $obj_db_result = $CI->mod_cruds->traer_usuario($id_usuario);

          $row = array(
          array(
          'id' => $obj_db_result[0]->id_meta_campo
          , 'campo' => $obj_db_result[0]->meta_campo
          , 'etiqueta' => $obj_db_result[0]->meta_etiqueta
          )
          );
         */

        return $row;
    }

// end function
}

// end Class