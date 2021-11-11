<a href="indexProjet.php?id_projet=<?php echo $participation->id_projet ?>" class="lienprojet"><article class="projet_accueil">
        <img src="uploads/<?php echo $projet->illustration_apercu ?>" alt="Photo du projet" class="image_projet"/>
        <h4><?php echo $projet->intitule ?></h4>
        <div class="desc">
            <span><?php echo $membreRepository->getUsername($projet->id_membre, $message);?></span>
            <span><?php echo $categorieRepository->getCategorie($projet->id_categorie, $message);?></span>
        </div>
        <div class="desc">
            <span><b>Participation de</b></span>
            <span><b><?php echo $participation->montant?>€</b></span>
        </div>
        <span class="echeance"><b>le <?php echo $participation->date_participation ?></b></span>
    </article></a>
