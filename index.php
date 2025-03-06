<?php
require_once __DIR__ . '/controllers/AuthController.php';
$authController = new AuthController();

$loggedInUser = $authController->getLoggedInUser();
?>

<?php include 'views/layout/header.php'; ?>


<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Accueil</title>
	</head>
	<body style="background-color:grey;">
		<center>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
						<center>
						
						<?php if ($loggedInUser): ?>
							<h1>Bonjour  <?php echo htmlspecialchars($loggedInUser['username']); ?> !</h1>
							<h2>Que veux-tu faire ?</h2>
							<?php if ($loggedInUser['role'] == 'Enfant'): ?>

								<table border="1" cellpadding="15" style="border-collapse:collapse;border: 15px solid #ff7700;background-color:#d6d6d6;">
									<tr>
										<td><center><a href="addition/index.php" style="color:black;font-weight:bold;text-decoration:none"><img src="./images/addition.png"><br />Addition</a></center></td>
										<td><center><a href="soustraction/index.php" style="color:black;font-weight:bold;text-decoration:none"><img src="./images/soustraction.png"><br />Soustraction</a></center></td>
										<td><center><a href="multiplication/index.php" style="color:black;font-weight:bold;text-decoration:none"><img src="./images/multiplication.png"><br />Multiplication</a></center></td>
									</tr>
									<tr>
										<td><center><a href="dictee/index.php" style="color:black;font-weight:bold;text-decoration:none"><img src="./images/dictee.png"><br />Dictée</a></center></td>
										<td><center><a href="conjugaison_verbe/index.php" style="color:black;font-weight:bold;text-decoration:none"><img src="./images/conjugaison_verbe.png"><br />Conjugaison<br />de verbes</a></center></td>
										<td><center><a href="conjugaison_phrase/index.php" style="color:black;font-weight:bold;text-decoration:none"><img src="./images/conjugaison_phrase.png"><br />Conjugaison<br />de phrases</a></center></td>
									</tr>
								</table>
								<?php elseif ($loggedInUser['role'] == 'Professeur(e)'): ?>
								<p>Gérer les exercices et les données des éleves</p>
								<?php elseif ($loggedInUser['role'] == 'Parent'): ?>
								<p>Voir la progression et les statistiques de votre enfant</p>
								<a href="views/stats.php" class="btn btn-primary">Voir les données</a>
								<?php endif; ?>
						<?php else: ?>
							<h2>Bienvenue aux tests de primaires</h2>
							<p>Merci de vous connecter ou vous inscrire pour accéder au fonctionnalités</p>
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
		<?php include 'views/layout/footer.php'; ?>
	</body>
</html>
