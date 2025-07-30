<?php

session_start();
$pdo = new PDO("mysql:host=localhost;dbname=chatdb;charset=utf8mb4", "root", "gueraike");

// Sanitizar y validar email
function cleanEmail($email)
{
    return filter_var(trim($email), FILTER_VALIDATE_EMAIL);
}

$action = $_POST['action'] ?? '';

if ($action === 'register') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $email = cleanEmail($_POST['email']);
    $pass = $_POST['password'];
    $pass2 = $_POST['password2'];

    if (!$email || $pass !== $pass2) {
        die("Error: Email inválido o contraseñas no coinciden");
    }

    $hash = password_hash($pass, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, password) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nombre, $apellido, $email, $hash]);
    echo "Registro exitoso. <a href='auth.html'>Ingresar</a>";
    exit;
}

if ($action === 'login') {
    $email = cleanEmail($_POST['email']);
    $pass = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: main.php");
        exit;
    } else {
        echo "Error de inicio de sesión";
        exit;
    }
}

if ($action === 'reset_request') {
    $email = cleanEmail($_POST['email']);
    if (!$email) die("Email inválido");

    $token = bin2hex(random_bytes(32));
    $expires = date("Y-m-d H:i:s", time() + 3600);

    $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
    $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)")
        ->execute([$email, $token, $expires]);

    $link = "http://localhost/sistemas/chat/reset_password.php?token=$token";
    // Enviar por correo en producción, aquí se muestra directamente
    echo "Enlace para restablecer: <a href='$link'>$link</a>";
    exit;
}

if ($action === 'reset_confirm') {
    $token = $_POST['token'];
    $pass = $_POST['password'];
    $pass2 = $_POST['password2'];

    if ($pass !== $pass2) die("Las contraseñas no coinciden");

    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ? AND expires_at > NOW()");
    $stmt->execute([$token]);
    $row = $stmt->fetch();

    if ($row) {
        $hash = password_hash($pass, PASSWORD_DEFAULT);
        $pdo->prepare("UPDATE usuarios SET password = ? WHERE email = ?")
            ->execute([$hash, $row['email']]);
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$row['email']]);
        echo "Contraseña actualizada. <a href='auth.html'>Ingresar</a>";
    } else {
        echo "Token inválido o vencido";
    }
    exit;
}

