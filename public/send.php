<?php

use Ignacio\ChatSsr\Chat\Chat;
use Ignacio\ChatSsr\Chat\RedisRepository;

require __DIR__ . '/../vendor/autoload.php';

$db = new RedisRepository();
$chat = new Chat($db->getClient());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user'] ?? 'Anónimo');
    $msg = trim($_POST['msg'] ?? '');

    if ($msg !== '') {
        $message[] =  date('H:i:s') . ' - ' .$user . ": " . $msg;
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
