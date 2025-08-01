<?php

use Ignacio\ChatSsr\Application\User\LoginUserCommandHandler;
use Ignacio\ChatSsr\Application\User\RegisterResetRequestCommandHandler;
use Ignacio\ChatSsr\Application\User\RegisterUserCommandHandler;
use Ignacio\ChatSsr\Application\User\ResetPasswordCommandHandler;
use Ignacio\ChatSsr\Domain\User\Consts\UserActions;
use Ignacio\ChatSsr\Infraestructure\Common\DB;
use Ignacio\ChatSsr\Infraestructure\User\UserMysqlRepository;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/../');
$dotenv->safeLoad();

$action = $_POST['action'] ?? '';

switch ($action) {
    case UserActions::REGISTER:
        $values = [];
        $values['nombre'] = $_POST['nombre'];
        $values['apellido']= $_POST['apellido'];
        $values['email'] = $_POST['email'];
        $values['password'] = $_POST['password'];
        $values['password_confirm'] = $_POST['password2'];

        $command = new RegisterUserCommandHandler(new UserMysqlRepository());
        $response = $command->handle($values);
        $result = [];
        if ($response['code'] === 200) {
            $result['url'] = "url=index.html";
            $result['type'] = 'type=success';
            $msg = urlencode('Usuario registrado exitosamente');
            $result['text'] = "msg={$msg}";
            $params = implode('&', $result);
            $targetUrl = "operation_info.php?{$params}";
            header('Location: ' . $targetUrl);
        } else {
            $result['url'] = "url=index.html";
            $result['type'] = 'type=error';
            $result['text'] = "msg={$response['message']}";
            $params = implode('&', $result);
            $targetUrl = "operation_info.php?{$params}";
            header('Location: ' . $targetUrl);
        }
        break;
    case UserActions::RESET_REQUEST:
        $email = $_POST['email'];
        $command = new RegisterResetRequestCommandHandler(new UserMysqlRepository());
        $response = $command->handle($email);
        $result = [];
        if ($response['code'] === 200) {
            $link = "http://localhost/sistemas/chat/reset_password.php?token={$response['data']}";
            $result['url'] = "url={$link}";
            $result['type'] = 'type=success';
            $result['text'] = "msg={$response['message']}";
            $params = implode('&', $result);
            $targetUrl = "operation_info.php?{$params}";
            header('Location: ' . $targetUrl);
        } else {
            $result['url'] = "url=index.html";
            $result['type'] = 'type=error';
            $result['text'] = "msg={$response['message']}";
            $params = implode('&', $result);
            $targetUrl = "operation_info.php?{$params}";
            header('Location: ' . $targetUrl);
        }
        break;
    case UserActions::RESET_CONFIRM:
        $newCredentials['token'] = $_POST['token'];
        $newCredentials['password'] = $_POST['password'];
        $newCredentials['password_confirm'] = $_POST['password2'];
        $command = new ResetPasswordCommandHandler(new UserMysqlRepository());
        $response = $command->handle($newCredentials);
        $result = [];
        if ($response['code'] === 200) {
            $result['url'] = "url=index.html";
            $result['type'] = 'type=success';
            $result['text'] = "msg={$response['message']}";
            $params = implode('&', $result);
            $targetUrl = "operation_info.php?{$params}";
            header('Location: ' . $targetUrl);
            echo "Contraseña actualizada. <a href='index.html'>Ingresar</a>";
        } else {
            $result['url'] = "url=index.html";
            $result['type'] = 'type=error';
            $result['text'] = "msg={$response['message']}";
            $params = implode('&', $result);
            $targetUrl = "operation_info.php?{$params}";
            header('Location: ' . $targetUrl);
        }
        break;
    case UserActions::LOGIN:
    default:
    $credentials['email'] = $_POST['email'];
    $credentials['password'] = $_POST['password'];
    $command  = new LoginUserCommandHandler(new UserMysqlRepository());
    $response = $command->handle($credentials);
    $result = [];
    if ($response['code'] === 200) {
        session_start();
        $_SESSION['user_id'] = $response['data'];
        header("Location: main.php");
    } else {
        $result['url'] = "url=index.html";
        $result['type'] = 'type=error';
        $result['text'] = "msg={$response['message']}";
        $params = implode('&', $result);
        $targetUrl = "operation_info.php?{$params}";
        header('Location: ' . $targetUrl);
    }
}
exit;
