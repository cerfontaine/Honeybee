<?php
require('php/session.inc.php');
require('php/function.inc.php');

use Projet\ProjetRepository as ProjetRepository;
use Participation\Participation as Participation;
use Participation\ParticipationRepository as ParticipationRepository;

$participationRepository = new ParticipationRepository();
$projetRepository = new ProjetRepository();
$message = '';
$title = 'Formulaire de soutiens';

if ($participationRepository->siParticipation($_GET['id_projet'], $_SESSION['id'], $message) == 1){
    header('location: index.php');
}
if(isset($_POST['participer'])) {
    $participation = new Participation();

    $participation->montant=htmlentities($_POST['montant']);
    $participation->id_projet=$_GET['id_projet'];
    $participation->id_membre=$_SESSION['id'];

    $participationRepository->storeParticipation($participation, $message);
    $projetRepository->updateTaux($_GET['id_projet'], $message);
}
?>
<?php include('inc/head.er.inc.php') ?>
<body>
    <?php include('inc/bee.inc.php');?>
	<header>		
		<h2><a href="index.php">You've got to <span class="bee">bee</span> kidding me</a></h2>
		<section class="cologin">
			<div class="navlogin profilehead">
                <?php include('inc/connexionHead.inc.php')?>
			</div>
		</section>
	</header>
	<main>
        <?php if(!isset($_POST['participer'])){ ?>
		<fieldset class="login">
			<h1>Formulaire de soutien pour</h1>
            <h2 class="nameproject"><?php echo $projetRepository->getProjectName($_GET['id_projet'], $message)?></h2>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
				 <label class="title">Montant</label><input id="montant" name="montant" type="number" min="<?php echo $projetRepository->getProjectMin($_GET['id_projet'], $message)?>" value="<?php echo $projetRepository->getProjectMin($_GET['id_projet'], $message)?>" required autofocus>
				 <input type="submit" name="participer" value="Participer">
			</form>
		</fieldset>
        <?php } ?>
        <?php if(isset($_POST['participer'])){ ?>
            <article class="success">
                <span><i class="fas fa-check"></i><?php echo $message;?> Vous allez être redirigé ! </span>
                <meta http-equiv="refresh" content="3;URL=indexProjet.php?id_projet=<?php echo $_GET['id_projet'] ?>">
            </article>
        <?php } ?>
	</main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>