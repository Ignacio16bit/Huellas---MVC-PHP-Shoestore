<?php
require_once dirname(__DIR__) . '/config/paths.php';
session_start();
require_once dirname(BASE_PATH) . '/models/order_model.php';
require_once BASE_PATH . 'database.php';
require_once dirname(BASE_PATH) . '/models/product_model.php';

//Registro del pedido en BD cuando se pague
if (isset($_POST['pago']) && isset($_SESSION['user_id'])){

    $user_id = $_SESSION['user_id'];

    if(isset($_POST['product_id']) && is_array($_POST['product_id'])){
        $product_ids = array_map('intval', $_POST['product_id']);
        $prices = isset($_POST['price']) ? array_map('floatval', $_POST['price']) : [];
        try{
            $order = new Order($mysqli);
            $date = date('Y-m-d H:i:s');
            $total = array_sum($prices);

            $pedido_id = $order-> createOrder($user_id, $date,$total);

            foreach($product_ids as $index => $product_id){
                $price = $prices[$index] ?? 0.0; //Igualo los indices y seteo un valor por defecto
                $order -> createDetail($product_id, $pedido_id, $price);
            }
        } catch(mysqli_sql_exception $e){
            error_log("Error en durante la creación del pedido: ".$e->getMessage());
            echo "No se pudo procesar el pedido. Pruebe de nuevo";
        }
    }
    header("Location: " . BASE_URL . "index.php");
} else{
    echo 'Error en el envío de formulario';
}
//PARA VER EL HISTORIAL LO CARGO EN HTML
?>

