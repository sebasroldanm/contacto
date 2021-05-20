<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ExcelTemplate extends MY_Controller {

    private $nombreArchivo;
    private $idempresa;
    private $estadoHora;
    private $estadoPerfil;
    private $estado;
    private $idbase = 0;
    private $client_id = 0;

    public function __construct() {

        parent::__construct();
        date_default_timezone_set('America/Bogota');
        header('Content-Type: text/html; charset=UTF-8');
// Unix
        setlocale(LC_TIME, 'es_ES.UTF-8');

        /**
         * Se cargan las librerias necesarias
         */
        $this->load->helper(array('form', 'url'));
        $this->load->library('excel');
        $this->load->library("reader");
//        $this->load->library('smpp');
        $this->load->model("CargaexcelModel");
        $this->nombreArchivo = '';
        $this->load->library('smpp');
        $this->idempresa = $this->session->userdata("idempresa");


        $this->estadoPerfil = ($this->session->userdata("idperfil") == 3) ? 6 : FALSE;
    }

    /**
     * metodo para cargar la vista principal
     */
    public function index() {

        $data["vista"] = "exceltemplate/inicio";

        $where = '';
        
        if ($this->session->userdata("client_id") != '') {
            $where = "idmarca= " . $this->session->userdata("client_id");
        }


        if ($this->session->userdata("idperfil") != 5 && $this->session->userdata("idperfil") != 1) {
            $where = "id= " . $this->session->userdata("idempresa");
        }


        $data["client"] = $this->CargaexcelModel->Buscar("empresas", '*', $where);
        $this->load->view("template", $data);
    }

    function getTemplate() {
        $in = $this->input->post();
        echo $this->datatables
                ->select("*")
                ->from("template_detail b")
                ->where(array("client_id" => $in["client_id"]))
                ->generate();
    }

    function borrarArchivo() {
        $data = $this->input->post();
        $where = 'idbase=' . $data["idbase"];
        $com = $this->CargaexcelModel->buscar("archivos", '*', $where, 'row');
        $this->CargaexcelModel->borrar('archivos', $data);
        unlink($com["ruta"]);
    }

    function preCarga() {
        $data = array();
        $idbase = 0;
        $this->nombreArchivo = $_FILES["archivo"]["name"];
        $archivo = $_FILES["archivo"]["tmp_name"];
        $post = $this->input->post();

        $datos = new Spreadsheet_Excel_Reader();

        $error = $datos->read($archivo);
//        $ruta = $this->crearRutaCarpeta($_SERVER['DOCUMENT_ROOT'] . "/tmp/" . date("Y-m-d"));
//        $ruta = $this->crearRutaCarpeta($_SERVER['DOCUMENT_ROOT'] . "/contactosms/tmp/" . date("Y-m-d"));
//        $ruta = $this->crearRutaCarpeta($_SERVER['DOCUMENT_ROOT'] . "/prbcontactosms/tmp/" . date("Y-m-d"));
//        $ruta = $this->crearRutaCarpeta($_SERVER['DOCUMENT_ROOT'] . "/contactosms/tmp/" . date("Y-m-d"));
        $ruta = $this->crearRutaCarpeta("/var/www/html/marcablanca/tmp/" . date("Y-m-d"));

//        if (ENVIRONMENT == 'development') {
//            $ruta = $this->crearRutaCarpeta($_SERVER['DOCUMENT_ROOT'] . "/contactosms/tmp/" . date("Y-m-d"));
//        } else {
//            $ruta = $this->crearRutaCarpeta("/var/www/html/prbcontactosms/tmp/" . date("Y-m-d"));
//        }

        $config['upload_path'] = $ruta;
        $config['allowed_types'] = '*';
        $config['file_name'] = $this->nombreArchivo;
        $this->load->library('upload', $config);

        $archivocompleto = $ruta . "/" . $this->nombreArchivo;

        if (file_exists($archivocompleto)) {
            unlink($archivocompleto);
        }

        if (!$this->upload->do_upload('archivo')) {
            print_r($this->upload->display_errors());
        } else {
            $datas = array('upload_data' => $this->upload->data());
            $respuesta["ruta"] = $datas["upload_data"]["full_path"];
            $respuesta["size"] = $datas["upload_data"]["file_size"];
        }

        $respuesta["filas"] = count($datos->sheets[0]['cells']) - 1;


        $arc["nombre"] = $this->nombreArchivo;
        $arc["idusuario"] = $this->idusuario;
        $arc["ruta"] = $respuesta["ruta"];
        $arc["registros"] = $respuesta["filas"];
        $arc["fecha"] = date("Y-m-d H:i:s");

        $where = "idusuario=" . $this->idusuario . " ORDER BY id DESC";
        $com = $this->CargaexcelModel->buscar("archivos", 'nombre,registros', $where, 'row');
        $idarchivo = $this->CargaexcelModel->insert("archivos", $arc);


        $data["description"] = $post["client_id"];
        $data["ip"] = $_SERVER["REMOTE_ADDR"];
        $data["file_name"] = $this->nombreArchivo;
        $data["finsert"] = date("Y-m-d H:i:s");
        $data["client_id"] = $this->session->userdata("client_id");
        unset($data["ruta"]);

        $idbase = $this->CargaexcelModel->insert("template", $data);
        $arc["idbase"] = $idbase;
        $idarchivo = $this->CargaexcelModel->update("archivos", $idarchivo, $arc);
        unset($respuesta["ruta"]);
        $respuesta["idarchivo"] = $idarchivo;
        $respuesta["idbase"] = $idbase;
        $respuesta["nombreactual"] = $this->nombreArchivo;
        $respuesta["nombreaanterior"] = (isset($com["nombre"]) && $com["nombre"] != '') ? $com["nombre"] : 'No hay registros';
        $respuesta["registrosactual"] = $respuesta["filas"];
        $respuesta["registrosanterior"] = (isset($com["registros"])) ? $com["registros"] : 'No hay registros';
        echo json_encode($respuesta);
    }

    function cargaExcel($dataext = NULL) {

        $fechapro = '';
        $this->idbase = 0;
        $data = ($dataext == NULL) ? $this->input->post() : $dataext;

        $this->client_id = $data["client_id"];
        /**
         * si el arreglo fue cargado se crea la base
         */
        $data["idempresa"] = $this->idempresa;
        $data["idusuario"] = $this->idusuario;

        $where = "id=" . $data["idarchivo"];
        $arc = $this->CargaexcelModel->buscar("archivos", 'ruta', $where, 'row');


        unset($data["idarchivo"]);
        /**
         * Se instancia objeto de la clase para leer el archivo excel
         */
        $datos = new Spreadsheet_Excel_Reader();
        $datos->setUTFEncoder('iconv');
        $datos->setOutputEncoding('CP1251');
        $error = $datos->read($arc["ruta"]);

        /**
         * Se valida que el archivo sea leido correctamente
         */
        if ($error["error"] == '') {

            $fechapro = (isset($data["fechaprogramado"]) && !empty($data["fechaprogramado"])) ? $data["fechaprogramado"] : '';

            /**
             * De ser valido el arreglo '$data' se inserta un registro con la informacion necesaria para 
             * la base que se monte
             */
            $this->idbase = $data["idbase"];

            /**
             * Iteracion para almacenar los datos del archivo en un arreglo
             */
            $this->CargaexcelModel->borrar("template_detail", array("client_id" => $this->client_id));

            $contador = 0;
            foreach ($datos->sheets[0]['cells'] as $cont => $arreglo) {
                if ($cont > 1) {
                    $contador++;
                    $this->agregaDatosSession($arreglo, $fechapro, $cont, $this->idbase);
                }
            }

            $wh = "id= " . $this->session->userdata("idempresa");
            $client = $this->CargaexcelModel->Buscar("empresas", '*', $wh, "row");

            $where = " client_id=" . $client["id"];


            $respuesta["data"] = $this->CargaexcelModel->buscar("template_detail", '*', $where);

            echo json_encode($respuesta);
        }
    }

    public function prueba2() {
        $data = $this->input->post();
        print_r($data);
    }

    public function cuentaRegistros() {
        $data = $this->input->post();
        $where = "id=" . $data["idarchivo"];
        $arc = $this->CargaexcelModel->buscar("archivos", 'registros', $where, 'row');
        echo json_encode($arc);
    }

    public function otroRegistros() {
        $data = $this->input->post();

        $where = "id=" . $data["idbase"];
        $arc = $this->CargaexcelModel->buscar("registros", 'count(*) registros', $where);
        echo json_encode($arc);
    }

    function asignaKey($arreglo, $keys) {
        $cont = 0;
        $otro = 0;

        foreach ($arreglo as $i => $arreglo) {
            foreach ($arreglo as $j => $val) {
                $consolidado[$cont][$keys[$j]] = $val;
            }
            $cont++;
        }
        return $consolidado;
    }

    function validaNumero($numero = NULL) {
        $numero = trim($numero);
        $rta = array();
        if ($numero == NULL) {
            $rta[] = FALSE;
            $rta[] = 'El numero vacio';
        } else {
            if (strlen($numero) > 10) {
                $rta[] = FALSE;
                $rta[] = 'El numero largo';
            } else if (strlen($numero) < 10) {
                $rta[] = FALSE;
                $rta[] = 'El numero corto';
            } else {
                $existe = $this->validaPrefijo($numero);

                if ($existe != FALSE) {
                    $num = substr($numero, 3, 10);
                    $num2 = substr($numero, 0, 3);
                    if ($num == '0000000' || ($num[0] < 2 && $num2 != '304') || ($num[0] < 1 && $num2 == '304'))  
                    {
                        $rta[] = FALSE;
                        $rta[] = 'Numero Invalido2';
                    } else {
                        if (is_numeric($num)) {
                            $rta[] = TRUE;
                            $rta[] = $numero;
                        } else {
                            $rta[] = FALSE;
                            $rta[] = 'El numero Contiene Letras';
                        }
                    }
                } else {
                    $rta[] = FALSE;
                    $rta[] = 'No existe el operador';
                }
            }
        }

        return $rta;
    }

    function insertaErrores($msj, $arreglo, $idbase, $fila) {
        $error["nota"] = $this->LimpiaMensaje($arreglo[3]);
        $error["mensaje"] = $this->LimpiaMensaje((isset($arreglo[2]) ? $arreglo[2] : ''));
        $error["numero"] = $arreglo[1];
        $error["error"] = $msj;
        $error["estado"] = 3;
        $error["idbase"] = $idbase;
        $error["fila"] = $fila;
        $error["fecha"] = date("Y-m-d H:i:s");
        $this->CargaexcelModel->insert("template_errores", $error);
    }

    /**
     * Metodo para validar y agregar la informacion a la base de datos
     * @param array $arreglo
     * @param int $idbase
     * @return array
     */
    function agregaDatosSession($arreglo, $fila, $idbase) {

        $validado = NULL;
        $validaNum = $this->validaNumero($arreglo[1]);

        if ($validaNum[0] == TRUE) {
            $validado = $this->validaFila($arreglo);

            if (is_array($validado)) {
                $this->insertRegistros($validado);
                $validado = '';
            } else {
                $this->insertaErrores(str_replace("error:", '', $validado), $arreglo, $idbase, $fila);
            }
        } else {
            $this->insertaErrores("Campo 1 es obligatorio", $arreglo, $idbase, $fila);
        }
    }

    /**
     * Metodo que realiza las validaciones pertinentes para el envio de mensajes
     * @param array $arreglo
     * @return array
     */
    function calculaFecha($fecha = NULL, $hora = NULL) {
        $rta = array();
        if ($fecha != NULL) {

            $fecha = preg_replace("/(\d+)\D+(\d+)\D+(\d+)/", "$3-$2-$1", $fecha);

            $fecha = $fecha . ' ' . (($hora != NULL) ? $hora : date("H:i"));
            $hoy = date('Y-m-d') . " 00:00";
            $futura = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($hoy)));
            $rta = array();

            if ($this->validateDate($fecha) == TRUE) {
                if ($fecha < $hoy) {
                    $rta[] = false;
                    $rta[] = "FECHA ANTIGUA: " . $fecha;
                } elseif ($fecha > $futura) {
                    $rta[] = false;
                    $rta[] = "FECHA FUTURA: " . $fecha;
                } else {
                    $rta[] = true;
                    $rta[] = $fecha;
                }
            } else {
                $rta[] = false;
                $rta[] = "Error en el formato de la fecha: " . $fecha;
            }
        }
        return $rta;
    }

    function validateDate($date, $format = 'Y-m-d H:i') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function validaFila($fila) {
        $errorNum = '';
        $errorSms = '';
        $existe = '';
        $smsdobles = 0;
        $preferencias = '';

        $fila[1] = $this->LimpiaMensaje($fila[1]);

        for ($i = 1; $i < 10; $i++) {
            $fila[$i] = $this->LimpiaMensaje($fila[$i]);
        }

        /**
         * Se valida la longitud del dato segun su tipo
         */
        $existe = $this->validaPrefijo($fila[1]);

        if ($existe == null) {
            $errorSms .= "Prefijo no existe";
        }

        /**
         * si no existe el carriers agrega el primero por defecto del usuario
         */
        return ($errorSms == '') ? $fila : 'error:' . $errorSms;
    }

    /**
     * Metodo para insertar registros
     * @param type $arreglo
     */
    function insertRegistros($arreglo) {
//        $where = "id= " . $this->session->userdata("idempresa");
//        $data["client"] = $this->CargaexcelModel->Buscar("empresas", '*', $where, "row");

        $in["phone"] = $arreglo[1];
        $in["campo1"] = $arreglo[2];
        $in["campo2"] = $arreglo[3];
        $in["campo3"] = $arreglo[4];
        $in["filtro1"] = $arreglo[5];
        $in["filtro2"] = $arreglo[6];
        $in["filtro3"] = $arreglo[7];
        $in["filtro4"] = $arreglo[8];
        $in["filtro5"] = $arreglo[9];
        $in["filtro6"] = $arreglo[10];
        $in["client_id"] = $this->client_id;
        $this->CargaexcelModel->insert("template_detail", $in);
    }

    /**
     * Metodo para crear el archivo excel con la informacion de mensajes que tuvo error
     * @param type $idbase
     */
    public function excelErrores($idbase) {
        $tituloReporte = '';
        /**
         * Se obtiene los datos de la tabla errror segun su base
         */
        $where = "idbase=" . $idbase;
        $datos = $this->CargaexcelModel->Buscar("errores", '*', $where);

        /**
         * Se instancia el objeto '$objPHPExcel' para crear el archivo
         */
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Contacto sms"); // Nombre del autor->setLastModifiedBy("Contacto sms") //Ultimo usuario que lo modificó->setTitle("Reporte Errrores Excel") // Titulo->setSubject("Reporte Errrores Excel") //Asunto->setDescription("Reporte de Errores") //Descripción->setKeywords("Reporte de Errores") //Etiquetas->setCategory("Reporte excel"); //Categorias


        $tituloReporte = "Relación de Registro con errores";
        $titulosColumnas = array('NUMERO', 'MENSAJE', 'NOTA', 'ERROR');


// Se agregan los titulos del reporte
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $titulosColumnas[0])
                ->setCellValue('B1', $titulosColumnas[1])
                ->setCellValue('C1', $titulosColumnas[2])
                ->setCellValue('D1', $titulosColumnas[3]);

        $cont = 2;
        /**
         * Se llena el archivo
         */
        foreach ($datos as $i => $arreglo) {

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $cont, $arreglo['numero'])->setCellValue('B' . $cont, $arreglo['mensaje'])->setCellValue('C' . $cont, $arreglo['nota'])->setCellValue('D' . $cont, $arreglo['error']);
            $cont++;
        }

        /**
         * Se agrega titulo
         */
        $objPHPExcel->getActiveSheet()->setTitle('Errores');
        $objPHPExcel->setActiveSheetIndex(0);
        /**
         * Se agregan los encabezados para que se genere la descarga
         */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Errores' . date("Y-m-d") . '.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function verBase() {
        $data = $this->input->post();
        $where = "idbase=" . $data["idbase"] . " limit 3";
        $datos = $this->CargaexcelModel->Buscar("registros", 'numero,mensaje,nota', $where);
        echo json_encode($datos);
    }

    public function verErrores() {
        $data = $this->input->post();
        $where = "idbase=" . $data["idbase"] . " and error NOT ILIKE '%LISTA NEGRA%' limit 20";
        $datos = $this->CargaexcelModel->Buscar("errores", 'numero,mensaje,nota,error', $where);
        echo json_encode($datos);
    }

    public function verBlacklist() {
        $data = $this->input->post();
        $where = "idbase=" . $data["idbase"] . " and error ILIKE '%LISTA NEGRA%' limit 20";
        $datos = $this->CargaexcelModel->Buscar("errores", 'numero,mensaje,nota,error', $where);
        echo json_encode($datos);
    }

    public function procesarCarga() {
        $data = $this->input->post();

        $cupo = $this->CargaexcelModel->CupoActual();


        if ($data["tipo"] == 'confirmado') {
            $solicitud = $this->CargaexcelModel->buscar("registros", 'count(id) sol', 'idbase=' . $data["idbase"], 'row');
            if ($solicitud != FALSE) {

                if ($solicitud["sol"] > 0) {
                    $sql = "UPDATE registros set estado='2' where idbase=" . $data["idbase"];
                    $update = $this->CargaexcelModel->ejecutar($sql, 'update');
                    $pendientes = $this->CargaexcelModel->buscar("usuarios", 'coalesce(pendientes,0) pendientes', 'id=' . $this->idusuario, 'row');
                    $pend["pendientes"] = $update + $pendientes["pendientes"];
                    $this->CargaexcelModel->update("usuarios", $this->idusuario, $pend, 'row');
                    $this->CargaexcelModel->update("bases", $data["idbase"], array("estado" => '1'));
                    $filas["mensaje"] = $update . " Registros Procesados con exito";
                } else {
                    $filas["errores"] = "usuario sin cupo";
                }
            } else {
                $filas["errores"] = "usuario sin cupo";
            }
        } else {
            $del["idbase"] = $data["idbase"];
            $this->CargaexcelModel->borrar("archivos", $del);
            $this->CargaexcelModel->borrar("bases", $data["idbase"]);
            $this->CargaexcelModel->borrar("registros", $del);
            $filas["mensaje"] = " Registros Cancelados con exito";
        }

        $filas["cupo"] = $this->CargaexcelModel->CupoActual();

        echo json_encode($filas);
    }

}
