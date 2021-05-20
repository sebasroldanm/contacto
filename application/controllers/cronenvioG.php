<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronenvioG extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
    }

    public function index() {
        $contador = 0;
        $suma = 0;
        /**
         * se pregunta el estado del registro de cron, para establecer si se ejecuta o no
         */
        $where = "nombre = 'cronenvioG' ";
        $data["cron"] = $this->AdministradorModel->obtenerCampos('crones', 'id,ejecutado,estado,consulta', $where);

        //si no existe el registro lo ingresa
        if(COUNT($data["cron"]) == 0)
        {
            $datos["nombre"] = 'cronenvioG';
            $datos["unidad"] = 1;
            $datos["medida"] = 'minuto';
            $datos["ejecutado"] = NULL;
            $datos["estado"] = 0;
            $datos["consulta"] = 0;
            $this->AdministradorModel->insert('crones', $datos);
        } //fin if   
        else
        {
            //echo $data["cron"][0]["estado"]."---";
            //echo $data["cron"][0]["consulta"]."---";
            //evalua si esta ejecutado
            if($data["cron"][0]["estado"] == 1)
            {
                if( $data["cron"][0]["consulta"] > 20 OR $data["cron"][0]["consulta"] == null)
                {
                    $cambios = array("consulta" => 0, "estado" => 0, "ejecutado" => null);
                    $nuevo = 0;
                }
                else
                {
                    $nuevo = $data["cron"][0]["consulta"] + 1;
                    $cambios = array("consulta" => $nuevo);
                }
                $this->AdministradorModel->update('crones', $data["cron"][0]["id"], $cambios);
                echo "cron en proceso, no se debe ejecutar de nuevo";
        
                $this->registraLog('cronenvioG', 'cronenvioProcess', $nuevo);
            }    
            else
            {
                $this->cronenvioIn($data["cron"][0]["id"]);
                
                    while($contador < 55)
                    {
                        $this->benchmark->mark('codigo_inicio');
                        /**
                         * se debe consultar los pendientes, se le pone el limite estima para el Canal
                         */
                        $where = "nomenclatura = 'G' ";
                        $data["canal"] = $this->AdministradorModel->obtenerCampos('canales', 'id', $where);

                        $where = "estado = '2' AND (idcanal = ".$data["canal"][0]["id"].") "
                                . " AND (fechaprogramado <= now() or fechaprogramado is null)"
                                . "  LIMIT 40";
                        $data["pendientes"] = $this->AdministradorModel->obtenerCampos('registros', 'id,numero,mensaje,idcanal,idbase,idcarrie,nota,cargue,fechacargue', $where);
                        //print_r($data["pendientes"]);
                        $tam = sizeof($data["pendientes"]);


                        //valida si hay pendiente
                        if ($tam == 0)
                        {
                            echo $contador." - NO HAY MENSAJES PARA EL CANAL H<br>";
                            //$coeficiente = $contador % 5;
                            //if( $coeficiente == 0)
                            sleep(1);
                            $contador++;
                        }
                        else 
                        {

                                //sleep(1);
                                for ($i = 0; $i < $tam; $i++) {
                                    //elige el from
                                    if($i%2 == 0)
                                       //$from  = '85673';
                                       $from = '55913'; 
                                    else
                                       //$from  = '85674';   
                                        $from = '55914';
                                    
                                    //2. enviar mensaje SMPP
                                    $data["pendientes"][$i]["mensaje"] = trim($data["pendientes"][$i]["mensaje"]);
                                    echo "<br>".$i . " - enviando mensaje " . $data["pendientes"][$i]["numero"] . " <br>";
                                    $datos = 0;
                                   
                                    $datos_my["sender"] = $from;
                                    $datos_my["recipient"] = $data["pendientes"][$i]["numero"];
                                    $datos_my["message"] = $data["pendientes"][$i]["mensaje"];
                                    $datos_my["status"] = 0;
                                    
                                    //inserta el registro
                                     $resultado =  $this->AdministradorModel->insert('sms_queue', $datos_my, null,'mysql');
                                    
                                    //var_dump($resultado);
                                    echo "<hr>" . $resultado . "<hr>";
                                    //3. validar exito o error
                                    if ($resultado) {
                                        /**
                                         * PROCESO DE ACTUALIZACION DE 
                                         * - Resumenes
                                         * - Regitro
                                         * - Bases
                                         * - Resumenes
                                         */
                                        $this->actualizar($data["pendientes"][$i]["id"],$data["pendientes"][$i]["idbase"],$data["pendientes"][$i]["idcarrie"],$data["pendientes"][$i]["numero"],$data["pendientes"][$i]["mensaje"],$data["pendientes"][$i]["nota"],$data["pendientes"][$i]["idcarrie"],$data["pendientes"][$i]["cargue"],'G',$data["pendientes"][$i]["fechacargue"]);
                                    }
                                    //3.2  si es error, actualiza la tabla de registros con el error y cambia el estado
                                    //     de pendiente a error, 
                                    else {
                                        //actualiza la tabla de registros  con el mensaje del smpp, actualiza el estado
                                        $cambios = array("estado" => "3",
                                            "respuesta" => "ERROR ENVIO");
                                        $this->AdministradorModel->update('registros', $data["pendientes"][$i]["id"], $cambios);
                                        //registra en el archivo de la empresa
                                        //PENDIENTE- ?????
                                        //registra en el archivo de consolidados
                                        //PENDIENTE- ?????
                                    }
                                }//fin for
                               $contador++;   
                        }//fin else    
                        $this->benchmark->mark('codigo_fin');
                        echo $this->benchmark->elapsed_time('codigo_inicio', 'codigo_fin')."<br>";
                        $suma += $this->benchmark->elapsed_time('codigo_inicio', 'codigo_fin');
                    }//fin WHILE
                    echo "<b>".$suma."</b>";
                
                $this->cronenvioOut($data["cron"][0]["id"]);
            }    
        }// fin else
    }

    function actualizar($id,$idbase,$idcarrie,$numero,$mensaje,$nota,$carrie,$cargue,$canal,$fcargue) {
            /**
             * actualiza la tabla de registros  con el mensaje del smpp, actualiza el estado
             */
            $cambios = array("estado" => "1",
                "fechaenvio" => date("Y-m-d H:i:s"),
                "respuesta" => "ok-nuevo");
            $this->AdministradorModel->update('registros', $id, $cambios);

            /**
             * actualiza la tabla bases aumenta un registro enviado
             */
            $where = "id = '" . $idbase . "'";
            $data["ultimo"] = $this->AdministradorModel->obtenerCampos('bases', 'enviados,archusuario,archconsolidado,ip,idempresa,idusuario', $where);
            if ($data["ultimo"][0]["enviados"] == null)
                $data["ultimo"][0]["enviados"] = 0;
            $nuevo = $data["ultimo"][0]["enviados"] + 1;
            $cambios = array("enviados" => $nuevo);
            $this->AdministradorModel->update('bases', $idbase, $cambios);
            
            /**
             * actualiza la tabla de resumenes
             */
            $where = "idusuario = '" . $data["ultimo"][0]["idusuario"] . "' ";
            $where .= " AND idcarrie = '".$idcarrie."'";
            $where .= " AND fecha = '".date("Y-m-d")."'";
            $data["resumen"] = $this->AdministradorModel->obtenerCampos('resumenes', 'id,cantidad', $where);
           
            if($data["resumen"] == null)
            {
                $datos = array("idusuario" => $data["ultimo"][0]["idusuario"],
                               "idcarrie" => $idcarrie, "fecha" => date("Y-m-d"), "cantidad" => 1);  
                $this->AdministradorModel->insert('resumenes', $datos);
    
            }//fin else
            else 
            {
                $nuevo = $data["resumen"][0]["cantidad"] + 1;
                $cambios = array("cantidad" => $nuevo);
                $this->AdministradorModel->update('resumenes', $data["resumen"][0]["id"], $cambios);
            }//fin if
            
            /**
             * actualiza los registros enviados para el limite
             */
            
            $where = "id = '" . $data["ultimo"][0]["idusuario"] . "' ";
            $data["envia"] = $this->AdministradorModel->obtenerCampos('usuarios', 'enviados,pendientes', $where);
           
            $nuevo = $data["envia"][0]["enviados"] + 1;
            $nuevo2 = $data["envia"][0]["pendientes"] - 1;
            if($nuevo2<0)
               $nuevo2 = 0; 
            $cambios = array("enviados" => $nuevo,"pendientes" => $nuevo2);
            $this->AdministradorModel->update('usuarios', $data["ultimo"][0]["idusuario"], $cambios);

            
            //datos del usuario
            $data["usuario"] = $this->AdministradorModel->datosUsuarios($parametros = '', $usuario = '', 'id=' .$data["ultimo"][0]["idusuario"]);

            /**
             * procedimiento para escribir en planos
             */
                $data["ultimo"][0]["archusuario"] = $data["ultimo"][0]["idusuario"]."/"
                              . "".$data["usuario"]["usuario"]."_".date("Y-m-d").".csv";
                $data["ultimo"][0]["archmes"] = $data["ultimo"][0]["idusuario"]."/"
                              . "".$data["usuario"]["usuario"]."_".date("Y-m").".csv";
                $data["ultimo"][0]["archconsolidado"] = "0/".date("Y-m-d").".csv";
              
            $cambios = array("archusuario" => $data["ultimo"][0]["archusuario"],
                            "archconsolidado" => $data["ultimo"][0]["archconsolidado"]);
            $this->AdministradorModel->update('bases', $idbase, $cambios);

            $where = "id = '" . $idcarrie. "' ";
            $data["carrier"] = $this->AdministradorModel->obtenerCampos('carries', 'codigo', $where);

            $titulo = array(0=>"IP",1=>"EMPRESA",2=>"USUARIO",3=>"FECHA CARGUE",4=>"HORA CARGUE",
                            5=>"FECHA ENVIO",6=>"HORA ENVIO",7=>"NUMERO",8=>"MENSAJE",
                            9=>"NOTA",10=>"CARRIER",11=>"CANAL",12=>"TIPO CARGUE",13=>"# TRANSACCION");
            
            $registro[0][0] = $data["ultimo"][0]["ip"];
            $registro[0][1] = $data["usuario"]["idempresa"];
            $registro[0][2] = $data["usuario"]["usuario"];
            $fec1 = explode(" ",$fcargue);
            $registro[0][3] = $fec1[0];
            $registro[0][4] = $fec1[1];
            $registro[0][5] = date("Y-m-d");
            $registro[0][6] = date("H:i:s");
            $registro[0][7] = $numero;
            $registro[0][8] = $mensaje;
            $registro[0][9] = $nota;
            $registro[0][10] = $idcarrie;
            $registro[0][11] = $canal;
            $registro[0][12] = $cargue;
            $registro[0][13] = $id;
            $separador = ';';
            $this->crearPlano($_SERVER['DOCUMENT_ROOT'].'/planos/'.$data["ultimo"][0]["archusuario"] , $titulo, $registro, $separador);
            $this->crearPlano($_SERVER['DOCUMENT_ROOT'].'/planos.c/'.$data["ultimo"][0]["archmes"] , $titulo, $registro, $separador);
            $this->crearPlano($_SERVER['DOCUMENT_ROOT'].'/planos/'.$data["ultimo"][0]["archconsolidado"] ,$titulo, $registro, $separador);
    }

    function cronenvioIn($id){
        $cambios = array("estado" => 1,
                         "ejecutado" => date("Y-m-d H:i:s"),"consulta" => 0);
        $this->AdministradorModel->update('crones', $id, $cambios);    
        
        $this->registraLog('cronenvioD', 'cronenvioIn', '+++++');
    }

    function cronenvioOut($id){
        $cambios = array("estado" => 0,
                         "ejecutado" => null,"consulta" => 0);
        $this->AdministradorModel->update('crones', $id, $cambios);        

        $this->registraLog('cronenvioG', 'cronenvioOut', '-----');
    }

    function buscarFrom($rango1,$rango2,$actual,$id){
        //si no se asignado o hay errores
        if($actual < $rango1)
            $from = $rango1;
        else {
            if($actual == $rango2)
                $from = $rango1;
            else
                $from = $actual+1;
        }
        
        $cambios = array("ultimo" => $from);
        $this->AdministradorModel->update('canales', $id, $cambios);

        return $from;
    }
    
    function registraLog($nombre,$estado,$detalle)
    {
        $ruta = "/var/www/html/contactosms/logs/";
        $fecha = date("Ymd");
        $fechahora = date("Y-m-d H:i:s");
        $nombre = $nombre."_".$fecha.".txt";
        $mensaje = $fechahora."\t".$estado."\t".$detalle."\n";
        
        $fp = fopen($ruta.$nombre, 'a+');
        fwrite($fp, $mensaje);
        fclose($fp);
    }
}
