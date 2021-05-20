<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ExceltemplateModel extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getWhereFilter($in = NULL, $client_id) {
        $filter = '';
        $where = '';
        $cont = 0;

        if (isset($in["filter1"])) {
            foreach ($in["filter1"] as $value) {
                $filter .= ($filter == '') ? '' : " OR";
                $filter .= " filtro1='" . $value . "'";
                $cont++;
            }
            $where = "(" . $filter . ")";
        }



        if (isset($in["filter2"])) {
            $filter = '';
            foreach ($in["filter2"] as $value) {
                $filter .= ($filter == '') ? '' : " OR";
                $filter .= " filtro2='" . $value . "'";
                $cont++;
            }
            $where .= ($where == '') ? '' : " AND ";
            $where .= " (" . $filter . ")";
        }

        if (isset($in["filter3"])) {
            $filter = '';
            foreach ($in["filter3"] as $value) {
                $filter .= ($filter == '') ? '' : " OR";
                $filter .= " filtro3 ='" . $value . "'";
                $cont++;
            }
            $where .= ($where == '') ? '' : " AND ";
            $where .= " (" . $filter . ")";
        }
        if (isset($in["filter4"])) {
            $filter = '';
            foreach ($in["filter4"] as $value) {
                $filter .= ($filter == '') ? '' : " OR";
                $filter .= " filtro4='" . $value . "'";
                $cont++;
            }
            $where .= ($where == '') ? '' : " AND ";
            $where .= " (" . $filter . ")";
        }

        if (isset($in["filter5"])) {
            $filter = '';
            foreach ($in["filter5"] as $value) {
                $filter .= ($filter == '') ? '' : " OR";
                $filter .= " filtro5='" . $value . "'";
                $cont++;
            }
            $where .= ($where == '') ? '' : " AND ";
            $where .= " (" . $filter . ")";
        }
        if (isset($in["filter6"])) {
            $filter = '';
            foreach ($in["filter6"] as $value) {
                $filter .= ($filter == '') ? '' : " OR";
                $filter .= " filtro6='" . $value . "'";
                $cont++;
            }
            $where .= ($where == '') ? '' : " AND ";
            $where .= " (" . $filter . ")";
        }



        if ($client_id != null) {
            $where .= ($where == '') ? '' : ' AND';
            $where .= " client_id=" . $client_id;
        }


        return $where;
    }

    public function getFilter($in, $client_id) {

        $where = $this->getWhereFilter($in, $client_id);

        if ($where != false) {
            $sql = "
            select filtro1
            from template_detail 
            where " . $where . "
            group by 1 order by 1";

            $res["filter1"] = $this->db->query($sql)->result_array();

            $sql = "
            select filtro2
            from template_detail 
            where " . $where . "
            group by 1 order by 1";
            $res["filter2"] = $this->db->query($sql)->result_array();
            $sql = "
            select filtro3
            from template_detail 
            where " . $where . "
            group by 1 order by 1";
            $res["filter3"] = $this->db->query($sql)->result_array();
            $sql = "
            select filtro4
            from template_detail 
            where " . $where . "
            group by 1 order by 1";
            $res["filter4"] = $this->db->query($sql)->result_array();
            $sql = "
            select filtro5
            from template_detail 
            where " . $where . "
            group by 1 order by 1";
            $res["filter5"] = $this->db->query($sql)->result_array();
            $sql = "
            select filtro6
            from template_detail 
            where " . $where . "
            group by 1 order by 1";
            $res["filter6"] = $this->db->query($sql)->result_array();
        } else {
            $res = false;
        }

        return $res;
    }

}
