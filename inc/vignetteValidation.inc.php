<article class="projet_accueil"><a href="indexProjet.php?id_projet=<?php echo $projet->id_projet ?>" class="lienprojet">
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
        <span class="echeance">Statut : <b><?php
            if($projet->est_valide == NULL){
                echo "EN ATTENTE";
            }elseif($projet->est_valide == 0){
                echo "REFUSÉ";
            }else{
                echo "VALIDÉ";
            }?>
            </b></span>
    </a>
    <?php if($projet->est_valide != 1){?>
        <form class="projet" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
            <input name="id_projet" type="hidden" value="<?php echo $projet->id_projet ?>">
            <button type="submit" name="validate" class="projet" value="1"><i class="fas fa-check"></i> Valider</button>
            <button type="submit" name="validate" class="projet" value="0"><i class="fas fa-times"></i> Refuser</button>
        </form>
    <?php } ?>
</article>