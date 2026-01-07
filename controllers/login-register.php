<?php
require_once dirname(__DIR__) . '/config/paths.php';
session_start();
require_once dirname(BASE_PATH) . '/models/user_model.php';
require_once BASE_PATH . 'database.php';

//Lógica de registro
if (isset($_POST['register'])){
    $role = 'cliente'; //Como valor base, ya que no se pueden registrar admin desde web
    
    //Valido los campos en servidor
    if (empty($_POST['name'])|| empty($_POST['email'])||empty($_POST['password'])){
        echo "Rellene todos los campos";
        exit();
    } 

    $name = $_POST['name'];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'] ?? 'cliente';

    if (!$email){
        echo "Rellene todos los campos";
        exit();
    }
    try {
        //Creo un nuevo user_model
        $usuario = new userModel($mysqli);
        
        //Si existe la entrda devuelve true, el usuario ya existe
        if($usuario->getUserByEmail($email)){
            echo "El usuario ya existe";
            exit();
        } 

        //Confirmado el mail, intento crear el usuario con los valores POST
        if ($usuario-> createUser($name, $email, $password, $role)){
            session_start();
            header("Location: " . BASE_URL . "index.php");
            exit();
        } else{
            header("Location: " . BASE_URL . "index.php");
            exit();
        }
    } catch (mysqli_sql_exception $e){
        error_log("Error en registro: ".$e->getMessage());
        echo "Error en el registro. Pruebe de nuevo.";
    }
    
}

if (isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        //separo el modelo de los datos
        $userModel = new userModel($mysqli);
        $usuario = $userModel->getUserByEmail($email);

        if($usuario){
            if (password_verify($password, $usuario['password'])){
                $_SESSION['user_id']=$usuario['id'];
                $_SESSION['name'] = $usuario['name'];
                $_SESSION['email'] = $usuario['email'];
                $_SESSION['role']=$usuario['role'];

            if ($usuario['role']=== 'admin'){
                header ("Location: ". BASE_URL . "view/admin_view.php");
            } else {
                header("Location: " . BASE_URL . "index.php");
            }
            exit();
            } 
        } 
    } catch (mysqli_sql_exception $e){
        error_log("Error en login: ".$e->getMessage());
        echo "Error en el login"; 
        exit();
    }
}
?>