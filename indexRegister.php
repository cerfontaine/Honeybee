<!DOCTYPE html>
<?php
require 'php/function.inc.php';
use Membre\MembreRepository as MembreRepository;
use Membre\Membre as Membre;
$membreRepository = new MembreRepository();
$nom_fichier='';
$message='';

if(isset($_POST['register'])){
    $notEmpty = isNotEmpty($_POST, $message);
    $existIndb = $membreRepository->existsInDb($_POST['pseudo'], $message);
    $equalpass = passwordEqual($_POST['mdp'], $_POST['mdps'], $message);
    $courrielok = MailIsValid($_POST['mail'], $message);
    $isLuhn = isLunh($_POST['visa'], $message);

    if($notEmpty && $equalpass && $courrielok && $existIndb && $isLuhn){
        $add = addPicture($_FILES['avatar'], $nom_fichier,$message);

        if($add) {
            $membre = new Membre;

            $membre->login = htmlentities($_POST['pseudo']);
            $membre->prenom = htmlentities($_POST['prenom']);
            $membre->nom = htmlentities($_POST['nom']);
            $membre->mot_passe = htmlentities($_POST['mdp']);
            $membre->courriel = htmlentities($_POST['mail']);
            $membre->tel = htmlentities($_POST['téléphone']);
            $membre->adresse_rue = htmlentities($_POST['rue']);
            $membre->adresse_num = htmlentities($_POST['numero']);
            $membre->adresse_code = htmlentities($_POST['code']);
            $membre->adresse_ville = htmlentities($_POST['ville']);
            $membre->adresse_pays = htmlentities($_POST['pays']);
            $membre->avatar = $nom_fichier;
            $membre->carte_VISA = htmlentities($_POST['visa']);

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
	<header>
		<h2><a href="index.php">COLLECT'OR</a></h2>
	</header>
	<main>
        <?php
        echo $message;
        ?>
        <fieldset class="register">
			<h1>Inscription</h1>
			<form autocomplete="off" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
				<label>INFORMATIONS DE CONNEXION</label>
				<label class="title">Nom d'utilisateur</label><input id="pseudo" name="pseudo" type="text" value="<?php if (isset($_POST['pseudo'])){echo $_POST['pseudo'];} ?>" required autofocus>
				<label class="title">Email</label><input id="mail" name="mail" type="email" value="<?php if (isset($_POST['mail'])){echo $_POST['mail'];} ?>" required>
				<label class="title">Mot de passe</label><input id="mdp" name="mdp" type="password" required>
				<label class="title">Retapez le mot de passe</label><input type="password" id="mdps" name="mdps" required>

			     
				<label>INFORMATIONS PERSONNELLES</label>
				<label class="title">Nom</label><input id="nom" name="nom" type="text" value="<?php if (isset($_POST['nom'])){echo $_POST['nom'];} ?>" required>
				<label class="title">Prénom</label><input id="prenom" name="prenom" type="text" value="<?php if (isset($_POST['prenom'])){echo $_POST['prenom'];} ?>" required>
				<label class="title">Votre avatar</label><input type="file" id="avatar" name="avatar" accept=".png, .jpeg, .jpg, .gif" required>
				<label class="title">Numéro de téléphone</label><input id="téléphone" name="téléphone" type="text" value="<?php if (isset($_POST['téléphone'])){echo $_POST['téléphone'];} ?>" required>
			
			
				<label>INFORMATIONS DE LOCALISATION</label>
				<label class="title">Rue</label><input id="rue" name="rue" type="text" value="<?php if (isset($_POST['rue'])){echo $_POST['rue'];} ?>" required>
				<label class="title">N°/Boîte</label><input id="numero" name="numero" type="text" value="<?php if (isset($_POST['numero'])){echo $_POST['numero'];} ?>" required>
				<label class="title">Code postal</label><input id="code" name="code" type="text" value="<?php if (isset($_POST['code'])){echo $_POST['code'];} ?>" required>
				<label class="title">Ville</label><input id="ville" name="ville" type="text" value="<?php if (isset($_POST['ville'])){echo $_POST['ville'];} ?>" required>
				<label class="title">Pays</label><input id="pays" name="pays" type="text"value="<?php if (isset($_POST['pays'])){echo $_POST['pays'];} ?>" required>
			
			
				<label>INFORMATIONS FINANCIERES</label> 
				<label class="title">Visa</label><input id="visa" name="visa" type="text" value="<?php if (isset($_POST['visa'])){echo $_POST['visa'];} ?>" pattern="^-?\d{16}$" required>
				
				<input type="submit" name="register" value="Créer mon compte">
			</form>
		</fieldset>
	</main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>