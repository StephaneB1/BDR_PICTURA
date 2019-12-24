<ul class="menu-h">
    <li style="float: left;"><a href='index.php'>PICTURA</a></li>
    <?php
    $isLoggedIn = checkIfLoggedIn();

    if ($isLoggedIn)
        echo "
    <li class=\"dropdown\" style=\"float: left;\">
        <a>Mes commu'<i class='material-icons'>keyboard_arrow_down</i></a>
		<div class='dropdown-content'>
            <a href=''>Communauté 1</a>
			<a href=''>Communauté 2</a>
			<a href=''>...</a>
        </div>
    </li>";
    ?>
    <li style="float: right;" class="dropdown">
        <?php
        include_once("php/include/func.php");

        if ($isLoggedIn) {
            echo "<a><i class='material-icons'>account_circle</i>" . $_SESSION["pseudo"] . "</a>
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