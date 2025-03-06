<?php

require_once __DIR__ . '/../database.php';

class User {
    private $conn;

    public function __construct() {
        $this->conn = get_database_connection();
    }

    public function signup($username, $password, $email, $role) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashed_password, $email, $role);
        if ($stmt->execute()) {
            return true;
        } else {
            return false;
        }
        $stmt->close();
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT user_id, username, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user; // Return user data on successful login
            }
        }
        return false; // Login failed
        $stmt->close();
    }

    public function findById($user_id) {
        $stmt = $this->conn->prepare("SELECT user_id, username, email, role FROM users WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        return null;
        $stmt->close();
    }

    public function isParentOfChild($parent_id, $child_id) {
        $stmt = $this->conn->prepare("SELECT relative_id FROM relatives WHERE parent_id = ? AND child_id = ?");
        $stmt->bind_param("ii", $parent_id, $child_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
        $stmt->close();
    }

    public function getChildStats($child_id) {
        $stmt = $this->conn->prepare("SELECT s.*, e.title as exercise_title
                                        FROM stats s
                                        JOIN exercises e ON s.exercise_id = e.exercise_id
                                        WHERE s.user_id = ?");
        $stmt->bind_param("i", $child_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }

    public function getConnection() { //For direct database queries in other models if needed.
        return $this->conn;
    }
}

?>