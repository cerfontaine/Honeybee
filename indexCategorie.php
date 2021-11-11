<?php
require('php/sessionNone.inc.php');
require('php/function.inc.php');

use Categorie\CategorieRepository as CategorieRepository;
use Projet\ProjetRepository as ProjetRepository;
use Membre\MembreRepository as MembreRepository;
use Participation\ParticipationRepository as ParticipationRepository;


$message = '';
$categorieRepository = new CategorieRepository();
$projetRepository = new ProjetRepository();
$membreRepository = new MembreRepository();
$participationRepository = new ParticipationRepository();

if($_GET['id_categorie']) {
    $Categorie = $categorieRepository->getAllInfoC($_GET['id_categorie'], $message);
}else{
    header('Location: index.php');
}
?>
<?php
foreach($Categorie as $listInfo){
$title = $listInfo->categorie;
?>
<?php include('inc/head.er.inc.php') ?>
<body>
<header>
    <h2><a href="index.php">COLLECT'OR</a></h2>
    <section id="cologin">
        <div class="navlogin">
            <form class="research"  action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded" autocomplete="off">
                <input name="searchbar" class="champ" type="text" placeholder="Recherche"/>
                <button name="subsearch" class="champ" type="submit" id="boutoncache"><i class="fas fa-search"></i>
                </button>
            </form>
            <?php include('inc/connexionHead.inc.php') ?>
        </div>
    </section>
</header>
<?php include('inc/nav.inc.php') ?>
<main>
    <h1><?php echo $listInfo->categorie; ?></h1>
    <section class="projetColec">
        <?php
        $listProjet = $projetRepository->getAllProjectByCat($_GET['id_categorie'], $message);
        foreach($listProjet as $projet) {
            ?>
            <?php include('inc/vignetteProjet.inc.php')?>
        <?php } ?>
    </section>
    <?php include('inc/search.inc.php') ?>
</main>
<?php
}
    ?>
<?php include('inc/footer.inc.php')?>
</body>
</html>
