<?php
if(isset($_SESSION['username'])){ ?>
    <ul>
        <li class="menutop"><a href="#"><i class="fas fa-user"></i> <?php echo ucfirst($username); ?></a>
            <ul class="menubottom">
                <li><a href="indexProfile.php">Mon profil</a></li>
                <li><a href="indexCreationProjet.php">Lancer un projet</a></li>
                <?php if(isset($_SESSION['est_admin']) and $_SESSION['est_admin'] == 1) {?>
                <li><a href="indexAdmin.php">Admin Panel</a></li>
                <?php } ?>
                <li>
                    <form method="post" action="index.php" class="comenu">
                        <button name="logout"><i class="fas fa-sign-out-alt"></i> Déconnexion</button>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
<?php } ?>
<?php if(!isset($_SESSION['username'])){ ?>
    <a href="indexLogin.php" class="header_login"><i class="fas fa-sign-in-alt"></i> Se connecter</a>
<?php } ?>


