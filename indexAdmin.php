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
if(isset($_POST['enadis'])){
    $membreRepository->updateAccountStatus($_POST['id_member'], $_POST['enadis'], $message);
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
<?php include('inc/bee.inc.php');?>
    <header>
            <h2><a href="index.php">You've got to <span class="bee">bee</span> kidding me</a></h2>
            <section id="cologin">
                <div class="navlogin  profilehead">
                    <?php include('inc/connexionHead.inc.php')?>
                </div>
            </section>
        </header>
        <?php include('inc/nav.inc.php') ?>
    <main>
        <?php echo $message; ?>
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
        <?php if(!isset($_POST['categorie']) AND !isset($_POST['usersearch']) AND !isset($_POST['deleteCat']) and !isset($_POST['AddCat']) and !isset($_POST['searchmember']) and !isset($_POST['enadis'])) {?>
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
            <h1>Current log-in users</h1>
            <section class="searchresult">
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">Username | Lastseen</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $loginu = $membreRepository->getAllCurrentLog($message);
                    foreach($loginu as $loginp){?>
                        <tr>
                            <td><?php echo $loginp->login; ?></td>
                            <td><?php echo $loginp->last_seen; ?></td>
                        </tr>
                    <?php }?>
                    </tbody>
                </table>
            </section>
        <?php }?>
        <!-- Recherche utilisateur -->
        <?php if(isset($_POST['usersearch']) or isset($_POST['searchmember']) or isset($_POST['enadis'])) {?>
            <h1>Users research</h1>
            <section class="modif">
                <form class="modif"  action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded" autocomplete="off">
                    <input name="searchmember" class="modif" type="text" placeholder="Recherche" required/>
                    <button name="subsearch" class="champ" type="submit" id="boutoncache"><i class="fas fa-search"></i></button>
                </form>
            </section>
            <section class="searchresult">
                <?php if (!isset($_POST['searchmember'])){
                    include('inc/vignetteUser.php');
                }else{
                    include('inc/vignetteUserCheck.inc.php');
                }?>
            </section>
        <?php } ?>
    </main>
    <?php include('inc/footer.inc.php')?>
</body>