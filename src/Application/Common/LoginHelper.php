<?php

namespace Ignacio\ChatSsr\Application\Common;

use Ignacio\ChatSsr\Application\User\GetChatUsersQueryHandler;
use Ignacio\ChatSsr\Application\User\GetUserByIdQueryHandler;
use Ignacio\ChatSsr\Application\User\LoginUserCommandHandler;
use Ignacio\ChatSsr\Application\User\RegisterResetRequestCommandHandler;
use Ignacio\ChatSsr\Application\User\RegisterUserCommandHandler;
use Ignacio\ChatSsr\Application\User\ResetPasswordCommandHandler;
use Ignacio\ChatSsr\Infraestructure\Chat\RedisRepository;
use Ignacio\ChatSsr\Infraestructure\User\UserMysqlRepository;

class LoginHelper
{
    public static function getUserForActiveSession(): mixed
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.html');
            exit;
        }

        $query = new GetUserByIdQueryHandler(new UserMysqlRepository());
        $response = $query->handle($_SESSION['user_id']);

        if ($response['code'] !== 200) {
            session_destroy();
            header('Location: index.html');
            exit;
        }
        return $response['data'];
    }

    /**
     * @param $request
     * @return void
     */
    public static function registerUser($request): void
    {
        $values = [];
        $values['nombre'] = $request['nombre'];
        $values['apellido'] = $request['apellido'];
        $values['email'] = $request['email'];
        $values['password'] = $request['password'];
        $values['password_confirm'] = $request['password2'];

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
    }

    /**
     * @param $request
     * @return void
     */
    public static function resetPasswordRequest($request): void
    {
        $email = $request['email'];
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
    }

    /**
     * @param $request
     * @return void
     */
    public static function resetPassword($request): void
    {
        $newCredentials['token'] = $request['token'];
        $newCredentials['password'] = $request['password'];
        $newCredentials['password_confirm'] = $request['password2'];
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
    }

    /**
     * @param $request
     * @return void
     */
    public static function loginUser($request): void
    {
        $credentials['email'] = $request['email'];
        $credentials['password'] = $request['password'];
        $command = new LoginUserCommandHandler(new UserMysqlRepository(),new RedisRepository());
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

    public static function getChatUsers(): array
    {
        $query = new GetChatUsersQueryHandler(new UserMysqlRepository());
        $response = $query->handle();
        return $response['data'];
    }
}