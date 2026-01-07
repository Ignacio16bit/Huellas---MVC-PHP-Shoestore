<?php
//Manejo de errores de mysqli
$is_local = ($_SERVER['HTTP_HOST']==='localhost' || $_SERVER['SERVER_NAME']==='localhost');
//Creamos los parámetros de conexión a MySql
if($is_local){
    //BBDD EN LOCAL
    $host = 'localhost:3307';
    $user = 'root';
    $password = '';
    $database = 'tienda_db';
} else {
    //BBDD EN PRODUCCIÓN -> INFINITY FREE
    $host = 'sql101.infinityfree.com';
    $user = 'if0_40822799';
    $password = '6DJ5uHZwXpII';
    $database = 'if0_40822799_tienda_db';
}
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
//Realizo la conexión
try{
    $mysqli = new mysqli($host, $user, $password, $database);
    $mysqli-> set_charset("utf8mb4");

} catch (mysqli_sql_exception $e){
    //Genero un erro en log que diga cual es el error en cuestión en que archivo y que línea. Además de la fecha
    error_log("Error en DB[".date('Y-m-d H:i:s')."]: ". $e->getMessage(). " en archivo: ".$e->getFile(). ": ".$e->getLine());
    die ("Error en conexión a base de datos: ".mysqli_connect_error());
}
?>