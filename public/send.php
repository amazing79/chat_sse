<?php

use Ignacio\ChatSsr\Chat\Chat;
use Ignacio\ChatSsr\Chat\MysqlRepository;
use Ignacio\ChatSsr\Chat\RedisRepository;

require __DIR__ . '/../vendor/autoload.php';

// $db = new RedisRepository();
$db = new MysqlRepository();
$chat = new Chat($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user'] ?? 'Anónimo');
    $msg = trim($_POST['msg'] ?? '');

    if ($msg !== '') {
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
