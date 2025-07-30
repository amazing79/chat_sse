<?php

namespace Ignacio\ChatSsr\Infraestructure\Common;

use PDO;
use PDOException;

class DB{

    private ?PDO $connexion = null;

    public function __construct(
        $host = "localhost",
        $user = "root",
        $pass = "gueraike",
        $dbname = "chatdb")
    {
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }
        $this->connexion = $pdo;
    }

    public function getConnexion(): ?PDO
    {
        return $this->connexion;
    }
}
