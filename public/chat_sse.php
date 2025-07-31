<?php
set_time_limit(0);

use Ignacio\ChatSsr\Domain\Chat\Chat;
use Ignacio\ChatSsr\Infraestructure\Chat\MySqlMessagePresenter;
use Ignacio\ChatSsr\Infraestructure\Chat\MysqlRepository;
use Ignacio\ChatSsr\Infraestructure\Chat\RedisRepository;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ .'/../');
$dotenv->safeLoad();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');

$db = new RedisRepository();
//$db = new MysqlRepository();
//$presenter = new MySqlMessagePresenter();
//$chat = new Chat($db, $presenter);
$chat = new Chat($db);
$lastCount = $chat->getTotalMessages();

// Al conectar, mandar el historial inicial
if ($lastCount > 0) {
    echo $chat->getAllMessagesEvent();
    ob_flush();
    flush();
}

while (true) {
    // Ver cuántos mensajes hay ahora
    $count = $chat->getTotalMessages();

    if ($count > $lastCount) {
        // Hay mensajes nuevos → obtener solo los nuevos
        echo $chat->getLastsMessagesEvents($lastCount);
        ob_flush();
        flush();
        $lastCount = $count;
    }

    if (connection_aborted()) break;
    usleep(500000); // medio segundo para no sobrecargar CPU
    // sleep(1);
}
