<?php

use Ignacio\ChatSsr\Domain\Chat\Chat;
use Ignacio\ChatSsr\Infraestructure\Chat\MysqlRepository;
use Ignacio\ChatSsr\Infraestructure\Chat\RedisRepository;
use Ignacio\ChatSsr\Application\Common\LoginHelper;

require __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/../');
$dotenv->safeLoad();

$user = LoginHelper::getUserForActiveSession();
$db = new RedisRepository();
// En caso de trabajar con mysql para repositorio de mensajes, comentar el anterior y descomentar Mysql
// $db = new MysqlRepository();
$chat = new Chat($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($user['nombre'] ?? 'Anónimo');
    $msg = trim($_POST['msg'] ?? '');

    if ($msg !== '') {
        $message=[];
        $message['user'] = $user;
        $message['message'] =  $msg;

        $chat->sendGlobalMessage($message);
        echo "OK";
    } else {
        http_response_code(400);
        echo "Mensaje vacío";
    }
} else {
    http_response_code(405);
    echo "Método no permitido";
}
