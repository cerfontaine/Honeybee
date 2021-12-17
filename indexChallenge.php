<?php
require('php/function.inc.php');

use Projet\ProjetRepository as ProjetRepository;
use Categorie\CategorieRepository as CategorieRepository;
use Membre\MembreRepository as MembreRepository;
use Participation\ParticipationRepository as ParticipationRepository;
use Quote\Quote as Quote;
use Quote\QuoteRepository as QuoteRepository;

$categorieRepository = new CategorieRepository();
$projetRepository = new ProjetRepository();
$membreRepository = new MembreRepository();
$participationRepository = new ParticipationRepository();
$quoteRepository = new QuoteRepository();
$message = '';
$title = 'Challenge Time !';
?>
<?php include('inc/head.er.inc.php')?>
<body>
<?php /*include('inc/bee.inc.php');*/?>
<header>
    <h2><a href="index.php">You've got to <span class="bee">bee</span> kidding me</a></h2>
    <section id="cologin">
        <div class="navlogin">
            <form class="research"  action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded" autocomplete="off">
                <input name="searchbar" class="champ" type="text" placeholder="Recherche"/>
                <button name="subsearch" class="champ" type="submit" id="boutoncache"><i class="fas fa-search"></i></button>
            </form>
            <?php include('inc/connexionHead.inc.php')?>
        </div>
    </section>
    <section class="beequote">
        <?php
        $randquote = $quoteRepository->getARandomQuote($message);
        foreach($randquote as $quote){?>
            <span class="beequote"><?php echo $quote->quote; ?></span>
        <?php }?>
    </section>
</header>
<?php include('inc/nav.inc.php')?>
<main>
    <article class="stat" style="flex-direction:column;">
        <?php if (!empty($message)) { echo "<div class='test'><span>$message</span></div>"; } ?>
        <h1>More informations about the challenges</h1>
        <span class="descriptionprojet">Challenge 1 : Find a vulnerable input to SQL Injection (Hint: Old).</span>
        <span class="descriptionprojet">Challenge 2 : Try to find and display our secret. Insert it here.</span>
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded" autocomplete="off">
            <label><input name="secret" placeholder="Enter the secret" type="text"></label>
            <input type="submit" value="Submit">
        </form>
        <?php if(isset($_POST['secret']) and $_POST['secret']=="ILovePHP"){
            echo '<span class="descriptionprojet" style="font-weight: bold; text-align: center;">BRAVO CHAMPION !!!!!!!!!!!!!!</span>
<img src="img/bee.gif" alt="Photo du projet" class="image_projet"/>';
        }?>
        <span class="descriptionprojet">Challenge 3 : Insert yourself in the team database to be display on the team page.</span>
        <span class="descriptionprojet">Challenge 4 : Find XSS vulnerable input.</span>
        <span class="descriptionprojet">Challenge 5 : Try to retreive some information with that. </span>
    </article>
</main>
<?php include('inc/footer.inc.php')?>
</body>
</html>

