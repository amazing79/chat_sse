<?php

namespace Ignacio\ChatSsr\Infraestructure\User;

use Ignacio\ChatSsr\Domain\User\User;
use Ignacio\ChatSsr\Domain\User\UserRepository;
use Ignacio\ChatSsr\Infraestructure\Common\DB;

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

    public function resetPassword($newCredentials): void
    {
        $pdo = $this->db->getConnexion();
        $sql = "SELECT * FROM password_resets WHERE token = :token AND expires_at > NOW()";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':token', $newCredentials['token']);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
            //1ro actualizo el password
            $hash = password_hash($newCredentials['password'], PASSWORD_DEFAULT);
            $sql = "UPDATE usuarios SET password = :hash WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':hash', $hash);
            $stmt->bindValue(':email', $row['email']);
            $stmt->execute();
            //2do limpio el registro de la solicitud de passwords
            $this->deleteRequestResetPassword($row['email']);
        }
    }

    public function requestChangePassword(string $email): string
    {
        //1ro Borro cualquier solicitud anterior
        $this->deleteRequestResetPassword($email);
        //2do Registro la solicitud
        $pdo = $this->db->getConnexion();
        $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)";
        $stmt = $pdo->prepare($sql);
        $token = bin2hex(random_bytes(32));
        $expiresAt = date("Y-m-d H:i:s", time() + 3600);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':token', $token);
        $stmt->bindValue(':expires_at',$expiresAt);
        $stmt->execute();
        return $token;
    }

    private function deleteRequestResetPassword(string $email): void
    {
        $pdo = $this->db->getConnexion();
        $sql = "DELETE FROM password_resets WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
    }
}