<?php

namespace Ignacio\ChatSsr\Domain\Chat;

use Ignacio\ChatSsr\Domain\User\User;

interface ChatRepository
{
    public function getAllMessages();
    public function getNewMessages($lastCount);
    public function getUserMessages($user);
    public function getTotalMessages(): int;
    public function saveMessage(Message $message);

    public function getActiveUsers():array;
    public function addActiveUser(User $user);
    public function removeActiveUser(User $user);
    public function getTotalActiveUsers(): mixed;
}