<?php
	@ob_start();
	session_start();
    include 'utils.php';

	$_SESSION['origine']="question";
    if($_SESSION['prenom']=="" && $_POST['prenom']==""){
        log_adresse_ip("logs/log.txt","question.php - accès irrégulier");
        unset($_SESSION);
        unset($_POST);
        header('Location: ./index.php');
    }	
	if($_SESSION['prenom']==""){
		$_SESSION['prenom']=$_POST['prenom'];
	}
    $numQuestion=$_SESSION['nbQuestion']+1;
    log_adresse_ip("logs/log.txt","question.php - ".$_SESSION['prenom']." - Question numéro ".$numQuestion);
?>

<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Question</title>
	</head>
	<body style="background-color:grey;">
		<?php 
			$_SESSION['nbQuestion']=$_SESSION['nbQuestion']+1;
			$fichier = file("listeDeMots/liste_dictee_20230407.txt");
			$total = count($fichier);
			$alea=mt_rand(0,$total-1);
			$ligneFichier=explode(';',$fichier[$alea]);
		?>
		<center>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
						<center>

		
		

		
							<h1>Question Numéro <?php echo "".$_SESSION['nbQuestion']."" ?></h1><br />
							<audio autoplay controls>
								<source src="./<?php echo './sons/'.$ligneFichier[1].''?>" type="audio/mpeg">
								Votre navigateur ne supporte pas l'audio. Passez à Firefox !
							</audio>
							<form action="./correction.php" method="post">
								<input type="hidden" name="correction" value="<?php echo ''.$ligneFichier[0].''?>"></input>
								<input type="hidden" name="nomFichierSon" value="<?php echo ''.$ligneFichier[1].''?>"></input>
								<br />
								<label for="fname">Qu'as-tu entendu ?</label><br>
								<input type="text" id="mot" name="mot" autocomplete="off" autofocus><br /><br /><br />
								<input type="submit" value="Valider">
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
