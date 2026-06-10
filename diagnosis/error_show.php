<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Probando index.php línea por línea</h2>";

echo "1. Incluyendo paths.php...<br>";
require_once __DIR__ . '/config/paths.php';
echo "✓ OK<br>";

echo "2. Iniciando sesión...<br>";
session_start();
echo "✓ OK<br>";

echo "3. Incluyendo database.php...<br>";
require_once BASE_PATH . 'config/database.php';
echo "✓ OK<br>";

echo "4. Incluyendo modelo...<br>";
require_once BASE_PATH . 'models/product_model.php';
echo "✓ OK<br>";

echo "5. Creando instancia de Product...<br>";
$productModel = new Product($mysqli);
echo "✓ OK<br>";

echo "6. Obteniendo productos...<br>";
$producto = $productModel->getAllProducts();
echo "✓ OK - Productos encontrados: " . count($producto) . "<br>";

echo "7. Renderizando HTML...<br>";
// Incluir el resto del index.php
include __DIR__ . '/index.php';