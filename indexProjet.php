<?php
require('php/sessionNone.inc.php');
require('php/function.inc.php');

use Projet\ProjetRepository as ProjetRepository;
use Membre\MembreRepository as MembreRepository;
use Commentaire\Commentaire as Commentaire;
use Commentaire\CommentaireRepository as CommentaireRepository;
use Categorie\CategorieRepository as CategorieRepository;
use Participation\ParticipationRepository as ParticipationRepository;
use News\News as News;
use News\NewsRepository as NewsRepository;

$message = '';
$nom_fichier='';
$projetRepository = new ProjetRepository();
$memberRepository = new MembreRepository();
$commentaireRepository = new CommentaireRepository();
$categorieRepository = new CategorieRepository();
$participationRepository = new ParticipationRepository();
$newsRepository = new NewsRepository();

if(isset($_POST['commenter'])) {
    $notEmpty = isNotEmpty($_POST, $message);

    if($notEmpty) {
        $commentaire = new Commentaire();

        $commentaire->commentaire=htmlentities($_POST['comment']);
        $commentaire->id_membre=htmlentities($_SESSION['id']);
        $commentaire->id_projet=htmlentities($_GET['id_projet']);

        $commentaireRepository->storeComment($commentaire, $message);
    }
}

if(isset($_POST['newser'])){
    if(!empty($_FILES['fichier'])){
        addFile($_FILES['fichier'], $nom_fichier, $message);
    }
    $news = new News;

    $news->intitule = htmlentities($_POST['intitule']);
    $news->description = htmlentities($_POST['description']);
    $news->url = $nom_fichier;
    $news->id_projet = htmlentities($_GET['id_projet']);

    $newsRepository ->storeNews($news, $message);
}

if($_GET['id_projet']){
    $comment = $commentaireRepository->getAllCommentP($_GET['id_projet'], $message);
    $projet = $projetRepository->getAllInfoP($_GET['id_projet'], $message);
    $title = $projetRepository->getProjectName($_GET['id_projet'], $message);
}else{
    header('Location: index.php');
}

if(isset($_POST['confirm'])) {
    $commentaireRepository->deleteComment($_POST['id_comment_verif'], $message);
    $succes = '1';
}

if(isset($_POST['confirmn'])) {
    $newsRepository->deleteNews($_POST['id_news_verif'], $message);
    $succes = '1';
}

