<!DOCTYPE html>
<?php
require 'php/function.inc.php';
use Membre\MembreRepository as MembreRepository;
use Membre\Membre as Membre;
$membreRepository = new MembreRepository();
$nom_fichier='';
$message='';
$tel = "067845956256";
$rue = "Rue de la loi";
$num = "14";
$poste= "1000";
$city = "Brussels";
$country = "Belgium";
$visa = "4318868773104749";
if(isset($_POST['register'])){
    $notEmpty = isNotEmpty($_POST, $message);
    $existIndb = $membreRepository->existsInDb($_POST['pseudo'], $message);
    $equalpass = passwordEqual($_POST['mdp'], $_POST['mdps'], $message);
    $courrielok = MailIsValid($_POST['mail'], $message);

    if($notEmpty && $equalpass && $courrielok && $existIndb ){
        $add = addPicture($_FILES['avatar'], $nom_fichier,$message);

        if($add) {
            $membre = new Membre;

            $membre->login = htmlentities($_POST['pseudo']);
            $membre->prenom = htmlentities($_POST['prenom']);
            $membre->nom = htmlentities($_POST['nom']);
            $membre->mot_passe = htmlentities($_POST['mdp']);
            $membre->courriel = htmlentities($_POST['mail']);
            $membre->tel = "067845956256";
            $membre->adresse_rue = "Rue de la loi";
            $membre->adresse_num = "14";
            $membre->adresse_code = "1000";
            $membre->adresse_ville = "Brussels";
            $membre->adresse_pays = "Belgium";
            $membre->avatar = $nom_fichier;
            $membre->carte_VISA = "4318868773104749";

            $membreRepository->storeMembre($membre, $message);

//            if ($membreRepository->storeMembre($membre, $message)) {
//                 envoyerMailActivation($_POST['mail'], $_POST['nom']);
//            }
        }
    }
}
?>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="CSS/collector.css">
    <link href="https://fonts.googleapis.com/css?family=Sonsie+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="icon" href="img/icon.gif">
</head>
<body>
    <?php include('inc/bee.inc.php');?>
	<header>
		<h2><a href="index.php">You've got to <span class="bee">bee</span> kidding me</a></h2>
	</header>
	<main>
        <?php
        echo $message;
        ?>
        <fieldset class="register">
			<h1>Registration</h1>
			<form autocomplete="off" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
				<label>Connection informations</label>
				<label class="title">Username</label><input id="pseudo" name="pseudo" type="text" value="<?php if (isset($_POST['pseudo'])){echo $_POST['pseudo'];} ?>" required autofocus>
				<label class="title">Email</label><input id="mail" name="mail" type="email" value="<?php if (isset($_POST['mail'])){echo $_POST['mail'];} ?>" required>
				<label class="title">Password</label><input id="mdp" name="mdp" type="password" required>
				<label class="title">Confirm the password</label><input type="password" id="mdps" name="mdps" required>

			     
				<label>Personal informations</label>
				<label class="title">Last name</label><input id="nom" name="nom" type="text" value="<?php if (isset($_POST['nom'])){echo $_POST['nom'];} ?>" required>
				<label class="title">First name</label><input id="prenom" name="prenom" type="text" value="<?php if (isset($_POST['prenom'])){echo $_POST['prenom'];} ?>" required>
				<label class="title">Profile picture</label><input type="file" id="avatar" name="avatar" accept=".png, .jpeg, .jpg, .gif" required>
				
				<input type="submit" name="register" value="Create an account">
			</form>
		</fieldset>
	</main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>