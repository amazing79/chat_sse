<?php

namespace Ignacio\ChatSsr\Domain\User;
class User
{
    public function __construct(
        private int $userId,
        private string $name,
        private string $lastName,
        private string $password,
        private string $email,
    )
    {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public static function createUserFromArray(array $data): User
    {
        $id = $data['id'] ?? 0;
        $name = $data['nombre'] ?? '';
        $lastName = $data['apellido'] ?? '';
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        return new User($id, $name, $lastName, $password, $email);
    }
}