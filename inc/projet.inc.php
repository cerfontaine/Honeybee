<nav class="navprojet">
    <form class="projet" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="POST" enctype="application/x-www-form-urlencoded">
        <ul>
            <li>
                <button class="projet" name="accueil"><i class="fas fa-project-diagram"></i> Accueil</button>
            </li>
            <li>
                <button class="projet" name="news"><i class="fas fa-newspaper"></i> News</button>
            </li>
            <li>
                <button class="projet" name="commentaire" value="1"><i class="fas fa-comments"></i> Commentaires</button>
            </li>
        </ul>
    </form>
</nav>