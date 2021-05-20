<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Documentos extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
    }
    /**
     * Index para cargar el documento pdf
     */
    public function index(){
        /**
         * Se carga la libreria pdf
         */
        $this->load->library("pdf");
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A4', true, 'UTF-8', false);
        /**
         * Se configura la pagina
         */
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Contacto SMS');
        $pdf->SetTitle('formato');
        $pdf->SetSubject('Manual de Usuario');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        /**
         * Se agrega una pagina
         */
        $pdf->AddPage();
        /**
         * Se adicciona HTML
         */
        $html = '<h1 style="align:text-center">Manual de Usuario</h1><br>';
        $pdf->writeHTML($html, true, false, true, false, '');
        $html = '<p>Manual completo......</p>'
                . '<ol>'
                . '<li><b>Introducción<li>'
                . '</ol>';
        
        $pdf->writeHTML($html, true, false, true, false, '');
        /**
         * Se muestra pdf con el html
         */
        $pdf->Output('Manual Usuario.pdf', 'I');
    }
    
    function prueba(){
        echo "preuba";
    }
    
}
