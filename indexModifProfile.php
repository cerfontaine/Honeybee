<?php
require('php/session.inc.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Mon profil</title>
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
		<section class="cologin">
			<div class="navlogin profilehead">
                <?php include('inc/connexionHead.inc.php')?>
			</div>	
		</section>
	</header>
	<main>
		<h1>Mon profil</h1>
		<article id="topprofile">
			<img src="img/pdp.jpg" alt="Photo de profil" class="profilepic">
			<div class="profile">
				<form action="indexSucces.php" method=get enctype=multipart/form-data>
					<label class="profilelab">Nom</label><input id="nom" name="nom" type="text" required autofocus>
					<label class="profilelab">Prénom</label><input id="prenom" name="prenom" type="text" required>
					<label class="profilelab">Nom d'utilisateur</label><input id="pseudo" name="pseudo" type="text" required>

					<label class="profilelab catprofile">Informations du compte</label>
					<label class="profilelab">Email</label><input id="mail" name="mail" type="email" required>
					<label class="profilelab">Numéro de téléphone</label><input id="téléphone" name="téléphone" type="text" required>
					<label class="profilelab">Date de naissance</label><input id="naissance" name="naissance" type="date" required>

					<label class="profilelab catprofile">Informations de localisation</label>
					<label class="profilelab">Rue</label><input id="rue" name="rue" type="text" required>
					<label class="profilelab">N°/Boîte</label><input id="numero" name="numero" type="text" required>
					<label class="profilelab">Code postal</label><input id="code" name="code" type="text" required>
					<label class="profilelab">Ville</label><input id="ville" name="ville" type="text" required>
					<label class="profilelab">Pays</label><input id="pays" name="pays" type="text" required>

					<label class="profilelab catprofile">Informations financières</label>
					<label class="profilelab">Carte Visa</label><input id="visa" name="visa" type="text" pattern="^-?\d{16}$" required>
					<input type="submit" name="register" value="Modifier">
				</form>
			</div>
		</article>
	</main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>