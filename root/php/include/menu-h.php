<ul class="menu-h">
    <li style="float: left;"><a href='index.php'>Accueil</a></li>
    <li class="dropdown" style="float: left;">
            <?php

            echo "
        <a>Dropdown list<i class='material-icons'>keyboard_arrow_down</i></a>
		<div class='dropdown-content'>
            <a href='page1.php'>Page 1</a>
			<a href='page2.php'>Page 2</a>
        </div>";

            ?>
        <!-- </div> ? -->
    </li>
    <li style="float: right;" class="dropdown">
        <?php
            include_once("php/include/func.php");

            if(checkIfLoggedIn()) {
                echo "<a><i class='material-icons'>account_circle</i>".$_SESSION["pseudo"]."</a>
                <div class='dropdown-content' style='right: 0;'>
                <a href='profile.php'><i class='material-icons'>person_outline</i>Profil</a>
                <a href='php/form/logoutUserForm.php'><i class='material-icons'>exit_to_app</i>Déconnexion</a>
            </div>";
            } else {
                echo "<a href='connexion.php'>Se connecter</a>";
            }
        ?>
    </li>
</ul>