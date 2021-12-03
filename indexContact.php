<?php
require('php/function.inc.php');
use Team\Team as Team;
use Team\TeamRepository as TeamRepository;

$teamRepo = new TeamRepository();
$message = '';
$title='Contact';
if(isset($_POST['contacter'])){
    $notempty = isNotEmpty($_POST, $message);
    $mailok = MailIsValid($_POST['reponsemail'], $message);
    if($notempty && $mailok){
        envoyerMailContact($_POST['reponsemail'], $_POST['sujet'], $_POST['message']);
    }
}
?>
<?php include('inc/head.er.inc.php')?>
<body>
    <?php include('inc/bee.inc.php');?>
	<header>		
		<h2><a href="index.php">You've got to <span class="bee">bee</span> kidding me</a></h2>
	</header>
	<main>
        <section class="searchresult">
            <h2 style="width:100%;">Our Team</h2>
            <?php $team =$teamRepo->getAllInfo($message);
            foreach($team as $member){?>
                <article class="projet_accueil">
                    <img src="img/bee.gif" alt="Photo du projet" class="image_projet"/>
                    <h4><?php echo $member->name;?></h4>
                    <div class="desc">
                        <span><?php echo $member->title;?></span>
                    </div>
                    <div class="desc">
                        <span>Buzz</span>
                    </div>
                    <span class="echeance">Bumble bee power</span>
                </article>
            <?php }?>
        </section>
	</main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>