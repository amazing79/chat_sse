<?php

namespace Ignacio\ChatSsr\Infraestructure\Common;

use PDO;
use PDOException;

class DB{

    private ?PDO $connexion = null;

    public function __construct()
    {
        try {
            $host = $_ENV["DB_HOST"];
            $user = $_ENV["DB_USER"];
            $pass = $_ENV["DB_PASS"];
            $dbname = $_ENV["DB_NAME"];
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
