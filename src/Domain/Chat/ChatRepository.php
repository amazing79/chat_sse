<?php

namespace Ignacio\ChatSsr\Domain\Chat;

interface ChatRepository
{
    public function getAllMessages();
    public function getNewMessages($lastCount);
    public function getUserMessages($user);
    public function getTotalMessages(): int;
    public function saveMessage(Message $message);
}