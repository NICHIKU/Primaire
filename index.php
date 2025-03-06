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
							<?php if ($loggedInUser['role'] == 'child'): ?>

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
								<?php elseif ($loggedInUser['role'] == 'teacher'): ?>
								<p>Manage exercises and student data.</p>
								<?php elseif ($loggedInUser['role'] == 'parent'): ?>
								<p>View your child's progress and stats.</p>
								<a href="views/stats.php" class="btn btn-primary">View Child Stats</a>
								<?php endif; ?>
						<?php else: ?>
							<h2>Welcome to the Elementary School System</h2>
							<p>Please login or signup to access the system.</p>
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
