<?php
require_once __DIR__ . '/../controllers/AuthController.php';
$authController = new AuthController();

// Vérifier que l'utilisateur est connecté et est un parent
$authController->checkRole(['Parent']);
$loggedInUser = $authController->getLoggedInUser();

// Récupérer la liste des enfants du parent connecté
$children = $authController->getChildrenForParent($loggedInUser['user_id']);

include 'layout/header.php';
?>

<div class="container">
    <h2>Statistiques des enfants</h2>
    
    <?php if (empty($children)): ?>
        <div class="alert alert-info">
            Vous n'avez pas encore associé d'enfants à votre compte. 
            <a href="profile.php">Gérer mes enfants</a>
        </div>
    <?php else: ?>
        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        Mes enfants
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($children as $child): ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <?php echo htmlspecialchars($child['username']); ?>
                                    <a href="child_stats.php?id=<?php echo $child['user_id']; ?>" class="btn btn-sm btn-primary">Voir détails</a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aperçu des performances générales -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Aperçu des résultats récents
                    </div>
                    <div class="card-body">
                        <p>Cette section affichera un graphique des performances récentes.</p>
                        <!-- Ici nous pourrions ajouter un graphique avec Chart.js -->
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Exercices récents
                    </div>
                    <div class="card-body">
                        <p>Cette section affichera les derniers exercices effectués.</p>
                        <!-- Liste des derniers exercices complétés -->
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'layout/footer.php'; ?>