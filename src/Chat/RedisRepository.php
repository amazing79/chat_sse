<?php

namespace Ignacio\ChatSsr\Chat;
use Predis\Client;
class RedisRepository
{
    private Client $client;

    public function __construct(Client $client = null)
    {
        $this->client = $client ?? new Client([
            'scheme' => 'tcp',
            'host' => '127.0.0.1',
            'port' => 6379,
            'database' => 0,
        ]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}