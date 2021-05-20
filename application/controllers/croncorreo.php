<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class cronCorreo extends MY_Controller {

    public function __construct() {
        parent::__construct();
        /**
         * Se carga el modelo a usar
         */
        $this->load->model("AdministradorModel");
        /**
         * Carga de librerias necesarioas para envio de correo
         */
        $this->load->library('smpp');

//        $this->load->library('Mandril');
    }

    public function index() {


        /**
         * Se instancia un objeto de clase zip para comprimir archivos
         */
        $zip = new ZipArchive();

        /**
         * Se obtiene el correo de todos los usuarios que esten activos y que tenga contenido
         */
        $campos = "u.id,u.usuario,u.nombre,u.correos,(s.maximo - u.enviados) cupo,s.maximo,u.idservicio,s.tiposervicio";
        $join = " JOIN servicios s ON s.id=u.idservicio";
        $where = " (u.correos!='' OR u.correos!=NULL) AND u.id IN(2)";
//        $where = " (u.correos!='' OR u.correos!=NULL)";
        $usuarios = $this->AdministradorModel->buscar("usuarios u " . $join, $campos, $where);

//        $usuarios = $this->AdministradorModel->buscar("usuarios", '*', "id=29");
        //cargamos la libreria email de ci

        $this->load->library("email");
//        $this->load->library("email");
        /**
         * Configuracion de la cuenta de correo
         */
        $correo = $this->AdministradorModel->buscar("correos", '*', 'id=1', 'row');
        $config['protocol'] = $correo["protocolo"];
        $config['smtp_host'] = $correo["host"];
        $config['smtp_port'] = $correo["puerto"];
        $config['smtp_user'] = $correo["usuario"];
        $config['smtp_pass'] = $correo["clave"];
        $config['smtp_timeout'] = '7';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not
//        $config['protocol'] = 'smtp';
//        $config['smtp_host'] = 'ssl://smtp.googlemail.com';
//        $config['smtp_port'] = 465;
//        $config['smtp_user'] = 'reportessms@gmail.com';
//        $config['smtp_pass'] = 'contactosms2014+';
//        $config['smtp_timeout'] = '7';
//        $config['charset'] = 'utf-8';
//        $config['newline'] = "\r\n";
//        $config['mailtype'] = 'html'; // or html
//        $config['validation'] = TRUE; // bool whether to validate email or not
        //
        //cargamos la configuraciÃ³n para enviar con gmail

        $mail = '';
        $cantidad = 0;
        $archivo = '';
        /**
         * Iteracion para recorre los usuarios con correo
         */
        foreach ($usuarios as $value) {
            /**
             * Consulta para obtener la sumatoria de lo que se envio en el dia
             */
            $registros = $this->AdministradorModel->reporteCorreo($value["id"]);

            /**
             * Valida que existan registros
             */
            if (COUNT($registros) > 0) {

                /**
                 * Se obtien las bases por usuario
                 */
                $archivo = $this->AdministradorModel->buscar("bases", 'archusuario archivo', "idusuario=" . $value["id"] . " AND fecha>='" . date("Y-m-d") . "'", 'row');

//                $archivos = $this->AdministradorModel->buscar("bases", 'archusuario as archivo', "idusuario=" . $value["id"] . " AND fecha>='2014-07-11'");
//                $rutaArc = $_SERVER["DOCUMENT_ROOT"] . "/contactosms/planos/" . $value["id"] . "/resumen_" . date("Y-m-d") . ".zip";
                /**
                 * Ruta para los archivos compresos
                 */
                $crearuta = $_SERVER["DOCUMENT_ROOT"] . "/zip/" . $value["id"];
//                $crearuta = "/var/www/html/contactosms.new/zip/" . $value["id"];
                
                $rutaArc = $this->crearRutaCarpeta($crearuta);

                $rutaArc = $rutaArc . "/resumen_" . date("Y-m-d") . ".zip";

                if (!file_exists($rutaArc)) {

                    $plano = '';

                    if (!empty($archivo["archivo"])) {

                        if ($zip->open($rutaArc, ZIPARCHIVE::CREATE) === true) {
                            $plano = "planos/" . $archivo["archivo"];
                            
                            if (file_exists($plano)) {
                                
                                $zip->addFile($plano);
                                $zip->close();

                                /**
                                 * Cierra el ZIP
                                 */
                                /**
                                 * se inicializa la configuracion de correo 
                                 */
                                $this->email->initialize($config);
                                /**
                                 * Nombre de quien envia
                                 */
                                $this->email->from('reportes contactosms');
                                /**
                                 * Agrega los correos para
                                 */
                                $this->email->to($value["correos"]);
                                /**
                                 * El asunto
                                 */
                                $this->email->subject('Resumenes Diarios');

                                $cupo = ($value["cupo"] / $value["maximo"]) * 100;

                                $sms = "<br>Cuenta: " . $value["nombre"] . "<br>";
                                $sms .= "Login: " . $value["usuario"] . "<br><br>";
                                //1 servicio bolsa, 2 mensualidad

                                if ($value["idservicio"] == 1) {
                                    if ($cupo <= 20) {
                                        $sms .= '<p style="color:#FF4000">Saldo disponible bajo. Comuniquese con <a href="mailto:comercial@contactosms.com.co">comercial@contactosms.com.co</a> para adquirir un nuevo plan.</p>';
                                    }
                                } else {
                                    if ($cupo <= 10) {
                                        $sms .= '<p style="color:#FF4000;font-size:13px">Saldo disponible bajo. Comuniquese con <a href="mailto:comercial@contactosms.com.co">comercial@contactosms.com.co</a> para adquirir un nuevo plan.</p>';
                                    }
                                }
                                $sms .= "<table border=1>";
                                $sms .= "<tr style='background-color:#ccc;color:black;border:1px solid #000;font-weight:bold;'><td>Operador</td><td>Cantidad</td></tr>";

                                /**
                                 * Iteracion para mostrar la informacion con la sumatoria de cada valor
                                 */
                                $cantidad = 0;
                                foreach ($registros as $valor) {
                                    $cantidad += $valor["cantidad"];
                                    $sms .= "<tr><td>" . $valor["nombre"] . "</td><td>" . $valor["cantidad"] . "</td></tr>";
                                }
                                $sms .= "<tr><td><b>Total</b></td><td>" . $cantidad . "</td></tr>";
                                $sms .= "</table>";
                                $sms .= "<br><br>ContactoSMS";
                                /**
                                 * Adjunta el archivo zip
                                 */
                                $adjunto = "zip/" . $value["id"] . "/resumen_" . date("Y-m-d") . ".zip";

                                $this->email->attach($adjunto);

                                /**
                                 * Agrega el mensaje
                                 */
                                $this->email->message($sms);
                                /**
                                 * Envia el mensaje
                                 */
                                $this->email->send();


                                //con esto podemos ver el resultado
                                var_dump($this->email->print_debugger());
                                $this->email->clear(TRUE);

                                $sms = '';
                            } else {
                                echo "No existe plano <br>";
                            }
                        }
                    }
                } else {
                    echo "No se encontro archivo <br>";
                }
            } else {
                echo "No hay reportes <br>";
            }
        }
    }

    /**
     * Funcion de prueba para envio de correo
     */
    public function ip() {
        $this->load->library("email");

//        configuracion para envio de correo
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.googlemail.com';
        $config['smtp_port'] = 465;
        $config['smtp_user'] = 'reportessms@gmail.com';
        $config['smtp_pass'] = 'contactosms2014+';
        $config['smtp_timeout'] = '7';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['validation'] = TRUE; // bool whether to validate email or not

        $this->email->initialize($config);

        $this->email->from('reportes contactosms');
        $this->email->to("oskarcuervo@gmail.com");
        $this->email->subject('Prueba de correo');
        $this->email->message('<h2>Email enviado con codeigniter haciendo uso del smtp de gmail</h2><hr><br> Bienvenido al blog');
        $this->email->send();
        //con esto podemos ver el resultado
        print_r($this->email->print_debugger());
    }

}
