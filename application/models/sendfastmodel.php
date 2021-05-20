<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class SendfastModel extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getQuantityBase() {
        $sql = "SElECT COUNT(*) total FROM registros where idbase=" . $this->session->userdata("base");
        $data = $this->db->query($sql);
        $res = $data->row_array();
        return $res["total"];
    }

    public function createBase($data) {
        $this->db->trans_start();
        $idbase = false;
        $bases = array(
            "idempresa" => $this->session->userdata("idempresa"),
            "idusuario" => $this->session->userdata("idusuario"),
            "nombre" => 'web_' . date("Y-m-d H:i"),
            "fecha" => date("Y-m-d H:i"),
            "registros" => $data["quantitynumbers"],
        );

        $this->db->insert("bases", $bases);
        $idbase = @$this->db->insert_id();


        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $idbase;
    }

    public function SaveRecord($param) {
        $this->db->trans_start();
        $finish = false;
        $id = array();
        $percent = 0;
        $datos_clientes = file_get_contents($param["path"]);
        $data = json_decode($datos_clientes, true);

        $this->benchmark->mark('code_start');

        foreach ($data["data"] as $value) {
            $carrier = substr($value["number"], 0, 3);
            $carrierfind = $this->Buscar("carries", "codigo", "prefijos ilike '%" . $carrier . "%'", 'row');
            $carrierfind = $this->db
                    ->select("codigo")
                    ->from("carries")
                    ->where("prefijos ilike '%" . $carrier . "%'")
                    ->get()
                    ->row_array();

            if (count($carrierfind) == 0) {
                $carrierfind["codigo"] = '';
            }

            $prefencias = explode(",", $data["preferences"]);
            $indice = $carrierfind["codigo"] - 1;
            $msg = $this->LimpiaMensaje($value["message"]);
            $record = array(
                "idbase" => $data["idbase"],
                "numero" => $value["number"],
                "mensaje" => $msg,
//                                    "estado" => "2",
                "estado" => $data["state"],
                "idcarrie" => $carrierfind["codigo"],
                "fechacargue" => date("Y-m-d H:i"),
                "idcanal" => $prefencias[$indice],
                "nota" => $data["note"],
                "orden" => $value["order"]
            );

            $this->db->insert("registros", $record);
            $id[] = @$this->db->insert_id();
        }

        $pendientes = $this->buscar("usuarios", 'coalesce(pendientes,0) pendientes', 'id=' . $this->session->userdata("idusuario"), 'row');
        $pend["pendientes"] = $data["quantitynumbers"] + $pendientes["pendientes"];

        $this->update("usuarios", $this->session->userdata("idusuario"), $pend, 'row');
        $this->update("bases", $param["idbase"], array("estado" => '1'));

        $this->benchmark->mark('code_end');

        $resp = array(
            "status" => true,
            "idbase" => $data["idbase"],
            "time" => $this->benchmark->elapsed_time('code_start', 'code_end')
        );



        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
        } else {
            $this->db->trans_commit();
        }
        return $resp;
    }

    public function validaPrefijo($numero) {
        $num = substr($numero, 0, 3);
        $where = "prefijos ILIKE '%{$num}%'";
        $existe = $this->buscar('carries', 'codigo', $where, 'row');
        return (isset($existe)) ? $existe : FALSE;
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
                    if ($num == '0000000' || $num[0] < 1) {
                        $rta[] = FALSE;
                        $rta[] = 'Numero Invalido0';
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
