<?php
require_once __DIR__ . '/controllers/AuthController.php';
$authController = new AuthController();
$authController->logout(); // This will redirect to login.php
?>