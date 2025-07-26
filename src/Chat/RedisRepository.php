<?php

namespace Ignacio\ChatSsr\Chat;
use Predis\Client;
class RedisRepository implements ChatRepository
{
    private Client $client;
    const CHAT_KEY  = 'chat_key';

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ]);
    }

    public function getAllMessages(): array
    {
        return $this->client->lrange(self::CHAT_KEY, 0, -1);
    }

    public function getNewMessages($lastCount): array
    {
        return $this->client->lRange(self::CHAT_KEY, $lastCount, $this->getTotalMessages());
    }

    public function getUserMessages($user)
    {
        $method = __METHOD__;
        throw new \Exception("Method {$method} not implemented");
    }

    public function getTotalMessages(): int
    {
        return $this->client->llen(self::CHAT_KEY);
    }

    public function saveMessage($message): void
    {
        $msg[] = "{$message->getUser()} says: {$message->getMessage()}";
        $this->client->rpush(self::CHAT_KEY, $msg);
    }
}