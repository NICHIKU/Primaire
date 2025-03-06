<?php

require_once __DIR__ . '/../../controllers/AuthController.php';
$authController = new AuthController();
$loggedInUser = $authController->getLoggedInUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elementary School System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="../../index.php">Tests Primaire</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <?php if ($loggedInUser): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="../../index.php">Accueil</a>
                    </li>
                    <?php if ($loggedInUser['role'] == 'parent'): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="stats.php">Stats Enfant</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <?php if ($loggedInUser): ?>
                    <li class="nav-item">
                        <span class="nav-link">Bienvenue, <?php echo htmlspecialchars($loggedInUser['username']); ?> (<?php echo htmlspecialchars(ucfirst($loggedInUser['role'])); ?>)</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../logout.php">Déconnecter</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="views/auth/login.php">Connecter</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="views/auth/signup.php">Inscrire</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">