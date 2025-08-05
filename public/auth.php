<?php

use Ignacio\ChatSsr\Domain\User\Consts\UserActions;
use Ignacio\ChatSsr\Application\Common\LoginHelper;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/../');
$dotenv->safeLoad();
$action = $_POST['action'] ?? '';

switch ($action) {
    case UserActions::REGISTER:
        LoginHelper::registerUser($_POST);
        break;
    case UserActions::RESET_REQUEST:
        LoginHelper::resetPasswordRequest($_POST);
        break;
    case UserActions::RESET_CONFIRM:
        LoginHelper::resetPassword($_POST);
        break;
    case UserActions::LOGIN:
    default:
    LoginHelper::loginUser($_POST);
}
exit;
