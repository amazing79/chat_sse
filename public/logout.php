<?php

use Ignacio\ChatSsr\Application\Services\RemoveActiveUserService;
use Ignacio\ChatSsr\Application\User\GetUserByIdQueryHandler;
use Ignacio\ChatSsr\Domain\User\User;
use Ignacio\ChatSsr\Infraestructure\Chat\RedisRepository;
use Ignacio\ChatSsr\Infraestructure\User\UserMysqlRepository;

require __DIR__ . '/../vendor/autoload.php';
session_start();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/../');
$dotenv->safeLoad();

if(isset($_SESSION['user_id'])){
    $userId = $_SESSION['user_id'];
    $query = new GetUserByIdQueryHandler(new UserMysqlRepository());
    $response = $query->handle($userId);
    if($response['code'] === 200){
        $user = User::createUserFromArray($response['data']);
        $service = new RemoveActiveUserService(new RedisRepository());
        $service->handle($user);
    }
}
session_unset();
session_destroy();

header("Location: index.html");
exit;
