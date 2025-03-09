<?php
require_once __DIR__ . '/../controllers/AuthController.php';
$authController = new AuthController();

// S'assurer que l'utilisateur est connecté et est un professeur
$authController->checkRole(['Professeur(e)']);
$loggedInUser = $authController->getLoggedInUser();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $new_limit = filter_input(INPUT_POST, 'addition_limit', FILTER_VALIDATE_INT, array("options" => array("min_range"=>1)));
    if ($new_limit !== false && $new_limit > 0) {
        // Store the limit in session
        $_SESSION['addition_max_limit'] = $new_limit;
        $success_message = "Limite maximale pour les additions mise à jour à " . $new_limit;
    } else {
        $error_message = "Veuillez entrer une limite valide (nombre entier positif).";
    }
}

// Récupérer la limite actuelle depuis la session ou utiliser une valeur par défaut
$current_limit = $_SESSION['addition_max_limit'] ?? 1000; // Default to 1000 if not set

include '../views/layout/header.php';
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Paramètres des Additions</title>
</head>
<body style="background-color:grey;">
    <center>
        <h1>Paramètres des Additions</h1>

        <?php if (isset($success_message)): ?>
            <div style="color:green; margin-bottom: 10px;"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($error_message)): ?>
            <div style="color:red; margin-bottom: 10px;"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="addition_settings.php" method="post">
            <label for="addition_limit">Limite maximale pour les additions :</label>
            <input type="number" id="addition_limit" name="addition_limit" value="<?php echo htmlspecialchars($current_limit); ?>" min="1" required>
            <button type="submit">Enregistrer la limite</button>
        </form>
        <br />
        <a href="../index.php">Retour à l'accueil</a>
    </center>
</body>
</html>

<?php include '../views/layout/footer.php'; ?>