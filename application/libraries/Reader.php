<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "/third_party/Excel/reader.php";

class Reader extends Spreadsheet_Excel_Reader {

    public function __construct() {
        parent::__construct();
    }

}
