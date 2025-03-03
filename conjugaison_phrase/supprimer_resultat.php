<?php
@ob_start();
include 'utils.php';
$nomFichier=$_GET['nomFichier'];
log_adresse_ip("logs/log.txt","supprime_resultat.php - ".$nomFichier);
rename('./resultats/'.$nomFichier,'./supprime/'.$nomFichier);
usleep(100000); //sleep(0.1), 0.1 seconde
header('Location: ./affiche_resultat.php?prenomRes='.$_GET['prenomRes']);
?>
