<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CargaExcelint extends MY_Controller {

    private $nombreArchivo;
    private $idempresa;
    private $estadoHora;
    private $estadoPerfil;
    private $estado;
    private $idbase = 0;

    public function __construct() {
        parent::__construct();
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
        $data["vista"] = "cargaexcel/inicioint";
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
        $ruta = $this->crearRutaCarpeta($_SERVER['DOCUMENT_ROOT'] . "/contactosms/tmp/" . date("Y-m-d"));
//        $ruta = $this->crearRutaCarpeta("/var/www/html/prbcontactosms/tmp/" . date("Y-m-d"));
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
            if (isset($_POST["fecha"])) {
                $fechapro = isset($_POST["fecha"]) ? $_POST["fecha"] : '';
            }

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

            $error = $this->CargaexcelModel->buscar("errores", 'COUNT(*) total', 'idbase=' . $this->idbase, 'row');
            $total = $this->CargaexcelModel->buscar("registros", 'COUNT(*) total', 'idbase=' . $this->idbase, 'row');
            $para["errores"] = $error["total"];
            $para["registros"] = $total["total"];
            $this->CargaexcelModel->update("bases", $this->idbase, $para);

            $duplicados = $this->CargaexcelModel->buscar("bases", 'dobles', 'id=' . $this->idbase, 'row');
            $cupo = $this->CargaexcelModel->CupoActual();


            $respuesta["errores"] = $error["total"];
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

    /**
     * Metodo para validar y agregar la informacion a la base de datos
     * @param array $arreglo
     * @param int $idbase
     * @return array
     */
    function agregaDatosSession($arreglo, $fechapro = '', $fila, $idbase) {
        $validado = NULL;
        $nombres = array('numero', 'mensaje', 'nota');
        $campos = 'coalesce(enviados,0) + coalesce(pendientes,0) consumo';
        $consumo = $this->CargaexcelModel->buscar("usuarios", $campos, 'id=' . $this->idusuario, 'row');
        $servicio = $this->CargaexcelModel->buscar("servicios", 'coalesce(maximo,0) maximo', 'id=' . $this->session->userdata("idservicio"), 'row');
        $disponible = $servicio["maximo"] - $consumo["consumo"];

        if ($disponible >= 1) {

            $validado = $this->validaFila($arreglo, $fechapro);
            if (is_array($validado)) {
                if (isset($validado[1])) {
                    $this->insertRegistros($validado, $fechapro);
                    $validado = '';
                } else {
                    $arregloTodo[] = $validado;
                    $this->insertRegistros($arregloTodo, $fechapro);
                    $arregloTodo = '';
                }
            } else {
                $arregloFull["nota"] = $this->LimpiaMensaje($arreglo[3]);
                $arregloFull["mensaje"] = $this->LimpiaMensaje($arreglo[2]);
                $arregloFull["numero"] = $arreglo[1];
                $arregloFull["error"] = str_replace("error:", '', $validado);
                $arregloFull["estado"] = 3;
                $arregloFull["idbase"] = $this->idbase;
                $arregloFull["fila"] = $fila;
                $arregloFull["fecha"] = date("Y-m-d H:i:s");
                $this->CargaexcelModel->insert("errores", $arregloFull);
            }
        } else {

            $error["nota"] = $this->LimpiaMensaje($arreglo[3]);
            $error["mensaje"] = $this->LimpiaMensaje($arreglo[2]);
            $error["numero"] = $arreglo[1];
            $error["error"] = "El usuario no cuenta con cupo suficiente";
            $error["estado"] = 3;
            $error["idbase"] = $idbase;
            $error["fila"] = $fila;
            $error["fecha"] = date("Y-m-d H:i:s");
            $this->CargaexcelModel->insert("errores", $error);
        }
    }

    /**
     * Metodo que realiza las validaciones pertinentes para el envio de mensajes
     * @param array $arreglo
     * @return array
     */
    function validaFila($fila, $fechapro) {
        $errorNum = '';
        $errorSms = '';
        $existe = '';
        $smsdobles = 0;
        $preferencias = '';

        $arreglo["numero"] = $fila[1];
        $arreglo["mensaje"] = $this->LimpiaMensaje($fila[2]);
        $arreglo["nota"] = $this->LimpiaMensaje($fila[3]);
        $arreglo["flash"] = (isset($fila[4]) && $fila[4] == 'SI') ? 1 : 0;


        if (isset($fila[5])) {
            if ($this->validarFecha($fila[5]) == TRUE) {
                $myDateTime = DateTime::createFromFormat('d/m/Y', $fila[5]);
                $arreglo["fechaprogramado"] = $myDateTime->format('Y-m-d');
            } else {
                $arreglo["fechaprogramado"] = $fila[5];
            }
        } else {
            $arreglo["fechaprogramado"] = date("Y-m-d");
        }


        /**
         * Se valida la longitud del dato segun su tipo
         */
        $numero = trim($arreglo["numero"]);
        $arreglo["numero"] = (strlen($numero) < 14) ? $numero : FALSE;
        $mensaje = (strlen($arreglo["mensaje"]) <= 160) ? $arreglo["mensaje"] : FALSE;

        /**
         * Se valida que no supere lo 10 caracteres
         */
        if ($arreglo["numero"] == FALSE) {
            $errorNum = "el numero presenta errores,";
        } else {
            /**
             * Se valida que sea numerico
             */
            if (!is_numeric($arreglo["numero"])) {
                $errorNum .="Solo se deben subir numeros,";
            } else {
                /**
                 * Valida el perfil y la hora para establecer el estado del registro
                 */
                list($hora, $min, $seg) = explode(":", date("H:i:s"));
                $estadoHora = ($hora >= 20) ? TRUE : FALSE;

                $this->estado = ($estadoHora == TRUE || $this->estadoPerfil == 6) ? 6 : 4;

                /**
                 * Se valia si el mensaje supero los 160 caracteres y si no existe error con el numero
                 */
                if ($mensaje == FALSE && $errorNum == '') {

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
                            $sms[$i]["idcanal"] = 3;
                            $sms[$i]["flash"] = (isset($fila[4]) && $fila[4] == 'SI') ? 1 : 0;
                            $sms[$i]["fechaprogramado"] = (isset($fila[5])) ? $fila[5] : date("Y-m-d");
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
                    $arreglo["idcarrie"] = 3;
                    $arreglo["orden"] = 1;
                    $arreglo["idcanal"] = 3;
                    $arreglo["fechacargue"] = date("Y-m-d H:i:s");
                }
            }
        }


        /**
         * Si no existe ningun error retorn el arreglo  de lo contrario retorne el error
         */
        return ($errorSms == '' && $errorNum == '') ? $arreglo : 'error:' . $errorNum . $errorSms;
    }

    /**
     * Metodo para insertar registros
     * @param type $arreglo
     */
    function insertRegistros($arreglo, $fechapro = NULL) {

        foreach ($arreglo as $value) {
            $value["cargue"] = 'web';
            $value["idbase"] = $this->idbase;
            $this->CargaexcelModel->insert("registros", $value);
//            if (is_numeric($idinser)) {
//                $pendientes = $this->CargaexcelModel->buscar("usuarios", 'coalesce(pendientes,0) pendientes', 'id=' . $this->idusuario, 'row');
//                $pend["pendientes"] = $pendientes["pendientes"] + 1;
//                $this->CargaexcelModel->update("usuarios", $this->idusuario, $pend);
//            }
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

    public function procesarCarga() {
        $data = $this->input->post();
        if ($data["tipo"] == 'confirmado') {
            $sql = "UPDATE registros set estado='2' where idbase=" . $data["idbase"];
            $update = $this->CargaexcelModel->ejecutar($sql, 'update');
            $pendientes = $this->CargaexcelModel->buscar("usuarios", 'coalesce(pendientes,0) pendientes', 'id=' . $this->idusuario, 'row');
            $pend["pendientes"] = $update + $pendientes["pendientes"];
            $this->CargaexcelModel->update("usuarios", $this->idusuario, $pend, 'row');
            $this->CargaexcelModel->update("bases", $data["idbase"], array("estado" => '1'));
            $filas["mensaje"] = $update . " Registros Procesados con exito";
        } else {
            $del["idbase"] = $data["idbase"];
            $this->CargaexcelModel->borrar("archivos", $del);
            $this->CargaexcelModel->borrar("bases", $data["idbase"]);
            $this->CargaexcelModel->borrar("registros", $del);
            $filas["mensaje"] = " Registros Cancelados con exito";
        }

        echo json_encode($filas);
    }

}
