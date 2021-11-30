<?php if(isset($_POST['searchmember'])) {
$search = htmlentities($_POST['searchmember']);?>
<h3 style="width:100%;">Research result for "<?php echo $search?>"</h3>
<?php
$membre = $membreRepository->memberResearch($_POST['searchmember'], $message);
foreach($membre as $listInfo) {
?>
<article class="projet_accueil">
    <img src="uploads/<?php echo $listInfo->avatar; ?>" alt="Photo du projet" class="image_projet"/>
    <h4><?php echo $listInfo->login; ?></h4>
    <div class="desc">
        <span><?php echo $listInfo->nom; ?> <?php echo $listInfo->prenom; ?></span>
    </div>
    <div class="desc">
            <span><?php echo $listInfo->courriel; ?></span>
    </div>
    <span class="echeance">Status : <b><?php
            if($listInfo->est_desactive == 0){
                echo "ENABLED";
            }else{
                echo "DISABLED";
            }?>
            </b></span>
    <form class="projet" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <input name="id_member" type="hidden" value="<?php echo $listInfo->id_membre ?>">
        <button type="submit" name="enadis" class="projet" value="0"><i class="fas fa-check"></i> Enable User</button>
        <button type="submit" name="enadis" class="projet" value="1"><i class="fas fa-times"></i> Disable User</button>
    </form>
</article>
<?php } ?>
<?php } ?>