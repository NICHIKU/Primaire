<?php
	@ob_start();
    include 'utils.php';


	session_start();
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
            error_reporting(E_ALL);
            ini_set("display_errors", 1);
            $_SESSION['nbQuestion']=$_SESSION['nbQuestion']+1;
	    $alea = mt_rand(2,2);
            $temps='present';
	    
            if($alea==1)
              $temps='futur';
	    if($alea==2)
	      $temps='imparfait';
            $fichier = file("verbes/".$temps.".txt");
			$total = count($fichier);
			$alea = mt_rand(0,$total-1);
            $verbe = $fichier[$alea];
            $verbe = substr($verbe,0,-1);
            $verbeSansAccent=str_replace("à","a",$verbe);
			$verbeSansAccent=str_replace("â","a",$verbeSansAccent);
			$verbeSansAccent=str_replace("é","e",$verbeSansAccent);
			$verbeSansAccent=str_replace("è","e",$verbeSansAccent);
			$verbeSansAccent=str_replace("ë","e",$verbeSansAccent);
			$verbeSansAccent=str_replace("ê","e",$verbeSansAccent);
			$verbeSansAccent=str_replace("î","i",$verbeSansAccent);
			$verbeSansAccent=str_replace("ï","i",$verbeSansAccent);
			$verbeSansAccent=str_replace("ô","o",$verbeSansAccent);
			$verbeSansAccent=str_replace("ö","o",$verbeSansAccent);
			$verbeSansAccent=str_replace("ù","u",$verbeSansAccent);
            $verbeSansAccent=str_replace("û","u",$verbeSansAccent);
			$verbeSansAccent=str_replace("ü","u",$verbeSansAccent);
			$verbeSansAccent=str_replace("ÿ","y",$verbeSansAccent);
            $verbeSansAccent=str_replace("ç","c",$verbeSansAccent);
            $nomFichier = "verbes/".$verbeSansAccent."_".$temps.".txt";
            $fichierVerbe = file($nomFichier);
            $reponse1 = $fichierVerbe[0];
            $reponse1 = substr($reponse1,0,-1);
            $reponse2 = $fichierVerbe[1];
            $reponse2 = substr($reponse2,0,-1);
            $reponse3 = $fichierVerbe[2];
            $reponse3 = substr($reponse3,0,-1);
            $reponse4 = $fichierVerbe[3];
            $reponse4 = substr($reponse4,0,-1);
            $reponse5 = $fichierVerbe[4];
            $reponse5 = substr($reponse5,0,-1);
            $reponse6 = $fichierVerbe[5];
            $reponse6 = substr($reponse6,0,-1);
		?>
		<center>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
						<center>
		
		
		

							<h1>Verbe Numéro <?php echo "".$_SESSION['nbQuestion'] ?></h1><br />
                                                          <h3>Conjugue le verbe ***<u><?php echo $verbe ?></u>*** au <?php if($temps=='present') echo 'présent'; else if($temps=='futur') echo 'futur'; else echo 'imparfait'; ?> :</h3>
							<form action="./correction.php" method="post">
								<input type="hidden" name="correction1" value="<?php echo ''.$reponse1.'' ?>"></input>
                                <input type="hidden" name="correction2" value="<?php echo ''.$reponse2.'' ?>"></input>
								<input type="hidden" name="correction3" value="<?php echo ''.$reponse3.'' ?>"></input>
                                <input type="hidden" name="correction4" value="<?php echo ''.$reponse4.'' ?>"></input>
								<input type="hidden" name="correction5" value="<?php echo ''.$reponse5.'' ?>"></input>
                                <input type="hidden" name="correction6" value="<?php echo ''.$reponse6.'' ?>"></input>

                                <table><tbody>
                                     <tr><td><label for="fname">Je/J' </label></td><td><input type="text" id="mot1" name="mot1" autocomplete="off" autofocus></td></tr>
                                     <tr><td><label for="fname">Tu </label></td><td><input type="text" id="mot2" name="mot2" autocomplete="off"></td></tr>
                                     <tr><td><label for="fname">Il/Elle/On&nbsp;&nbsp;</label></td><td><input type="text" id="mot3" name="mot3" autocomplete="off"></td></tr>
                                     <tr><td><label for="fname">Nous </label></td><td><input type="text" id="mot4" name="mot4" autocomplete="off"></td></tr>
                                     <tr><td><label for="fname">Vous </label></td><td><input type="text" id="mot5" name="mot5" autocomplete="off"></td></tr>
                                     <tr><td><label for="fname">Ils </label></td><td><input type="text" id="mot6" name="mot6" autocomplete="off"></td></tr>
                                 </tbody></table>
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
				et Image par <a href="https://pixabay.com/fr/users/everesd_design-16482457/">everesd_design</a> de <a href="https://pixabay.com/fr/?utm_source=link-attribution&amp;utm_medium=referral&amp;utm_campaign=image&amp;utm_content=5213756">Pixabay</a> <br />
			</center>
		</footer>
	</body>
</html>
