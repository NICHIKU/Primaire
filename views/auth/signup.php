<?php
require_once __DIR__ . '/../../controllers/AuthController.php';
$authController = new AuthController();

if ($authController->isLoggedIn()) {
    header('Location: ../../index.php'); // Redirect if already logged in
    exit();
}

$signup_error = '';
$signup_success = false;
$show_child_selection = false;
$selected_role = '';
// Étape 1: Inscription initiale
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['children_count']) && !isset($_POST['selected_children'])) {
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : '';
    
    if (empty($username) || empty($password) || empty($role)) {
        $signup_error = "Tous les champs obligatoires doivent être remplis.";
    } else if ($role === 'Parent') {
        // Si le rôle est parent, on stocke temporairement les données et affiche le formulaire enfants
        $_SESSION['temp_parent'] = [
            'username' => $username,
            'password' => $password,
            'email' => $email,
            'role' => $role
        ];
        $show_child_selection = true;
        $selected_role = 'Parent';
    } else {
        // Pour les autres rôles, on procède à l'inscription normale
        if ($authController->signup($username, $password, $email, $role)) {
            $signup_success = true;
        } else {
            $signup_error = "L'inscription a échoué. Le nom d'utilisateur existe peut-être déjà ou les informations sont invalides.";
        }
    }
}

// Étape 2: Traitement du nombre d'enfants
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['children_count'])) {
    $children_count = (int)$_POST['children_count'];
    $_SESSION['temp_parent']['children_count'] = $children_count;
    $show_child_selection = true;
    $selected_role = 'Parent';
}
// Étape 3: Finalisation de l'inscription parent avec liens aux enfants
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['selected_children'])) {
    // Vérifier que les enfants sont sélectionnés et que le nombre correspond
    if (!is_array($_POST['selected_children']) || count($_POST['selected_children']) == 0 || 
        (isset($_SESSION['temp_parent']['children_count']) && count($_POST['selected_children']) != $_SESSION['temp_parent']['children_count'])) {
        $signup_error = "Veuillez sélectionner le nombre correct d'enfants.";
        $show_child_selection = true;
        $selected_role = 'Parent';
    } else {
        // Récupérer les données temporaires du parent
        $parent_data = $_SESSION['temp_parent'];
        
        // Créer le compte parent
        $parent_id = $authController->signupParent(
            $parent_data['username'],
            $parent_data['password'],
            $parent_data['email'],
            $parent_data['role']
        );
        
        if ($parent_id) {
            // Associer les enfants sélectionnés au parent
            $selected_children = $_POST['selected_children'];
            foreach ($selected_children as $child_id) {
                $authController->linkParentToChild($parent_id, $child_id);
            }
            $signup_success = true;
            // Nettoyer les données temporaires
            unset($_SESSION['temp_parent']);
        } else {
            $signup_error = "L'inscription du parent a échoué.";
        }
    }
}

// Récupérer la liste des enfants pour l'étape de sélection
$children_list = [];
if ($show_child_selection && isset($_SESSION['temp_parent']['children_count'])) {
    $children_list = $authController->getChildrenList();
}
?>

<?php include '../layout/header.php'; ?>

<div class="container">
    <h2>Inscription</h2>
    <?php if ($signup_error): ?>
        <div class="alert alert-danger"><?php echo $signup_error; ?></div>
    <?php endif; ?>
    
    <?php if ($signup_success): ?>
        <div class="alert alert-success">Inscription réussie! <a href="login.php">Connectez vous</a>.</div>
    <?php elseif ($show_child_selection && !isset($_SESSION['temp_parent']['children_count'])): ?>
        <!-- Étape 2: Demander le nombre d'enfants -->
        <h3>Sélection du nombre d'enfants</h3>
        <form method="post" action="signup.php">
            <div class="mb-3">
                <label for="children_count" class="form-label">Combien d'enfants avez-vous ?</label>
                <input type="number" class="form-control" id="children_count" name="children_count" min="1" max="10" required>
            </div>
            <button type="submit" class="btn btn-primary">Continuer</button>
        </form>
    <?php elseif ($show_child_selection && isset($_SESSION['temp_parent']['children_count'])): ?>
        <!-- Étape 3: Sélection des enfants -->
        <h3>Sélection de vos enfants</h3>
        <p>Veuillez sélectionner <?php echo $_SESSION['temp_parent']['children_count']; ?> enfant(s) dans la liste:</p>
        
        <form method="post" action="signup.php">
            <div class="mb-3">
                <?php if (count($children_list) > 0): ?>
                    <div class="list-group">
                        <?php foreach ($children_list as $child): ?>
                            <label class="list-group-item">
                                <input class="form-check-input me-1" type="checkbox" name="selected_children[]" value="<?php echo $child['user_id']; ?>">
                                <?php echo htmlspecialchars($child['username']); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-text">
                        Si votre enfant n'est pas dans la liste, il doit d'abord s'inscrire avec un compte "Enfant".
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Aucun compte enfant n'est disponible actuellement. Vos enfants doivent d'abord s'inscrire avec un compte "Enfant".
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary" <?php echo count($children_list) > 0 ? '' : 'disabled'; ?>>Finaliser l'inscription</button>
        </form>
    <?php else: ?>
        <!-- Étape 1: Formulaire initial -->
        <form method="post" action="signup.php">
            <div class="mb-3">
                <label for="username" class="form-label">Nom d'utilisateur:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email">
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">Role:</label>
                <select class="form-select" id="role" name="role" required>
                    <option value="Enfant">Enfant</option>
                    <option value="Professeur(e)">Professeur(e)</option>
                    <option value="Parent">Parent</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Continuer</button>
            <p class="mt-3">Vous avez déjà un compte ? <a href="login.php">Connexion</a></p>
        </form>
    <?php endif; ?>
</div>

<?php include '../layout/footer.php'; ?>