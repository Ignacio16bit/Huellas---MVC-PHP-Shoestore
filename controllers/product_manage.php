<?php
require_once dirname(__DIR__) . '/config/paths.php';
session_start();
require_once BASE_PATH . 'database.php';
require_once dirname(BASE_PATH) . '/models/product_model.php';

if($_SESSION['role']==='admin'){
    if (isset($_POST['create'])){
        if (empty($_POST['product-name']) ||empty($_POST['price']) || empty($_POST['descript'])){
            header("Location: " . BASE_URL . "view/admin_view.php");
            exit();
        }
        try{
            //tratamiento de la imagen
            $image_path = null;
            if(isset($_FILES['image']) && $_FILES['image']['error']===UPLOAD_ERR_OK){
                //Subida de imagen
                $uploads_folder=  $_SERVER['DOCUMENT_ROOT']. "/Proyectos/Proyecto/uploads/";
                //Nombrar imagen
                $file_name = basename($_FILES["image"]["name"]);
                $file_path = $uploads_folder . $_FILES["image"]["name"];

                //guardo el archivo en /uploads
                if (move_uploaded_file($_FILES["image"]["tmp_name"], $file_path)){
                    $image_path = "/Proyectos/Proyecto/uploads/".$file_name;
                }
            }
            $name = $_POST['product-name'];
            $price =$_POST['price'];
            $descript = $_POST['descript'];
            $stock = $_POST['stock'] ?? 0;
            $product = new Product($mysqli);
            $product->createProduct($name, $price, $image_path, $descript, $stock);
            echo "Producto añadido a base de datos";
            header("Location: " . BASE_URL . "view/admin_view.php");
            exit();
        } catch(mysqli_sql_exception $e){
            error_log("Error en creación de producto: ".$e->getMessage());
            echo "No se pudo crear el producto";
        }
    }   

    if(isset($_POST['changeProd'])){
        if (empty($_POST['product_name']) ||empty($_POST['product_price'])){
            $_SESSION['error'] ='Campos vacíos';
            header("Location: " . BASE_URL . "view/admin_view.php");
            exit();
        }
        try{
            //Mando información al modelo
            $name=$_POST['product_name'];
            $price=$_POST['product_price'];
            $id=$_POST['product_id'];
            $product= new Product($mysqli);
            $product->alterProduct($name, $price, $id);
            echo "Producto modificado";
            header("Location: " . BASE_URL . "view/admin_view.php");
            exit();
        } catch(mysqli_sql_exception $e){
            error_log("Error en modificación de producto: ".$e->getMessage());
            echo "No se pudo modificar el producto. Pruebe de nuevo";
        }
    }

    if (isset($_POST['rmProd'])){
        try{
            $id = $_POST['product-id'];
            $product= new Product($mysqli);
            $product -> deleteProduct($id);
            header("Location: " . BASE_URL . "view/admin_view.php");
            exit();
        } catch(mysqli_sql_exception $e){
            error_log("Error al eliminar un producto: ".$e->getMessage());
            echo "No se pudo eliminar el producto. Pruebe de nuevo";
        }
    }

} else{
    //No posee permisos de admin
    header("Location: " . BASE_URL . "index.php");
    exit();
}
?>