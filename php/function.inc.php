<?php
require_once('db_link.inc.php');
require_once("db_membre.inc.php");
require_once("db_categorie.inc.php");
require_once("db_type.inc.php");
require_once("db_projet.inc.php");
require_once("db_commentaire.inc.php");
require_once("db_participation.inc.php");
require_once("db_news.inc.php");

const REP_UPLOAD = "uploads/";

function MailIsValid($email, &$message) {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $message .= "Adresse mail non valide<br>";
    }

    return (empty($message)) ? True : False;
}

function isNotEmpty($tab, &$message) {
    foreach($tab as $key=>$val){
        if(empty($val)){
            $message .= "Le champ $key est vide <br>";
        }
    }
    return (empty($message)) ? True : False;
}

function oneIsFilled($tab, &$message) {
    $emptycount = 0;
    $tabcount = 0;
    foreach($tab as $key=>$val){
        $tabcount++;
        if(empty($val)){
            $emptycount++;
        }
    }
    $result = $tabcount - $emptycount;
    if($result<=0) {
        $message .= "Un champ doit au moins être rempli <br>";
    }
    return (empty($message)) ? True : False;
}

function passwordEqual($mdp, $mdps, &$message){
    if($mdp != $mdps){
        $message .= "Les mots de passe ne correspondent pas <br>";
    }
    return (empty($message)) ? True : False;
}

function isNumeric($val, &$message) {
    if(is_int($val)){
        $message .= '';
    }
    if(is_double($val)){
        $message .= '';
    }
    else{
        $message .= "La valeur entrée n'est pas une valeur numérique";
    }
    return (empty($message)) ? True : False;
}

function addPicture($image, &$nomFichier, &$message){
    if($image['error'] > 0){
        $message .="Une erreur est survenue lors du téléchargement:<br>";
        switch($image['error']){
            case UPLOAD_ERR_NO_FILE:
                $message .= 'fichier manquant.';
                break;
            case UPLOAD_ERR_INI_SIZE:
                $message .= 'fichier dépassant la taille maximale autorisée par PHP.';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message .= 'fichier dépassant la taille maximale autorisée par le formulaire.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $message .= 'fichier partiellement téléchargé';
                break;
        }
        return $message;
    }
    $nomFichier = uniqid();

    $extension = pathinfo($image['name']);
    $nomFichier = $nomFichier.'.'.$extension['extension'];
    if(file_exists(REP_UPLOAD . $nomFichier)){
        $message.= 'Le fichier existe déjà! ';
    }
    if(!move_uploaded_file($image['tmp_name'], REP_UPLOAD.$nomFichier)){
        $message .= 'Une erreur est survenue lors de la copie.';
    }
    return (empty($message)) ? True : False;
}

function addApercu($image, &$nomFichier, &$message){
    if($image['error'] > 0){
        $message .="Une erreur est survenue lors du téléchargement:<br>";
        switch($image['error']){
            case UPLOAD_ERR_NO_FILE:
                $message .= 'fichier manquant.';
                break;
            case UPLOAD_ERR_INI_SIZE:
                $message .= 'fichier dépassant la taille maximale autorisée par PHP.';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message .= 'fichier dépassant la taille maximale autorisée par le formulaire.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $message .= 'fichier partiellement téléchargé';
                break;
        }
        return $message;
    }
    $nomFichier = uniqid();

    $extension = pathinfo($image['name']);
    $nomFichier = $nomFichier.'.'.$extension['extension'];
    if(file_exists(REP_UPLOAD . $nomFichier)){
        $message.= 'Le fichier existe déjà! ';
    }
    if(!move_uploaded_file($image['tmp_name'], REP_UPLOAD.$nomFichier)){
        $message .= 'Une erreur est survenue lors de la copie.';
    }
    return (empty($message)) ? True : False;
}

function addFile($image, &$nomFichier, &$message){
    if($image['error'] > 0){
        $message .="Une erreur est survenue lors du téléchargement:<br>";
        switch($image['error']){
            case UPLOAD_ERR_NO_FILE:
                $message .= 'fichier manquant.';
                break;
            case UPLOAD_ERR_INI_SIZE:
                $message .= 'fichier dépassant la taille maximale autorisée par PHP.';
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message .= 'fichier dépassant la taille maximale autorisée par le formulaire.';
                break;
            case UPLOAD_ERR_PARTIAL:
                $message .= 'fichier partiellement téléchargé';
                break;
        }
        return $message;
    }
    $nomFichier = uniqid();

    $extension = pathinfo($image['name']);
    $nomFichier = $nomFichier.'.'.$extension['extension'];
    if(file_exists(REP_UPLOAD . $nomFichier)){
        $message.= 'Le fichier existe déjà! ';
    }
    if(!move_uploaded_file($image['tmp_name'], REP_UPLOAD.$nomFichier)){
        $message .= 'Une erreur est survenue lors de la copie.';
    }
    return (empty($message)) ? True : False;
}

function envoyerMailActivation($email, $nom){

    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('no-reply@collector.be');
        $mail->addAddress($email);
        $mail->addReplyTo('no-reply@collector.be');
        $mail->isHTML(false);
        $mail->Subject = 'Bienvenue sur Collector';
        $mail->Body = "Bonjour Monsieur/Madame $nom,\n\nNous vous souhaitons la bienvenue sur notre plateforme de crowdfunding\nNous avons hâte de voir vos futurs projets !\n\nVous avez un problème ? N'hésitez pas à nous contacter : http://192.168.128.13/~e190526/EVAL_V4/indexContact.php \n\n © Collector.";
        $mail->send();
    } catch (Exception $e) {
        return 'Erreur survenue lors de l\'envoi de l\'email<br>' . $mail->ErrorInfo;
    }
}

function envoyerMailContact($email, $sujet, $com){

    $mail = new PHPMailer(true);
    try {
        $mail->CharSet = 'UTF-8';
        $mail->setFrom('admininfo@collector.be');
        $mail->addAddress('sibilancetech@gmail.com');
        $mail->addReplyTo($email);
        $mail->isHTML(false);
        $mail->Subject = $sujet;
        $mail->Body = "Un utilisateur a une question, vous la trouverez ci-dessous, merci de lui répondre à $email.\n\n\n$com";
        $mail->send();
    } catch (Exception $e) {
        return 'Erreur survenue lors de l\'envoi de l\'email<br>' . $mail->ErrorInfo;
    }
}

function isLunh($visa, &$message){
    $longueur = strlen($visa);
    $total = 0;
    for($i=$longueur-1;$i>=0,$i--;) {
        $numero = $visa[$i];
        if((($longueur - $i)%2)==0){
            $numero = $numero*2;
            if($numero>9){
                $numero = $numero-9;
            }
        }
        $total += $numero;
    }
    if(($total % 10) != 0){
        $message .= 'Le numéro de carte VISA n\'est pas valide.';
    }
    return (empty($message)) ? True : False;
}
?>
