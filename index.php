<?php
//Creo las rutas bases que diferencien entornos
require_once __DIR__ . '/config/paths.php';
session_start();
require_once BASE_PATH . 'database.php';
require_once dirname(BASE_PATH) . '/models/product_model.php';

$productModel = new Product($mysqli);
$producto = $productModel->getAllProducts();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
    <title>Huellas!</title>
</head>
<body>
    <header>
        <nav>
            <div class="ui centered menu">
                <?php if (isset($_SESSION['user_id'])) : ?>
                <a class="ui header centered item disabled">Bienvenido, <?php echo $_SESSION['name']; ?> </a>
                <?php if (isset ($_SESSION['role']) && $_SESSION['role']=== 'admin') : ?>
                    <a href="<?php echo BASE_URL; ?>view/admin_view.php" class="ui header item">Vista de administrador</a>
                <?php endif; ?>
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

<main class="ui container" style="margin-top:20px;">
    <div style="margin-bottom:30px;">
        <h1 class="ui header center aligned">Bienvenido a Huellas!</h1>
        <h2 class="ui header center aligned">Crea tu camino</h2>
    </div>
    <div class="ui three column grid">
        <div class="ui link cards centered">
                <?php foreach ($producto as $product): ?>
                    <div class="ui card">
                        <div class="image">
                            <a href="<?php echo BASE_URL; ?>view/product.php?id=<?php echo $product['id']; ?>" aria-label="Ver <?php echo $product['name']; ?>">
                                <img class="ui large image" src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
                            </a>
                        </div>
                        <a href="<?php echo BASE_URL; ?>view/product.php?id=<?php echo $product['id']; ?>" aria-label="Ver <?php echo $product['name']; ?>">
                            <h3 class="ui header"><?php echo $product['name']; ?></h3>
                        </a>
                        <div class="extra content">
                            <div class="description"><?php echo $product['price']; ?>€</div>
                        </div>
                    </div>
                <?php endforeach; ?>
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
            <h2>Registrarse</h2>
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

    <footer style="margin-top:50px;">
        <div class="ui segment" style="text-align: center;">
            <p>&copy; 2026. Todos los derechos reservados</p>
            <p>Desarrollado por Ignacio Prieto | Tecnologías: HTML5, Semantic UI, JavaScript, PHP, MySQL</p>
            <p>Este proyecto ha sido desarrollado con fines académicos. El titular de la web no posee los derechos de venta de los productos aquí mostrados, ni busca el rédito económico con esta actividad</p>
        </div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>
    
    <script src="<?php echo BASE_URL; ?>view/assets/product.js?v=<?php echo filemtime('view/assets/product.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/cart.js?v=<?php echo filemtime('view/assets/cart.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/auth.js?v=<?php echo filemtime('view/assets/auth.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/modal.js?v=<?php echo filemtime('view/assets/modal.js'); ?>"></script>
    
    
</html>
</body>
</html>