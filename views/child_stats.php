<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../models/Stats.php';
$authController = new AuthController();
$statsModel = new Stats();

// Vérifier que l'utilisateur est connecté et est un parent ou un professeur
$authController->checkRole(['Parent', 'Professeur(e)']);
$loggedInUser = $authController->getLoggedInUser();
$userRole = $loggedInUser['role'];

// Vérifier que l'ID de l'enfant est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: stats.php');
    exit();
}

$child_id = $_GET['id'];
$child_info = null;

// Vérification différente selon le rôle (inchangé)
if ($userRole == 'Parent') {
    $children = $authController->getChildrenForParent($loggedInUser['user_id']);
    $child_belongs_to_parent = false;
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
} elseif ($userRole == 'Professeur(e)') {
    $allChildren = $authController->getAllChildren();
    foreach ($allChildren as $child) {
        if ($child['user_id'] == $child_id) {
            $child_info = $child;
            break;
        }
    }
    if (!$child_info) {
        header('Location: stats.php');
        exit();
    }
}

// Types d'opérations pour lesquelles afficher les stats
$operation_types = ['addition', 'subtraction', 'multiplication'];
$operation_types_fr = ['Additions', 'Soustractions', 'Multiplications'];

// Récupérer les statistiques globales (toutes opérations confondues)
$global_stats_all = $statsModel->getGlobalStatsByUserId($child_id);


include 'layout/header.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Statistiques de <?php echo htmlspecialchars($child_info['username']); ?></h2>
        <a href="stats.php" class="btn btn-secondary">Retour</a>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Score moyen (Tous types)</h5>
                    <p class="card-text display-4">
                        <?php
                        $avg_score_all = 0;
                        if ($global_stats_all && $global_stats_all['average_score'] !== null) {
                            $avg_score_all = $global_stats_all['average_score'];
                        }
                        echo number_format($avg_score_all, 1);
                        ?> / 10
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Questions complétées (Tous types)</h5>
                    <p class="card-text display-4"><?php echo $global_stats_all ? $global_stats_all['total_exercises'] : 0; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Dernière activité (Tous types)</h5>
                    <p class="card-text">
                        <?php
                        $child_stats_all = $statsModel->getStatsByUserId($child_id); // Récupérer pour toutes opérations
                        if (!empty($child_stats_all)) {
                            $latest_date_all = $child_stats_all[0]['completion_time'];
                            echo date('d/m/Y H:i', strtotime($latest_date_all));
                        } else {
                            echo "Aucune activité";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <?php foreach ($operation_types as $index => $operation):
        $global_stats_op = $statsModel->getGlobalStatsByUserId($child_id, $operation);
        $child_stats_op = $statsModel->getStatsByUserId($child_id, $operation);
        ?>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Score moyen (<?php echo $operation_types_fr[$index]; ?>)</h5>
                    <p class="card-text display-4">
                        <?php
                        $avg_score_op = 0;
                        if ($global_stats_op && $global_stats_op['average_score'] !== null) {
                            $avg_score_op = $global_stats_op['average_score'];
                        }
                        echo number_format($avg_score_op, 1);
                        ?> / 10
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Questions complétées (<?php echo $operation_types_fr[$index]; ?>)</h5>
                    <p class="card-text display-4"><?php echo $global_stats_op ? $global_stats_op['total_exercises'] : 0; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Dernière activité (<?php echo $operation_types_fr[$index]; ?>)</h5>
                    <p class="card-text">
                        <?php
                        if (!empty($child_stats_op)) {
                            $latest_date_op = $child_stats_op[0]['completion_time'];
                            echo date('d/m/Y H:i', strtotime($latest_date_op));
                        } else {
                            echo "Aucune activité";
                        }
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>


    <div class="card">
        <div class="card-header">
            Historique des exercices (Questions) - Tous types
        </div>
        <div class="card-body">
            <?php
            $all_child_stats = $statsModel->getStatsByUserId($child_id); // Récupère toutes les stats sans filtre d'opération
            if (empty($all_child_stats)): ?>
                <div class="alert alert-info">Aucune question complétée pour le moment.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Question N°</th>
                                <th>Opération</th>
                                <th>Réponse de l'enfant</th>
                                <th>Réponse Correcte</th>
                                <th>Correct?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_child_stats as $stat): ?>
                                <tr>
                                    <td><?php echo date('d/m/Y H:i', strtotime($stat['completion_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($stat['question_number']); ?></td>
                                    <td><?php echo htmlspecialchars($stat['operation']); ?></td>
                                    <td><?php echo htmlspecialchars($stat['user_answer']); ?></td>
                                    <td><?php echo htmlspecialchars($stat['correct_answer']); ?></td>
                                    <td><?php echo $stat['is_correct'] ? '<span class="text-success">Oui</span>' : '<span class="text-danger">Non</span>'; ?></td>
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