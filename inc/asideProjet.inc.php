<aside class="asideprojetbis">
    <section class="chiffresec">
        <article class="chiffres">
            <span class="montantreco">22 458€</span>
            <span class="montanttotal">raised out of 30 000€</span>
        </article>
    </section>
    <section class="echeanceprojet">
        <span class="echeancespan">Il reste 2 heures</span>
    </section>
    <section class="whopropose">
        <img src="img/projet_soon/dargaud.jpg" alt="Photo de profil" class="createurprofile">
        <span class="createur">Dargaud</span>
    </section>
    <section class="whosupport">
        <label class="contributeur">Contributeurs</label>
        <span class="montantreco">587 <i class="fas fa-users"></i></span>
    </section>
    <?php if(isset($_SESSION['username'])) {?>
    <section class="support">
        <a href="indexSoutenir.php?id_projet=<?php echo $_GET['id_projet']?>" class="liensoutien lienprofil">Soutenir ce projet</a>
    </section>
    <?php } ?>
</aside>




