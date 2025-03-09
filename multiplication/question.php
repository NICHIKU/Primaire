<?php
include '../views/layout/header.php';
include 'utils.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../config/database.php';

$authController = new AuthController();

// S'assurer que l'utilisateur est connecté
if (!$authController->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$loggedInUser = $authController->getLoggedInUser();
$userId = $loggedInUser['user_id'];

$_SESSION['origine'] = "question_all"; // Changed origin to differentiate

// Récupérer le nombre total de questions depuis la session (défini dans index.php)
$num_questions_to_ask = $_SESSION['num_questions_total'];

// Si le nombre de questions n'est pas défini, rediriger vers index.php pour la sélection
if ($num_questions_to_ask == 0) {
    header('Location: index.php');
    exit();
}

// Initialisation des variables de session pour les questions si elles n'existent pas
if (!isset($_SESSION['questions'])) {
    $_SESSION['questions'] = [];
}
if (!isset($_SESSION['correct_answers'])) {
    $_SESSION['correct_answers'] = [];
}
if (!isset($_SESSION['user_answers'])) {
    $_SESSION['user_answers'] = array_fill(1, $num_questions_to_ask, ''); // Initialize user_answers array with empty strings
}
if (!isset($_SESSION['question_number'])) {
    $_SESSION['question_number'] = 0; // Reset question number for single page approach, will be used as index
}
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

if (empty($_SESSION['questions'])) {
    // Récupérer la limite maximale depuis la session ou utiliser une valeur par défaut
    $max_limit = $_SESSION['multiplication_max_limit'] ?? 1000; // Default to 1000 if not set

    for ($i = 0; $i < $num_questions_to_ask; $i++) {
        $nombre1 = rand(100, $max_limit); // Use the limit here, adjusted range for multiplication
        $nombre2 = rand(11, 99); // Adjusted range for multiplication
        $operation = "".$nombre1." x ".$nombre2." = ?";
        $correction = $nombre1 * $nombre2;

        $_SESSION['questions'][] = $operation;
        $_SESSION['correct_answers'][] = $correction;
    }
    $_SESSION['question_number'] = 1; // Start question number from 1 for display
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['mot'])) { // Changed to accept array of 'mot'
    // Traitement des réponses soumises
    $_SESSION['origine'] = "correction_all"; // Changed origin to differentiate correction for all questions
    $_SESSION['score'] = 0; // Reset score before recalculating

    $operationType = 'multiplication'; // Type d'opération fixe
    $difficulty = 'facile'; // Difficulté fixe

    for ($i = 1; $i <= $num_questions_to_ask; $i++) {
        $userAnswer = isset($_POST['mot'][$i]) ? $_POST['mot'][$i] : ''; // Récupérer la réponse de chaque question
        $operation = $_SESSION['questions'][$i-1]; // Get operation for current question
        $correction = $_SESSION['correct_answers'][$i-1]; // Get correct answer

        $_SESSION['user_answers'][$i] = $userAnswer; // Stocker la réponse de l'utilisateur

        // Vérifier si la réponse est correcte
        $isCorrect = (trim($userAnswer) == trim($correction));
        if ($isCorrect) {
            $_SESSION['score']++;
        }

        // Enregistrer le résultat dans la base de données pour chaque question
        $databaseInstance = Database::getInstance();
        $db = $databaseInstance->getConnection();
        $stmt = $db->prepare("INSERT INTO question_results (user_id, question_number, operation, user_answer, correct_answer, is_correct, operation_type, difficulty)
                                    VALUES (:user_id, :question_number, :operation, :user_answer, :correct_answer, :is_correct, :operation_type, :difficulty)");
        $stmt->execute([
            ':user_id' => $userId,
            ':question_number' => $i, // Question number is the index in this loop
            ':operation' => $operation,
            ':user_answer' => $userAnswer,
            ':correct_answer' => $correction,
            ':is_correct' => $isCorrect ? 1 : 0,
            ':operation_type' => $operationType,
            ':difficulty' => $difficulty
        ]);
    }


    log_adresse_ip("logs/log.txt", "correction_multiplication.php - ".$_SESSION['prenom']." - Correction Quiz Complet Multiplication");


    // Quiz terminé, afficher le score final et proposer de recommencer (identique à la version précédente)
    $score_percent = ($_SESSION['score'] / $num_questions_to_ask) * 100;
    $average_score_10 = round(($_SESSION['score'] / $num_questions_to_ask) * 10, 2);

    echo '<!doctype html><html lang="fr"><head><meta charset="utf-8"><title>Résultats du Quiz</title></head><body style="background-color:grey;"><center>';
    echo '<h1>Quiz Terminé !</h1><br />';
    echo '<h2>Votre score final est de : '.$_SESSION['score'].' / '.$num_questions_to_ask.'</h2>';
    echo '<h2>Pourcentage de réussite : '.number_format($score_percent, 2).'%</h2>';
    echo '<h2>Note sur 10 : '.$average_score_10.' / 10</h2><br />';

    echo '<h3>Réponses :</h3>';
    echo '<table border="1">';
    echo '<tr><th>Question</th><th>Votre réponse</th><th>Réponse correcte</th><th>Statut</th></tr>';
    foreach ($_SESSION['questions'] as $index => $question) {
        $question_index_display = $index + 1;
        $user_response = isset($_SESSION['user_answers'][$question_index_display]) ? $_SESSION['user_answers'][$question_index_display] : 'Non répondu';
        $correct_response = $_SESSION['correct_answers'][$index];
        $status = (trim($user_response) == trim($correct_response)) ? '<span style="color:green;">Correct</span>' : '<span style="color:red;">Incorrect</span>';
        echo '<tr><td>'.$question.'</td><td>'.$user_response.'</td><td>'.$correct_response.'</td><td>'.$status.'</td></tr>';
    }
    echo '</table><br />';

    echo '<form action="./index.php" method="post">'; // Retour à index.php pour rejouer
    echo '<input type="hidden" name="num_questions_select" value="'.$num_questions_to_ask.'">'; // Réinitialise le nombre de questions choisi
    echo '<input type="submit" value="Rejouer le Quiz de Multiplications">';
    echo '</form>';
    echo '</center></body></html>';

    // Réinitialiser les sessions pour un nouveau quiz (mais garder num_questions_total)
    $_SESSION['questions'] = [];
    $_SESSION['correct_answers'] = [];
    $_SESSION['user_answers'] = [];
    $_SESSION['question_number'] = 0;
    $_SESSION['score'] = 0;

    include '../views/layout/footer.php'; // Inclure le footer si nécessaire
    exit(); // Arrêter l'exécution pour ne pas afficher les questions à nouveau
}


?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Question Multiplications - Toutes les questions</title>
</head>
<body style="background-color:grey;">
    <center>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
                    <center>
                        <h1>Quiz de Multiplications</h1><br />
                        <?php if (!empty($_SESSION['questions']) && $_SESSION['question_number'] <= $num_questions_to_ask): ?>
                            <form action="./question.php" method="post">
                                <?php foreach ($_SESSION['questions'] as $index => $operation): ?>
                                    <?php $question_number_display = $index + 1; ?>
                                    <p style="font-size: 20px;">Question Numéro <?php echo $question_number_display; ?> : <?php echo $operation; ?></p>
                                    Réponse : <input type="text" name="mot[<?php echo $question_number_display; ?>]" value="<?php echo isset($_SESSION['user_answers'][$question_number_display]) ? $_SESSION['user_answers'][$question_number_display] : ''; ?>" autofocus /><br /><br />
                                    <input type="hidden" name="correction[<?php echo $question_number_display; ?>]" value="<?php echo $_SESSION['correct_answers'][$index]; ?>">
                                    <input type="hidden" name="operation[<?php echo $question_number_display; ?>]" value="<?php echo $operation; ?>">
                                <?php endforeach; ?>
                                <input type="submit" value="Correction du Quiz de Multiplications">
                            </form>
                        <?php endif; ?>
                    </center>
                </td>
                <td style="width:280px;height:430px;background-image:url('./images/NE.jpg');background-repeat:no-repeat;"></td>
            </tr> 
            <tr>
                <td style="width:1000px;height:323px;background-image:url('./images/SO.jpg');background-repeat:no-repeat;"></td>
                <td style="width:280px;height:323px;background-image:url('./images/SE.jpg');background-repeat:no-repeat;"></td>
            </tr> 
        </table>
    </center>
    <br />
    <footer style="background-color: #45a1ff;">
        <center>
            Rémi Synave<br />
            Contact : remi . synave @ univ - littoral [.fr]<br />
            Crédits image : Image par <a href="https://pixabay.com/fr/users/Mimzy-19397/">Mimzy</a> de <a href="https://pixabay.com/fr/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=1576791">Pixabay</a> <br />
            et Image par <a href="https://pixabay.com/fr/users/everesd_design-16482457/">everesd_design</a> de <a href="https://pixabay.com/fr/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=5213756">Pixabay</a> <br />
        </center>
        </footer>
</body>
</html>