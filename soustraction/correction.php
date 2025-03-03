<?php
	@ob_start();
    include 'utils.php';
	session_start();
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
		
		
		
							<?php 
								if($_POST['mot']==$_POST['correction']){
									echo '<h1>Super '.$_SESSION['prenom'].' ! Bonne réponse.</h1>';
									$_SESSION['nbBonneReponse']=$_SESSION['nbBonneReponse']+1;
									$_SESSION['historique']=$_SESSION['historique'].''.$_POST['operation'].$_POST['correction']."\n";
								}else{
                                    echo '<h1>Oh non !</h1><br />';
									echo '<h2>La bonne réponse était : '.$_POST['operation'].$_POST['correction'].'.</h2>';
									$_SESSION['historique']=$_SESSION['historique'].'********'.$_POST['operation'].$_POST['mot'].';'.$_POST['correction']."\n";
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
								if($_SESSION['nbQuestion']<$_SESSION['nbMaxQuestions']){
							?>
							<form action="./question.php" method="post">
								<input type="submit" value="Suite" autofocus>
							</form>
							<?php
								}else{
                            ?>
                                    <form action="./fin.php" method="post">
                                        <input type="submit" value="Suite" autofocus>
                                    </form>
                            <?php
                                }
							?>
					 
    
    
    
    
    
    
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
