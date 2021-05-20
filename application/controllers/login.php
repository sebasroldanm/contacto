<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct() {
        parent::__construct();
	date_default_timezone_set("America/Bogota");
        $this->load->model("LoginModel");
    }

    /**
     * metodo principal para iniciar el controlador
     */
    public function index() {
        $data["error"] = '';
        $this->load->view('login', $data);
    }

    public function valida() {
        $post = $this->input->post();
        /**
         * Se obtiene los datos para verficar la existencia del usuario
         */
        $campos = "usuarios.id,usuarios.usuario,usuarios.nombre,emp.id as idempresa,usuarios.idperfil,usuarios.concatena,usuarios.idservicio";
        $where = " usuarios.usuario = '{$post["login"]}' AND usuarios.clave='" . base64_encode($post["pass"]) . "' AND usuarios.estado=1 AND emp.activo=1";
        $datos = $this->LoginModel->Buscar('usuarios JOIN empresas as emp ON usuarios.idempresa=emp.id', $campos, $where, 'row');

    

        /**
         * Se verifica que exista
         */

        if ($datos!=false) {

            $where="idusuario=".$datos["id"];
            $ips = $this->LoginModel->Buscar('usuarios_ips', "*", $where);

            if($ips != false){
                $authorized=false;
                
                foreach($ips as $val){
                    if($_SERVER["REMOTE_ADDR"]==$val["ip"]){
                        $authorized=true;
                    }
                }

                if(!$authorized){
                    $this->session->set_flashdata('error', 'Tienes restricciones de Ip');
                    redirect("login");
                }

            }



//            if ($datos->logueado == 0) {
//                $user["logueado"] = 1;
//                $this->LoginModel->update("usuarios", $datos->id, $user);
            $usuario = array(
                'idusuario' => $datos["id"],
                'usuario' => $datos["usuario"],
                'nombre' => $datos["nombre"],
                'idempresa' => $datos["idempresa"],
                'idperfil' => $datos["idperfil"],
                'concatena' => $datos["concatena"],
                'idservicio' => $datos["idservicio"],
                'ip' => $_SERVER['REMOTE_ADDR'],
            );
            /**
             * Se agregan los datos se SESSION
             */
            $this->session->set_userdata($usuario);
            $session = array(
                "idusuario" => $datos["id"],
                'ingreso' => date("Y-m-d H:i:s"),
                'ip' => $_SERVER['REMOTE_ADDR'],
                'eventos' => 'Inicio de sesion'
            );
            /**
             * Se agrega el registro de session
             */
            $this->LoginModel->insert("sesiones", $session);
            redirect(base_url() . "inicio");

        } else {
            /**
             * En caso de error redirecciona
             */
            $this->session->set_flashdata('error', 'Usuario o clave incorrecta');
            redirect("login");
        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
