<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronResumenes extends MY_Controller {

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
        //$hoy = '2014-11-23';
        echo $hoy."<br>";
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

                $where = "estado = '1' AND idbase IN ("
                        . "SELECT id FROM bases WHERE idusuario = ".$data["usuarios"][$i]["id"]." AND "
                        . "fecha >= '".$hoy." 00:00:00' AND fecha <= '".$hoy." 23:59:59') "
                        . "group by 1";
                $data["registros"] = $this->AdministradorModel->buscar('registros', 'idcarrie, COUNT(*)', $where);

                for($j=0;$j<sizeof($data["registros"]);$j++)
                {
                    //pregunta si hay registros en la tabla de resumenes
                    $where = " idusuario = ".$data["usuarios"][$i]["id"]." AND fecha = '".$hoy."' "
                            . "AND  idcarrie = ".$data["registros"][$j]["idcarrie"];
                    $data["resumen"] = $this->AdministradorModel->buscar('resumenes', 'id,cantidad', $where);
                    
                    $tam = sizeof($data["resumen"]);
                    if($tam == 0)
                    {
                        $datos = array("idusuario" => $data["usuarios"][$i]["id"],
                                       "idcarrie" => $data["registros"][$j]["idcarrie"], 
                                       "fecha" => $hoy, "cantidad" => $data["resumen"][0]["cantidad"]);  
                        $this->AdministradorModel->insert('resumenes', $datos);
                    }
                    else if($tam == 1)   
                    {
                        if($data["registros"][$j]["count"] != $data["resumen"][0]["cantidad"])
                        {
                            echo "<br>".$data["registros"][$j]["count"]."-".$data["resumen"][0]["cantidad"]."<br>";
                            $nuevo = $data["registros"][$j]["count"];
                            $cambios = array("cantidad" => $nuevo);
                            $this->AdministradorModel->update('resumenes', $data["resumen"][0]["id"], $cambios);
                        }
                    } 
                    else
                    {
                        for($k=0;$k<$tam;$k++)
                           $this->AdministradorModel->delete('resumenes', $data["resumen"][$k]["id"]);

                        $datos = array("idusuario" => $data["usuarios"][$i]["id"],
                                       "idcarrie" => $data["registros"][$j]["idcarrie"], 
                                       "fecha" => $hoy, "cantidad" => $data["resumen"][0]["cantidad"]);  
                        $this->AdministradorModel->insert('resumenes', $datos);
                    }     
                }
                
                //$cambios = array("enviados" => $data["registros1"][0]["count"], "pendientes" => $data["registros2"][0]["count"]);
                //$this->AdministradorModel->update('usuarios',$data["usuarios"][$i]["id"], $cambios);            
                echo $data["usuarios"][$i]["id"]."<br>";
                echo "<hr>";
            }
        }//fiin for
        

    }
}
