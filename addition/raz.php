<?php
@ob_start();
include 'utils.php';
log_adresse_ip("logs/log.txt","raz.php");
session_destroy();
session_unset();
unset($_POST);
header('Location: ./index.php');
?>
