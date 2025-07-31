<?php

namespace Ignacio\ChatSsr\Domain\User;

interface UserRepository
{
    public function findByEmail(string $email): ?User;
    public function findById(int $id): ?User;
    public function save(User $user): int;
    public function findUserByCredentials(array $credentials): ?User;
    public function resetPassword(array $newCredentials): void;
    public function requestChangePassword(string $email): string;

}