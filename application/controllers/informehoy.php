<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class informehoy extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
    }
    /**
     * Metodo para cargar la vista de informes de hoy
     */
    public function index(){
        $this->load->view('informes/hoy');
    }
    
}
