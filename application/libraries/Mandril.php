<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'third_party/mandril/Mandrill.php';  

class Mandril extends Mandrill{
    
    public function __construct($apikey = null) {
        parent::__construct($apikey);
    }
    
}