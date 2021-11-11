<a href="indexProjet.php?id_projet=<?php echo $projet->id_projet ?>" class="lienprojet"><article class="projet_accueil">
        <img src="uploads/<?php echo $projet->illustration_apercu ?>" alt="Photo du projet" class="image_projet"/>
        <h4><?php echo $projet->intitule ?></h4>
        <div class="desc">
            <span><?php echo $membreRepository->getUsername($projet->id_membre, $message);?></span>
            <span><?php echo $categorieRepository->getCategorie($projet->id_categorie, $message);?></span>
        </div>
        <div class="desc">
            <span><?php
                if($participationRepository->getAllParticipationForP($projet->id_projet, $message) == NULL){
                    echo 0;
                }else{
                    echo $participationRepository->getAllParticipationForP($projet->id_projet, $message);
                }
                ?>/<?php echo $projet->montant ?></span>
            <span><?php if($projet->taux_participation == NULL) {
                    echo 0;
                }else{
                    echo $projet->taux_participation;
                }?>%</span>
        </div>
        <span class="echeance"><?php echo $projet->date_echeance ?></span>
    </article></a>