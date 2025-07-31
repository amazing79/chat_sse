<?php

use Ignacio\ChatSsr\Application\User\LoginUserCommandHandler;
use Ignacio\ChatSsr\Application\User\RegisterResetRequestCommandHandler;
use Ignacio\ChatSsr\Application\User\RegisterUserCommandHandler;
use Ignacio\ChatSsr\Application\User\ResetPasswordCommandHandler;
use Ignacio\ChatSsr\Infraestructure\Common\DB;
use Ignacio\ChatSsr\Infraestructure\User\UserMysqlRepository;
use Ignacio\ChatSsr\Domain\Common\Utils;

session_start();

require __DIR__ . '/../vendor/autoload.php';

$action = $_POST['action'] ?? '';

if ($action === 'register') {
    $values = [];
    $values['nombre'] = $_POST['nombre'];
    $values['apellido']= $_POST['apellido'];
    $values['email'] = $_POST['email'];
    $values['password'] = $_POST['password'];
    $values['password_confirm'] = $_POST['password2'];

    $command = new RegisterUserCommandHandler(new UserMysqlRepository(new DB()));
    $result = $command->handle($values);
    if ($result['code'] === 200) {
        echo "Registro exitoso. <a href='index.html'>Ingresar</a>";
    } else {
        echo "Error: " . $result['message'];
    }
    exit;
}

if ($action === 'login') {
    $credentials['email'] = Utils::cleanEmail($_POST['email']);
    $credentials['password'] = $_POST['password'];
    $command  = new LoginUserCommandHandler(new UserMysqlRepository(new DB()));
    $response = $command->handle($credentials);

    if ($response['code'] === 200) {
        $_SESSION['user_id'] = $response['data'];
        header("Location: main.php");
        exit;
    } else {
        header("Location: index.html");
        exit;
    }
}

if ($action === 'reset_request') {
    $email = Utils::cleanEmail($_POST['email']);
    if (!$email) die("Email inválido");
    $values['email'] = $email;
    $values['token'] = bin2hex(random_bytes(32));
    $values['expires_at'] = date("Y-m-d H:i:s", time() + 3600);

    $command = new RegisterResetRequestCommandHandler(new UserMysqlRepository(new DB()));
    $response = $command->handle($values);

    if ($response['code'] === 200) {
        $link = "http://localhost/sistemas/chat/reset_password.php?token=$values[token]";
        // Enviar por correo en producción, aquí se muestra directamente
        echo "Enlace para restablecer: <a href='$link'>$link</a>";
        exit;
    }
}

if ($action === 'reset_confirm') {
    $pass = $_POST['password'];
    $pass2 = $_POST['password2'];

    if ($pass !== $pass2) die("Las contraseñas no coinciden");
    $newCredentials['token'] = $_POST['token'];
    $newCredentials['password'] = $pass;
    $command = new ResetPasswordCommandHandler(new UserMysqlRepository(new DB()));
    $response = $command->handle($newCredentials);
    if ($response['code'] === 200) {
        echo "Contraseña actualizada. <a href='index.html'>Ingresar</a>";
    } else {
        echo $response['message'];
    }
    exit;
}

