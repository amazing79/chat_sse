<?php

namespace Ignacio\ChatSsr\Infraestructure\User;

use Ignacio\ChatSsr\Domain\User\User;
use Ignacio\ChatSsr\Domain\User\UserRepository;
use Ignacio\ChatSsr\Infraestructure\Common\DB;
use PDO;

class UserMysqlRepository implements UserRepository
{
    public function __construct(
        private DB $db
    )
    {
    }
    public function findByEmail(string $email): ?User
    {
        $pdo = $this->db->getConnexion();
        $sql = "SELECT id, nombre, apellido, email FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user) {
            return User::createUserFromArray($user);
        }
        return null;
    }

    public function findById(int $id): ?User
    {
        $pdo = $this->db->getConnexion();
        $sql = "SELECT id, nombre, apellido, email FROM usuarios WHERE id = :idUsuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idUsuario', $id);
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user) {
            return User::createUserFromArray($user);
        }
        return null;
    }

    public function save(User $user): int
    {
        $pdo = $this->db->getConnexion();
        $sql = "INSERT INTO usuarios (nombre, apellido, email, password) VALUES (:nombre, :apellido, :email, :password)";

        $hash = password_hash($user->getPassword(), PASSWORD_DEFAULT);
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nombre', $user->getName());
        $stmt->bindValue(':apellido', $user->getLastName());
        $stmt->bindValue(':email', $user->getEmail());
        $stmt->bindValue(':password', $hash);
        $stmt->execute();
        return $pdo->lastInsertId();
    }

    public function findUserByCredentials(array $credentials): ?User
    {
        $pdo = $this->db->getConnexion();
        $sql = "SELECT id, nombre, apellido, email, password FROM usuarios WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $credentials['email']);
        $stmt->execute();
        $user = $stmt->fetch();
        if ($user && password_verify($credentials['password'], $user['password'])) {
            unset($user['password']);
            return User::createUserFromArray($user);
        }
        return null;
    }
}