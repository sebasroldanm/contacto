<?php

class MY_Model extends CI_Model {

    public $dbproduccion;
    public $dbmysql;

    function __construct() {
        parent::__construct();
        $this->dbproduccion = $this->load->database('produccion', TRUE);
        $this->dbmysql = $this->load->database('dbmysql', TRUE);
    }

    /**
     * 
     * @param type $tabla
     * @param type $campos
     * @param type $where
     * @param type $tipo
     * @param type $db
     * @return boolean
     */
    function Buscar($tabla, $campos, $where = NULL, $tipo = NULL, $db = NULL) {



        $limit = ($tipo == 'row') ? " limit 1" : '';

        $where = ($where == NULL) ? '' : 'WHERE ' . $where;

        $sql = "SELECT $campos 
                FROM $tabla
                $where $limit";

        if ($tipo == "xdebug") {
            print_r($sql);
            exit;
        }

        if ($db == 'produccion') {
            $datos = $this->dbproduccion->query($sql);
        } else if ($db == 'mysql') {
            $datos = $this->dbmysql->query($sql);
        } else {
            $datos = $this->db->query($sql);
        }


        if ($datos->num_rows() > 0) {
            if ($tipo == 'debug') {
                print_r($sql);
                exit;
            } else if ($tipo == 'pre') {
                echo"<pre>";
                print_r($datos->result_array());
                echo"</pre>";
                exit;
            } else if ($tipo == 'row') {
                return $datos->row(null, 'array');
            } else {
                return $datos->result_array();
            }
        } else {
            return FALSE;
        }
    }

    public function ejecutar($sql, $print = NULL) {
        if ($print == 'debug') {
            print_r($sql);
            exit;
        } else {
            $datos = $this->db->query($sql);

            if ($print == 'row') {
                return $datos->row(null, 'array');
            } else if ($print == 'update') {
                if ($this->db->affected_rows() > 0) {
                    return $this->db->affected_rows();
                } else {
                    echo $this->db->last_query();
                }
            } else if ($print == 'delete') {
                return $this->db->affected_rows();
            } else {
                if ($print == 'result') {
                    return $datos->result();
                } else {
                    return $datos->result_array();
                }
            }
        }
    }

    public function ejecutar2($sql, $print = NULL) {
        if ($print == 'debug') {
            print_r($sql);
            exit;
        } else {
            $datos = $this->db->query($sql);

            if ($print == 'row') {
                return $datos->row(null, 'array');
            } else if ($print == 'update') {
                if ($this->db->affected_rows() > 0) {
                    return $this->db->affected_rows();
                } else {
                    echo $this->db->last_query();
                }
            } else {
                if ($print == 'result') {
                    return $datos->result();
                } else if ($print == 'delete') {
                    return $this->db->affected_rows();
                } else {
                    return $datos->result_array();
                }
            }
        }
    }

