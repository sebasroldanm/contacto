<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class DescargaArchivo extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("AdministradorModel");
    }

    /**
     * metodo para cargar la vista princiapl
     */
    public function index() {
        /**
         * Se listan los archivos planos y se lo pasan  a la vista
         */

        $data["vista"] = "informes/descargaarchivo";
        $this->load->view("template", $data);
    }

    /**
     * Metodo que permite ejecutar la descarga del texto plano
     * @param type $control
     * @param type $carpeta
     * @param type $nombre
     */
    public function descargar($control, $carpeta, $nombre) {
        $archivo = $control . "/" . $carpeta . "/" . $nombre;
        /**
         * Se vericia que el arhivo exista para poderlo descargar
         */
        if (file_exists($archivo)) {
            header('Content-disposition: attachment; filename=' . $archivo);
            header('Content-type: text/plain');

            ob_clean();
            flush();
            readfile($archivo);
        }
    }

    public function CargarTabla() {
	
        $archivos = $this->Directorios("planos/" . $this->session->userdata("idusuario"));
        
        $idusuario = $this->session->userdata("idusuario");
        $sql = "DELETE FROM tmp_archivos WHERE idusuario=" . $idusuario;
	
        $this->AdministradorModel->ejecutar2($sql, 'delete');
	
        if ($archivos != FALSE) {
            foreach ($archivos as $value) {
                if ($value["nombre"] != '') {
                    $fec = preg_replace("/[^0-9]/", "", $value["nombre"]);
                    $value["fecha"] = date("Y-m-d", strtotime($fec));
                }
                $value["idusuario"] = $idusuario;
                $this->AdministradorModel->insert("tmp_archivos", $value);
            }
        }
        $html = '<a href="#" onclick=descargar($1)><img src="' . base_url() . 'imagenes/descargar.png"></a>';
        $this->datatables->edit_column('ruta', $html, 'id,ruta');

	//        echo $idusuario."asdasd";exit;
	echo $this->datatables
                ->select("id,nombre,size,fecha,ruta")
                ->from("tmp_archivos")
                ->where("idusuario", $idusuario)
                ->generate();
    }

    public function obtienelink() {
        $data = $this->input->post();
        $where = "id=" . $data["id"];
        $res = $this->AdministradorModel->buscar("tmp_archivos", 'ruta', $where, 'row');
        echo json_encode($res);
    }

}
