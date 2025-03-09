<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Relative.php';
require_once __DIR__ . '/../models/QuestionResult.php';

class AuthController {
    private $userModel;
    private $relativeModel;
    private $questionResultModel;

    private function startSessionIfNeeded() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function __construct() {
        $this->userModel = new User();
        $this->relativeModel = new Relative();
        $this->questionResultModel = new QuestionResult();
    }

    public function signup($username, $password, $email, $role) {
        if ($this->userModel->signup($username, $password, $email, $role)) {
            return true; // Signup successful
        } else {
            return false; // Signup failed (e.g., username already exists)
        }
    }
    
    public function checkAuthentication() {
        if (!$this->isLoggedIn()) {
            header('Location: login.php'); // Redirect if not logged in
            exit();
        }
    }

    // Méthode pour inscrire un parent et retourner son ID
    public function signupParent($username, $password, $email, $role) {
        return $this->userModel->signupAndGetId($username, $password, $email, $role);
    }

    // Méthode pour lier un parent à un enfant
    public function linkParentToChild($parent_id, $child_id) {
        return $this->relativeModel->createRelationship($parent_id, $child_id);
    }

    public function getAllChildren() {
        // Correction : utiliser le userModel au lieu d'accéder directement à la base de données
        return $this->userModel->getAllChildren();
    }

    // Méthode pour récupérer la liste des enfants
    public function getChildrenList() {
        return $this->userModel->getAllChildren();
    }

    // Méthode pour récupérer les enfants d'un parent
    public function getChildrenForParent($parent_id) {
        return $this->relativeModel->getChildrenByParentId($parent_id);
    }

    // Méthode pour obtenir les résultats récents d'un enfant
    public function getChildRecentResults($child_id, $limit = 10) {
        return $this->questionResultModel->getRecentResultsByChildId($child_id, $limit);
    }

    // Méthode pour obtenir les statistiques d'un enfant
    public function getChildStats($child_id) {
        return $this->questionResultModel->getStatsByChildId($child_id);
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