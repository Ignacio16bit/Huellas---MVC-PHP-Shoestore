<?php
require_once __DIR__ . '/config/paths.php';
session_start();
require_once BASE_PATH . 'config/database.php';
require_once BASE_PATH . 'models/product_model.php';

//Manejo de la petición del formulario

//Búsqueda del campo en el modelo -> Llamada al objeto product()
?>