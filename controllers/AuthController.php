<?php

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function signup($username, $password, $email, $role) {
        if ($this->userModel->signup($username, $password, $email, $role)) {
            return true; // Signup successful
        } else {
            return false; // Signup failed (e.g., username already exists)
        }
    }

    public function login($username, $password) {
        $user = $this->userModel->login($username, $password);
        if ($user) {
            // Start session and set user data
            session_start();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true; // Login successful
        } else {
            return false; // Login failed
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: login.php'); // Redirect to login page after logout
        exit();
    }

    public function isLoggedIn() {
        session_start();
        return isset($_SESSION['user_id']);
    }

    public function getLoggedInUser() {
        if ($this->isLoggedIn()) {
            return $this->userModel->findById($_SESSION['user_id']);
        }
        return null;
    }

    public function checkRole($allowedRoles) {
        if (!$this->isLoggedIn()) {
            header('Location: login.php'); // Redirect if not logged in
            exit();
        }
        if (!in_array($_SESSION['role'], $allowedRoles)) {
            echo "Unauthorized access."; // Or redirect to an error page
            exit();
        }
    }
}

?>