<?php
	@ob_start();
	session_start();
    include 'utils.php';
	
    log_adresse_ip("logs/log.txt","correction.php - ".$_SESSION['prenom']." - Question numéro ".$_SESSION['nbQuestion']);


	if($_POST['correction']==""){
		session_destroy();
		session_unset();
		unset($_POST);
		header('Location: ./index.php');
	}
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
							
							
							
							
							
							<!-- Mise en minuscule du mot entré -->
							<?php 
								//$_POST['mot']=strtolower($_POST['mot']); 
							?>

							<?php 
								if($_POST['mot']==$_POST['correction']){
									echo '<h1>Super '.$_SESSION['prenom'].' ! Bonne réponse.</h1>';
									$_SESSION['nbBonneReponse']=$_SESSION['nbBonneReponse']+1;
									$_SESSION['historique']=$_SESSION['historique'].''.$_POST['mot']."\n";
								}else{
									echo '<h1>Oh non !</h1><br /><h2>Tu as écrit '.$_POST['mot'].'.</h2><h2>La bonne réponse était : '.$_POST['correction'].'.</h2>';
									$_SESSION['historique']=$_SESSION['historique'].'********'.$_POST['mot'].';'.$_POST['correction']."\n";
								}
								echo '<br />';
								if($_SESSION['nbQuestion']<$_SESSION['nbMaxQuestions']){
									if($_SESSION['nbQuestion']==1)
										echo 'Tu as '.$_SESSION['nbBonneReponse'].' bonne réponse sur '.$_SESSION['nbQuestion'].' question.';
									else{
										if($_SESSION['nbBonneReponse']>1)
											echo 'Tu as '.$_SESSION['nbBonneReponse'].' bonnes réponses sur '.$_SESSION['nbQuestion'].' questions.';
										else
											echo 'Tu as '.$_SESSION['nbBonneReponse'].' bonne réponse sur '.$_SESSION['nbQuestion'].' questions.';
										}
								}
							?>
							<br /><br />
							<?php
								if($_POST['mot']==$_POST['correction']){
									if($_SESSION['nbQuestion']<$_SESSION['nbMaxQuestions']){
							?>
							<!-- Cas où la réponse est correcte mais ce n'était pas la dernière question -->
							<form action="./question.php" method="post">
								<input type="submit" value="Suite" autofocus>
							</form>
							<?php
									}else{
							?>
							<!-- Cas où la réponse est correcte et c'était la dernière question -->
							<form action="./fin.php" method="post">
								<input type="submit" value="Suite" autofocus>
							</form>
							<?php
									}
								}else{
							?>
							<!-- Cas où la réponse n'était pas correcte -->
							<form action="./recopie.php" method="post">
								<input type="hidden" name="recopie" value=""></input>
								<input type="hidden" name="correction" value="<?php echo "".$_POST['correction']."" ?>"></input>
								<input type="submit" value="Suite" autofocus>
							</form>
							<?php
								}		
							?>
							<br /><br />
							<form action="./raz.php" method="post">
								<input type="submit" value="Tout recommencer">
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
				Crédits voix : Denise de <a href="https://azure.microsoft.com/fr-fr/services/cognitive-services/text-to-speech/">Microsoft Azure</a>
			</center>
		</footer>
	</body>
</html>
