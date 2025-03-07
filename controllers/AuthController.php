<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Relative.php';

class AuthController {
    private $userModel;
    private $relativeModel;

    private function startSessionIfNeeded() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function __construct() {
        $this->userModel = new User();
        $this->relativeModel = new Relative();
    }

    public function signup($username, $password, $email, $role) {
        if ($this->userModel->signup($username, $password, $email, $role)) {
            return true; // Signup successful
        } else {
            return false; // Signup failed (e.g., username already exists)
        }
    }

    // Nouvelle méthode pour inscrire un parent et retourner son ID
    public function signupParent($username, $password, $email, $role) {
        return $this->userModel->signupAndGetId($username, $password, $email, $role);
    }

    // Nouvelle méthode pour lier un parent à un enfant
    public function linkParentToChild($parent_id, $child_id) {
        return $this->relativeModel->createRelationship($parent_id, $child_id);
    }

    // Nouvelle méthode pour récupérer la liste des enfants
    public function getChildrenList() {
        return $this->userModel->getAllChildren();
    }

    // Nouvelle méthode pour récupérer les enfants d'un parent
    public function getChildrenForParent($parent_id) {
        return $this->relativeModel->getChildrenByParentId($parent_id);
    }

    public function login($username, $password) {
        $user = $this->userModel->login($username, $password);
        if ($user) {
            // Start session and set user data
            $this->startSessionIfNeeded();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            return true; // Login successful
        } else {
            return false; // Login failed
        }
    }

    public function logout() {
        $this->startSessionIfNeeded();
        session_unset();
        session_destroy();
        header('Location: index.php'); // Redirect to login page after logout
        exit();
    }

    public function isLoggedIn() {
        $this->startSessionIfNeeded();
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