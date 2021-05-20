<?php

/* Permitir que el script permanezca en espera de conexiones. */
set_time_limit(0);

/* Habilitar vaciado de salida implicito, de modo que veamos lo que
* obtenemos a medida que va llegando. */
ob_implicit_flush();

 echo "En Espera de Conexiones... \n";
 $i=0;
while(true){
        echo "hola mundo \n";
        conectar();
        sleep(1);
        $i++;
}//end while

function conectar(){
    $host="localhost";
    $port="5432";
    $user="postgres";
    $pass="123";
    $dbname="contactosms";
    echo "host=$host port=$port user=$user password=$pass dbname=$dbname";

    $connect = pg_connect("host=$host port=$port user=$user password=$pass dbname=$dbname");

    if(!$connect)
        echo "<p><i>No me conecte</i></p>";
    else
        echo "<p><i>Me conecte</i></p>";

}
