<?php
session_start();
require('php/function.inc.php');
use Membre\MembreRepository as MembreRepository;
use Membre\Membre as Membre;
$membreRepository = new MembreRepository();

$message = '';
$title = 'Connexion';
if (isset($_SESSION['username'])){
    header('location: index.php');
}
if (isset($_POST['connexion']) && isNotEmpty($_POST, $message)){
    if($membreRepository->checklogin(htmlentities($_POST['pseudo']), htmlentities($_POST['mdp']), $message)) {
        $membreInfo = new MembreRepository();

        $membreInfo = $membreRepository->getSMTG('login', $_POST['pseudo'], $message);
        $id_membre = $membreInfo->id_membre;
        $isAdmin = $membreInfo->est_admin;

        $membre = new Membre();

        $membre->pseudo = htmlentities($_POST['pseudo']);
        $membre->mdp = htmlentities($_POST['mdp']);


        $_SESSION['username'] = htmlentities($_POST['pseudo']);
        $_SESSION['id']= $id_membre ;
        $_SESSION['est_admin'] = $isAdmin;
        $membreRepository->updateLoginStatus($id_membre, 1, $message);
        header('location: index.php');
    }
}
?>
<?php include('inc/head.er.inc.php')?>
<body>
    <?php include('inc/bee.inc.php');?>
	<header>
		<h2><a href="index.php">You've got to <span class="bee">bee</span> kidding me</a></h2>
	</header>
	<main>
        <?php if (!empty($message)) { echo "<div class='test'><span>$message</span></div>"; } ?>
		<fieldset class="login">
			<h1>Connexion</h1>
			<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" enctype="application/x-www-form-urlencoded">
				 <input id="pseudo" name="pseudo" type="text" placeholder="Nom d'utilisateur" required autofocus>
				 <input id="mdp" name="mdp" type="password" placeholder="Mot de passe" required>
				 <input type="submit" name="connexion" value="Connexion">
			</form>
			<span class="mdpo">Vous n'avez pas encore de compte ?<a href="indexRegister.php" class="sousligne"> Inscrivez-vous !</a></span>
		</fieldset>
	</main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>