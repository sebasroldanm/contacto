<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reportes extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('excel');
        $this->load->library("reader");
        $this->load->model("ReportesModel");
    }

    public function index() {
        
    }

    /**
     * Metodo para obtener los datos del reporte mensual
     */
    public function mensual() {
        $data["mensual"] = $this->ReportesModel->mensual();
        $data["vista"] = "informes/mensual";
        $this->load->view("template", $data);
    }

    /**
     * Metodo para obtener los datos del reporte diario
     */
    public function diario() {
        $data["diario"] = $this->ReportesModel->diario();
        $data["vista"] = "informes/diario";
        $this->load->view("template", $data);
    }

    /**
     * Metodo para obtener los datos del reporte hoy
     */
    public function hoy() {
        $data["hoy"] = $this->ReportesModel->hoy();
        $data["vista"] = "informes/hoy";
        $this->load->view("template", $data);
    }

    /**
     * Metodo para obtener los datos del reporte por fechas
     */
    public function fecha() {
        $data["vista"] = "informes/fecha";
        $this->load->view("template", $data);
    }

    /**
     * metodo para reordenar la fecha
     */
    public function datosFecha() {

        $data = $this->input->post();

        $inicio = date("Y-m-d", strtotime($data["inicio"]));
        $final = date("Y-m-d", strtotime($data["final"]));
        $res = $this->ReportesModel->fecha($inicio, $final);
        echo json_encode($res);
    }

    public function bases() {
        $data["bases"] = $this->ReportesModel->bases();
        $data["vista"] = "informes/bases";
        $this->load->view("template", $data);
    }

    public function enviosCanales() {
        $data["empresas"] = $this->ReportesModel->Buscar("empresas ORDER BY nombre", 'id,nombre', NULL, NULL, 'produccion');
        $data["canales"] = $this->ReportesModel->Buscar("canales", 'id,nombre', NULL, NULL, 'produccion');
        $data["carrier"] = $this->ReportesModel->Buscar("carries", 'codigo,nombre', NULL, NULL, 'produccion');
        $data["vista"] = "informes/enviocanales";
        $this->load->view("template", $data);
    }

