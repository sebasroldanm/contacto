<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronContadores extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
    }

    public function index() {
        /**
         * trae todos los usuarios
         */
        $data["usuarios"] = $this->AdministradorModel->buscar('usuarios', '*', null);

        $hoy = date("Y-m-d");
        //$hoy = '2014-12-15';
        for($i=0;$i<sizeof($data["usuarios"]);$i++)
        {
            /**
             * trae las bases cargadas en el dia por cada usuario
             */
            $where = "fecha >= '".$hoy." 00:00:00' AND fecha <= '".$hoy." 23:59:59' "
                    . "AND idusuario = ".$data["usuarios"][$i]["id"];
            $data["bases"] = $this->AdministradorModel->buscar('bases', '*', $where);
            if(COUNT($data["bases"]) > 0)
            {
                $suma1 = $suma2 = $suma3 = 0;
                for($j=0;$j<sizeof($data["bases"]);$j++)
                {
                    $where = "estado = '1' AND idbase = ".$data["bases"][$j]["id"];
                    $data["registros1"] = $this->AdministradorModel->buscar('registros', 'COUNT(*)', $where);
                    $suma1 += $data["registros1"][0]["count"];

                    $where = "estado = '2' AND idbase = ".$data["bases"][$j]["id"];
                    $data["registros2"] = $this->AdministradorModel->buscar('registros', 'COUNT(*)', $where);
                    $suma2 += $data["registros2"][0]["count"];

                    $where = "estado = '3' AND idbase = ".$data["bases"][$j]["id"];
                    $data["registros3"] = $this->AdministradorModel->buscar('registros', 'COUNT(*)', $where);
                    $suma3 += $data["registros3"][0]["count"];

                    //cambia lso datos del registro de bases
                    $total = $data["registros1"][0]["count"]+$data["registros2"][0]["count"]+$data["registros3"][0]["count"];
                    $cambios = array("registros" => $total, "errores" => $data["registros3"][0]["count"],
                                     "enviados" => $data["registros1"][0]["count"]);
                    $this->AdministradorModel->update('bases',$data["bases"][$j]["id"], $cambios);            

                }

                $where = "estado = '1' AND idbase IN ("
                        . "SELECT id FROM bases WHERE idusuario = ".$data["usuarios"][$i]["id"]." AND "
                        . "fecha >= '".$data["usuarios"][$i]["fechainicio"]." 00:00:00' AND fecha <= '".$hoy." 23:59:59')";
                $data["registros1"] = $this->AdministradorModel->buscar('registros', 'COUNT(*)', $where,'debug');
                
                $where = "estado = '2' AND idbase IN ("
                        . "SELECT id FROM bases WHERE idusuario = ".$data["usuarios"][$i]["id"]." AND "
                        . "fecha >= '".$data["usuarios"][$i]["fechainicio"]." 00:00:00' AND fecha <= '".$hoy." 23:59:59')";
                $data["registros2"] = $this->AdministradorModel->buscar('registros', 'COUNT(*)', $where);

                $cambios = array("enviados" => $data["registros1"][0]["count"], "pendientes" => $data["registros2"][0]["count"]);
                $this->AdministradorModel->update('usuarios',$data["usuarios"][$i]["id"], $cambios);            
                echo $data["usuarios"][$i]["id"]."<br>";
                echo $suma1."<br>";
                echo $suma2."<br>";
                echo $suma3."<br>";
                echo "<hr>";
            }
        }//fiin for
        

    }
}
