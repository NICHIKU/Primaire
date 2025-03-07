<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/Stats.php';
$authController = new AuthController();
$statsModel = new Stats();

// Vérifier que l'utilisateur est connecté et est un parent
$authController->checkRole(['Parent']);
$loggedInUser = $authController->getLoggedInUser();

// Vérifier que l'ID de l'enfant est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: stats.php');
    exit();
}

$child_id = $_GET['id'];

// Vérifier que l'enfant appartient au parent connecté
$children = $authController->getChildrenForParent($loggedInUser['user_id']);
$child_belongs_to_parent = false;
$child_info = null;

foreach ($children as $child) {
    if ($child['user_id'] == $child_id) {
        $child_belongs_to_parent = true;
        $child_info = $child;
        break;
    }
}

if (!$child_belongs_to_parent) {
    header('Location: stats.php');
    exit();
}

// Récupérer les statistiques de l'enfant
$child_stats = $statsModel->getStatsByUserId($child_id);

include 'layout/header.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Statistiques de <?php echo htmlspecialchars($child_info['username']); ?></h2>
        <a href="stats.php" class="btn btn-secondary">Retour</a>
    </div>
    
    <!-- Résumé des statistiques -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Score moyen</h5>
                    <p class="card-text display-4">
                        <?php 
                        $avg_score = 0;
                        if (!empty($child_stats)) {
                            $scores = array_column($child_stats, 'score');
                            $avg_score = array_sum($scores) / count($scores);
                        }
                        echo number_format($avg_score, 1);
                        ?> / 10
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Exercices complétés</h5>
                    <p class="card-text display-4"><?php echo count($child_stats); ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Dernière activité</h5>
                    <p class="card-text">
                        <?php 
                        if (!empty($child_stats)) {
                            $latest = max(array_column($child_stats, 'date_taken'));
                            echo date('d/m/Y H:i', strtotime($latest));
                        } else {
                            echo "Aucune activité";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Historique détaillé -->
    <div class="card">
        <div class="card-header">
            Historique des exercices
        </div>
        <div class="card-body">
            <?php if (empty($child_stats)): ?>
                <div class="alert alert-info">Aucun exercice complété pour le moment.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Exercice</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($child_stats as $stat): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($stat['date_taken'])); ?></td>
                                    <td><?php echo htmlspecialchars($stat['title']); ?></td>
                                    <td><?php echo $stat['score']; ?> / 10</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'layout/footer.php'; ?>