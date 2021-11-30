<?php
require('php/session.inc.php');
require('php/function.inc.php');

use Type\TypeRepository as TypeRepository;
use Categorie\CategorieRepository as CategorieRepository;
use Projet\Projet as Projet;
use Projet\ProjetRepository as ProjetRepository;

$message='';
$nomFichier = '';
$projetRepository = new ProjetRepository();
$categorie = new CategorieRepository();
$type = new TypeRepository();

$cat = $categorie->getAllcategorie($message);
$typ = $type->getAllInfo($message);

if(isset($_POST['create'])){
    $add = addApercu($_FILES['avatar'], $nomFichier, $message);
    $notEmpty = isNotEmpty($_POST, $message);
    $isLuhn = isLunh($_POST['visa'], $message);

    if($add && $notEmpty && $isLuhn){
        $projet = new Projet();

        $projet->intitule=htmlentities($_POST['nomProjet']);
        $projet->description=htmlentities($_POST['description']);
        $projet->date_echeance=htmlentities($_POST['echeance']);
        $projet->montant=htmlentities($_POST['dem']);
        $projet->montant_min=htmlentities($_POST['mini']);
        $projet->illustration_apercu=$nomFichier;
        $projet->carte_visa=htmlentities($_POST['visa']);
        $projet->nom_visa=htmlentities($_POST['tvisa']);
        $projet->id_membre=$_SESSION['id'];
        $projet->id_categorie=htmlentities($_POST['categorie']);
        $projet->id_type_part=htmlentities($_POST['participation']);

        if($projetRepository->storeProjet($projet, $message)){
            header('location: indexCreationProjet.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<title>Création de votre projet</title>
    <link rel="stylesheet" type="text/css" href="CSS/collector.css">
    <link href="https://fonts.googleapis.com/css?family=Sonsie+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
    <link rel="icon" href="img/icon.gif">
</head>
<body>
	<header>		
		<h2><a href="index.php">You've got to <span class="bee">bee</span> kidding me</a></h2>
		<section class="cologin">
			<div class="navlogin profilehead">
                <?php include('inc/connexionHead.inc.php')?>
			</div>	
		</section>
	</header>
	<main>
        <?php if (!empty($message)) { echo "<div class='test'><span>$message</span></div>"; } ?>
        <form autocomplete="off" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
            <fieldset class="login">
                <h1>Création de projet</h1>
                 <label class="title">Nom de votre projet</label><input id="nomProjet" name="nomProjet" type="text" value="<?php if (isset($_POST['nomProjet'])){echo $_POST['nomProjet'];} ?>" required autofocus>
                 <label class="title">Catégorie</label><select id="categorie" name="categorie">
                    <option value selected>-----</option>
                    <?php
                    foreach($cat as $listCat) {
                    ?>
                    <option value="<?php echo $listCat->id_categorie?>"><?php echo $listCat->categorie?></option>
                    <?php } ?>
                 </select>
                 <label class="title">Description</label><textarea id="message" name="description" rows="3" cols="20" required></textarea>
                 <label class="title">Échéance</label><input type="date" id="echeance" name="echeance" min="<?php echo date('Y-m-d'); ?>" value="<?php if (isset($_POST['echeance'])){echo $_POST['echeance'];} ?>" required>
                 <label class="title">Type de participation</label><select id="participation" name="participation" required>
                    <option selected value>-----</option>
                    <?php
                    foreach($typ as $listTyp) {
                    ?>
                    <option value="<?php echo $listTyp->id_type_part?>"><?php echo $listTyp->libelle?></option>
                    <?php } ?>
                 </select>
                 <label class="title">Montant demandé</label><input id="dem" name="dem" type="number" min="1" value="<?php if (isset($_POST['dem'])){echo $_POST['dem'];} ?>" required>
                 <label class="title">Montant minimum de participation</label><input id="mini" name="mini" type="number" min="1" value="<?php if (isset($_POST['mini'])){echo $_POST['mini'];} ?>" required>
                 <label class="title">Visa</label><input id="visa" name="visa" type="text" pattern="^-?\d{16}$" value="<?php if (isset($_POST['visa'])){echo $_POST['visa'];} ?>" required>
                 <label class="title">Titulaire de la Visa</label><input id="tvisa" name="tvisa" type="text" value="<?php if (isset($_POST['tvisa'])){echo $_POST['tvisa'];} ?>" required>
                 <label class="title">Image illustrant votre projet</label><input id="avatar" type="file" name="avatar" accept=".png, .jpeg, .jpg, .gif" required>
                 <input type="submit" name="create" value="Créer le projet">
            </fieldset>
        </form>
	</main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>