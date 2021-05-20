<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AdministradorModel extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    /**
     * meotod para obtener los datos de los usuarios
     * @param string $datos
     * @param string $user
     * @param string $where
     * @return array
     */
    public function datosUsuarios($datos = NULL, $user = NULL, $where = NULL) {
        $where = ($where == NULL) ? '' : 'WHERE usuarios.' . $where;
        $sql = "
                SELECT 
                    usuarios.id,usuarios.nombre,ltrim(usuarios.usuario) usuario,ltrim(emp.nombre) idempresa,per.perfil idperfil,usuarios.estado,
                    ser.maximo,usuarios.pendientes,usuarios.enviados 
                FROM usuarios
		LEFT JOIN empresas emp ON usuarios.idempresa = emp.id
		LEFT JOIN perfiles per ON usuarios.idperfil = per.id
		JOIN servicios ser ON usuarios.idservicio=ser.id
                    $where ORDER BY usuarios.nombre";
        $res = $this->db->query($sql);
        return ($where==NULL)?$res->result_array():(array)$res->row();
    }
    
    /**
     * Metodo para obtener los carriers
     * @return type
     */
    public function datosCarries(){
        $sql="
            SELECT  *
            FROM carries
            ORDER BY id";
        $res=$this->db->query($sql);
        
        return $res->result_array();
    }
    /**
     * Meotodo para obtnere los servicios ordenador por "id"
     * @return type
     */
    public function datosServicios(){
        $sql="
            SELECT  *
            FROM servicios
            ORDER BY id";
        $res=$this->db->query($sql);
        
        return $res->result_array();
    }
    /**
     * Metodo para obtener la sumatoria por cada operador por cada fecha y usuario
     * @param int $idusuario
     * @return array
     */
    public function reporteCorreo($idusuario){

        $sql="
            SELECT 
                us.usuario,car.nombre,SUM(res.cantidad) as cantidad
            FROM resumenes as res
                JOIN usuarios as us ON res.idusuario=us.id
                JOIN carries as car ON res.idcarrie=car.id
            WHERE res.idusuario=".$idusuario." AND fecha='".date("Y-m-d")."'
            GROUP BY res.idcarrie,us.usuario,car.nombre";
        
        $res=$this->db->query($sql);
        
        return $res->result_array();
    }
 
}
