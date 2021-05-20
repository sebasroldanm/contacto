<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CargaExcel extends MY_Controller {

    private $nombreArchivo;
    private $idempresa;
    private $estadoHora;
    private $estadoPerfil;
    private $estado;
    private $idbase = 0;

    public function __construct() {
        parent::__construct();
        header('Content-Type: text/html; charset=UTF-8');
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
        $data["vista"] = "cargaexceltest/inicio";
        $this->load->view("template", $data);
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

        $data["idempresa"] = $this->idempresa;
        $data["idusuario"] = $this->idusuario;

        $datos = new Spreadsheet_Excel_Reader();

        $error = $datos->read($archivo);
//        $ruta = $this->crearRutaCarpeta($_SERVER['DOCUMENT_ROOT'] . "/tmp/" . date("Y-m-d"));
//        $ruta = $this->crearRutaCarpeta($_SERVER['DOCUMENT_ROOT'] . "/contactosms/tmp/" . date("Y-m-d"));
//        $ruta = $this->crearRutaCarpeta($_SERVER['DOCUMENT_ROOT'] . "/prbcontactosms/tmp/" . date("Y-m-d"));
//        $ruta = $this->crearRutaCarpeta($_SERVER['DOCUMENT_ROOT'] . "/contactosms/tmp/" . date("Y-m-d"));
        $ruta = $this->crearRutaCarpeta("/var/www/html/contactosms.new/tmp/" . date("Y-m-d"));
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


        $data["nombre"] = 'web_' . $data["idempresa"] . '_' . $data["idusuario"] . '_' . date("Y-m-d H:i:s");
        $data["ip"] = $_SERVER["REMOTE_ADDR"];
        $data["archoriginal"] = $this->nombreArchivo;
        $data["fecha"] = date("Y-m-d H:i:s");
        $data["estado"] = "2";
        unset($data["ruta"]);

        $idbase = $this->CargaexcelModel->insert("bases", $data);
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

//    function cargaExcel($dataext = NULL) {
//        include APPPATH . 'third_party/PHPExcel/IOFactory.php';
//        $fechapro = '';
//        $this->idbase = 0;
//        $data = ($dataext == NULL) ? $this->input->post() : $dataext;
//        /**
//         * si el arreglo fue cargado se crea la base
//         */
//        $data["idempresa"] = $this->idempresa;
//        $data["idusuario"] = $this->idusuario;
//
//        $where = "id=" . $data["idarchivo"];
//        $arc = $this->CargaexcelModel->buscar("archivos", 'ruta', $where, 'row');
//
//
//        unset($data["idarchivo"]);
//        /**
//         * Se instancia objeto de la clase para leer el archivo excel
//         */
//        $inputFileName = $arc["ruta"];
//        $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
//
//        $rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();
//        foreach ($rowIterator as $row) {
//            echo "fila " . $row->getRowIndex();
//            $cellIterator = $row->getCellIterator();
//            $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
//            if (1 == $row->getRowIndex())
//                continue; //skip first row la del encabezado
//            $rowIndex = $row->getRowIndex();
//            $array_data[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '');
//
//            foreach ($cellIterator as $cell) {
//                if ('A' == $cell->getColumn()) {
//                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
//                    $datos[$rowIndex]['pac_identificacion'] = $cell->getCalculatedValue();
//                } else if ('B' == $cell->getColumn()) {
//                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
//                    $datos[$rowIndex]['pac_nombre'] = $cell->getCalculatedValue();
//                } else if ('C' == $cell->getColumn()) {
//                    $array_data[$rowIndex][$cell->getColumn()] = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'YYYY-MM-DD');
//                    $datos[$rowIndex]['pac_fecha'] = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'MM-DD-YYYY');
//                } else if ('D' == $cell->getColumn()) {
//                    $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
//                }
//            }
//        }
//        print_r($datos);
//        exit;
//    }

    function cargaExcel($dataext = NULL) {

        $fechapro = '';
        $this->idbase = 0;
        $data = ($dataext == NULL) ? $this->input->post() : $dataext;



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
            $contador = 0;
            foreach ($datos->sheets[0]['cells'] as $cont => $value) {
                if ($cont > 1) {
                    $contador++;
                    $this->agregaDatosSession($value, $fechapro, $cont, $this->idbase);
                }
            }
            $where = 'idbase=' . $this->idbase . " and error NOT ILIKE '%LISTA NEGRA%'";
            $error = $this->CargaexcelModel->buscar("errores", 'COUNT(*) total', $where, 'row');
            $where = 'idbase=' . $this->idbase . " and error ILIKE '%LISTA NEGRA%'";
            $errorBlack = $this->CargaexcelModel->buscar("errores", 'COUNT(*) total',$where, 'row');
            $total = $this->CargaexcelModel->buscar("registros", 'COUNT(*) total', 'idbase=' . $this->idbase, 'row');
            $para["errores"] = $error["total"];
            $para["registros"] = $total["total"];
            $this->CargaexcelModel->update("bases", $this->idbase, $para);

            $duplicados = $this->CargaexcelModel->buscar("bases", 'dobles', 'id=' . $this->idbase, 'row');
            $cupo = $this->CargaexcelModel->CupoActual();


            $respuesta["errores"] = $error["total"];
            $respuesta["blacklist"] = $errorBlack["total"];
            $respuesta["ok"] = $total["total"];
            $respuesta["idbase"] = $this->idbase;
            $respuesta["cupo"] = $cupo;
            $respuesta["duplicados"] = ($duplicados["dobles"] == null) ? 0 : $duplicados["dobles"];

            if ($total["total"] > $cupo["disponible"]) {
                $respuesta["cupo"] = $total["total"] - $cupo["disponible"];
            }

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

    function subePruebas() {
        $this->nombreArchivo = $_FILES["archivo"]["name"];
        $archivo = $_FILES["archivo"]["tmp_name"];

        /**
         * Se instancia objeto de la clase para leer el archivo excel
         */
        $datos = new Spreadsheet_Excel_Reader();
        $datos->setOutputEncoding('CP1251');
        $error = $datos->read($archivo);

        if ($error == '') {
            foreach ($datos->sheets[0]['cells'] as $i => $value) {
                if ($i <= 4) {
                    foreach ($value as $j => $val) {
                        $arreglo[$i][$j] = $val;
                    }
                }
            }
        }

        $where = "id = 1";
        $data["smpp"] = $this->CargaexcelModel->buscar('canales', 'host,port,usr,password,systemtype,nomenclatura', $where, 'row');
        $smpphost = $data["smpp"]->host;
        $smppport = $data["smpp"]->port;
        $systemid = $data["smpp"]->usr;
        $password = $data["smpp"]->password;
        $system_type = $data["smpp"]->systemtype;
        $from = "contactos";

        $smpp = new SMPPClass();
        $smpp->SetSender($from);

        $conectado = $smpp->Start($smpphost, $smppport, $systemid, $password, $system_type);

        $keys = $arreglo[1];
        unset($arreglo[1]);
        $this->asignaKey($arreglo, $keys);

        $consolidado = $this->asignaKey($arreglo, $keys);

        print_r($conectado);

        if ($conectado == 1) {
            foreach ($consolidado as $i => $value) {
                echo $i . " - enviando mensaje " . $value["NUMERO"] . " " . $value["MENSAJE"] . " <br>";

                $resultado = $smpp->Send($value["NUMERO"], $value["MENSAJE"]);
                //aqui seria hacer el insert a registros...
            }
        }
        $smpp->End();
    }

    function asignaKey($arreglo, $keys) {
        $cont = 0;
        $otro = 0;

        foreach ($arreglo as $i => $value) {
            foreach ($value as $j => $val) {
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
                        $rta[] = 'Numero Invalido7';
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
        $this->CargaexcelModel->insert("errores", $error);
    }

    /**
     * Metodo para validar y agregar la informacion a la base de datos
     * @param array $arreglo
     * @param int $idbase
     * @return array
     */
    function agregaDatosSession($arreglo, $fechapro = '', $fila, $idbase) {

        if ($fechapro == '') {
            if (empty($arreglo[4]) && empty($arreglo[5])) {
                $arreglo[4] = date("d/m/Y");
                $arreglo[5] = date("H:i");
            } else {
                $arreglo[4] = isset($arreglo[4]) ? $arreglo[4] : NULL;
                $arreglo[5] = isset($arreglo[5]) ? $arreglo[5] : NULL;
            }
        } else {
            $datos = explode(" ", $fechapro);
            $arreglo[4] = $datos[0];
            $arreglo[5] = $datos[1];
        }

        $valFecha = $this->calculaFecha($arreglo[4], $arreglo[5]);
        if ($valFecha[0] == true) {
            $arreglo[4] = $valFecha[1];
            $validaNum = $this->validaNumero($arreglo[1]);

            if ($validaNum[0] == TRUE) {
                if (!empty($arreglo[2]) && isset($arreglo[2])) {
                    $validado = NULL;
                    $nombres = array('numero', 'mensaje', 'nota');
                    $campos = 'coalesce(enviados,0) + coalesce(pendientes,0) consumo';
                    $consumo = $this->CargaexcelModel->buscar("usuarios", $campos, 'id=' . $this->idusuario, 'row');
                    $servicio = $this->CargaexcelModel->buscar("servicios", 'coalesce(maximo,0) maximo', 'id=' . $this->session->userdata("idservicio"), 'row');
                    $disponible = $servicio["maximo"] - $consumo["consumo"];

                    if ($disponible >= 1) {
                        $validado = $this->validaFila($arreglo);
                        if (is_array($validado)) {
                            if (isset($validado[1])) {
                                $this->insertRegistros($validado);
                                $validado = '';
                            } else {
                                $arregloTodo[] = $validado;
                                $this->insertRegistros($arregloTodo);
                                $arregloTodo = '';
                            }
                        } else {
                            $this->insertaErrores(str_replace("error:", '', $validado), $arreglo, $idbase, $fila);
                        }
                    } else {
                        $this->insertaErrores("El usuario no cuenta con cupo suficiente", $arreglo, $idbase, $fila);
                    }
                } else {
                    $this->insertaErrores("Contenido del mensaje vacio", $arreglo, $idbase, $fila);
                }
            } else {
                $this->insertaErrores($validaNum[1], $arreglo, $idbase, $fila);
            }
        } else {
            $this->insertaErrores($valFecha[1], $arreglo, $idbase, $fila);
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

        $arreglo["mensaje"] = $this->LimpiaMensaje($fila[2]);

        $arreglo["numero"] = $this->LimpiaMensaje($fila[1]);

        $fila[3] = (isset($fila[3])) ? $fila[3] : '';

        $arreglo["nota"] = $this->LimpiaMensaje($fila[3]);
        $arreglo["flash"] = (isset($fila[6]) && $fila[6] == 'SI') ? 1 : 0;
        $arreglo["correo"] = (isset($fila[7])) ? $fila[7] : '';
        $arreglo["fechaprogramado"] = (isset($fila[4])) ? $fila[4] : '';
        /**
         * Se valida la longitud del dato segun su tipo
         */
        $mensaje = (strlen($arreglo["mensaje"]) <= 160) ? $arreglo["mensaje"] : FALSE;

        $existe = $this->validaPrefijo($arreglo["numero"]);

        /**
         * si no existe el carriers agrega el primero por defecto del usuario
         */
        $preferencias = $this->CargaexcelModel->Buscar("usuarios", '*', 'id=' . $this->session->userdata("idusuario"), 'row');

        $busca = explode(",", $preferencias["preferencias"]);
        $resta = (int) $existe["codigo"] - 1;
        $preferencias["idcanal"] = $busca[$resta];

        if (empty($preferencias)) {
            $preferencias = $this->CargaexcelModel->Buscar("canales", 'id as idcanal', null, 'row');
        }

        /**
         * Valida el perfil y la hora para establecer el estado del registro
         */
        list($hora, $min, $seg) = explode(":", date("H:i:s"));
        $estadoHora = ($hora >= 20) ? TRUE : FALSE;

        $this->estado = ($estadoHora == TRUE || $this->estadoPerfil == 6) ? 6 : 4;

        /**
         * Se valia si el mensaje supero los 160 caracteres y si no existe error con el numero
         */
        $where = "numero='" . $arreglo["numero"] . "' and user_id=" . $this->session->userdata("idusuario");
        $black = $this->CargaexcelModel->Buscar("blacklist", "*", $where, 'row');

        if ($black == false) {

            if ($mensaje == FALSE) {

                /**
                 * Se verifica si el usuario cuenta con la opcion para concatenar
                 */
                $concatena = $this->session->userdata("concatena");

                if ($concatena == 1) {

                    /**
                     * Si puede concatenar dividalo de tal manera que lo pueda enviar
                     */
                    $anterior = 0;

                    $tam = ceil(strlen($arreglo["mensaje"]) / 160);
                    $sms = array();
                    for ($i = 1; $i <= $tam; ++$i) {
                        $largo = $i * 160;
                        $sms[$i]["numero"] = $arreglo["numero"];
                        $sms[$i]["mensaje"] = trim(substr($arreglo["mensaje"], $anterior, 160));
                        $sms[$i]["nota"] = $arreglo["nota"];
                        $sms[$i]["idcarrie"] = (isset($existe["codigo"])) ? $existe["codigo"] : '0';
                        $sms[$i]["orden"] = $i;
                        $sms[$i]["cargue"] = 'web';
                        $sms[$i]["idbase"] = $this->idbase;
                        $sms[$i]["estado"] = $this->estado;
                        $sms[$i]["fechacargue"] = date("Y-m-d H:i:s");
                        $sms[$i]["idcanal"] = $preferencias["idcanal"];
                        $sms[$i]["flash"] = (isset($fila[4]) && $fila[4] == 'SI') ? 1 : 0;
                        $sms[$i]["fechaprogramado"] = (isset($fila[4])) ? $fila[4] : date("Y-m-d H:i");
                        $anterior = $largo;
                    }

                    $arreglo = $sms;

                    $dobles = $this->CargaexcelModel->buscar("bases", 'dobles', 'id=' . $this->idbase, 'row');
                    $datadobles["dobles"] = $dobles["dobles"] + 1;
                    $this->CargaexcelModel->update("bases", $this->idbase, $datadobles);
                } else {
                    /**
                     * Si no cuenta con la opcion de concatenar agregue un error
                     */
                    $errorSms = "No tiene permisos para contactenar sms supera los 160,";
                }
            } else {
                /**
                 * En caso de que no supere los 160 caracteres
                 */
                $arreglo["estado"] = $this->estado;
                $arreglo["mensaje"] = $mensaje;
                $arreglo["nota"] = $arreglo["nota"];
                $arreglo["idcarrie"] = $existe["codigo"];
                $arreglo["orden"] = 1;
                $arreglo["idcanal"] = $preferencias["idcanal"];
                $arreglo["fechacargue"] = date("Y-m-d H:i:s");
            }
        } else {
            $errorSms = "NUMERO EN LISTA NEGRA - " . $arreglo["numero"];
        }


        /**
         * Si no existe ningun error retorn el arreglo  de lo contrario retorne el error
         */
        return ($errorSms == '') ? $arreglo : 'error:' . $errorSms;
    }

    /**
     * Metodo para insertar registros
     * @param type $arreglo
     */
    function insertRegistros($arreglo) {

        foreach ($arreglo as $value) {
            $value["cargue"] = 'web';
            $value["idbase"] = $this->idbase;
            $idinser = $this->CargaexcelModel->insert("registros", $value);
        }
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
        foreach ($datos as $i => $value) {

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $cont, $value['numero'])->setCellValue('B' . $cont, $value['mensaje'])->setCellValue('C' . $cont, $value['nota'])->setCellValue('D' . $cont, $value['error']);
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
