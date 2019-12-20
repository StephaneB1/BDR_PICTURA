<?php

include_once("php/include/func.php");
$isConnected = checkIfLoggedIn();

?>

<ul class="menu-h">
    <li style="float: left;"><a href='index.php'>Accueil</a></li>
    <li class="dropdown" style="float: left;">
            <?php

            //If user is connected -> display dropdown menu
            if($isConnected) {
                echo "
        <a>Événements<i class='material-icons'>keyboard_arrow_down</i></a>
		<div class='dropdown-content'>
            <a href='events.php'>Parcourir (tous)</a>
			<a href='myEvents.php'>Mes événements</a>
        </div>";
            } else {
                echo "
		<a href='events.php'>Événements</a>";			
			}

            ?>
        </div>
    </li>
    <li style="float: right;" class="dropdown">
        <?php

        if($isConnected){
            if(!empty($_SESSION["userPicture"])) {
                echo "<a><div class='userpicture small' style='background-image: url(files/".$_SESSION['userPicture'].");'></div>".$_SESSION["userName"]."</a>";

            } elseif(checkIfAdmin()) {
                echo "<a style='background-color: #23384D;' title='Session administrateur'>
                <i class='material-icons'>account_circle</i>
                ".$_SESSION["userName"]."</a>";
            } else {
                echo "<a><i class='material-icons'>account_circle</i>".$_SESSION["userName"]."</a>";
            }
            echo"<div class='dropdown-content' style='right: 0;'>
                <a href='profile.php'><i class='material-icons'>person_outline</i>Profil</a>
                <a href='php/form/logoutUserForm.php'><i class='material-icons'>exit_to_app</i>Déconnexion</a>
            </div>
            ";
        } else {
            echo "<a href='connexion.php'>Se connecter</a>
            ";
        }

        ?>
    </li>
</ul>