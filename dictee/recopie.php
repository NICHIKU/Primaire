<?php
	@ob_start();
	session_start();
	$_SESSION['origine']="recopie";
?>

<!doctype html>
<html lang="fr">
	<head>
		<meta charset="utf-8">
		<title>Recopie</title>
	</head>
	<body style="background-color:grey;">
		<center>
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td style="width:1000px;height:430px;background-image:url('./images/NO.jpg');background-repeat:no-repeat;">
						<center>

							
							
		
		
		
							<?php 
								if($_POST['recopie']==$_POST['correction']){
									if($_SESSION['nbQuestion']<$_SESSION['nbMaxQuestions']){
							?>
							<form id="formulairePourRecopieCorrecte" action="question.php" method="post">
							</form>
							<script type="text/javascript">
								document.getElementById('formulairePourRecopieCorrecte').submit();
							</script>
							<?php
									}else{
							?>
							<form id="formulairePourRecopieCorrecte" action="fin.php" method="post">
							</form>
							<script type="text/javascript">
								document.getElementById('formulairePourRecopieCorrecte').submit();
							</script>
							<?php									
									}
								}else{
									echo '<h2>La bonne réponse était : '.$_POST['correction'].'</h2>';
									echo 'Recopie la ici : <br />';
							?>
							<form action="./recopie.php" method="post">
								<input type="hidden" name="correction" value="<?php echo "".$_POST['correction']."" ?>"></input>
								<input type="text" id="recopie" name="recopie" autocomplete="off" autofocus> <br /><br />
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
				Crédits voix : Denise de <a href="https://azure.microsoft.com/fr-fr/services/cognitive-services/text-to-speech/">Microsoft Azure</a>
			</center>
		</footer>
	</body>
</html>
