<?php

namespace Ignacio\ChatSsr\Chat;

class MysqlRepository implements ChatRepository
{
    private DB $db;

    public function __construct()
    {
        $this->db = new DB();
    }
    public function getAllMessages(): array
    {
        $pdo = $this->db->getConnexion();
        $sql = 'SELECT id, usuario, texto, fecha FROM mensajes ORDER BY id ASC';
        $stmt = $pdo->query($sql);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if($data) {
            $messages = [];
            foreach ($data as $message) {
                $messages[] = Message::fromArray($message);
            }
            return $messages;
        }
        return [];
    }

    public function getNewMessages($lastCount): array
    {
        $pdo = $this->db->getConnexion();
        $sql = 'SELECT id, usuario, texto, fecha FROM mensajes WHERE id > :id ORDER BY id ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $lastCount, \PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($data) {
            $messages = [];
            foreach ($data as $message) {
                $messages[] = Message::fromArray($message);
            }
            return $messages;
        }
        return [];
    }

    public function getUserMessages($user)
    {
        $method = __METHOD__;
        throw new \Exception("Method {$method} not implemented");
    }

    public function getTotalMessages(): int
    {
        $pdo = $this->db->getConnexion();
        $sql = 'SELECT MAX(id) FROM mensajes;';
        $stmt = $pdo->query($sql);
        return $stmt->fetchColumn() ?? 0;
    }

    public function saveMessage($message): void
    {
        $user = $message->getUser() ?? 'anonimo';
        $messsage = $message->getMessage() ?? 'Mensaje Vacio';

        $sql = 'INSERT INTO mensajes (usuario, texto) VALUES (:usuario, :texto)';

        $pdo =  $this->db->getConnexion();
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':usuario', $user);
        $stmt->bindValue(':texto',$messsage);
        $stmt->execute();
    }
}