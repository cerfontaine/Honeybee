<?php
require_once('./php/function.inc.php');

use Categorie\CategorieRepository as CategorieRepository;

$categorie = new CategorieRepository();

$cat = $categorie->getAllcategorie($message);
?>

<nav>
    <ul>
        <?php
        foreach($cat as $listCat) {
            ?>
            <li><a href="indexCategorie.php?id_categorie=<?php echo $listCat->id_categorie; ?>" class="navi"><?php echo $listCat->categorie ?></a></li>
            <?php
        }
        ?>
    </ul>
</nav>
