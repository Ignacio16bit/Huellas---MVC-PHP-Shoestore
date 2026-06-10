<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Diagnóstico</h2>";

// 1. Verificar paths.php
echo "<h3>1. Cargando paths.php:</h3>";
require_once __DIR__ . '/config/paths.php';
echo "BASE_URL: " . BASE_URL . "<br>";
echo "BASE_PATH: " . BASE_PATH . "<br>";
echo "Environment: " . ENVIRONMENT . "<br>";

// 2. Verificar database.php
echo "<h3>2. Cargando database.php:</h3>";
require_once __DIR__ . '/config/database.php';
echo "Conexión establecida<br>";

// 3. Verificar modelo
echo "<h3>3. Cargando modelo:</h3>";
require_once BASE_PATH . 'models/product_model.php';
echo "Modelo cargado<br>";

// 4. Probar consulta
echo "<h3>4. Probando consulta:</h3>";
$productModel = new Product($mysqli);
$productos = $productModel->getAllProducts();
echo "Productos encontrados: " . count($productos) . "<br>";