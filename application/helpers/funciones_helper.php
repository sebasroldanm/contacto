<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if(!function_exists("print_x")){
    
    function print_x($dato){
        echo "<pre>";
        print_r($dato);
        echo "</pre>";
    }
}

