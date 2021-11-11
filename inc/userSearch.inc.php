<?php if(isset($_POST['searchmember'])) {
    $search = htmlentities($_POST['searchmember']);?>
    <h3>Résultat de la recherche pour "<?php echo $search?>"</h3>
    <?php
    $membre = $membreRepository->memberResearch($_POST['searchmember'], $message);
    foreach($membre as $listInfo) {
    ?>
    <section class="searchprofile">
        <article id="topprofile">
            <img src="uploads/<?php echo $listInfo->avatar; ?>" alt="Photo de profil" class="profilepic">
        </article>
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
            </div>
        </article>
    </section>
<?php } ?>
<?php } ?>
