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
    <?php include('inc/bee.inc.php');?>
	<header>
		<h2><a href="index.php">You've got to <span class="bee">bee</span> kidding me</a></h2>
        <section id="cologin">
            <h1 class="hidden">Accueil Collect'or</h1>
            <div class="navlogin profilehead">
                <?php include('inc/connexionHead.inc.php')?>
            </div>
        </section>
    </header>
    <?php include('inc/nav.inc.php') ?>
	<main>
        <article class="stat" style="flex-direction:column;">
            <h2>Looking for an old project ?</h2>
            <form class="research"  action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded" autocomplete="off">
                <input name="searchbar" class="champ" type="text" placeholder="Recherche"/>
                <button name="subsearch" class="champ" type="submit" id="boutoncache"><i class="fas fa-search"></i></button>
            </form>
            <h3>Search result :</h3>
            <?php
            $pattern = "/DROP TABLE/i";
            if(isset($_POST['searchbar']) and preg_match($pattern, $_POST['searchbar']) != 1){
                $servername = 'localhost';
                $username = 'root';
                $password = 'sexydorian';
                $dbname = 'honeybee';

                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $query = "SELECT * FROM collector_challenge;";
                $query .= "SELECT * FROM collector_challenge WHERE title = '{$_POST['searchbar']}'";
                $result = $conn->multi_query($query);
                do{
                    if($result = $conn->store_result()){
                        while($row = $result->fetch_row()){
                            echo"<span>id: ". $row[0]. " - ". $row[1]. " - " .$row[2] . "</span><br>";
                        }
                    }else{
                        echo "<span>0 results </span>";
                    }
                    if ($conn->more_results()) {
                        printf("-----------------\n");
                    }
                }while ($conn->next_result());
            }
            if(isset($_POST['searchbar']) and preg_match($pattern, $_POST['searchbar']) == 1){
                echo"<span>Permission denied silly boy.</span>";
            }?>
        </article>
    </main>
    <?php include('inc/footer.inc.php')?>
</body>
</html>
}