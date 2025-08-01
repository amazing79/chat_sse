<?php

namespace Ignacio\ChatSsr\Infraestructure\Chat;

use Ignacio\ChatSsr\Domain\Chat\ChatRepository;
use Ignacio\ChatSsr\Domain\Chat\Message;
use Predis\Client;
class RedisRepository implements ChatRepository
{
    private Client $client;
    private mixed $chat_key;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client([
            'scheme' => $_ENV['REDIS_CHAT_SCHEME'] ?? 'tcp',
            'host' => $_ENV['REDIS_CHAT_HOST'] ?? '127.0.0.1',
            'port' => $_ENV['REDIS_CHAT_PORT'] ?? 6379,
            'database' => $_ENV['REDIS_CHAT_DB'] ?? 0,
        ]);
        $this->chat_key = $_ENV['REDIS_CHAT_KEY'] ?? 'chat_token';
    }

    public function getAllMessages(): array
    {
        return $this->client->lrange($this->chat_key, 0, -1);
    }

    public function getNewMessages($lastCount): array
    {
        return $this->client->lRange($this->chat_key, $lastCount, $this->getTotalMessages());
    }

    public function getUserMessages($user)
    {
        $method = __METHOD__;
        throw new \Exception("Method {$method} not implemented");
    }

    public function getTotalMessages(): int
    {
        return $this->client->llen($this->chat_key);
    }

    public function saveMessage(Message $message): void
    {
        $msg[] = "{$message->getUser()} says: {$message->getMessage()}";
        $this->client->rpush($this->chat_key, $msg);
    }
}