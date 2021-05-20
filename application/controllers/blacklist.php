<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blacklist extends MY_Controller {

    private $tabla;

    public function __construct() {

        parent::__construct();
        $this->load->library('excel');
        $this->load->library("reader");
        $this->load->model("AdministradorModel");
        $this->tabla = "blacklist";
    }

    /**
     * Metodo para cargar la vista principal de administrador
     */
    public function index() {

        /**
         * Metodos que se obtiene para precargar datos en los formularios
         */
        $data["usuarios"] = $this->AdministradorModel->buscar("usuarios order by usuario", '*', '');
        $data["vista"] = 'blacklist/init';
        $this->load->view('template', $data);
    }

    public function getList() {

        if ($this->session->userdata("idperfil") != 1) {
            $this->datatables->where("user_id", $this->session->userdata("idusuario"));
        }

        echo $this->datatables
                ->select("b.id,b.numero,u.usuario,b.date_insert")
                ->from("blacklist b")
                ->join("usuarios u", "b.user_id=u.id", "left")
                ->where(array("status_id" => 1))
                ->generate();
    }

    public function getNumber($id) {
        $where = "id=" . $id;
        $com = $this->AdministradorModel->buscar("blacklist", '*', $where, 'row');
        echo json_encode($com);
    }

    public function add() {
        $data = $this->input->post();
        $id = $data["id"];
        unset($data["id"]);
        $user = ($this->session->userdata("idperfil") == 1 && $data["idusuario"] != "0") ? $data["idusuario"] : $this->session->userdata("idusuario");
        $data["user_id"] = $user;
        $data["status_id"] = 1;
        unset($data["idusuario"]);

        $where = "numero = '" . $data["numero"] . "' and user_id=" . $user;
        $com = $this->AdministradorModel->buscar("blacklist", '*', $where, 'row');

        if ($id == '' && $com == false) {
            $data["date_insert"] = date("Y-m-d H:i");
            $this->AdministradorModel->insert("blacklist", $data);
        } else {
            $insert["numero"] = $data["numero"];

            if ($com != false) {
                $insert["date_update"] = date("Y-m-d H:i");
                $this->AdministradorModel->update("blacklist", $com["id"], $insert);
            } else {
                $insert["date_insert"] = date("Y-m-d H:i");
                $this->AdministradorModel->insert("blacklist", $insert);
            }

            $data["date_update"] = date("Y-m-d H:i");
            $this->AdministradorModel->update("blacklist", $id, $data);
        }

        echo json_encode(["success" => true]);
    }

    public function deleteNumber($id) {
        $com = $this->AdministradorModel->borrar("blacklist", $id);
        echo json_encode(["success" => true]);
    }

    public function confirmation() {
        $data = $this->input->post();
        $com = $this->AdministradorModel->update("blacklist", array("archivo_id" => $data["archivo_id"]), array("status_id" => 1));
        echo json_encode(["success" => true]);
    }

    public function deleteNumberItem() {
        $data = $this->input->post();
        $this->AdministradorModel->borrar("blacklist", $data["id"]);
        $data = $this->AdministradorModel->buscar("blacklist", "*", "archivo_id=" . $data["archivo_id"]);
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
                
                //$validaNum = $this->validaNumero($value["A"]);
                if ($value["A"] !='') {
                    $where = "numero = '" . $value["A"] . "' and user_id=" . $user;
                    $com = $this->AdministradorModel->buscar("blacklist", '*', $where, 'row');

                    $insert["user_id"] = $user;
                    $insert["numero"] = $value["A"];
                    $insert["status_id"] = 3;
                    $insert["archivo_id"] = $archivo_id;

                    if ($com != false) {
                        $insert["date_update"] = date("Y-m-d H:i");
                        $this->AdministradorModel->update("blacklist", $com["id"], $insert);
                    } else {
                        $insert["date_insert"] = date("Y-m-d H:i");
                        $this->AdministradorModel->insert("blacklist", $insert);
                    }
                } 
                //else {
//                    $this->insertaErrores($validaNum[1], $value, $archivo_id, $i);
                //}
            }
        }

        $where = "archivo_id=" . $archivo_id;
        $resp["data"] = $this->AdministradorModel->buscar("blacklist", '*', $where);
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
                        $rta[] = 'Numero Invalido6';
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

}
