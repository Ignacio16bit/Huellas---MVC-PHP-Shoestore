<?php
require_once dirname(__DIR__) . '/config/paths.php';
session_start();
require_once BASE_PATH . 'database.php';
require_once dirname(BASE_PATH) . '/models/product_model.php';
require_once dirname(BASE_PATH) . '/models/user_model.php';
require_once dirname(BASE_PATH) . '/models/admin_model.php';


//Control de acceso en la propia vista
if (isset($_SESSION['role']) && $_SESSION['role']!== 'admin'){
    header("Location: <?php echo BASE_URL; ?>index.php");
    exit();
}

//CARGO USUARIOS Y PRODUCTOS
$productModel = new Product($mysqli);
$product = $productModel -> getAllProducts();

$adminModel = new AdminModel($mysqli);
$admin = $adminModel -> getAllUsers();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
    <title>Huellas/Admin</title>
</head>
<body>
    <header>
        <nav>
            <div class="ui menu">
                <a href="<?php echo BASE_URL; ?>index.php" class="ui header item">
                    <i class="home icon"></i>
                    Inicio
                </a>
                <?php if (isset($_SESSION['user_id'])) : ?>
                <a href="<?php echo BASE_URL; ?>controllers/logout.php" name="close-session" class="ui header item">
                    Cerrar Sesión
                </a>
                <?php else : ?>
                <a href="#" id="login-link" class="ui header item">
                    Inicia Sesión
                </a>
                <a href="#" id="register-link" class="ui header item">
                    Regístrate
                </a>
                <?php endif; ?>
                <a class="ui header item disabled">Vista de administrador</a>
            </div>
        </nav>
    </header>

    <main class="ui container">
            <div class="ui pointing menu" id="sidebar">
                <a class="ui header active item" data-tab="add-product">Añadir productos</a>
                <a class="ui header item" data-tab="admin-create">Crear administrador</a>
                <a class="ui header item" data-tab="admin-prod">Gestionar productos</a>
                <a class="ui header item" data-tab="admin-user">Gestionar usuarios</a>
            </div>
        
    <!--Formulario añádir productos// Conectado con JavaScrip para validación-->
        <div class="ui tab segment active" data-tab="add-product">
            <form class="ui form" action="<?php echo BASE_URL; ?>controllers/product_manage.php" method="POST" enctype="multipart/form-data">
                <div class="field">
                    <input class="ui input" type="text" name="product-name" placeholder="Nombre">
                </div>
                <div class="field">
                    <input class="ui input" type="number" step="0.01" name="price" placeholder="Precio (10,2)">
                </div>
                <div class="field">
                    <label style="font-size: 17px;">Descripción</label>
                    <textarea name="descript" id="descript"></textarea>
                </div>
                <div class="field">
                    <input class="ui input" type="number" name="stock" placeholder="Stock">
                    </div>
                <div class="field">
                    <input type="file" name="image" accept="image/*" id="file-input" class="ui input">
                </div>
                    <button type="submit" id="upload-button" name="create" class="positive ui big button"style="margin-left: 50%; transform: translateX(-50%);">Añadir</button>
                
            </form>
        </div>

        <!--Formulario creación de admin-->
        <div class="ui tab segment" data-tab="admin-create">
            <!--<i class="close icon"></i>-->
            <form action="<?php echo BASE_URL; ?>controllers/user_manager.php" method="post" id="admin-form" class="ui form">
                <input type="hidden" name="register" value="1">
                <div class="field">
                    <input name="name" type="text" placeholder="Nombre" id="register-name" required>
                </div>
                <div class="field"
                ><input name="email" type="text" placeholder="Email" id="register-email" required>
                </div>
                <div class="field">
                    <input name="password" type="password" placeholder="Contraseña" id="register-password" required>
                </div>
                <button name="register" type="submit" class="ui button">Registrar nuevo administrador</button>
            </form>

                <!--Formulario para cambiar rol-->
        </div>

        <div class="ui tab segment" data-tab="admin-prod">
            <table class="ui selectable celled table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Nombre del producto</th>
                        <th>Precio</th>
                        <th></th>
                    </tr>
                </thead>
                <?php foreach($product as $row):?>
                    <tr>
                        <td style="font-size: 17px;" id="product-id"><?php echo $row['id']; ?></td>
                        <td style="font-size: 17px;" id="product-name"><?php echo $row['name']; ?></td>
                        <td style="font-size: 17px;" id="product-price"><?php echo $row['price']." €"; ?></td>
                        <td>
                            <button name="edit-prod" onclick="editProduct(<?=$row['id']?>, '<?=$row['name']?>', <?=$row['price']?>);" class="ui big button">Editar</button>
                            <button type="submit" name="" class="ui big red button" onclick="removeProduct(<?=$row['id']?>,'<?=$row['name']?>');">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="ui tab segment" data-tab="admin-user">
            <table class="ui selectable celled table">
                <thead>
                    <tr>
                        <th style="font-size: 17px;">Id</th>
                        <th style="font-size: 17px;">Nombre</th>
                        <th style="font-size: 17px;">Email</th>
                        <th></th>
                    </tr>
                </thead>
                <?php foreach($admin as $user):?>
                    <tr>
                        <td style="font-size: 17px;" id="user-id"><?php echo $user['id']; ?></td>
                        <td style="font-size: 17px;" id="user-name"><?php echo $user['name']; ?></td>
                        <td style="font-size: 17px;" id="user-price"><?php echo $user['email']; ?></td>
                        <td>
                            
                            <button name="edit-user" onclick="editUser(<?=$user['id']?>, '<?=$user['name']?>', '<?=$user['email']?>');" class="ui big button">Editar</button>
                            <button type="submit" name="" class="ui big red button" onclick="deleteUser(<?=$user['id']?>,'<?=$user['name']?>');">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

        <!--Modal edicion producto-->
        <div class="ui modal" id="edit-modal">
            <div class="ui content">
                <i class="close icon"></i>
                <h3 class="header">Editar Producto</h3>
                <div class="content">
                    <form class="ui form" id="edit-form" action="<?php echo BASE_URL; ?>controllers/product_manage.php" method="POST">
                        <input type="hidden" name="product_id" value="" id="edit-product-id">
                        <div class="field">
                            <input type="text" id="product_name" name="product_name"  value="">
                        </div>
                        <div class="field">
                            <input type="number" step="0.01"  id="product_price" name="product_price" value="">
                        </div>
                        <button type="submit" class="ui primary button" name="changeProd">Guardar</button>
                    </form>
                </div>
            </div>
        </div>

            <!--Modal edicion usuario-->
        <div class="ui modal" id="edit-user-modal">
            <div class="ui content">
                <i class="close icon"></i>
                <h3 class="header">Editar información de usuario</h3>
                <div class="content">
                    <form class="ui form" id="edit-user-form" action="<?php echo BASE_URL; ?>controllers/user_manager.php" method="POST">
                        <input type="hidden" name="user_id" value="" id="edit-user-id">
                        <div class="field">
                            <input type="text" id="user_name" name="user_name"  value="">
                        </div>
                        <div class="field">
                            <input type="email" step="0.01"  id="user_email" name="user_email" value="">
                        </div>
                        <button type="submit" class="ui primary button" name="changeUser">Guardar</button>
                    </form>
                </div>
            </div>
        </div>

            <!--Modal confirmación de borrado-Usuario-->
            <div class="ui modal" id="rm-user-modal">
                <div class="ui content">
                    <i class="close icon"></i>
                    <h3 class="header">Eliminar usuario?</h3>
                    <form class="ui form" action="<?php echo BASE_URL; ?>controllers/user_manager.php" method="POST">
                        <input type="hidden" name="user-id" id="uID" class="field" value="">
                        <p style="font-size: 17px;">El usuario a eliminar es:</p>
                        <div class="field">
                            <input type="text" readonly="" id="uName" class="disabled" placeholder="">
                        </div>
                        <button type="submit" name="rmUser" class="ui red button">Eliminar</button>
                    </form>
                </div>
            </div>

                <!--Modal confirmación de borrado-->
            <div class="ui modal" id="rm-modal">
                <div class="ui content">
                    <i class="close icon"></i>
                    <h3 class="header">Eliminar producto?</h3>
                    <form class="ui form" action="<?php echo BASE_URL; ?>controllers/product_manage.php" method="POST">
                        <input type="hidden" name="product-id" id="pID" class="field" value="">
                        <p style="font-size: 17px;">El producto a eliminar es:</p>
                        <div class="field">
                            <input type="text" readonly="" id="pName" placeholder="">
                        </div>
                        <button type="submit" name="rmProd" class="ui red button">Eliminar</button>
                    </form>
                </div>
            </div>

    </div>



    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/editProduct.js?v=<?php echo filemtime('assets/editProduct.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/editUser.js?v=<?php echo filemtime('assets/editUser.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/auth.js?v=<?php echo filemtime('assets/auth.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/tab.js?v=<?php echo filemtime('assets/tab.js'); ?>"></script>
</body>
</html>