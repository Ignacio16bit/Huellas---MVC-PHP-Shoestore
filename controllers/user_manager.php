<?php
require_once dirname(__DIR__) . '/config/paths.php';
session_start();
require_once BASE_PATH . 'database.php';
require_once dirname(BASE_PATH) . '/models/admin_model.php';
require_once dirname(BASE_PATH) . '/models/user_model.php';

//Aquí gestionamos las llamadas tanto de ADMIN como de USUARIO
//Desde la vista de admin-> Edición de usuarios (sólo nombre y email)
if (isset($_POST['changeUser'])){
    if(empty($_POST['user_id'])){
        header("Location: " . BASE_URL . "view/admin_view.php");
        exit();
    }
    if(empty($_POST['user_name'])||empty($_POST['user_email'])){
        header("Location: " . BASE_URL . "view/admin_view.php");
        exit();
    }

    $name = $_POST['user_name'];
    $email =$_POST['user_email'];
    $id = $_POST['user_id'];

    $admin = new AdminModel($mysqli);
    $admin -> modifyUser($name, $email, $id);

    header("Location: " . BASE_URL . "view/admin_view.php");
    exit();
}

//Borrado de usuarios del admin
if (isset($_POST['rmUser'])){
    $id = $_POST['user-id'];
    $admin = new AdminModel($mysqli);
    $admin -> deleteUser($id);

    header("Location: " . BASE_URL . "view/admin_view.php");
    exit();
}

//Registro de administrdores
if (isset($_POST['register'])){
    
    //Valido los campos en servidor
    if (empty($_POST['name'])|| empty($_POST['email'])||empty($_POST['password'])){
        header("Location: " . BASE_URL . "view/admin_view.php");
        exit();
    } 

    $name = $_POST['name'];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'] ?? 'admin';

    if (!$email){
        $_SESSION ['register_error'] = 'Email inválido';
        header("Location: " . BASE_URL . "view/admin_view.php");
        exit();
    }
    try{
        //Creo un nuevo user_model
        $admin = new AdminModel($mysqli);
        
        //Si existe la entrda devuelve true, el usuario ya existe
        if($admin->getUserByEmail($email)){
            header("Location: " . BASE_URL . "view/admin_view.php");
            exit();
        } 

        //Confirmado el mail, intento crear el admin con los valores POST
        if ($admin-> createAdmin($name, $email, $password, $role)){
            header("Location: " . BASE_URL . "view/admin_view.php");
            exit();
        } else{
            header("Location: " . BASE_URL . "view/admin_view.php");
            exit();
        }
    } catch (mysqli_sql_exception $e){
        error_log("Error en registro de admin: ".$e->getMessage());
        echo "Error en el registro de admin";
        exit();
    }
}

//BOTONES PERFIL USER: update_email, update_name
if (isset($_POST['update_email'])){
    if(empty($_POST['user_name']) || empty($_POST['new_email'])){
        //session error
        header ("Location: <?php echo BASE_URL; ?>view/profile.php");
        exit();
    }

    $name = $_POST['user_name'];
    $email = filter_var($_POST['new_email'], FILTER_VALIDATE_EMAIL);
    $id = $_POST['user_id'];
    try{
        $user = new UserModel($mysqli);
        $success = $user->updateUser($name, $email, $id);

        if($success){
            $_SESSION['email'] = $email;
        }

        //success
        header("Location: " . BASE_URL . "view/profile.php");
        exit();
    } catch(mysqli_sql_exception $e){
        error_log("Error en cambio de correo: ".$e->getMessage());
        echo "Error en el cambio de mail";
        exit();
    }
}

if (isset($_POST['update_name'])){
    if(empty($_POST['user_email']) || empty($_POST['new_name'])){
        //session error
        header ("Location: <?php echo BASE_URL; ?>view/profile.php");
        exit();
    }

    $name = $_POST['new_name'];
    $email = $_POST['user_email'];
    $id = $_POST['user_id'];
    
    try{
        $user = new UserModel($mysqli);
        $success = $user->updateUser($name, $email, $id);

        //Actualizo datos de SESSION
        if($success){
            $_SESSION['name']= $name;
        }
        //success
        header("Location: " . BASE_URL . "view/profile.php");
        exit();
    } catch(mysqli_sql_exception $e){
        error_log("Error al cambiar el nombre: ".$e->getMessage());
        echo "Error al cambiar el nombre";
        exit();
    }
}

if (isset($_POST['removeUser'])){
    $id = $_POST['user-id'];

    $user = new UserModel($mysqli);
    $success = $user->deleteUser($id);

    if($success){
        session_unset();
        session_destroy();
        header("Location: " . BASE_URL . "index.php");
    }

    header("Location: " . BASE_URL . "controllers/logout.php");
    exit();
}

//Cambio de contraseña
if (isset($_POST['update_pass'])){
    $password = password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
    $id = $_POST['user_id'];
    try{
        $user=new UserModel($mysqli);
        $success=$user->changePass($password, $id);

        if($success){
            header("Location: " . BASE_URL . "controllers/logout.php");
            exit();
        }
    } catch(mysqli_sql_exception $e){
        error_log("Error al cambiar contraseña: ".$e->getMessage());
        echo "Error al cambiar la contraseña";
        exit();
    }
}

?>