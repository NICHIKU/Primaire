<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/Stats.php';
$authController = new AuthController();
$statsModel = new Stats();

// Vérifier que l'utilisateur est connecté
$authController->checkAuthentication();
$loggedInUser = $authController->getLoggedInUser();
$userRole = $loggedInUser['role'];

// Récupérer la liste des enfants en fonction du rôle de l'utilisateur
if ($userRole == 'Parent') {
    // Pour les parents, récupérer uniquement leurs enfants
    $children = $authController->getChildrenForParent($loggedInUser['user_id']);
} elseif ($userRole == 'Professeur(e)') {
    // Pour les professeurs, récupérer tous les enfants
    $children = $authController->getAllChildren(); // Assume you have this function in AuthController
} else {
    // Si ce n'est ni un parent ni un professeur (ne devrait pas arriver si checkRole est bien configuré), rediriger ou gérer l'erreur.
    header('Location: index.php'); // Rediriger vers l'accueil par exemple
    exit();
}


// Récupérer les statistiques récentes combinées de tous les enfants
$recent_stats_all_children = [];
if (!empty($children)) {
    foreach ($children as $child) {
        $recent_child_stats = $statsModel->getRecentStatsByUserId($child['user_id'], 1); // Récupérer la statistique la plus récente pour chaque enfant
        if (!empty($recent_child_stats)) {
            $recent_stats_all_children = array_merge($recent_stats_all_children, $recent_child_stats);
        }
    }
    // Trier les statistiques combinées par date décroissante
    usort($recent_stats_all_children, function($a, $b) {
        return strtotime($b['completion_time']) - strtotime($a['completion_time']);
    });
}


include 'layout/header.php';
?>

<div class="container">
    <h2>Statistiques des enfants</h2>

    <?php if (empty($children)): ?>
        <div class="alert alert-info">
            <?php if ($userRole == 'Parent'): ?>
                Vous n'avez pas encore associé d'enfants à votre compte.
                <a href="profile.php">Gérer mes enfants</a>
            <?php elseif ($userRole == 'Professeur(e)'): ?>
                Aucun enfant inscrit pour le moment.
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="row mb-4">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        <?php echo ($userRole == 'Parent') ? 'Mes enfants' : 'Tous les enfants'; ?>
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

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Aperçu des résultats récents
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_stats_all_children)): ?>
                            <?php
                            $total_recent_score = 0;
                            foreach ($recent_stats_all_children as $stat) {
                                $total_recent_score += $stat['is_correct'] * 10; // Assuming score is out of 10 for a correct answer
                            }
                            $average_recent_score = count($recent_stats_all_children) > 0 ? $total_recent_score / count($recent_stats_all_children) : 0;
                            ?>
                            <p>Score moyen des dernières tentatives de tous les enfants : <strong><?php echo number_format($average_recent_score, 1); ?> / 10</strong></p>
                        <?php else: ?>
                            <p>Aucune tentative récente enregistrée pour les enfants.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        Exercices récents
                    </div>
                    <div class="card-body">
                        <?php if (!empty($recent_stats_all_children)): ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Enfant</th>
                                            <th>Date</th>
                                            <th>Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_stats_all_children as $stat): ?>
                                            <?php
                                                $childUsername = '';
                                                foreach ($children as $child) {
                                                    if ($child['user_id'] == $stat['user_id']) {
                                                        $childUsername = htmlspecialchars($child['username']);
                                                        break;
                                                    }
                                                }
                                            ?>
                                            <tr>
                                                <td><?php echo $childUsername; ?></td>
                                                <td><?php echo date('d/m/Y H:i', strtotime($stat['completion_time'])); ?></td>
                                                <td><?php echo $stat['is_correct'] ? '10 / 10' : '0 / 10'; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <p>Aucun exercice récent à afficher.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include 'layout/footer.php'; ?>