    /**
     * Metodo generico para eliminar un registro
     * @param int $id
     * @param string $table
     * @return string
     */
    function borrar($table, $id, $tipo = NULL) {
        $this->db->trans_begin();
        if (is_array($id)) {
            $this->db->where(key($id), $id[key($id)]);
        } else {
            $this->db->where('id', $id);
        }

        $this->db->delete($table);

        if ($tipo == 'debug') {
            print_r($this->db->last_query());
            $this->db->trans_rollback();
            exit;
        } else {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return ($this->db->affected_rows() > 0) ? 'ok' : $this->db->last_query();
            }
        }
    }

    /**
     * Metodo generico para editar registros
     * @param int $id
     * @param string $tabla ejemplo='tn_marcas'
     * @param array $datos ejemplo=$datos["indice"]=$valor
     * @return string
     */
    function update($tabla, $id, $datos, $debug = '') {
        $this->db->trans_begin();

        if (is_array($id)) {
            foreach ($id as $key => $value) {
                $this->db->where($key, $value);
            }
        } else {
            $this->db->where('id', $id);
        }
        $res = $this->db->update($tabla, $datos);
        if ($debug == 'debug') {
            print_r($this->db->last_query());
            $this->db->trans_rollback();
        } else {
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return ($this->db->affected_rows() > 0) ? $id : $this->db->last_query();
            }
        }
    }

    /**
     * Metodo para insert datos en una tabla
     * @param string $tabla ejemplo='tn_marcas'
     * @param array $datos ejemplo=array('indice'=>$valor)
     * @param string $debug para devolver la consulta sql
     * @return int
     */
    function insert($tabla, $datos, $debug = null, $db = NULL) {
        if ($db == 'mysql') {
            $this->dbmysql->trans_begin();
            $this->dbmysql->insert($tabla, $datos);
        } else {
            $this->db->trans_begin();
            $this->db->insert($tabla, $datos);
        }

        if ($debug == 'debug') {
            if ($db == 'mysql') {
                print_r($this->dbmysql->last_query());
                $this->dbmysql->trans_rollback();
            } else {
                print_r($this->db->last_query());
                $this->db->trans_rollback();
            }

            exit;
        } else {
            if ($db == 'mysql') {
                if ($this->dbmysql->trans_status() === FALSE) {
                    $this->dbmysql->trans_rollback();
                    return FALSE;
                } else {
                    $this->dbmysql->trans_commit();
                    //return ($this->dbmysql->affected_rows() > 0) ? @$this->dbmysql->insert_id() : false;
		    //var_dump( $this->dbmysql->insert_id());exit;
                    //return $this->dbmysql->insert_id();

                    $query = $this->dbmysql->query('SELECT LAST_INSERT_ID()');
                    $row = $query->row_array();//var_dump($row);exit;
                    return $row['LAST_INSERT_ID()'];

                    //return $this->dbmysql->mysql_insert_id();
                }
            } else {
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return FALSE;
                } else {
                    $this->db->trans_commit();
                    return ($this->db->affected_rows() > 0) ? @$this->db->insert_id() : false;
                }
            }
        }
    }

    function dataTable($tabla, $like, $columnaslike, $columnasreal, $columnasfor, $tipo = NULL) {

        $iDisplayStart = $this->input->get_post('iDisplayStart', true);
        $iDisplayLength = $this->input->get_post('iDisplayLength', true);
        $iSortCol_0 = $this->input->get_post('iSortCol_0', true);
        $iSortingCols = $this->input->get_post('iSortingCols', true);
        $sSearch = $this->input->get_post('sSearch', true);
        $sEcho = $this->input->get_post('sEcho', true);

        if (isset($iDisplayStart) && $iDisplayLength != '-1') {
            $this->limit = "limit " . $iDisplayLength . " offset " . $iDisplayStart;
//            $this->db->limit($this->db->escape_str($iDisplayLength), $this->db->escape_str($iDisplayStart));
        }
// Ordering
        if (isset($iSortCol_0)) {
            for ($i = 0; $i < intval($iSortingCols); $i++) {
                $iSortCol = $this->input->get_post('iSortCol_' . $i, true);
                $bSortable = $this->input->get_post('bSortable_' . intval($iSortCol), true);
                $sSortDir = $this->input->get_post('sSortDir_' . $i, true);

                if ($bSortable == 'true') {
                    $this->order = " ORDER BY " . $iSortCol . " " . $sSortDir;
//                    $this->db->order_by($aColumns[intval($this->db->escape_str($iSortCol))], $this->db->escape_str($sSortDir));
                }
            }
        }

        /*
         * Filtering
         * NOTE this does not match the built-in DataTables filtering which does it
         * word by word on any field. It's possible to do here, but concerned about efficiency
         * on very large tables, and MySQL's regex functionality is very limited
         */


        $columnaslike = explode(",", $columnaslike);
        $or = '';
        if (isset($sSearch) && !empty($sSearch)) {
            for ($i = 0; $i < count($columnaslike); $i++) {
                $bSearchable = $this->input->get_post('bSearchable_' . $i, true);

                // Individual column filtering
                if (isset($bSearchable) && $bSearchable == 'true') {
                    $this->like .= ($this->like == '') ? '' : ' OR ';
                    $this->like .= ' ' . $columnaslike[$i] . ' ilike \'%' . $sSearch . '%\' ';
//                    $this->db->or_like($aColumns[$i], $this->db->escape_like_str($sSearch));
                }
            }
        }

//        $rResult = $this->ObtenerDatos($tabla, $like, $columnasreal);
        $tipo = ($tipo == NULL) ? NULL : $tipo;
        $rResult = $this->Buscar($tabla . " " . $like . " " . $this->order . " " . $this->limit, $columnasreal, '', $tipo);

//        echo $this->db->last_query();
// Data set length after filtering
        $iFilteredTotal = $this->buscar($tabla, 'max(id) ultimo', '', 'row');


// Total data set length
//        $iTotal = $this->db->count_all($sTable);
        $iTotal = $this->buscar($tabla, 'count(*) total', '', 'row');

// Output
        $output = array(
            'sEcho' => intval($sEcho),
            'iTotalRecords' => $iTotal->total,
            'iTotalDisplayRecords' => $iFilteredTotal->ultimo,
            'aaData' => array()
        );

        $columnasfor = explode(",", $columnasfor);


        foreach ($rResult as $aRow) {
            $row = array();

            foreach ($columnasfor as $col) {
                $valor = ($aRow[trim($col)] == NULL) ? '' : $aRow[trim($col)];
                $row[] = $valor;
            }

            $output['aaData'][] = $row;
        }

        return $output;
    }

    public function CupoActual() {

        $where = "ser.id=" . $this->session->userdata("idservicio") . " AND usr.id=" . $this->session->userdata("idusuario");

        $JOIN = "JOIN servicios as ser ON usr.idservicio=ser.id";
        $campos = '(ser.maximo - coalesce(usr.enviados,0) - coalesce(usr.pendientes))  disponible';
        $cantidad = $this->buscar('usuarios as usr ' . $JOIN, $campos, $where, 'row');
        return $cantidad;
    }

    public function obtenerCampos($tabla, $campos, $where = NULL, $debug = NULL) {
        $where = ($where == NULL) ? '' : 'WHERE ' . $where;
        $sql = "
                SELECT $campos
                FROM $tabla
                $where";
//        $this->logs("obtenerCampos", $sql);
        $datos = $this->db->query($sql);
        return ($debug == 'debug') ? $sql : $datos->result_array();
    }

    /**
     * 
     * @param string $campos ejemplo ='usuarios'
     * @return array
     */
    public function obtenerCamposId($tabla, $campos, $where = NULL, $debug = NULL) {
        $where = ($where == NULL) ? '' : 'WHERE ' . $where;
        $sql = "
                SELECT $campos
                FROM $tabla
                $where";

        $datos = $this->db->query($sql);
//        $this->logs("obtenerCamposId", $sql);
        return ($debug == 'debug') ? $sql : $datos->row();
    }

    function LimpiaMensaje($string) {
        $string = trim($string);

        $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C'), $string
        );

        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä', 'Ã'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', '', 'A', 'A'), $string
        );

        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('í', 'ì', 'ï', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );



        $string = utf8_encode((filter_var($string, FILTER_SANITIZE_STRING)));
        $string = str_replace(
                array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä', 'Ã'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', '', 'A', 'A'), $string
        );

        $string = str_replace(
                array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('í', 'ì', 'ï', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
                array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C'), $string
        );


        $string = str_replace(
                array("\\", "¨", "º", "–", "~", "|", "·",
            "¡", "[", "^", "`", "]", "¨", "´", "¿",
            '§', '¤', '¥', 'Ð', 'Þ'), '', $string
        );


        $string = str_replace(
                array(";",), array(","), $string
        );



        $string = str_replace(
                array("&#39;", "&#39,", '&#34;', '&#34,'), array("'", "'", '"', '"'), $string
        );

        $string = htmlentities($string, ENT_QUOTES | ENT_IGNORE, 'UTF-8');

        $string = str_replace(
                array('&quot;', '&#39;', '&#039;'), array('"', "'", "'"), $string
        );
        $string = str_replace(
                array('&amp;', '&nbsp;'), array('&', ' '), $string
        );
        $string = str_replace(
                array('&deg;', '&sup3;', '&shy;'), array(''), $string
        );
        $string = str_replace(
                array('&copy;', '&sup3;', '&shy;', '&plusmn;'), array('e', 'o', 'i', 'n'), $string
        );

        return $string;
    }

}
