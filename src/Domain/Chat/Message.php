<?php

namespace Ignacio\ChatSsr\Domain\Chat;

class Message
{
    public function __construct(
        private int $id,
        private string $user,
        private string $message,
        private string $date,
        private string $target = 'ALL'
    )
    {
        $this->id = $id;
        $this->user = $user;
        $this->message = $message;
        $this->date = $date;
        $this->target = $target;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public static function fromArray(array $data): Message
    {
        $id = $data['id'] ?? 0 ;
        $user = $data['usuario'] ?? 'anonimo';
        $message = $data['texto'] ?? 'Empty';
        $date = $data['fecha'] ?? time();
        $target = $data['target'] ?? 'ALL';
        return new self(
            (int) $id,
            $user,
            $message,
            $date,
            $target
        );
    }
}