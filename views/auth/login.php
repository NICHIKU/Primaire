<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
$authController = new AuthController();

if ($authController->isLoggedIn()) {
    header('Location: ../../index.php'); // Redirect if already logged in
    exit();
}

$login_error = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($authController->login($username, $password)) {
        header('Location: ../../index.php'); // Redirect to homepage after login
        exit();
    } else {
        $login_error = "Invalid username or password.";
    }
}
?>

<?php include '../layout/header.php'; ?>

<div class="container">
    <h2>Connexion</h2>
    <?php if ($login_error): ?>
        <div class="alert alert-danger"><?php echo $login_error; ?></div>
    <?php endif; ?>
    <form method="post" action="login.php">
        <div class="mb-3">
            <label for="username" class="form-label">Nom d'utilisateur :</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe :</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Connexion</button>
        <p class="mt-3">Vous n'avez pas de compte ? <a href="signup.php">Inscrivez vous</a></p>
    </form>
</div>

<?php include '../layout/footer.php'; ?>