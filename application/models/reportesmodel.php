<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class ReportesModel extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    /**
     * Metodo para obtener el reporte de mensajes mensuales
     * @return array
     */
    public function mensual() {

        $sql = "
                SELECT usr.usuario,car.nombre as operador,to_char(res.fecha,'YYYY-MM') as fecha,sum(res.cantidad) as cantidad
                FROM resumenes as res
                    JOIN usuarios AS usr ON res.idusuario=usr.id
                    JOIN carries AS car ON res.idcarrie=car.id
                WHERE fecha BETWEEN '" . date("Y") . "-01-01' AND '" . date("Y-m-d") . "'
                AND usr.id=" . $this->session->userdata("idusuario") . " 
                GROUP BY 1,2,3 ORDER BY fecha DESC";
        $res = $this->db->query($sql);

        return $res->result_array();
    }

    /**
     * Metodo para obtener el reporte de mensajes diarios
     * @return array
     */
    public function diario() {

        $sql = "
                SELECT res.id,usr.usuario,car.nombre as operador,res.fecha,res.cantidad
                FROM resumenes as res
                    JOIN usuarios AS usr ON res.idusuario=usr.id
                    JOIN carries AS car ON res.idcarrie=car.id
                WHERE fecha BETWEEN '" . date("Y-m") . "-01' AND '" . date('Y-m-d') . "'
                AND usr.id=" . $this->session->userdata("idusuario") . "
                ORDER BY res.fecha ASC";

        $res = $this->db->query($sql);

        return $res->result_array();
    }

    /**
     * Metodo para obtener el reporte de mensajes de hoy
     * @return array
     */
    public function hoy() {

        $sql = "
                SELECT res.id,usr.usuario,car.nombre as operador,res.fecha,res.cantidad
                FROM resumenes as res
                    JOIN usuarios AS usr ON res.idusuario=usr.id
                    JOIN carries AS car ON res.idcarrie=car.id
                WHERE fecha = '" . date("Y-m-d") . "'
                AND usr.id=" . $this->session->userdata("idusuario");
        $res = $this->db->query($sql);

        return $res->result_array();
    }

    /**
     * Metodo para obtener el reporte de por la fecha inicial y final
     * @return array
     */
    public function fecha($inicio, $final) {

        $sql = "
                SELECT res.id,usr.usuario,car.nombre as operador,res.fecha,res.cantidad
                FROM resumenes as res
                    JOIN usuarios AS usr ON res.idusuario=usr.id
                    JOIN carries AS car ON res.idcarrie=car.id
                WHERE usr.id=" . $this->session->userdata("idusuario") . " AND res.fecha BETWEEN '" . $inicio . "' AND '" . $final . "'"
                . "ORDER BY res.fecha DESC";
        $res = $this->db->query($sql);
        return $res->result_array();
    }

    public function bases() {

        $administrador = ($this->session->userdata("idperfil") == 1) ? '' : " idusuario=" . $this->session->userdata("idusuario") . " AND ";


        $fecha = date('Y-m-j');
        $nuevafecha = strtotime('-7 day', strtotime($fecha));
        $nuevafecha = date('Y-m-j', $nuevafecha);

        $sql = "
                SELECT 
                    bases.id,bases.nombre,bases.fecha, registros,errores,coalesce(bases.enviados,0) enviados,
                    ((bases.registros-coalesce(bases.errores,0))-coalesce(bases.enviados,0)) pendientes,usuarios.usuario
                FROM bases
                JOIN usuarios ON bases.idusuario=usuarios.id
                WHERE $administrador
                     fecha>'" . $nuevafecha . "'
                AND (bases.estado='1' or bases.estado is null)
                ORDER BY fecha desc";
        $res = $this->db->query($sql);
        return $res->result_array();
    }

    public function reporteBases($data) {
        $carrier = '';
        $canal = '';
        $cliente = '';
        $fecha = " BETWEEN '" . $data["inicio"] . " 00:00:00' and '" . $data["final"] . " 23:59:59'";
        if (isset($data["idcarrier"]) && ($data["idcarrier"] != 0)) {
            $carrier = "AND idcarrie=" . $data["idcarrier"];
        }
        if (isset($data["idcanal"]) && ($data["idcanal"] != 0)) {
            $canal = 'AND idcanal=' . $data["idcanal"];
        }

        if (isset($data["empresa"])) {
            $cliente = "AND idbase IN (
                SELECT id
		FROM bases 
                WHERE idusuario IN(
                    SELECT id 
                    FROM usuarios 
                    WHERE idempresa IN(" . implode(",", $data["empresa"]) . ")
                        )
                )  ";
        }
        $sql = "
                SELECT canales.nombre canal,count(*) envio 
                FROM registros  JOIN canales ON canales.id = registros.idcanal
                WHERE registros.estado in ('1','3','7') 
                AND fechaenvio $fecha
                    $carrier $canal $cliente
                GROUP BY idcanal,canales.nombre "; 
	
        $res = $this->db->query($sql);
        return $res->result_array();
    }

    public function reporteDisponible($data) {
        $usuarios = '';

        if (isset($data["idusuario"]) && ($data["idusuario"][0] != 0)) {
            $usuarios = ' AND us.id IN (' . implode(",", $data["idusuario"]) . ')';
        }



        $sql = "
                SELECT 
                        us.nombre usuario,ser.nombre plan, ser.maximo cupototal,
                        (ser.maximo - (us.enviados + us.pendientes)) disponible,
                        ser.maximo- (ser.maximo - (us.enviados + us.pendientes)) consumido
                FROM usuarios us
                JOIN servicios ser ON ser.id=us.idservicio
		WHERE us.estado='1'
                $usuarios
                    ORDER BY us.nombre
                ";
        $res = $this->db->query($sql);
        return $res->result_array();
    }

}
