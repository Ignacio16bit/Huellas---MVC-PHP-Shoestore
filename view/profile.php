<?php
require_once dirname(__DIR__) . '/config/paths.php';
session_start();
require_once dirname(BASE_PATH) . '/models/user_model.php';
require_once BASE_PATH . 'database.php';
require_once dirname(BASE_PATH) . '/models/order_model.php';

if (isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $userModel = new userModel($mysqli);
    $usuario = $userModel->getUserByEmail($_SESSION['email']);

    $pedidos = new Order($mysqli);
    $historial = $pedidos->getOrdersByUserId($user_id);

    $pedidos_agrupados = [];
    if(!empty($historial)){
        foreach($historial as $item){
            $pedido_id = $item['pedido_id'];
            if(!isset($pedidos_agrupados[$pedido_id])){
                $pedidos_agrupados[$pedido_id] = [
                    'fecha' => $item['fecha'],
                    'productos' => []
                ];
            }
            $pedidos_agrupados[$pedido_id]['productos'][]=[
                'name' => $item['name'],
                'price' => $item['price'],
                'image' => $item['image']
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.css">
    <title>Huellas! - Perfil</title>
</head>
<body>
    <header>
        <nav>
            <div class="ui menu">
                <?php if (isset($_SESSION['user_id'])) : ?>
                <a href="<?php echo BASE_URL; ?>index.php" class="ui header item">
                    <i class="home icon"></i>
                    Inicio
                </a>
                <a class="item disabled">Bienvenido, <?php echo $_SESSION['name']; ?> </a>
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
                <a href="<?php echo BASE_URL; ?>view/cart.php" class="ui header item">
                    Carrito(<span id="cart-count"></span>)
                </a>
            </div>
        </nav>
    </header>

    <main class="ui container">
        <div class="ui pointing menu" id="sidebar">
            <a class="ui header item active" data-tab="info-tab">Información del perfil</a>
            <a class="ui header item" data-tab="remove-tab">Eliminar Perfil</a>
            <a class="ui header item" data-tab="order-tab">Historial de pedidos</a>
        </div>



        <div class="ui tab segment active" data-tab="info-tab">
            <div class="segment">
                <h3 class="sub header" id="user-name"><?php echo $_SESSION['name']; ?>
                    <button class="ui medium button" onclick="editName();">Cambiar</button>
                </h3>
            </div>
            <div class="ui divider"></div>
            <div class="segment">
                <h3 class="sub header"><?php echo $_SESSION['email']; ?>
                    <button class="ui medium button" onclick="editEmail();">Cambiar</button>
                </h3>
            </div>
            <div class="ui divider"></div>
            <div class="segment">
                <h3 class="sub header">Cambiar contraseña
                    <button class="ui medium button" onclick="changePass()">Cambiar</button>
                </h3>
            </div>
        </div>

        <div class="ui tab segment" data-tab="remove-tab">
            <div>
                <h3 class="header">Eliminar perfil</h3>
                <button class="ui red button" name="" onclick="rmUser()">Eliminar</button>
            </div>
        </div>

        <div class="ui tab segment" data-tab="order-tab">
            <?php if(!empty($pedidos_agrupados)) : ?>
                <?php foreach($pedidos_agrupados as $pedido_id =>$pedido) : ?>
                    <h3>Pedido nº <?php echo $pedido_id; ?> - <?php echo $pedido['fecha']; ?></h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th style="font-size: 17px;">Producto</th>
                                <th style="font-size: 17px;">Precio</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($pedido['productos'] as $producto) : ?>
                                <tr>
                                    <td style="font-size: 17px;"><img src="<?php echo $producto['image']; ?>" width="70" class="small image"></td>
                                    <td style="font-size: 17px;"><?php echo $producto['name']; ?></td>
                                    <td style="font-size: 17px;"><?php echo $producto['price']; ?>€</td>
                                </tr>
                                <?php endforeach; ?>
                        </tbody>
                    </table>
                    <br>
                <?php endforeach; ?>
            <?php else : ?>
            <p>No hay pedidos que mostrar</p>
            <?php endif; ?>
        </div>

                <!--MODALES-->

        <div class="ui modal" id="name-modal">
            <div class="ui content">
                <i class="close icon"></i>
                <form action="<?php echo BASE_URL; ?>controllers/user_manager.php" method="POST" class="ui form">
                    <div class="field">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                        <input type="hidden" name="user_email" value="<?php echo $_SESSION['email']; ?>">
                        <label style="font-size: 17px;">Nombre actual</label>
                        <input type="text" id="change-name" value="<?php echo $_SESSION['name']; ?>" disabled>
                    </div>
                    <div class="field">
                        <label style="font-size: 17px;">Nombre nuevo</label>
                        <input type="text" name="new_name" required>
                    </div>
                    <button type="submit" class="ui button" name="update_name">Actualizar</button>
                </form>
            </div>
        </div>

        <div class="ui modal" id="email-modal">
            <div class="ui content">
                <form action="<?php echo BASE_URL; ?>controllers/user_manager.php" method="POST" class="ui form">
                    <i class="close icon"></i>
                    <div class="field">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                        <input type="hidden" name="user_name" value="<?php echo $_SESSION['name']; ?>">
                        <label style="font-size: 17px;">Email actual</label>
                        <input type="text" id="change-email" value="<?php echo $_SESSION['email']; ?>" disabled>
                    </div>
                    <div class="field">
                        <label style="font-size: 17px;">Email nuevo</label>
                        <input type="email" name="new_email" required>
                    </div>
                    <button type="submit" class="ui button" name="update_email">Actualizar</button>
                </form>
            </div>
        </div>

        <div class="ui modal" id="rm-modal">
            <div class="ui content">
                <i class="close icon"></i>
                <h3 class="header">Eliminar perfil?</h3>
                <form class="ui form" action="<?php echo BASE_URL; ?>controllers/user_manager.php" method="POST">
                    <input type="hidden" name="user-id" id="uID" class="field" value="<?php echo $_SESSION['user_id']; ?>">
                    <label style="font-size: 17px;">Eliminar perfil de <?php echo $_SESSION['name']; ?></label>
                    <button type="submit" name="removeUser" class="ui red button">Eliminar</button>
                </form>
            </div>
        </div>

        <div class="ui modal" id="pass-modal">
            <div class="ui content">
                <form action="<?php echo BASE_URL; ?>controllers/user_manager.php" method="POST" class="ui form">
                    <div class="field">
                        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">
                    </div>
                    <div class="field">
                        <label style="font-size: 17px;">Contraseña nueva</label>
                        <input type="password" name="new_pass" required>
                    </div>
                    <button type="submit" class="ui button" name="update_pass">Actualizar</button>
                </form>
            </div>
        </div>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.5.0/dist/semantic.min.js"></script>

    <script src="<?php echo BASE_URL; ?>view/assets/tab.js?v=<?php echo filemtime('assets/tab.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/editUser.js?v=<?php echo filemtime('assets/editUser.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/product.js?v=<?php echo filemtime('assets/product.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/cart.js?v=<?php echo filemtime('assets/cart.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/auth.js?v=<?php echo filemtime('assets/auth.js'); ?>"></script>
    <script src="<?php echo BASE_URL; ?>view/assets/modal.js?v=<?php echo filemtime('assets/modal.js'); ?>"></script>
</body>
</html>