<?php
require_once dirname(__DIR__) . '/config/paths.php';
session_start();
require_once BASE_PATH . 'database.php';
require_once dirname(BASE_PATH) . '/models/product_model.php';

//Le paso la id recogida por click en index.php. Si no hay id no carga nada por defecto
$id = $_GET['id']??0;

$productModel = new Product($mysqli);
$product = $productModel->getProductById($id);

//Manejo de errores si falla la carga del producto
if (!$product){
    header ("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
    <title><?php echo $product['name']; ?> - Huellas!</title>
</head>
<body id="product-page" data-page="product">
    <header>
        <nav>
            <div class="ui menu">
                <a href="<?php echo BASE_URL; ?>index.php" class="ui header item">
                    <i class="home icon"></i>
                    Inicio
                </a>
                <?php if (isset($_SESSION['user_id'])) : ?>
                <a class="item disabled">Bienvenido, <?php echo $_SESSION['name']; ?> </a>
                <a href="<?php echo BASE_URL; ?>controllers/logout.php" name="close-session" class="ui header item">
                    Cerrar Sesión
                </a>
                <a href="<?php echo BASE_URL; ?>view/profile.php" class="ui header item">
                    Ver perfil
                </a>
                <?php else : ?>
                <a href="#" id="login-link" class="ui header item">
                    Inicia Sesión
                </a>
                <a href="#" id="register-link" class="ui header item">
                    Regístrate
                </a>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>view/cart.php" class="ui header item">
                    Carrito(<span id="cart-count"></span>)
                </a>
            </div>
        </nav>
    </header>

    <main>
        <div class="ui segment" style="margin:2% 5% 0% 5%;">
            <div class="ui center aligned three column grid">
                <div class="column">
                    <p hidden id="product-id"><?php echo $product['id']; ?></p>
                    <!--Foto-->
                    <img class="ui big image" src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" id="main-product-img">
                </div>

                <div id="product-description" class="column" >
                    <!--Descripcion-->
                    <h1 id="product-title"><?php echo $product['name']; ?></h1>
                    <p style="margin-top:20%; font-size:20px;" class="sub header"><?php echo $product['descript']; ?></p>
                </div>

                <div class="column" style="margin-top:0%;">
                    <!--Precio-->
                    <h2 class="ui header" id="product-price" style="margin-top:30%;"><?php echo $product['price']; ?>€</h3>
                    <div class="ui buttons">
                        <button type="button" id="btn-add" class="ui big blue button">Añadir</button>
                        <button type="button" id="delete-storage" class="ui big button">Borrar</button>
                    </div>
                </div>
            </div>

            <div>

            </div>
            
        </div>

    
    </main>

    <!--Modal Login-->
    <div id="loginModal" class="ui modal">
        <div class="content">
            <i class="close icon"></i>
            <h2>Inicio de sesión</h2>
            <form action="<?php echo BASE_URL; ?>controllers/login-register.php" method="post" id="login-form" class="ui form">
                <input type="hidden" name="login" value="1">
                <div class="field">
                    <input  name="email" type="email" placeholder="Email" id="login-email" required>
                </div>
                <div class="field">
                    <input  name="password" type="password" placeholder="Contraseña" id="login-password" required>
                </div>
                <button name="login" type="submit" class="ui button">Iniciar</button>
            </form>
        </div>
    </div>

    <!--Modal registro-->
    <div id="registerModal" class="ui modal">
        <div class="content">
            <i class="close icon"></i>
            <form action="<?php echo BASE_URL; ?>controllers/login-register.php" method="post" id="register-form" class="ui form">
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
                <button name="register" type="submit" class="ui button">Registrarse</button>
            </form>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>

    <script src="<?php echo BASE_URL; ?>view/assets/product.js?v=<?php echo filemtime('assets/product.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/cart.js?v=<?php echo filemtime('assets/cart.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/auth.js?v=<?php echo filemtime('assets/auth.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/modal.js?v=<?php echo filemtime('assets/modal.js'); ?>"></script>
    

</body>
</html>
</body>
</html>