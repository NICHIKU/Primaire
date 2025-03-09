<?php include '../views/layout/header.php'; ?>

<?php
    @ob_start();
    include 'utils.php';
    log_adresse_ip("logs/log.txt","index.php");

    $_SESSION['nbMaxQuestions']=10; // Default, but will be set by user selection now
    $_SESSION['nbQuestion']=0;
    $_SESSION['nbBonneReponse']=0;
    $_SESSION['prenom']="";
    $_SESSION['historique']="";
    $_SESSION['origine']="index";

    // Initialize session variables related to questions if not already set
    if (!isset($_SESSION['questions'])) {
        $_SESSION['questions'] = [];
    }
    if (!isset($_SESSION['correct_answers'])) {
        $_SESSION['correct_answers'] = [];
    }
    if (!isset($_SESSION['user_answers'])) {
        $_SESSION['user_answers'] = [];
    }
    if (!isset($_SESSION['question_number'])) {
        $_SESSION['question_number'] = 0;
    }
    if (!isset($_SESSION['num_questions_total'])) {
        $_SESSION['num_questions_total'] = 0; // Initialiser à 0 par défaut
    }
    if (!isset($_SESSION['score'])) {
        $_SESSION['score'] = 0;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['num_questions_select'])) {
        $_SESSION['num_questions_total'] = intval($_POST['num_questions_select']);
        header('Location: question.php'); // Redirect to question_multiplication.php to start quiz
        exit();
    }


    $_POST['nbQuestion']=0;
    $_POST['nbBonneReponse']=0;
    $_POST['prenom']="";
    $_POST['historique']="";
    $_POST['nbMaxQuestions']=10; // Still setting this, but session will be leading now
?>

<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>Accueil - Multiplications</title>
    </head>
    <body style="background-color:grey;">
        <center>
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
                        <center>
                            <h1>Quiz de Multiplications !</h1><br />
                            <h2>Nous allons faire du calcul mental.</h2><br />

                            <p>Choisissez le nombre de questions pour ce quiz :</p>
                            <form action="./index.php" method="post" style="display:inline-block;">
                                <select name="num_questions_select">
                                    <option value="5" <?php if($_SESSION['num_questions_total'] == 5) echo 'selected'; ?>>5</option>
                                    <option value="10" <?php if($_SESSION['num_questions_total'] == 10 || $_SESSION['num_questions_total'] == 0) echo 'selected'; ?>>10</option>
                                    <option value="15" <?php if($_SESSION['num_questions_total'] == 15) echo 'selected'; ?>>15</option>
                                    <option value="20" <?php if($_SESSION['num_questions_total'] == 20) echo 'selected'; ?>>20</option>
                                </select>
                                <input type="submit" value="Valider Nombre">
                            </form>
                            <br /><br />

                            <?php if ($_SESSION['num_questions_total'] > 0): ?>
                                <h2>Vous avez sélectionné <?php echo $_SESSION['num_questions_total']; ?> calculs de multiplications.</h2>
                                <a href=".\question.php"><input type="submit" value="Commencer le Quiz de Multiplications"></a>
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