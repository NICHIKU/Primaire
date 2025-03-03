<?php
	@ob_start();
    include 'utils.php';
	session_start();
    log_adresse_ip("logs/log.txt","correction.php - ".$_SESSION['prenom']." - Question numéro ".$_SESSION['nbQuestion']);


	if($_POST['correction1']=="" || $_POST['correction2']=="" || $_POST['correction3']=="" || $_POST['correction4']=="" || $_POST['correction5']=="" || $_POST['correction6']==""){
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
                             <h2>Voici tes bonnes et mauvaises réponses !</h2>
		
		
							<?php
                                $nbPointsLocal=0;
								if($_POST['mot1']==$_POST['correction1']){
									echo 'Je/J\' '.$_POST['mot1'].' &#9989;<br />';
									$_SESSION['nbBonneReponse']=$_SESSION['nbBonneReponse']+1;
                                    $nbPointsLocal=$nbPointsLocal+1;
									$_SESSION['historique']=$_SESSION['historique'].'Je/J\' '.$_POST['correction1']."\n";
								}else{
                                    echo 'Je/J\' <strike>'.$_POST['mot1'].'</strike> &#10060; &#10132; '.$_POST['correction1'].'<br />';
									$_SESSION['historique']=$_SESSION['historique'].'********Je/J\' '.$_POST['mot1'].';Je/J\' '.$_POST['correction1']."\n";
								} 
                                if($_POST['mot2']==$_POST['correction2']){
									echo 'Tu '.$_POST['mot2'].' &#9989;<br />';
									$_SESSION['nbBonneReponse']=$_SESSION['nbBonneReponse']+1;
                                    $nbPointsLocal=$nbPointsLocal+1;
									$_SESSION['historique']=$_SESSION['historique'].'Tu '.$_POST['correction2']."\n";
								}else{
                                    echo 'Tu <strike>'.$_POST['mot2'].'</strike> &#10060; &#10132; '.$_POST['correction2'].'<br />';
									$_SESSION['historique']=$_SESSION['historique'].'********Tu '.$_POST['mot2'].';Tu '.$_POST['correction2']."\n";
								}
								if($_POST['mot3']==$_POST['correction3']){
									echo 'Il/Elle/On '.$_POST['mot3'].' &#9989;<br />';
									$_SESSION['nbBonneReponse']=$_SESSION['nbBonneReponse']+1;
                                    $nbPointsLocal=$nbPointsLocal+1;
									$_SESSION['historique']=$_SESSION['historique'].'Il/Elle/On '.$_POST['correction3']."\n";
								}else{
                                    echo 'Il/Elle/On <strike>'.$_POST['mot3'].'</strike> &#10060; &#10132; '.$_POST['correction3'].'<br />';
									$_SESSION['historique']=$_SESSION['historique'].'********Il/Elle/On '.$_POST['mot3'].';Il/Elle/On '.$_POST['correction3']."\n";
								}
								if($_POST['mot4']==$_POST['correction4']){
									echo 'Nous '.$_POST['mot4'].' &#9989;<br />';
									$_SESSION['nbBonneReponse']=$_SESSION['nbBonneReponse']+1;
                                    $nbPointsLocal=$nbPointsLocal+1;
									$_SESSION['historique']=$_SESSION['historique'].'Nous '.$_POST['correction4']."\n";
								}else{
                                    echo 'Nous <strike>'.$_POST['mot4'].'</strike> &#10060; &#10132; '.$_POST['correction4'].'<br />';
									$_SESSION['historique']=$_SESSION['historique'].'********Nous '.$_POST['mot4'].';Nous '.$_POST['correction4']."\n";
								}
								if($_POST['mot5']==$_POST['correction5']){
									echo 'Vous '.$_POST['mot5'].' &#9989;<br />';
									$_SESSION['nbBonneReponse']=$_SESSION['nbBonneReponse']+1;
                                    $nbPointsLocal=$nbPointsLocal+1;
									$_SESSION['historique']=$_SESSION['historique'].'Vous '.$_POST['correction5']."\n";
								}else{
                                    echo 'Vous <strike>'.$_POST['mot5'].'</strike> &#10060; &#10132; '.$_POST['correction5'].'<br />';
									$_SESSION['historique']=$_SESSION['historique'].'********Vous '.$_POST['mot5'].';Vous '.$_POST['correction5']."\n";
								}
								if($_POST['mot6']==$_POST['correction6']){
									echo 'Ils/Elles '.$_POST['mot6'].' &#9989;<br />';
									$_SESSION['nbBonneReponse']=$_SESSION['nbBonneReponse']+1;
                                    $nbPointsLocal=$nbPointsLocal+1;
									$_SESSION['historique']=$_SESSION['historique'].'Ils/Elles '.$_POST['correction6']."\n";
								}else{
                                    echo 'Ils/Elles <strike>'.$_POST['mot6'].'</strike> &#10060; &#10132; '.$_POST['correction6'].'<br />';
									$_SESSION['historique']=$_SESSION['historique'].'********Ils/Elles '.$_POST['mot6'].';Ils/Elles '.$_POST['correction6']."\n";
								}

								echo '<br />';
                                if($nbPointsLocal>1)
                                    echo 'Tu as '.$nbPointsLocal.' bonnes réponses sur 6 questions.';
                                else
                                    echo 'Tu as '.$nbPointsLocal.' bonne réponse sur 6 questions.';
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
