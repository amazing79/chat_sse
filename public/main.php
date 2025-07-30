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
        <a class="logout" href="logout.php">Cerrar sesión</a>
    </h2>

    <div id="messages"></div>

    <div id="input-area">
        <input id="msgInput" type="text" placeholder="Escribe un mensaje">
        <button id="sendBtn">Enviar</button>
    </div>
</div>
<script src="assets/js/chat.js"></script>
</body>
</html>
