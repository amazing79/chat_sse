<?php
/**
 * @return mixed|void
 */
use Ignacio\ChatSsr\Application\Common\LoginHelper;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/../');
$dotenv->safeLoad();

$user = LoginHelper::getUserForActiveSession();
$users = LoginHelper::getChatUsers();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Chat</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/chat.css">
</head>
<body>
<div id="chat-container" class="container">
    <h2>Bienvenido <?= htmlspecialchars($user['nombre']) ?> <?= htmlspecialchars($user['apellido']) ?>
        <a id="logout" class="logout" href="logout.php">Salir</a>
    </h2>
    <div class="message_container">
        <div class="user_container">
            <ul id="users_list" class="user_list">
                <?php
                foreach ($users as $userChat) {
                    $state = $userChat['email'] === $user['email'] ? 'user_list_active' : '';
                    $item = '<li class="user_list__item ' . $state . '" data-user="'. $userChat['email'] .'"> ' . $userChat['nombre'] . '</li>';
                    echo $item;
                }
                ?>

            </ul>
        </div>
        <div id="messages">

        </div>
    </div>
    <div id="input-area">
        <input id="msgInput" type="text" placeholder="Escribe un mensaje">
        <button id="sendBtn">Enviar</button>
    </div>
</div>
<script src="assets/js/chat.js"></script>
</body>
</html>
