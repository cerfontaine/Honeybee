<?php
if(isset($_POST['searchbar'])){
    $search = htmlentities($_POST['searchbar']);?>
    <h3>Search result for <?php echo $_POST['searchbar'];?></h3>
		<section class="projetColec">
            <?php
            $listProjet = $projetRepository->projectResearch($search, $message);
            foreach($listProjet as $projet) {
            ?>
			<a href="indexProjet.php?id_projet=<?php echo $projet->id_projet ?>" class="lienprojet"><article class="projet_accueil">
				<img src="uploads/<?php echo $projet->illustration_apercu ?>" alt="Photo du projet" class="image_projet"/>
				<h4><?php echo $projet->intitule ?></h4>
				<div class="desc">
                    <span><?php echo $membreRepository->getUsername($projet->id_membre, $message);?></span>
                    <span><?php echo $categorieRepository->getCategorie($projet->id_categorie, $message);?></span>
				</div>
				<div class="desc">
					<span>220/<?php echo $projet->montant ?></span>
					<span><?php echo $projet->taux_participation?>%</span>
				</div>
				<span class="echeance"><?php echo $projet->date_echeance ?></span>
			</article></a>
            <?php } ?>
		</section>
<?php } ?>
