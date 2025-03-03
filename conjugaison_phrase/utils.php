<?php
// log adresse ip
// paramètre : nom du fichier de log
function log_adresse_ip($cheminFichierLog, $nomPage) {
    $adresseIP = $_SERVER['REMOTE_ADDR'];
    $fichierLog = fopen($cheminFichierLog, "a");
    $tdate=getdate();
    $jour=sprintf("%02.2d",$tdate["mday"])."/".sprintf("%02.2d",$tdate["mon"])."/".$tdate["year"];
    $heure=sprintf("%02.2d",$tdate["hours"])."h".sprintf("%02.2d",$tdate["minutes"])."m".sprintf("%02.2d",$tdate["seconds"])."s";
    $d="[".$jour." ".$heure."]";
    fwrite($fichierLog,$d." - ".$adresseIP." : ".$nomPage."\n");
    fclose($fichierLog);
}
?>

<?php
function supprime_caracteres_speciaux($chaine) { 
    $chaine=str_replace("à","a",$chaine);
    $chaine=str_replace("â","a",$chaine);
    $chaine=str_replace("é","e",$chaine);
    $chaine=str_replace("è","e",$chaine);
    $chaine=str_replace("ë","e",$chaine);
    $chaine=str_replace("ê","e",$chaine);
    $chaine=str_replace("î","i",$chaine);
    $chaine=str_replace("ï","i",$chaine);
    $chaine=str_replace("ô","o",$chaine);
    $chaine=str_replace("ö","o",$chaine);
    $chaine=str_replace("ù","u",$chaine);
    $chaine=str_replace("û","u",$chaine);
    $chaine=str_replace("ü","u",$chaine);
    $chaine=str_replace("ÿ","y",$chaine);
    $chaine=str_replace("ç","c",$chaine);
    return $chaine;
}
?>

<?php
function conjugaison($nomFichier, $numLigne) {
    $fichierVerbe = file($nomFichier);
    $reponse = $fichierVerbe[$numLigne-1];
    $reponse = substr($reponse,0,-1);
    return $reponse;
}
?>
