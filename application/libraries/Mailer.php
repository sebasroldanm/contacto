<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH.'third_party/PHPMailer/class.phpmailer.php';  

class Mailer extends PHPMailer{
    
    public function __construct($exceptions = false) {
        parent::__construct($exceptions);
    }
    
}