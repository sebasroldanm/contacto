<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . "/third_party/smpp4/smppclass.php";

Class Smpp4 extends SMPP{
    
    public function __construct() {
        parent::__construct();
    }
}