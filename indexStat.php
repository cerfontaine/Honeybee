<?php
require('php/sessionNone.inc.php');
require('php/function.inc.php');

use Categorie\CategorieRepository as CategorieRepository;
use Projet\ProjetRepository as ProjetRepository;
use Participation\ParticipationRepository as ParticipationRepository;

$title='Statistiques';
$message='';
$categorieRepository = new CategorieRepository();
$projetRepository = new ProjetRepository();
$participationRepository = new ParticipationRepository();
?>
<?php include('inc/head.er.inc.php');?>
<body>
	<header>
		<h2><a href="index.php">COLLECT'OR</a></h2>
        <section id="cologin">
            <h1 class="hidden">Accueil Collect'or</h1>
            <div class="navlogin profilehead">
                <?php include('inc/connexionHead.inc.php')?>
            </div>
        </section>
    </header>
    <?php include('inc/nav.inc.php') ?>
    <?php $allCategorie = $categorieRepository->getStatCategorie($message); ?>
	<main>
        <?php
        $longueur = sizeof($allCategorie);
        ?>
		<h1 class="space">Statistiques</h1>
        <article class="stat">
            <section class="graph">
                <span class="json">
            {"type":"bar"
            ,"width":400
            ,"height":400
            ,"datasets":
                [{
                    "label": "Nombre de projet(s)",
                    "data": [<?php for($i=0; $i<$longueur; $i++) {
                        if ($i == $longueur - 1) {
                            echo $allCategorie[$i]['compteur'];
                        } else {
                            echo $allCategorie[$i]['compteur'] . ",";
                        }
                    }?>]
                }],
            "dataLabel": [<?php for($i=0; $i<$longueur; $i++) {
                        if ($i == $longueur - 1) {
                            echo "\"" . $allCategorie[$i]['categorie'] . "\"";
                        } else {
                            echo "\"" . $allCategorie[$i]['categorie'] . "\"" . ",";
                        }
                    }?>]
            }
        </span>
                <div class="graph"></div>
            </section>

            <section class="graph">
                <span class="json">
            {"type":"bar"
            ,"width":400
            ,"height":400
            ,"datasets":
                [{
                    "label": "Montant en €",
                    "data": [<?php echo $participationRepository->getStatParticipation($message)?>]
                }],
            "dataLabel": ["Montant total récolté"]
            }
        </span>
                <div class="graph"></div>
            </section>
            <span>Collect'or c'est :<br><br>- <?php echo $projetRepository->getStatProjet($message);?> projets.<br>- <?php echo $projetRepository->getStatProjetFinance($message);?> projets financés avec succès.<br>- Une communauté très active<br>- Probablement le meilleur moyen pour vous de financer votre projet !</span>
        </article>
    </main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>
}