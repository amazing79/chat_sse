<?php

namespace Ignacio\ChatSsr\Chat;

use Predis\Client;

class Chat
{
    const CHAT_KEY = 'chat';
    private Client $chatStore;
    public function __construct(Client $client)
    {
        $this->chatStore = $client;
    }

    public function getAllMessagesEvent(): string
    {
        $messages = $this->chatStore->lrange(self::CHAT_KEY, 0, -1);
        $output = '\n';
        foreach ($messages as $message) {
            $output .= "event: message\n";
            $output .= "data: " . json_encode(['text' => $message]) . "\n\n";
        }
        return $output;
    }

    public function getLastsMessagesEvents($lastCount): string
    {
        // Hay mensajes nuevos → obtener solo los nuevos
        $lastMessages = $this->chatStore->lRange(self::CHAT_KEY, $lastCount, $this->getTotalMessages());
        $output = '\n';
        foreach ($lastMessages as $message) {
            $output .= "event: message\n";
            $output .= "data: " . json_encode(['text' => $message]) . "\n\n";
        }

        return $output;
    }

    public function sendGlobalMessage(array $message): void
    {
        $this->chatStore->rpush(self::CHAT_KEY, $message);
    }

    public function getTotalMessages(): int
    {
        return $this->chatStore->llen(self::CHAT_KEY);
    }
}