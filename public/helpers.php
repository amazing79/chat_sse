<?php

use Ignacio\ChatSsr\Application\User\GetUserByIdQueryHandler;
use Ignacio\ChatSsr\Infraestructure\User\UserMysqlRepository;
use Ignacio\ChatSsr\Infraestructure\Common\DB;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
function getUserForActiveSession(): mixed
{
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.html');
        exit;
    }

    $query = new GetUserByIdQueryHandler(new UserMysqlRepository(new DB()));
    $response = $query->handle($_SESSION['user_id']);

    if ($response['code'] !== 200) {
        session_destroy();
        header('Location: index.html');
        exit;
    }
    return $response['data'];
}