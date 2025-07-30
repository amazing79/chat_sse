<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.html');
    exit;
}

$pdo = new PDO("mysql:host=localhost;dbname=chatdb;charset=utf8mb4", "root", "gueraike");
$stmt = $pdo->prepare("SELECT nombre, apellido FROM usuarios WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header('Location: index.html');
    exit;
}

use Ignacio\ChatSsr\Domain\Chat\Chat;
use Ignacio\ChatSsr\Infraestructure\Chat\MysqlRepository;
use Ignacio\ChatSsr\Infraestructure\Chat\RedisRepository;

require __DIR__ . '/../vendor/autoload.php';

$db = new RedisRepository();
//$db = new MysqlRepository();
$chat = new Chat($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($user['nombre'] ?? 'Anónimo');
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
