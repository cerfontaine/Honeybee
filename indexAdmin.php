<?php
require('php/session.inc.php');
require('php/function.inc.php');

use Membre\MembreRepository as MembreRepository;
use Projet\ProjetRepository as ProjetRepository;
use Categorie\CategorieRepository as CategorieRepository;
use Participation\ParticipationRepository as ParticipationRepository;


$message='';
$membreRepository = new MembreRepository();
$categorieRepository = new CategorieRepository();
$projetRepository = new ProjetRepository();
$participationRepository = new ParticipationRepository();

$title = 'Admin Panel';

if($_SESSION['est_admin']!=1){
    header('location: index.php');
}
if(isset($_POST['validate'])){
    $projetRepository->updateValidation($_POST['id_projet'], $_POST['validate'], $message);
}
if(isset($_POST['deleteCat'])){
    $categorieRepository->deleteCategorie($_POST['categorieToDelete'], $message);
}
if(isset($_POST['AddCat'])){
    $categorieRepository->addCategorie($_POST['nameCat'], $message);
}
?>
<?php include('inc/head.er.inc.php')?>
<body>
    <header>
            <h2><a href="index.php">COLLECT'OR</a></h2>
            <section id="cologin">
                <div class="navlogin  profilehead">
                    <?php include('inc/connexionHead.inc.php')?>
                </div>
            </section>
        </header>
        <?php include('inc/nav.inc.php') ?>
    <main>
        <nav class="navprojet navprofile">
            <form class="projet" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                <ul>
                    <li>
                        <button class="projet" name="validation"><i class="fas fa-check"></i> Projet à valider</button>
                    </li>
                    <li>
                        <button class="projet" name="categorie"><i class="fas fa-bars"></i> Gestion des catégories</button>
                    </li>
                    <li>
                        <button class="projet" name="usersearch"><i class="fas fa-user-tie"></i> Recherche utilisateur</button>
                    </li>
                </ul>
            </form>
        </nav>
        <!-- Projet à valider -->
        <?php if(!isset($_POST['categorie']) AND !isset($_POST['usersearch']) AND !isset($_POST['deleteCat']) and !isset($_POST['AddCat']) and !isset($_POST['searchmember'])) {?>
            <h1>Projets à valider</h1>
            <section class="projetColec">
                <?php
                $listProjet = $projetRepository->getProjetToValidate($message);
                foreach($listProjet as $projet) {
                    ?>
                    <?php include('inc/vignetteValidation.inc.php')?>
                <?php } ?>
            </section>
        <?php }?>
        <!-- Gestion des catégories -->
        <?php if(isset($_POST['categorie']) OR isset($_POST['deleteCat']) or isset($_POST['AddCat'])) {?>
            <h1>Gestion des catégories</h1>
            <section class="modif">
                <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                    <label class="title"><i class="fas fa-plus"></i>Ajouter une catégorie</label><input type="text" name="nameCat" required>
                    <input type="submit" value="Ajouter" name="AddCat">
                </form>
            </section>
            <?php
            $categorie = $categorieRepository->getDeletableCategorie($message);
            ?>
            <section class="modif">
                <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                    <label class="title"><i class="fas fa-minus"></i>Supprimer une catégorie</label><select id="categorie" name="categorieToDelete" required>
                        <option value selected>-----</option>
                        <?php
                        foreach($categorie as $listCat) {
                            ?>
                            <option value="<?php echo $listCat->id_categorie;?>"><?php echo $listCat->categorie;?></option>
                        <?php } ?>
                    </select>
                    <input type="submit" value="Supprimer" name="deleteCat">
                </form>
            </section>
        <?php }?>
        <!-- Recherche utilisateur -->
        <?php if(isset($_POST['usersearch']) or isset($_POST['searchmember'])) {?>
            <h1>Recherche utilisateur</h1>
            <section class="modif">
                <form class="modif"  action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded" autocomplete="off">
                    <input name="searchmember" class="modif" type="text" placeholder="Recherche" required/>
                    <button name="subsearch" class="champ" type="submit" id="boutoncache"><i class="fas fa-search"></i></button>
                </form>
            </section>
            <section class="searchresult">
            <?php include('inc/userSearch.inc.php') ?>
            </section>
        <?php } ?>
    </main>
    <?php include('inc/footer.inc.php')?>
</body>
