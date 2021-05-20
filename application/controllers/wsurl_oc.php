<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class WsUrl extends MY_Controller {


    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
    }

    public function index() {
        //print_r($_GET);

        $numero = $_GET["numero"];
        $usuario = $_GET["usuario"];
        $clave = $_GET["clave"];
        
        //valida los nuevos valores
        if(isset($_GET["fecha"]))
            $fecha = $_GET["fecha"];
        if(!isset($fecha))
            $fecha = date('Y-m-d H:i:s');
        //valida si es fecha    
        $esfecha = $this->validarFecha($fecha);

        if(!isset($_GET["flash"]))
            $flash = 0;
        else   
        {
            $flash = $_GET["flash"];
            if($flash != 1)
                $flash = 0;
        }
        if(isset($_GET["correo"]))
            $correo = $_GET["correo"];
        else 
            $correo = null;
        $mensaje = $_GET["mensaje"];
        $mensaje = $this->LimpiaMensaje(utf8_encode((filter_var($mensaje, FILTER_SANITIZE_STRING))));
        $mensaje = html_entity_decode($mensaje);        

        $where="usuario='{$usuario}' AND clave='" . base64_encode($clave) . "' AND estado = 1";
	    $idusuario = $this->AdministradorModel->buscar('usuarios','*',$where,'row');
        
        if(COUNT($idusuario)>0)
        {
             $idbase = $this->buscarBase($idusuario["idempresa"],$idusuario["id"]);

             //PREGUNTA LOS DATOS DE CONSUMO
             $where="id ='{$idusuario["id"]}'";
             $data["servicio"] = $this->AdministradorModel->buscar('usuarios','idservicio,enviados,pendientes,concatena',$where,'row');

             $where="id ='".$data["servicio"]["idservicio"]."'";
             $data["maximo"] = $this->AdministradorModel->buscar('servicios','maximo',$where,'row');

             $maximo = $data["maximo"]["maximo"];
             $consumo = $data["servicio"]["enviados"] + $data["servicio"]["pendientes"];
             
                $errsms = $errno = $errfec = '';

                $datos["numero"] = (strlen($numero) == 10) ? $numero : FALSE;
                $mensaje = (strlen($this->LimpiaMensaje($mensaje)) < 161) ? $this->LimpiaMensaje($mensaje) : FALSE;

                if($data["servicio"]["concatena"] and strlen($this->LimpiaMensaje($_GET["mensaje"])) > 160)
                {
                    //declara el primer mensaje
                    $mensaje = substr($this->LimpiaMensaje($_GET["mensaje"]),0,160);
                    //declara el segundo mensaje
                    $mensaje2 = substr($this->LimpiaMensaje($_GET["mensaje"]),160,160);
                }   
                else
                    $mensaje2 = null;
                    
                
                if ($datos["numero"] == FALSE && !is_numeric($datos["numero"])) {
                    $errno = "Error en el numero: ".$numero;
                    $datos["idbase"] = $idbase;
                    $datos["numero"] = $numero;
                    $datos["mensaje"] = $mensaje;
                    $datos["orden"] = 1;
                    $datos["nota"] = $this->LimpiaMensaje($_GET["nota"]);
                    $datos["error"] = $errno;
                    $datos["fecha"] = date("Y-m-d H:i:s");
                    $datos["fila"] = '0';
                    $datos["estado"] = '3';
                    $id = $this->AdministradorModel->insert('errores', $datos);
                }

                if ($mensaje != FALSE) {
                    $datos["mensaje"] = $mensaje;
                } else {
                    $errsms = "Mensaje largo";
                    $datos["idbase"] = $idbase;
                    $datos["numero"] = $numero;
                    $datos["mensaje"] = substr($this->LimpiaMensaje($_GET["mensaje"]),0,180)."...";
                    $datos["orden"] = 1;
                    $datos["nota"] = $this->LimpiaMensaje($_GET["nota"]);
                    $datos["error"] = $errsms;
                    $datos["fecha"] = date("Y-m-d H:i:s");
                    $datos["fila"] = '0';
                    $datos["estado"] = '3';
                    $id = $this->AdministradorModel->insert('errores', $datos);
                }
                if($consumo >= $maximo){
                    $errsms = "NO TIENE CUPO DISPONIBLE";
                    $datos["idbase"] = $idbase;
                    $datos["numero"] = $numero;
                    $datos["mensaje"] = $mensaje;
                    $datos["orden"] = 1;
                    $datos["nota"] = $this->LimpiaMensaje($_GET["nota"]);
                    $datos["error"] = $errsms;
                    $datos["fecha"] = date("Y-m-d H:i:s");
                    $datos["fila"] = '0';
                    $datos["estado"] = '3';
                    $id = $this->AdministradorModel->insert('errores', $datos);
                }
                //calcula si existe el prefijo
                $idcarrie = $this->AdministradorModel->buscar('carries', 'id,codigo', "prefijos like '%" . substr($datos["numero"], 0, 3) . "%'",'row');
                if(!$idcarrie){
                    $errno = "PREFIJO DE OPERADOR NO REGISTRADO";
                    $datos["idbase"] = $idbase;
                    $datos["numero"] = $numero;
                    $datos["mensaje"] = $mensaje;
                    $datos["orden"] = 1;
                    $datos["nota"] = $this->LimpiaMensaje($_GET["nota"]);
                    $datos["error"] = $errsms;
                    $datos["fecha"] = date("Y-m-d H:i:s");
                    $datos["fila"] = '0';
                    $datos["estado"] = '3';
                    $id = $this->AdministradorModel->insert('errores', $datos);
                    
                }

                if($esfecha[0] == FALSE){
                    $errfec = "FECHA ERRADA - ".$esfecha[1];
                    $datos["idbase"] = $idbase;
                    $datos["numero"] = $numero;
                    $datos["mensaje"] = $mensaje;
                    $datos["orden"] = 1;
                    $datos["nota"] = $this->LimpiaMensaje($_GET["nota"]);
                    $datos["error"] = $errfec;
                    $datos["fecha"] = date("Y-m-d H:i:s");
                    $datos["fila"] = '0';
                    $datos["estado"] = '3';
                    $id = $this->AdministradorModel->insert('errores', $datos);
                    
                }

                if ($errno == '' && $errsms == '' and $errfec == '') {
                    $this->actualizaBase($idbase);
                    $idcarrie = $this->AdministradorModel->buscar('carries', 'id,codigo', "prefijos like '%" . substr($datos["numero"], 0, 3) . "%'",'row');

                    $prefencias = explode(",", $idusuario["preferencias"]);
                    $indice = $idcarrie["codigo"] - 1;
                    
                    $datos["nota"] = $this->LimpiaMensaje($_GET["nota"]);
                    $datos["idcanal"] = $prefencias[$indice];
                    $datos["estado"] = 2;
                    $datos["orden"] = 1;
                    $datos["cargue"] = 'url';
                    $datos["idcarrie"] = $idcarrie["codigo"];
                    $datos["idbase"] = $idbase;
                    $datos["fechacargue"] = date("Y-m-d H:i:s");

                    $datos["fechaprogramado"] = $fecha;
                    $datos["flash"] = $flash;
                    $datos["correo"] = $correo;
                    
                    //inserta el registro
                    $idtransaccion = $this->AdministradorModel->insert('registros', $datos);
                    //actualiza la tabla de bases y de usuarios
                    $nuevo = $data["servicio"]["pendientes"] + 1;
                    $cambios = array("pendientes" => $nuevo);
                    $this->AdministradorModel->update('usuarios',$idusuario["id"], $cambios);
                    //print_r($datos);
                    echo $idtransaccion."-DATO CARGADO EXITOSAMENTE (".($consumo+1)." / ".$maximo.")\n";
                    if($mensaje2)
                    {
                        $datos["mensaje"] = $mensaje2;
                        $datos["orden"] = 2;
                        //inserta el registro
                        $idtransaccion = $this->AdministradorModel->insert('registros', $datos);
                        //actualiza la tabla de bases y de usuarios
                        $nuevo = $data["servicio"]["pendientes"] + 2;
                        $cambios = array("pendientes" => $nuevo);
                        $this->AdministradorModel->update('usuarios',$idusuario["id"], $cambios);
                        //print_r($datos);
                        echo $idtransaccion."-DATO CARGADO EXITOSAMENTE (".($consumo+2)." / ".$maximo.")\n";
                    }    
                } else {
                    $error["numero"] = $errno;
                    $error["mensaje"] = $errsms;
                    $error["fecha"] = $errfec;
                    $error["tam"] = strlen($_GET["mensaje"]);
                    print_r($error);
                }
        }
        else 
        {
            echo "ERROR EN LA VALIDACION DEL USUARIO";
        }
    }
    
    function buscarBase($idempresa,$idusuario){
        $codigo = "url_{$idempresa}_{$idusuario }_".date("Y-m-d");
        
        $where="nombre like '{$codigo}%'";
        //echo $where;
        
	    $base = $this->AdministradorModel->buscar('bases','*',$where,'row');
        //var_dump($base);
        if(!isset($base["nombre"]))
        {
            $datos["idempresa"] = $idempresa;
            $datos["idusuario"] = $idusuario;
            $datos["nombre"] = $codigo." 00:00:00";
            $datos["fecha"] = date("Y-m-d H:i:s");
            $datos["registros"] = 0;
            $datos["errores"] = 0;
            $datos["ip"] = $_SERVER["REMOTE_ADDR"];
            //echo "aqui";
            $id = $this->AdministradorModel->insert('bases', $datos);
        }
        else
            $id = $base["id"];
        return $id;
    }

    function actualizaBase($idbase){
        
        $where="id = '{$idbase}'";
	    $base = $this->AdministradorModel->buscar('bases','*',$where,'row');
        
        $nuevo = $base["registros"] + 1;
        $cambios = array("registros" => $nuevo);
        $this->AdministradorModel->update('bases',$idbase, $cambios);
    }
    /**
     * Valida formato dd-mm-yyyy
     * @param type $fecha
     * @return type
     */
   function validarFecha($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        $rta[0] = $d;
        if($d != false) 
            $rta[1] = $d->format($format) == $date;
        
        if($d == FALSE)
        {
            $rta[0] = false; 
            $rta[1] = "FORMATO NO VALIDO";
        }else
        {
            $hoy = date('Y-m-d')." 00:00:00";
            $futura = date('Y-m-d H:i:s', strtotime ( '+1 year' , strtotime ( $hoy ) )) ;

            if($date < $hoy)
            {
                $rta[0] = false; 
                $rta[1] = "FECHA ANTIGUA";
            }elseif($date > $futura)
            {
                $rta[0] = false; 
                $rta[1] = "FECHA FUTURA";
            }
        }
        return $rta;
    }
}