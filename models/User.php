<?php

require_once __DIR__ . '/../config/Database.php';

class User {
    private $conn;

    public function __construct() {
        $database = Database::getInstance(); // Use getInstance() here
        $this->conn = $database->getConnection();
    }

    // ... (rest of your User class code remains the same) ...

    public function signup($username, $password, $email, $role) {
        // Vérifier si le nom d'utilisateur existe déjà
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            return false; // Username already exists
        }

        // Hasher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $email, $role]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Nouvelle méthode pour inscrire et récupérer l'ID
    public function signupAndGetId($username, $password, $email, $role) {
        // Vérifier si le nom d'utilisateur existe déjà
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            return false; // Username already exists
        }

        // Hasher le mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashed_password, $email, $role]);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    // Récupérer tous les utilisateurs ayant le rôle "Enfant"
    public function getAllChildren() {
        try {
            $stmt = $this->conn->prepare("SELECT user_id, username FROM users WHERE role = 'Enfant'");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function login($username, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);

            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $user['password'])) {
                    return $user;
                }
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function findById($user_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = ?");
            $stmt->execute([$user_id]);

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}