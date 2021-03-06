<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    public $idusuario;
    public $entorno;

    public function __construct() {
        parent::__construct();
        date_default_timezone_set("America/Bogota");
        $this->idusuario = $this->session->userdata('idusuario');
        $this->load->model("AdministradorModel");
        $this->entorno = $this->config->item("entorno");
        /**
         * Se valida si el usuario esta logueado
         */
//        if (!isset($this->idusuario) || empty($this->idusuario)) {
//            redirect('login');
//        }

        if ($this->session->userdata("idperfil")) {
            $data["menu"] = $this->cargaMenu();
            $this->session->set_userdata($data);
        } else {
            $seg = $this->uri->segment(1);
            if (strpos($seg, 'cron') === FALSE) {
                if (strpos($seg, 'ws') === FALSE) {
                    redirect('login');
                }
            }
        }
    }

    public function cargaMenu() {
        $campos = "p.id,p.title,p.url,p.node_id,p.nivel";
        $join = " JOIN permission p ON p.id=u.permission_id";
        $where = "u.user_id = " . $this->session->userdata("idusuario") . " AND p.nivel=1 ORDER by p.order ASC";
        $permission = $this->AdministradorModel->buscar("permission_users  u " . $join, $campos, $where);

        if ($permission != false) {
            foreach ($permission as $i => $val) {
                $where = " p.node_id=" . $val["id"] . " AND u.user_id=" . $this->session->userdata("idusuario");
                $node = $this->AdministradorModel->buscar("permission_users  u " . $join, $campos, $where);
                if ($node != false) {
                    $permission[$i]["node"] = $node;
                }
            }
        } else {
            $menu = $this->AdministradorModel->buscar("perfiles", '*', 'id=' . $this->session->userdata("idperfil"), 'row');

//            $ruta = base_url() . $menu["menu"];
            switch ($_SESSION["idperfil"]) {
                case 1: {
                        $ruta = "/var/www/html/contactosms.new/public/menu/menu1.ini";
//                        $ruta = "/var/www/html/contactosms/public/menu/menu1.ini";
                        break;
                    }
                case 2: {
//                        $ruta = "/var/www/html/contactosms/public/menu/menu2.ini";
                        $ruta = "/var/www/html/contactosms.new/public/menu/menu2.ini";
                        break;
                    }
                case 3: {
                        $ruta = "/var/www/html/contactosms.new/public/menu/menu.ini";
//                        $ruta = "/var/www/html/contactosms/public/menu/menu.ini";
                        break;
                    }
                case 4: {
                        $ruta = "/var/www/html/contactosms.new/public/menu/enviorapido.ini";
//                        $ruta = "/var/www/html/contactosms/public/menu/menu.ini";
                        break;
                    }
            }
            $data = (parse_ini_file($ruta, true));
            $cont = 0;
            foreach ($data as $i => $value) {

                $permission[$cont]["title"] = $i;
                $permission[$cont]["url"] = '';
                if (count($value) > 0) {
                    foreach ($value as $j => $val) {
                        $permission[$cont]["node"][] = array("title" => $j, "url" => $val);
                    }
                }
                $cont++;
            }
        }


        return $permission;
    }

    /**
     * Metodo para asignar NULL a las campos POST que llegan del formulario
     * @param array $arreglo
     * @return array
     */
    public function asignaNull($arreglo) {
        foreach ($arreglo as $i => $value) {
            $respuesta[$i] = ($value == '') ? NULL : $value;
        }
        return $respuesta;
    }

    /**
     * Metodo para crear o escribir en un plano
     * @param type $archivo
     * @param type $datos
     * @param type $separador
     */
    public function crearPlano($archivo, $titulo, $datos, $separador) {
        /**
         * crea el texto a escribir
         */
        $alto = sizeof($datos);
        $largo = sizeof($datos[0]);
        $largotitulo = sizeof($titulo);
        $texto = '';

        /**
         * crea los titulos del archivo si no existe
         */
        if (!file_exists($archivo)) {
            for ($j = 0; $j < $largotitulo; $j++)
                $texto .= trim($titulo[$j]) . $separador;
            $texto .= "\n";
        }
        /**
         * crea el texto a escribir
         */
        for ($i = 0; $i < $alto; $i++) {
            for ($j = 0; $j < $largo; $j++)
                $texto .= trim($datos[$i][$j]) . $separador;
            $texto .= "\n";
        }

        /**
         * abre la conexion con el archivo
         */
        $link = fopen($archivo, "a");

        /**
         * escribre en el archivo
         */
        fwrite($link, $texto);

        /**
         * cierra el archivo
         */
        fclose($link);
    }

    /**
     * Metodo para reemplazar valores no validos para los envios de mensaje
     * @param string $string
     * @return string
     */
    function quitaComilla($string) {
        $arreglo = str_split($string);
        $tam = count($arreglo);
        for ($i = 0; $i < $tam; $i++) {

            if ($i == 0 || $i == ($tam - 1)) {
                $arreglo[$i] = str_replace("'", '', ($arreglo[$i]));
            }
        }

        return implode($arreglo);
    }

    function LimpiaMensaje($string) {
        $string = trim($string);
        $string = utf8_encode((filter_var($string, FILTER_SANITIZE_STRING)));

        $string = str_replace(
                array('??', '??', '??', '??', '??', '??', '??', '??', '??', '??'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A', 'A'), $string
        );

        $string = str_replace(
                array('??', '??', '??', '??', '??', '??', '??', '??'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
        );

        $string = str_replace(
                array('??', '??', '??', '??', '??', '??', '??', '??', '??'), array('i', 'i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
        );

        $string = str_replace(
                array('??', '??', '??', '??', '??', '??', '??', '??'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
        );

        $string = str_replace(
                array('??', '??', '??', '??', '??', '??', '??', '??'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
        );

        $string = str_replace(
                array('??', '??', '??', '??'), array('n', 'N', 'c', 'C'), $string
        );


        $string = str_replace(
                array("\\", "??", "??", "???", "~", "|", "??",
            "??", "[", "^", "`", "]", "??", "??", "??",
            '??', '??', '??', '??', '??'), '', $string
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

    function dataTable($data) {
        foreach ($data as $i => $value) {
            foreach ($value as $val) {
                $arreglo[] = $val;
            }
            $datos[] = $arreglo;
            $arreglo = array();
        }

        $respuesta["data"] = $datos;
        return $respuesta;
    }

    /**
     * Metodo para listar la informacion de un directorio
     * @param type $ruta
     * @return string|boolean
     */
    function Directorios($ruta) {
        $respuesta = array();
        /**
         * Valida se existe una ruta
         */
        if (is_dir($ruta)) {
            /**
             * Abre la carpetas
             */
            if ($aux = opendir($ruta)) {
                /**
                 * recorre la carpeta
                 */
                while (($archivo = readdir($aux)) !== false) {
                    /**
                     * No tome directorios superiores
                     */
                    if ($archivo != "." && $archivo != "..") {
                        $ruta_completa = $ruta . '/' . $archivo;


                        if (is_dir($ruta_completa)) {
                            $otro[] = $ruta_completa;
                        } else {
                            $archivos["nombre"] = $archivo;
                            $archivos["size"] = filesize($ruta_completa);
                            $archivos["fecha"] = date('Y-m-d', filemtime($ruta_completa));
                            $archivos["ruta"] = $ruta_completa;

                            $respuesta[] = $archivos;
                        }
                    }
                }
                closedir($aux);
                return $respuesta;
            }
        } else {

            return false;
        }
    }

    public function crearRutaCarpeta($rutaCompleta, $archivo = null) {
        $ruta = explode("/", $rutaCompleta);
        $completa = '';
        foreach ($ruta as $value) {
            $completa .= $value . "/";
            if (!file_exists($completa)) {
                mkdir($completa);
                chmod($completa, 0777);
            } else {
                if ($archivo != null) {
                    if (file_exists($completa . $archivo)) {
                        unlink($completa . $archivo);
                    }
                }
            }
        }

        return $rutaCompleta;
    }

    public function validaPrefijo($numero) {
        $num = substr($numero, 0, 3);
        $where = "prefijos ILIKE '%{$num}%'";
        $existe = $this->CargaexcelModel->buscar('carries', 'codigo', $where, 'row');
        return (isset($existe)) ? $existe : FALSE;
    }

    /**
     * Valida formato dd-mm-yyyy
     * @param type $fecha
     * @return type
     */
    function validarFecha($date, $format = 'Y-m-d H:i:s') {
        $d = DateTime::createFromFormat($format, $date);
        $rta[0] = $d;
        if ($d != false)
            $rta[1] = $d->format($format) == $date;

        if ($d == FALSE) {
            $rta[0] = false;
            $rta[1] = "FORMATO NO VALIDO";
        } else {
            $hoy = date('Y-m-d') . " 00:00:00";
            $futura = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($hoy)));

            if ($date < $hoy) {
                $rta[0] = false;
                $rta[1] = "FECHA ANTIGUA";
            } elseif ($date > $futura) {
                $rta[0] = false;
                $rta[1] = "FECHA FUTURA";
            }
        }
        return $rta;
    }

    /**
     * Meotodo para eliminar cache
     */
    public function removeCache() {
        $this->output->set_header('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
        $this->output->set_header('Cache-Control: post-check=0, pre-check=0', false);
        $this->output->set_header('Pragma: no-cache');
    }

}
