<?php
$is_local = ($_SERVER['HTTP_HOST']==='localhost' || strpos($_SERVER['HTTP_HOST'], '127.0.0.1')!== false || strpos($_SERVER['HTTP_HOST'], '.local')!==false);

if($is_local){
    //DESARROLLO LOCAL
    define('BASE_URL', 'http://localhost/Proyecto/');
    define('BASE_PATH', '/opt/lampp/htdocs/Proyecto/');
} else {
    //PRODUCCIÓN
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off') ? 'https' : 'http';

    define('BASE_URL', $protocol . '://' . $_SERVER['HTTP_HOST'] . '/');
    define('BASE_PATH', __DIR__ . '/');
}

define('ENVIRONMENT', $is_local ? 'development':'production');
?>