<?php
require('php/session.inc.php');
require('php/function.inc.php');

use Projet\ProjetRepository as ProjetRepository;
use Categorie\CategorieRepository as CategorieRepository;
use Membre\MembreRepository as MembreRepository;
use Participation\ParticipationRepository as ParticipationRepository;
use Quote\Quote as Quote;
use Quote\QuoteRepository as QuoteRepository;

$categorieRepository = new CategorieRepository();
$projetRepository = new ProjetRepository();
$membreRepository = new MembreRepository();
$participationRepository = new ParticipationRepository();
$quoteRepository = new QuoteRepository();
$message = '';
$title = 'Collector';

if (isset($_POST['logout'])){
    if(isset($_SESSION['id'])){
        $membreRepository->updateLoginStatus($_SESSION['id'], 0, $message);
    }
    $_SESSION = array();
    setcookie("PHPSESSID", "", time()-3600, "/");
    session_destroy();
}
?>
<?php include('inc/head.er.inc.php')?>
<body>
<?php include('inc/bee.inc.php');?>
    <header>
        <h2><a href="index.php">You've got to <span class="bee">bee</span> kidding me</a></h2>
		<section id="cologin">
			<h1 class="hidden">Accueil Collect'or</h1>
			<div class="navlogin">
				<form class="research"  action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded" autocomplete="off">
					<input name="searchbar" class="champ" type="text" placeholder="Recherche"/>
					<button name="subsearch" class="champ" type="submit" id="boutoncache"><i class="fas fa-search"></i></button>
				</form>
                <?php include('inc/connexionHead.inc.php')?>
			</div>
		</section>
        <section class="beequote">
            <?php
            $randquote = $quoteRepository->getARandomQuote($message);
            foreach($randquote as $quote){?>
            <span class="beequote"><?php echo $quote->quote; ?></span>
            <?php }?>
        </section>
	</header>
	<?php include('inc/nav.inc.php') ?>
	<main>
        <?php
        if(!isset($_POST['searchbar'])){
        ?>
		<h3>Derniers Projets</h3>
		<section class="projetColec">
            <?php
            $listProjet = $projetRepository->getAllProject($message);
            foreach($listProjet as $projet) {
            ?>
           <?php include('inc/vignetteProjet.inc.php')?>
            <?php } ?>
		</section>
        <h3>Projets bientôt financés</h3>
        <section class="projetColec">
            <?php
            $listProjet = $projetRepository->getAllProjectFin($message);
            foreach($listProjet as $projet) {
                ?>
                <?php include('inc/vignetteProjet.inc.php')?>
            <?php } ?>
        </section>
        <?php } ?>
        <?php include('inc/search.inc.php') ?>
	</main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>
