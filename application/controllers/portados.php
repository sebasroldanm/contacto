<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Portados extends MY_Controller {

    private $tabla;

    public function __construct() {

        parent::__construct();
        $this->load->library('excel');
        $this->load->library("reader");
        $this->load->model("AdministradorModel");
        $this->load->model("CargaexcelModel");
        $this->tabla = "portados";
    }

    /**
     * Metodo para cargar la vista principal de administrador
     */
    public function index() {

        /**
         * Metodos que se obtiene para precargar datos en los formularios
         */
        $data["carries"] = $this->AdministradorModel->buscar("carries", '*', '');
        $data["vista"] = 'portados/init';
        $this->load->view('template', $data);
    }

    public function getList() {

        echo $this->datatables
                ->select("id,numero,previuos,current_carrie,date_insert")
                ->from("vportados")
                ->generate();
    }

    public function getNumber($id) {
        $where = "id=" . $id;
        $com = $this->AdministradorModel->buscar("portados", '*', $where, 'row');
        echo json_encode($com);
    }

    public function add() {
        $data = $this->input->post();
        $id = $data["id"];
        unset($data["id"]);
        $data["status_id"] = 1;
        unset($data["idusuario"]);
        $where = "numero = '" . $data["numero"] . "'";
        $com = $this->AdministradorModel->buscar("portados", '*', $where, 'row');

        if ($id == '' && $com == false) {
            $data["date_insert"] = date("Y-m-d H:i");
            $this->AdministradorModel->insert("portados", $data);
        } else {
            $insert["numero"] = $data["numero"];
            $insert["previous_carrie_id"] = $data["previous_carrie_id"];
            $insert["current_carrie_id"] = $data["current_carrie_id"];

            if ($com != false) {
                $insert["date_update"] = date("Y-m-d H:i");
                $this->AdministradorModel->update("portados", $com["id"], $insert);
            } else {

                $insert["date_insert"] = date("Y-m-d H:i");
                $this->AdministradorModel->insert("portados", $insert);
            }

            $data["date_update"] = date("Y-m-d H:i");
            $this->AdministradorModel->update("portados", $id, $data);
        }

        echo json_encode(["success" => true]);
    }

    public function deleteNumber($id) {
        $com = $this->AdministradorModel->borrar("portados", $id);
        echo json_encode(["success" => true]);
    }

    public function confirmation() {
        $data = $this->input->post();
        $com = $this->AdministradorModel->update("portados", array("archivo_id" => $data["archivo_id"]), array("status_id" => 1));
        echo json_encode(["success" => true]);
    }

    public function deleteNumberItem() {
        $data = $this->input->post();
        $this->AdministradorModel->borrar("portados", $data["id"]);
        $data = $this->AdministradorModel->buscar("portados", "*", "archivo_id=" . $data["archivo_id"]);
        echo json_encode(["success" => true, "data" => $data]);
    }

    public function uploadExcel() {
        $data = $this->input->post();
        $datas = array();
        $name = $_FILES["file_excel"]["name"];
        $archivo = $_FILES["file_excel"]["tmp_name"];

        //        $ruta = $this->crearRutaCarpeta(FCPATH . "\tmp\\" . date("Y-m-d"));
        $config['upload_path'] = FCPATH . 'tmp';

//        $config['upload_path'] = FCPATH . '\tmp';
        $config['allowed_types'] = '*';
        $config['file_name'] = $name;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_excel')) {
            print_r($this->upload->display_errors());
        } else {

            $datas = array('upload_data' => $this->upload->data());
        }

        $objPHPExcel = PHPExcel_IOFactory::load($archivo);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);


        $base["nombre"] = $name;
        $base["ruta"] = $datas["upload_data"]["full_path"];
        $base["idusuario"] = $this->session->userdata("idusuario");
        $base["registros"] = count($sheetData);
        $base["fecha"] = date("Y-m-d H:i");

        $archivo_id = $this->AdministradorModel->insert("archivos", $base);

        $user = ($this->session->userdata("idperfil") == 1 && $data["idusuario"] != "0") ? $data["idusuario"] : $this->session->userdata("idusuario");
        foreach ($sheetData as $i => $value) {
            if ($i > 1) {
                $validaNum = $this->validaNumero($value["A"]);
                if ($validaNum != false) {
                    if ($value["A"] != '') {
                        $where = "numero = '" . $value["A"] . "'";
                        $com = $this->AdministradorModel->buscar("portados", '*', $where, 'row');
                        $where = "nombre ilike '%" . strtolower($value["B"]) . "%'";
                        $previous = $this->AdministradorModel->buscar("carries", '*', $where, 'row');
                        $where = "nombre ilike '%" . strtolower($value["C"]) . "%'";
                        $current = $this->AdministradorModel->buscar("carries", '*', $where, 'row');

                        $insert["numero"] = $value["A"];
                        $insert["status_id"] = 3;
                        $insert["archivo_id"] = $archivo_id;
                        $insert["previous_carrie_id"] = $previous["id"];
                        $insert["current_carrie_id"] = $current["id"];
                        if ($previous != false && $current != false) {
                            if ($com != false) {
                                $insert["date_update"] = date("Y-m-d H:i");
                                $this->AdministradorModel->update("portados", $com["id"], $insert);
                            } else {
                                $insert["date_insert"] = date("Y-m-d H:i");
                                $this->AdministradorModel->insert("portados", $insert);
                            }
                        } else {
                            $this->insertaErrores("Problemas con los carries", $value, $archivo_id, $i);
                        }
                    }
                } else {
                    $this->insertaErrores($validaNum[1], $value, $archivo_id, $i);
                }
            }
        }

        $where = "archivo_id=" . $archivo_id;
        $join = " JOIN carries pre ON b.previous_carrie_id=pre.id
                JOIN carries cur ON b.current_carrie_id=cur.id
                ";

        $where = "archivo_id=" . $archivo_id;
        $campos = "b.id,b.numero,pre.nombre previuos,cur.nombre current_carrie,b.date_insert";
        $resp["data"] = $this->AdministradorModel->buscar("portados b " . $join, $campos, $where);
        $resp["archivo_id"] = $archivo_id;
        echo json_encode($resp);
    }

    public function uploadExcelBases() {
        $data = $this->input->post();
        $datas = array();
        $name = $_FILES["file_excel"]["name"];
        $archivo = $_FILES["file_excel"]["tmp_name"];

        //        $ruta = $this->crearRutaCarpeta(FCPATH . "\tmp\\" . date("Y-m-d"));
        $config['upload_path'] = FCPATH . 'tmp';

//        $config['upload_path'] = FCPATH . '\tmp';
        $config['allowed_types'] = '*';
        $config['file_name'] = $name;
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('file_excel')) {
            print_r($this->upload->display_errors());
        } else {

            $datas = array('upload_data' => $this->upload->data());
        }

        $objPHPExcel = PHPExcel_IOFactory::load($archivo);
        $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);


        $base["nombre"] = $name;
        $base["ruta"] = $datas["upload_data"]["full_path"];
        $base["idusuario"] = $this->session->userdata("idusuario");
        $base["registros"] = count($sheetData);
        $base["fecha"] = date("Y-m-d H:i");

        $archivo_id = $this->AdministradorModel->insert("archivos", $base);

        $user = ($this->session->userdata("idperfil") == 1 && $data["idusuario"] != "0") ? $data["idusuario"] : $this->session->userdata("idusuario");
        $sql = "delete from validate_bases where user_id=" . $this->session->userdata("idusuario");
        $this->AdministradorModel->ejecutar($sql, "update");
        
        foreach ($sheetData as $i => $value) {
            if ($i > 1) {
                $validaNum = $this->validaNumero($value["A"]);
                if ($validaNum != false) {
                    if ($value["A"] != '') {


                        $where = "p.numero = '" . $value["A"] . "'";
                        $campos = "p.id,p.numero,ca.nombre portado";
                        $join = " JOIN carries ca ON ca.id=p.current_carrie_id";
                        $com = $this->AdministradorModel->buscar("portados p " . $join, $campos, $where, 'row');

                        $insert["response"] = 'ok';

                        if ($com != false) {
                            $insert["response"] = "portado a " . $com["portado"];
                        }

                        $insert["numero"] = $value["A"];
                        $insert["archivo_id"] = $archivo_id;
                        $insert["user_id"] = $this->session->userdata("idusuario");

                        $this->AdministradorModel->insert("validate_bases", $insert);
                    }
                } else {
                    $this->insertaErrores($validaNum[1], $value, $archivo_id, $i);
                }
            }
        }

        $where = "archivo_id=" . $archivo_id;
        $join = " JOIN carries pre ON b.previous_carrie_id=pre.id
                JOIN carries cur ON b.current_carrie_id=cur.id
                ";

        $where = "archivo_id=" . $archivo_id;
        $campos = "b.id,b.numero,pre.nombre previuos,cur.nombre current_carrie,b.date_insert";
        $resp["data"] = $this->AdministradorModel->buscar("portados b " . $join, $campos, $where);
        $resp["archivo_id"] = $archivo_id;
        echo json_encode($resp);
    }

    function insertaErrores($msj, $arreglo, $idbase, $fila) {
        $error["mensaje"] = $this->LimpiaMensaje((isset($arreglo[2]) ? $arreglo[2] : ''));
        $error["numero"] = $arreglo[1];
        $error["error"] = $msj;
        $error["estado"] = 3;
        $error["idbase"] = $idbase;
        $error["fila"] = $fila;
        $error["fecha"] = date("Y-m-d H:i:s");
        $this->CargaexcelModel->insert("errores", $error);
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
                        $rta[] = 'Numero Invalido4';
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

    public function downloadPortados($file_id) {
        $tituloReporte = '';
        /**
         * Se obtiene los datos de la tabla errror segun su base
         */
        $where = "archivo_id=" . $file_id;
        $datos = $this->CargaexcelModel->Buscar("validate_bases", '*', $where);

        /**
         * Se instancia el objeto '$objPHPExcel' para crear el archivo
         */
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Contacto sms"); // Nombre del autor->setLastModifiedBy("Contacto sms") //Ultimo usuario que lo modificó->setTitle("Reporte Errrores Excel") // Titulo->setSubject("Reporte Errrores Excel") //Asunto->setDescription("Reporte de Errores") //Descripción->setKeywords("Reporte de Errores") //Etiquetas->setCategory("Reporte excel"); //Categorias


        $tituloReporte = "Numeros Portados";
        $titulosColumnas = array('NUMERO', 'Portado');


// Se agregan los titulos del reporte
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $titulosColumnas[0])
                ->setCellValue('B1', $titulosColumnas[1]);

        $cont = 2;
        /**
         * Se llena el archivo
         */
        foreach ($datos as $i => $value) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $cont, $value['numero'])->setCellValue('B' . $cont, $value['response']);
            $cont++;
        }

        /**
         * Se agrega titulo
         */
        $objPHPExcel->getActiveSheet()->setTitle('Validacion_portados');
        $objPHPExcel->setActiveSheetIndex(0);
        /**
         * Se agregan los encabezados para que se genere la descarga
         */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=bases_validadas_' . date("Y-m-d") . '.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

}
