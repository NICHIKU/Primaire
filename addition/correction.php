<?php
include '../views/layout/header.php';
include 'utils.php';
require_once __DIR__ . '/../controllers/AuthController.php';
$authController = new AuthController();

// S'assurer que l'utilisateur est connecté
if (!$authController->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$loggedInUser = $authController->getLoggedInUser();
$userId = $loggedInUser['user_id'];

$_SESSION['origine'] = "correction";
$operation = isset($_POST['operation']) ? $_POST['operation'] : '';
$correction = isset($_POST['correction']) ? $_POST['correction'] : '';
$mot = isset($_POST['mot']) ? $_POST['mot'] : '';
$operationType = isset($_POST['operation_type']) ? $_POST['operation_type'] : '';
$difficulty = isset($_POST['difficulty']) ? $_POST['difficulty'] : '';

// Vérifier si la réponse est correcte
$isCorrect = (trim($mot) == trim($correction));

// Enregistrer le résultat dans la base de données
$db = new PDO('mysql:host=localhost;dbname=votre_base_de_donnees;charset=utf8', 'username', 'password');
$stmt = $db->prepare("INSERT INTO question_results (user_id, question_number, operation, user_answer, correct_answer, is_correct, operation_type, difficulty) 
                     VALUES (:user_id, :question_number, :operation, :user_answer, :correct_answer, :is_correct, :operation_type, :difficulty)");
$stmt->execute([
    ':user_id' => $userId,
    ':question_number' => $_SESSION['nbQuestion'],
    ':operation' => $operation,
    ':user_answer' => $mot,
    ':correct_answer' => $correction,
    ':is_correct' => $isCorrect ? 1 : 0,
    ':operation_type' => $operationType,
    ':difficulty' => $difficulty
]);

// Le reste du code existant pour afficher le résultat à l'élève
log_adresse_ip("logs/log.txt", "correction.php - ".$_SESSION['prenom']." - Correction numéro ".$_SESSION['nbQuestion']);
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Correction</title>
    </head>
    <body style="background-color:grey;">
        <center>
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
                        <center>
                            <h1>Correction Question Numéro <?php echo "".$_SESSION['nbQuestion']."" ?></h1><br />
                            <?php
                            if($isCorrect) {
                                echo '<h2 style="color:green">Bravo ! Ta réponse '.$mot.' est correcte.</h2>';
                            } else {
                                echo '<h2 style="color:red">Désolé... Ta réponse '.$mot.' est fausse.</h2>';
                                echo '<h3>La bonne réponse était : '.$correction.'</h3>';
                            }
                            ?>
							<br /><br />
                            <form action="./question.php" method="post">
                                <input type="submit" value="Question suivante">
                            </form>
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