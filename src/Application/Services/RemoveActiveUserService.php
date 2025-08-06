<?php

namespace Ignacio\ChatSsr\Application\Services;

use Ignacio\ChatSsr\Domain\Chat\ChatRepository;
use Ignacio\ChatSsr\Domain\User\User;

class RemoveActiveUserService
{
    public function __construct(
        private ChatRepository $chatRepository)
    {

    }

    public function handle(User $user): void
    {
        try {
            $this->chatRepository->removeActiveUser($user);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}