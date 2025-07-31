<?php

use Ignacio\ChatSsr\Application\User\LoginUserCommandHandler;
use Ignacio\ChatSsr\Application\User\RegisterResetRequestCommandHandler;
use Ignacio\ChatSsr\Application\User\RegisterUserCommandHandler;
use Ignacio\ChatSsr\Application\User\ResetPasswordCommandHandler;
use Ignacio\ChatSsr\Infraestructure\Common\DB;
use Ignacio\ChatSsr\Infraestructure\User\UserMysqlRepository;

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
    $response = $command->handle($values);
    if ($response['code'] === 200) {
        echo "Registro exitoso. <a href='index.html'>Ingresar</a>";
    } else {
        echo "Error: " . $response['message'];
    }
    exit;
}

if ($action === 'login') {
    $credentials['email'] = $_POST['email'];
    $credentials['password'] = $_POST['password'];
    $command  = new LoginUserCommandHandler(new UserMysqlRepository(new DB()));
    $response = $command->handle($credentials);

    if ($response['code'] === 200) {
        $_SESSION['user_id'] = $response['data'];
        header("Location: main.php");
    } else {
        header("Location: index.html");
    }
    exit;
}

if ($action === 'reset_request') {
    $email = $_POST['email'];
    $command = new RegisterResetRequestCommandHandler(new UserMysqlRepository(new DB()));
    $response = $command->handle($email);
    if ($response['code'] === 200) {
        $link = "http://localhost/sistemas/chat/reset_password.php?token={$response['data']}";
        // Enviar por correo en producción, aquí se muestra directamente
        echo "Enlace para restablecer: <a href='$link'>$link</a>";
        exit;
    }
}

if ($action === 'reset_confirm') {
    $newCredentials['token'] = $_POST['token'];
    $newCredentials['password'] = $_POST['password'];
    $newCredentials['password_confirm'] = $_POST['password2'];
    $command = new ResetPasswordCommandHandler(new UserMysqlRepository(new DB()));
    $response = $command->handle($newCredentials);
    if ($response['code'] === 200) {
        echo "Contraseña actualizada. <a href='index.html'>Ingresar</a>";
    } else {
        echo $response['message'];
    }
    exit;
}

