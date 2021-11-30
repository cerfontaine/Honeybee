<?php
require('php/session.inc.php');
require('php/function.inc.php');

use Membre\Membre as Membre;
use Membre\MembreRepository as MembreRepository;
use Projet\ProjetRepository as ProjetRepository;
use Categorie\CategorieRepository as CategorieRepository;
use Participation\ParticipationRepository as ParticipationRepository;


$message='';
$nom_fichier='';
$membreRepository = new MembreRepository();
$categorieRepository = new CategorieRepository();
$projetRepository = new ProjetRepository();
$participationRepository = new ParticipationRepository();

$membre = $membreRepository->getAllInfo($_SESSION['username'], $message);

$title = 'Mon profil';

if(isset($_POST['etape'])) {
    switch ($_POST['etape']) {
        case 1:
            $exist = $membreRepository->existsInDb($_POST['pseudo'], $message);
            if($exist){
                $membreRepository->updateLogin($_SESSION['id'], $_POST['pseudo'], $message);
                setcookie("PHPSESSID", "", time()-3600, "/");
                session_destroy();
                header('location: index.php ');
            }
            break;
        case 2:
            $equalpass = passwordEqual($_POST['mdp'], $_POST['mdps'], $message);
            if ($equalpass) {
                $membreRepository->updatePassword($_POST['mdp'], $_SESSION['id'], $message);
                $succes = '1';
            }
            break;
        case 3:
            $notempty = isNotEmpty($_POST, $message);
            $mailvalid = MailIsValid($_POST['mail'], $message);
            if($notempty && $mailvalid){
                $objetMembre = new Membre();

                $objetMembre->prenom=htmlentities($_POST['prenom']);
                $objetMembre->nom=htmlentities($_POST['nom']);
                $objetMembre->tel=htmlentities($_POST['téléphone']);
                $objetMembre->courriel = htmlentities($_POST['mail']);

                $membreRepository->updatePerso($objetMembre, $_SESSION['id'], $message);
                $succes = '1';

            }
            break;
        case 4:
            $notempty = isNotEmpty($_POST, $message);
            if($notempty) {
                $objetMembre = new Membre();

                $objetMembre->adresse_rue = htmlentities($_POST['rue']);
                $objetMembre->adresse_num = htmlentities($_POST['numero']);
                $objetMembre->adresse_code = htmlentities($_POST['code']);
                $objetMembre->adresse_ville = htmlentities($_POST['ville']);
                $objetMembre->adresse_pays = htmlentities($_POST['pays']);

                $membreRepository->updateLocalisation($objetMembre, $_SESSION['id'], $message);
                $succes = '1';
            }
            break;
        case 5:
            $isLuhn = isLunh($_POST['visa'], $messagz);
            if($isLuhn){
                $membreRepository->updateVisa($_POST['visa'], $_SESSION['id'], $message);
                $succes = '1';
            }
            break;
        case 6:
            $add = addPicture($_FILES['avatar'], $nom_fichier,$message);
            if($add) {
                $avatarNow = 'uploads/' . $membreRepository->getAvatar($_SESSION['id'], $message);
                if(file_exists($avatarNow)){
                    unlink($avatarNow);
                }
                $membreRepository->updateAvatar($nom_fichier, $_SESSION['id'], $message);
                $succes = '1';
            }
            break;
    }
}
if(isset($_POST['confirmDelete'])){
    $count = $membreRepository->checkForDelete($_SESSION['id'], $message) + $membreRepository->checkForDelete2($_SESSION['id'], $message);
    if($count == 0 ){
        $membreRepository->deleteProfile($_SESSION['id'], $message);
        setcookie("PHPSESSID", "", time()-3600, "/");
        session_destroy();
        header('location: indexSucces.php ');

    }else{
        $membreRepository->updateDesactive($_SESSION['id'], $message);
        setcookie("PHPSESSID", "", time()-3600, "/");
        session_destroy();
        header('location: indexSucces.php ');
    }
}
?>
<?php include('inc/head.er.inc.php')?>
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
        <nav class="navprojet navprofile">
            <form class="projet" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                <ul>
                    <li>
                        <button class="projet" name="profil"><i class="fas fa-user"></i> Mon profil</button>
                    </li>
                    <li>
                        <button class="projet" name="modifier"><i class="fas fa-user-edit"></i> Modifier mon profil</button>
                    </li>
                    <li>
                        <button class="projet" name="participation"><i class="fas fa-columns"></i> Mes participations</button>
                    </li>
                    <li>
                        <button class="projet" name="projets"><i class="fas fa-project-diagram"></i> Mes projets</button>
                    </li>
                </ul>
            </form>
        </nav>
        <?php
        foreach($membre as $listInfo) {
            ?>
        <!-- Mon profil -->
        <?php if((!isset($_POST['projets']) and !isset($_POST['deleteProfile']) and !isset($_POST['modifier']) and !isset($_POST['change']) and !isset($_POST['Modify']) and !isset($_POST['participation'])) OR (isset($_POST['profil']) AND (!isset($_POST['projets']) and !isset($_POST['modifier']) and !isset($_POST['change']) and !isset($_POST['deleteProfile']) and !isset($_POST['Modify']) and !isset($_POST['participation'])))) { ?>
        <article>
                <h1>Mon profil</h1>
                <article id="topprofile">
                    <img src="uploads/<?php echo $listInfo->avatar; ?>" alt="Photo de profil" class="profilepic">
                </article>
                <section>
                    <article>
                        <div class="profile">
                            <span class="profilespan catprofile">Informations de connexion</span>

                            <span class="profilespan">Nom d'utilisateur: <?php echo $listInfo->login; ?></span>
                            <span class="profilespan">Email: <?php echo $listInfo->courriel; ?></span>

                            <span class="profilespan catprofile">Informations personnelles</span>

                            <span class="profilespan">Nom Complet: <?php echo $listInfo->nom; ?> <?php echo $listInfo->prenom; ?> </span>
                            <span class="profilespan">Numéro de téléphone: <?php echo $listInfo->tel; ?></span>

                            <span class="profilespan catprofile">Informations de localisation</span>

                            <span class="profilespan">Rue: <?php echo $listInfo->adresse_rue; ?></span>
                            <span class="profilespan">N°/Boîte: <?php echo $listInfo->adresse_num; ?></span>
                            <span class="profilespan">Code postal: <?php echo $listInfo->adresse_code; ?></span>
                            <span class="profilespan">Ville: <?php echo $listInfo->adresse_ville; ?></span>
                            <span class="profilespan">Pays: <?php echo $listInfo->adresse_pays; ?></span>

                            <span class="profilespan catprofile">Informations financières</span>
                            <span class="profilespan">Carte Visa: <?php echo $listInfo->carte_VISA; ?></span>
                        </div>
                    </article>
                </section>
                <div class="modifsup">
                    <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                        <button class="projet profile" type="submit" name="deleteProfile"><i class="fas fa-trash"></i> Supprimer mon profil</button>
                    </form>
                </div>

        </article>
        <?php } ?>
        <?php if(isset($_POST['deleteProfile'])){?>
            <article>
                <h1>Confirmez-vous la suppression de votre profil ? </h1>
                <span class="profilespan catprofile">Si vous n'avez pas porté ou soutenu un projet, votre compte sera supprimé.</span>
                <span class="profilespan catprofile">Si non, ce dernier sera simplement désactivé.</span>
                <div class="modifsup">
                    <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                        <button class="projet profile" type="submit" name="confirmDelete"><i class="fas fa-check"></i> Confirmer</button>
                        <button class="projet profile" type="submit" name="refuseDelete"><i class="fas fa-times"></i> Refuser</button>
                    </form>
                </div>
            </article>
        <?php }?>

        <!-- Modification Profil -->
        <?php if(isset($_POST['modifier']) or isset($_POST['change'])) {?>
            <?php if(!isset($_POST['Modify'])) { ?>
                <?php if(isset($succes)) {?>
                    <article class="success">
                        <span><i class="fas fa-check"></i> Des changements ont été apporté avec succès à votre profil, vous allez être redirigé... </span>
                        <meta http-equiv="refresh" content="2">
                    </article>
                <?php }else{ ?>
            <h1>Modification du profil</h1>
            <section class="modif">
                <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST">
                    <button class="projet profile" type="submit" name="Modify" value="1"> Modifier mon pseudo/login</button>
                    <button class="projet profile" type="submit" name="Modify" value="2"> Modifier mon mot de passe </button>
                    <button class="projet profile" type="submit" name="Modify" value="3"> Modifier mes informations personnelles</button>
                    <button class="projet profile" type="submit" name="Modify" value="4"> Modifier mes informations de localisation</button>
                    <button class="projet profile" type="submit" name="Modify" value="5"> Modifier mes informations financières</button>
                    <button class="projet profile" type="submit" name="Modify" value="6"> Modifier mon avatar</button>
                </form>
            </section>
                    <?php }?>
            <?php } ?>
        <?php } ?>

        <?php if(isset($_POST['Modify'])){?>
            <?php if($_POST['Modify'] == 1) {?>
                <h1>Modification des informations de connexion</h1>
                <section class="modif">
                    <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                        <label class="title">Nom d'utilisateur</label><input id="pseudo" name="pseudo" type="text" value="<?php echo $listInfo->login ?>" required autofocus>
                        <input type="hidden" name="etape" value="1">
                        <input type="submit" name="change" value="Modifier">
                    </form>
                </section>
            <?php }?>
            <?php if($_POST['Modify'] == 2) {?>
                <h1>Modification du mot de passe</h1>
                <section class="modif">
                    <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                        <label class="title">Mot de passe</label><input id="mdp" name="mdp" type="password" required>
                        <label class="title">Retapez le mot de passe</label><input type="password" id="mdps" name="mdps" required>
                        <input type="hidden" name="etape" value="2">
                        <input type="submit" name="change" value="Modifier">
                    </form>
                </section>
            <?php }?>
            <?php if($_POST['Modify'] == 3) {?>
                <h1>Modification des informations personnelles</h1>
                <section class="modif">
                    <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                        <label class="title">Nom</label><input id="nom" name="nom" type="text" value="<?php echo $listInfo->nom ?>" required>
                        <label class="title">Prénom</label><input id="prenom" name="prenom" type="text" value="<?php echo $listInfo->prenom ?>" required>
                        <label class="title">Email</label><input id="mail" name="mail" type="email" value="<?php echo $listInfo->courriel ?>" required>
                        <label class="title">Numéro de téléphone</label><input id="téléphone" name="téléphone" type="text" value="<?php echo $listInfo->tel ?>" required>
                        <input type="hidden" name="etape" value="3">
                        <input type="submit" name="change" value="Modifier">
                    </form>
                </section>
            <?php }?>
            <?php if($_POST['Modify'] == 4) {?>
                <h1>Modification des informations de localisation</h1>
                <section class="modif">
                    <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                        <label class="title">Rue</label><input id="rue" name="rue" type="text" value="<?php echo $listInfo->adresse_rue ?>" required>
                        <label class="title">N°/Boîte</label><input id="numero" name="numero" type="text" value="<?php echo $listInfo->adresse_num ?>" required>
                        <label class="title">Code postal</label><input id="code" name="code" type="text" value="<?php echo $listInfo->adresse_code ?>" required>
                        <label class="title">Ville</label><input id="ville" name="ville" type="text" value="<?php echo $listInfo->adresse_ville ?>" required>
                        <label class="title">Pays</label><input id="pays" name="pays" type="text" value="<?php echo $listInfo->adresse_pays ?>" required>
                        <input type="hidden" name="etape" value="4">
                        <input type="submit" name="change" value="Modifier">
                    </form>
                </section>
            <?php }?>
            <?php if($_POST['Modify'] == 5) {?>
                <h1>Modification des informations financières</h1>
                <section class="modif">
                    <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                        <label class="title">Carte Visa</label><input id="visa" name="visa" type="text" pattern="^-?\d{16}$" value="<?php echo $listInfo->carte_VISA ?>" required>
                        <input type="hidden" name="etape" value="5">
                        <input type="submit" name="change" value="Modifier">
                    </form>
                </section>
            <?php }?>
            <?php if($_POST['Modify'] == 6) {?>
                <h1>Modification de votre avatar</h1>
                <section class="modif">
                    <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
                        <label class="title">Votre avatar</label><input type="file" id="avatar" name="avatar" accept=".png, .jpeg, .jpg, .gif" required>
                        <input type="hidden" name="etape" value="6">
                        <input type="submit" name="change" value="Modifier">
                    </form>
                </section>
            <?php }?>
        <?php } ?>

        <!-- Mes projets -->
        <?php if(isset($_POST['projets'])) { ?>
        <h1>Mes projets</h1>
        <section class="projetColec">
            <?php
            $listProjet = $projetRepository->getProjetByIdMember($_SESSION['id'], $message);
            foreach($listProjet as $projet) {
                ?>
                <?php include('inc/vignetteProfile.inc.php')?>
            <?php } ?>
        </section>
        <?php } ?>

        <!-- Mes participations -->
        <?php if(isset($_POST['participation'])) { ?>
            <h1>Mes participations</h1>
            <section class="projetColec">
                <?php
                $listParticipation = $participationRepository->getParticipationForMember($_SESSION['id'], $message);
                foreach($listParticipation as $participation){
                ?>
                    <?php
                    $listProjet = $projetRepository->getAllInfoP($participation->id_projet, $message);
                    foreach($listProjet as $projet) {
                        ?>
                        <?php include('inc/vignetteParticipation.inc.php')?>
                    <?php } ?>
                <?php } ?>
            </section>
        <?php } ?>

        <?php } ?>
	</main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>