<?php

class Database {
    private $host = "localhost:8889";
    private $db_name = "primaire";
    private $username = "root";
    private $password = "root";
    private $conn;

    // Instance unique de la base de données
    private static $instance;

    // Constructeur privé pour empêcher l'instanciation directe
    private function __construct() {
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
    }

    // Méthode statique pour obtenir l'instance unique de la base de données
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}