//    public function getEnvioCanales() {
//        $data = $this->input->post();
//
//        $where = "fechaenvio between '" . $data["inicio"] . " 00:00' and '" . $data["final"] . " 23:59' GROUP BY idcanal,canales.nombre";
//        $join = " JOIN canales ON canales.id = registros.idcanal";
//        
//        $datos = $this->ReportesModel->buscar('registros ' . $join, 'canales.nombre canal,count(*) envio', $where,'debug');
//        echo json_encode($datos);
//    }
    public function getEnvioCanales() {
        $data = $this->input->post();
        $datos = $this->ReportesModel->reporteBases($data);
        echo json_encode($datos);
    }

    public function informesEstados() {
        $data["vista"] = "informes/estados";
        $this->load->view("template", $data);
    }

    public function getEstados() {
        $data = $this->input->post();
        $where = $where = "fechaprogramado > '" . date("Y-m-d") . "'";

        $datos = $this->ReportesModel->buscar('registros ', 'numero,mensaje,fechaprogramado', $where);
        echo json_encode($datos);
    }

    public function disponibles() {

        $data["usuarios"] = $this->ReportesModel->Buscar("usuarios ORDER BY nombre", 'id,nombre', NULL, NULL, 'produccion');
        $data["vista"] = "informes/disponibles";
        $this->load->view("template", $data);
    }

    public function getDisponibles() {
        $data = $this->input->post();
        $datos = $this->ReportesModel->reporteDisponible($data);
        echo json_encode($datos);
    }

    public function errores() {
        $data["vista"] = "informes/errores";
        $this->load->view("template", $data);
    }

    public function getErrores() {
        $data = $this->input->post();
        $idbase = '';
        if ($data["idbase"] != '') {
            $idbase = ' AND idbase=' . $data["idbase"];
        }
        $where = "fecha between '" . $data["inicio"] . " 00:00' and '" . $data["final"] . " 23:59' " . $idbase;
        $datos = $this->ReportesModel->buscar('errores ', 'idbase,numero,mensaje,nota,error', $where);
        echo json_encode($datos);
    }

    public function gerencias() {
        $data["vista"] = "informes/gerencias";
        $this->load->view("template", $data);
    }

    public function getGerencias() {
        $this->datatables->set_database("natura");

        echo $this->datatables
                ->select("gerencia,codigo_gerencia,cupo_gerencia,sector,codigo_sector,cupo_sector")
                ->from("datagerencias")
                ->generate();
    }

    public function consumo() {
        $data["vista"] = "informes/consumo";
        $this->load->view("template", $data);
    }

    public function getConsumo() {
        $draw = 1;
        $in = $this->input->post();

        $sql = "
             select a.id,a.usuario, count(b.id) as consumo
            from usuarios a, registros b, bases c
            where b.fechaenvio > '" . date("Y-m-01") . " 00:00' and b.fechaenvio <= '" . $in["ffinal"] . " 23:59'
            and b.idbase = c.id and c.idusuario = a.id 
            and b.estado in ('1','7')
            group by 1,2 order by 2
            
                ";

        $datos = $this->AdministradorModel->ejecutar($sql);

        foreach ($datos as $i => $value) {


            $query = "
                    select d.nit,e.maximo-a.historicos  as maximo
            from usuarios a, empresas d, servicios e
            where a.idempresa = d.id and a.idservicio = e.id
            and a.id =" . $value["id"];

            $param = $this->AdministradorModel->ejecutar($query);
            if (count($param) > 0) {
                $consumo = $datos[$i]["consumo"];
                unset($datos[$i]["consumo"]);
                unset($datos[$i]["id"]);
                $datos[$i]["nit"] = $param[0]["nit"];
                $datos[$i]["consumo"] = $consumo;
                $datos[$i]["maximo"] = $param[0]["maximo"] - $consumo;
            }
        }


        /* $sql="
          select d.nit,a.usuario,count(b.id), e.maximo
          from usuarios a, registros b, bases c, empresas d, servicios e
          where b.fechaenvio > '" . date("Y-m-01") . "' and b.fechaenvio <= '" . $in["ffinal"] . " 23:59'
          and b.idbase = c.id and c.idusuario = a.id
          and a.idempresa = d.id and a.idservicio = e.id
          group by 1,2,4 order by 1
          "; */


        //$respuesta = $this->dataTable($datos);
        echo json_encode($this->dataTable($datos));
        //echo json_encode($datos);
    }

    public function getGerenciasExcel() {
        $this->datatables->set_database("natura");

        $query = "
                    select gerencia,codigo_gerencia,cupo_gerencia,sector,codigo_sector,cupo_sector
            from datagerencias";

        $datos = $this->AdministradorModel->ejecutar($query);
        /**
         * Se instancia el objeto '$objPHPExcel' para crear el archivo
         */
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->getProperties()->setCreator("Contacto sms"); // Nombre del autor->setLastModifiedBy("Contacto sms") //Ultimo usuario que lo modificó->setTitle("Reporte Errrores Excel") // Titulo->setSubject("Reporte Errrores Excel") //Asunto->setDescription("Reporte de Errores") //Descripción->setKeywords("Reporte de Errores") //Etiquetas->setCategory("Reporte excel"); //Categorias


        $tituloReporte = "Gerencias";
        $titulosColumnas = array('GERENCIA', 'CODIGO_GERENCIA',
            'CUPO_GERENCIA', 'SECTOR', "CODIGO_SECTOR", "CUPO_SECTOR");


// Se agregan los titulos del reporte
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $titulosColumnas[0])
                ->setCellValue('B1', $titulosColumnas[1])
                ->setCellValue('C1', $titulosColumnas[2])
                ->setCellValue('D1', $titulosColumnas[3])
                ->setCellValue('E1', $titulosColumnas[4])
                ->setCellValue('F1', $titulosColumnas[5]);

        $cont = 2;
        /**
         * Se llena el archivo
         */
        foreach ($datos as $i => $value) {
            $objPHPExcel->setActiveSheetIndex(0)->
                    setCellValue('A' . $cont, $value['gerencia'])
                    ->setCellValue('B' . $cont, $value['codigo_gerencia'])
                    ->setCellValue('C' . $cont, $value['cupo_gerencia'])
                    ->setCellValue('D' . $cont, $value['sector'])
                    ->setCellValue('E' . $cont, $value['codigo_sector'])
                    ->setCellValue('F' . $cont, $value['cupo_sector']);
            $cont++;
        }

        /**
         * Se agrega titulo
         */
        $objPHPExcel->getActiveSheet()->setTitle('Gerencias');
        $objPHPExcel->setActiveSheetIndex(0);
        /**
         * Se agregan los encabezados para que se genere la descarga
         */
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=Gerencias_' . date("Y-m-d") . '.xls');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;

        print_r($param);
        exit;
    }

    public function responses() {
        $data["empresas"] = $this->ReportesModel->Buscar("empresas ORDER BY nombre", 'id,nombre', NULL, NULL, 'produccion');
        $data["canales"] = $this->ReportesModel->Buscar("canales", 'id,nombre', NULL, NULL, 'produccion');
        $data["carrier"] = $this->ReportesModel->Buscar("carries", 'codigo,nombre', NULL, NULL, 'produccion');
        $data["vista"] = "informes/responses";
        $this->load->view("template", $data);
    }

    public function getResponses() {
        $in = $this->input->post();


        if ($in["idcanal"] != 0) {
            $this->datatables->where("idcanal", $in["idcanal"]);
        }

        $this->datatables->where("fecha >=", $in["inicio"] . " 00:00");
        $this->datatables->where("fecha <=", $in["final"] . " 23:59");

        echo $this->datatables
                ->select("id,canal,canal,source,numero,mensaje,fecha")
                ->from("vrespuesta")
                ->generate();
    }

    public function consolidado() {
        $data["vista"] = "informes/consolidado";
        $this->load->view("template", $data);
    }

    public function getConsolidado() {
        $role = "";

        if ($this->session->userdata("idperfil") != 1) {
            $role = "WHERE u.id=" . $this->session->userdata("idusuario");
        }

        $sql = "
            select u.id,u.usuario,
            u.enviados enviados,
            ser.maximo - u.enviados disponible, CASE WHEN ser.tiposervicio=1 THEN 'Bolsa' ELSE 'Mensual' END servicio,ser.tiposervicio
            from usuarios u
            JOIN servicios ser ON ser.id=u.idservicio
	    $role
            ORDER by 3 DESC
            
            ";





        $datos = $this->AdministradorModel->ejecutar($sql);

//        $respuesta = $this->dataTable($datos);
//        $respuesta["draw"] = 1;
//        echo json_encode($respuesta);
        echo json_encode(array("data" => $datos));
    }

}
