<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . "/third_party/smpp/smppclass.php";

Class Smpp extends SMPPClass{
    
    public function __construct() {
        parent::__construct();
    }
}