if(isset($_POST['deleteParticipation'])) {
    $id_part = $participationRepository->getIdParticipation($_GET['id_projet'], $_SESSION['id'], $message);
    $participationRepository->deleteParticipation($id_part, $message);
    $projetRepository->updateTaux($_GET['id_projet'], $message);
}
if(isset($_POST['descriptionChange'])){
    $projetRepository->updateDescription($_GET['id_projet'], $_POST['descriptionChange'], $message);
    $succes = '1';
}
if(isset($_POST['prolongation'])){
    $projetRepository->updateEcheance($_GET['id_projet'], $_POST['echeanceChange'], $message);
    $succes = '1';
}
if(isset($_POST['confirmDelete'])){
    $participationRepository->deleteParticipationByP($_GET['id_projet'], $message);
    $newsRepository->deleteNewsByP($_GET['id_projet'], $message);
    $commentaireRepository->deleteComByP($_GET['id_projet'], $message);
    $projetRepository->deleteProjet($_GET['id_projet'], $message);
    $succes = '1';
}
?>
<?php include('inc/head.er.inc.php')?>
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
        <?php
        foreach($projet as $listInfo) {
            ?>
            <?php $membreInfo = $memberRepository->getSMTG('id_membre', $listInfo->id_membre, $message); ?>
            <section class="secprojetbis">
                <article class="artprojetbis">
                    <header class="headprojet">
                        <h1 class="titreprojet"><?php echo $listInfo->intitule; ?></h1>
                        <span class="headspan">by <?php echo $membreInfo->login ?> le <?php echo $listInfo->date_creation; ?></span>
                    </header>
                    <?php include('inc/projet.inc.php')?>

                    <!-- ACCUEIL -->
                    <?php if(!isset($_POST['commentaire']) AND !isset($_POST['confirmDelete']) AND !isset($_POST['news']) AND !isset($_POST['commenter']) AND !isset($_POST['deleteCom']) AND !isset($_POST['confirm']) AND !isset($_POST['refuse']) AND !isset($succes) AND !isset($_POST['deleteNews']) AND !isset($_POST['confirmn']) AND !isset($_POST['refusen']) and !isset($_POST['newser']) and !isset($_POST['deleteProjet']) and !isset($_POST['modifierProjet']) and !isset($_POST['Modifier']) and !isset($_POST['prolongation']) and !isset($_POST['prolongerProjet'])) {?>
                    <article class="mainprojet">
                        <img src="uploads/<?php echo $listInfo->illustration_apercu; ?>" alt="Photo du projet" class="projetimg"/>
                        <div class="divdesc">
                            <label class="projetdesclab">Description du projet</label>
                            <span class="spancategorie"><i class="fas fa-tag"></i> <?php echo $categorieRepository->getCategorie($listInfo->id_categorie, $message) ?></span>
                        </div>
                        <span class="descriptionprojet"><?php echo $listInfo->description; ?></span>
                    </article>
                    <?php } ?>

                    <!-- COMMENTAIRE -->
                    <?php if(isset($_POST['commentaire']) or isset($_POST['commenter']) or isset($_POST['deleteCom']) or isset($_POST['confirm']) or isset($_POST['refuse'])){?>
                        <!-- Success -->
                        <?php if(isset($succes)) {?>
                            <article class="success">
                                <span><i class="fas fa-check"></i> Votre commentaire a été supprimé avec succès ! Vous allez être redirigé... </span>
                                <meta http-equiv="refresh" content="2">
                            </article>
                        <?php }else{ ?>
                    <article class="mainprojet">
                        <h3 class="projeth">Commentaires</h3>
                    </article>
                    <article class="comment">
                        <ul>
                            <?php foreach($comment as $commentlist) { ?>
                                <li class="commentli">
                                    <div class="commentdiv">
                                        <header class="pseudoCom"><span><?php if($listInfo->id_membre == $commentlist->id_membre){?><i class="fas fa-certificate"></i><?php }?><?php echo $memberRepository->getUsername($commentlist->id_membre, $message)?></span></header>
                                        <p class="commentaire"><?php echo $commentlist->commentaire?></p>
                                        <footer class="dateCom">
                                            <span><?php echo $commentlist->date_modification?></span>
                                            <?php if(isset($_SESSION['id']) and $commentlist->id_membre == $_SESSION['id']) {?>
                                            <form class="deletecom" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                                                <button class="projet" name="deleteCom" value="Supprimer le commentaire"><i class="fas fa-trash"></i></button>
                                                <input type="hidden" name="id_comment" value="<?php echo $commentlist->id_comment?>">
                                            </form>
                                                <?php if(isset($_POST['deleteCom']) AND $_POST['id_comment'] == $commentlist->id_comment ) {?>
                                                    <form class="deletecom" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                                                        <label class="labelcom">Confirmer ? </label>
                                                        <input type="hidden" name="id_comment_verif" value="<?php echo $commentlist->id_comment?>">
                                                        <button class="projet" name="confirm" value="Confirmer"><i class="fas fa-check"></i></button>
                                                        <button class="projet red" name="refuse" value="Refuser"><i class="fas fa-times"></i></button>
                                                    </form>
                                                <?php } ?>
                                            <?php } ?>
                                        </footer>
                                    </div>
                                </li>
                            <?php } ?>

                            <?php if(isset($_SESSION['username'])) { ?>
                                <li class="commentli">
                                    <div class="commentdiv">
                                        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                                            <textarea name="comment"></textarea>
                                            <input type="submit" name="commenter" value="Commenter">
                                        </form>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </article>
                    <?php } ?>
                    <?php } ?>


                    <!-- Modif Projet -->
                    <?php if(isset($_POST['modifierProjet']) or isset($_POST['change'])){?>
                        <?php if(isset($succes)) {?>
                            <article class="success">
                                <span><i class="fas fa-check"></i> Description modifiée avec succès ! Vous allez être redirigé... </span>
                                <meta http-equiv="refresh" content="2">
                            </article>
                        <?php }else{ ?>
                    <article class="mainprojet">
                        <h3 class="projeth">Modification Projet</h3>
                    </article>
                    <section class="modif">
                        <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                            <label class="title">Description du projet</label><input id="description" name="descriptionChange" type="text" value="<?php echo $listInfo->description ?>" required>
                            <input type="submit" name="change" value="Modifier">
                        </form>
                    </section>
                        <?php } ?>
                    <?php }?>

                    <!-- Suppresion Projet -->
                    <?php if(isset($_POST['deleteProjet']) OR isset($_POST['confirmDelete'])) { ?>
                        <?php if(isset($succes)) {?>
                            <article class="success">
                                <span><i class="fas fa-check"></i> Projet supprimé avec succès vous allez être redirigé... </span>
                                <meta http-equiv="refresh" content="2;URL=index.php">
                            </article>
                        <?php }else{ ?>
                    <article class="mainprojet">
                        <h3 class="projeth">Êtes-vous sûr de vouloir supprimer le projet ?</h3>
                        <div class="modifsup">
                            <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                                <button class="projet" type="submit" name="confirmDelete"><i class="fas fa-check"></i> Confirmer</button>
                                <button class="projet" type="submit" name="refuseDelete"><i class="fas fa-times"></i> Refuser</button>
                            </form>
                        </div>
                    </article>
                            <?php } ?>
                    <?php } ?>

                    <!-- Prolongation Projet -->
                    <?php if(isset($_POST['prolongerProjet']) OR isset($_POST['prolongation'])){?>
                        <?php if(isset($succes)) {?>
                            <article class="success">
                                <span><i class="fas fa-check"></i> Votre projet a été prolongé avec succès! Vous allez être redirigé... </span>
                                <meta http-equiv="refresh" content="2">
                            </article>
                        <?php }else{ ?>
                    <article class="mainprojet">
                        <h3 class="projeth">Prolongation projet</h3>
                    </article>
                    <section class="modif">
                        <form class="modif" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                            <label class="title">Échéance</label><input type="date" id="echeance" name="echeanceChange" min="<?php echo date('Y-m-d'); ?>" required>
                            <input type="submit" name="prolongation" value="Prolonger">
                        </form>
                    </section>
                            <?php } ?>
                    <?php }?>

                    <!-- NEWS -->
                    <?php if(isset($_POST['news']) or isset($_POST['deleteNews']) or isset($_POST['confirmn']) or isset($_POST['refusen']) or isset($_POST['newser'])){?>
                        <!--succes -->
                    <?php if(isset($succes)) {?>
                        <article class="success">
                            <span><i class="fas fa-check"></i> Votre news a été supprimé avec succès ! Vous allez être redirigé... </span>
                            <meta http-equiv="refresh" content="2">
                        </article>
                    <?php }else{ ?>
                    <article class="mainprojet">
                        <h3 class="projeth">News</h3>
                    </article>

                    <article class="comment">
                        <ul>
                            <?php
                            $listNews = $newsRepository->getAllNewsForP($_GET['id_projet'], $message);
                            foreach($listNews as $news){
                            ?>
                                <li class="commentli">
                                    <div class="commentdiv">
                                        <header class="pseudoCom"><span><?php echo $news->intitule?></span></header>
                                        <p class="commentaire"><?php echo $news->description?></p>
                                        <?php if($news->url != '') {?>
                                        <a href="uploads/<?php echo $news->url?>" download><i class="fas fa-download"></i>Pièce jointe de la news</a>
                                        <?php }?>
                                        <footer class="dateCom">
                                            <span><?php echo $news->date_publication?></span>
                                            <?php if(isset($_SESSION['id']) and $membreInfo->id_membre == $_SESSION['id']) {?>
                                                <form class="deletecom" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                                                    <button class="projet" name="deleteNews" value="Supprimer la News"><i class="fas fa-trash"></i></button>
                                                    <input type="hidden" name="id_news" value="<?php echo $news->id_news?>">
                                                </form>
                                                <?php if(isset($_POST['deleteNews']) and $_POST['id_news'] == $news->id_news) {?>
                                                    <form class="deletecom" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                                                        <label class="labelcom">Confirmer ? </label>
                                                        <input type="hidden" name="id_news_verif" value="<?php echo $news->id_news?>">
                                                        <button class="projet" name="confirmn" value="Confirmer"><i class="fas fa-check"></i></button>
                                                        <button class="projet red" name="refusen" value="Refuser"><i class="fas fa-times"></i></button>
                                                    </form>
                                                <?php } ?>
                                            <?php } ?>
                                        </footer>
                                    </div>
                                </li>
                            <?php } ?>
                            <?php if(isset($_SESSION['username']) and $membreInfo->id_membre == $_SESSION['id']) { ?>
                                <li class="commentli">
                                    <div class="commentdiv">
                                        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="multipart/form-data">
                                            <label class="title">Intitulé</label><textarea name="intitule" required></textarea>
                                            <label class="title">Description</label><textarea name="description" required></textarea>
                                            <label class="title">Fichier (non requis)</label><input type="file" id="fichier" name="fichier" accept=".png, .jpeg, .jpg, .gif, .pdf">
                                            <input type="submit" name="newser" value="Publier">
                                        </form>
                                    </div>
                                </li>
                            <?php } ?>
                        </ul>
                    </article>
                    <?php } ?>
                    <?php } ?>

                </article>


                <!-- aside -->
                <aside class="asideprojetbis">
                    <section class="chiffresec">
                        <article class="chiffres">
                            <span class="montantreco">
                                <?php
                                if($participationRepository->getAllParticipationForP($_GET['id_projet'], $message) == NULL){
                                    echo 0;
                                }else{
                                    echo $participationRepository->getAllParticipationForP($_GET['id_projet'], $message);
                                }
                                    ?>€</span>
                            <span class="montanttotal">récoltés sur <?php echo $listInfo->montant?> €</span>
                        </article>
                    </section>
                    <section class="echeanceprojet">
                        <span class="echeancespan">Clôture le <?php echo $listInfo->date_echeance?></span>
                    </section>
                    <section class="whopropose">
                        <img src="uploads/<?php echo $membreInfo->avatar; ?>" alt="Photo de profil" class="createurprofile">
                        <span class="createur"><?php echo $membreInfo->login ?></span>
                    </section>
                    <section class="whosupport">
                        <label class="contributeur">Contributeurs</label>
                        <span class="montantreco"><?php echo $participationRepository->getAllParticipantForP($_GET['id_projet'], $message)?> <i class="fas fa-users"></i></span>
                    </section>
                    <?php if(isset($_SESSION['username']) and $listInfo->taux_participation<100) {?>
                        <?php if($participationRepository->siParticipation($_GET['id_projet'], $_SESSION['id'], $message) == 0){ ?>
                            <section class="support">
                                <a href="indexSoutenir.php?id_projet=<?php echo $_GET['id_projet']?>" class="liensoutien lienprofil">Soutenir ce projet</a>
                            </section>
                        <?php }?>
                        <?php if($participationRepository->siParticipation($_GET['id_projet'], $_SESSION['id'], $message) == 1){ ?>
                            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                                <button class="liensoutien lienprofil" name="deleteParticipation" type="submit">Supprimer la participation</button>
                            </form>
                        <?php }?>
                        <?php if($_SESSION['id'] == $listInfo->id_membre) { ?>
                            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                                <button class="liensoutien lienprofil" name="modifierProjet" type="submit"><i class="fas fa-edit"></i>Modifier mon projet</button>
                            </form>
                            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                                <button class="liensoutien lienprofil" name="deleteProjet" type="submit"><i class="fas fa-trash"></i>Supprimer mon projet</button>
                            </form>
                            <?php if($listInfo->est_prolonge == 0) {?>
                            <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
                                <button class="liensoutien lienprofil" name="prolongerProjet" type="submit"><i class="far fa-clock"></i>Prolonger mon projet</button>
                            </form>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </aside>
            </section>
            <?php
        }
        ?>
	</main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>