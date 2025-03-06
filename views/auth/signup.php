<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
$authController = new AuthController();

if ($authController->isLoggedIn()) {
    header('Location: ../../index.php'); // Redirect if already logged in
    exit();
}

$signup_error = '';
$signup_success = false;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    if ($authController->signup($username, $password, $email, $role)) {
        $signup_success = true;
    } else {
        $signup_error = "Signup failed. Username may be taken or input invalid.";
    }
}
?>

<?php include '../layout/header.php'; ?>

<div class="container">
    <h2>Signup</h2>
    <?php if ($signup_error): ?>
        <div class="alert alert-danger"><?php echo $signup_error; ?></div>
    <?php endif; ?>
    <?php if ($signup_success): ?>
        <div class="alert alert-success">Signup successful! <a href="login.php">Login now</a>.</div>
    <?php endif; ?>
    <form method="post" action="signup.php">
        <div class="mb-3">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email">
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role:</label>
            <select class="form-select" id="role" name="role" required>
                <option value="child">Child</option>
                <option value="teacher">Teacher</option>
                <option value="parent">Parent</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Sign Up</button>
        <p class="mt-3">Already have an account? <a href="login.php">Login</a></p>
    </form>
</div>

<?php include '../layout/footer.php'; ?>