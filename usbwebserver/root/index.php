<?php

/**
 * HEIG-VD
 * Authors: Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date: 20.12.2019
 * Summary: Home page of the application
 */

include_once("php/include/header.php");
include_once("php/include/dbConnect.php");

$db = new db;

$isLoggedIn = checkIfLoggedIn();

?>
<title>PICTURA</title>

</head>
<body>
<?php

include_once("php/include/menu-h.php");

?>

<div id="wrapper-content">
    <div class="col-1-4">
        <h2>Communautés</h2>
        <?php

        if ($isLoggedIn) {
            echo "<p>
            <a onclick=\"displayId('insertCommunityPopup', null)\">+ Créer</a>
        </p>";
        }

        ?>

        <?php
        $communities = $db->getAllCommunities();

        for ($i = 0; $i < count($communities); ++$i) {
            echo "
        <p>            
            <a href='community.php?n=" . htmlentities($communities[$i]["nom"]) . "' title='" . htmlentities($communities[$i]["detail"]) . "'>
            " . htmlentities($communities[$i]["nom"]) . "
            </a>
        </p>";
        }
        ?>
    </div>
    <div class="col-3-4">
        <h1>Bienvenue,</h1>
        <p>Accueil chaleureux, bla bla bla.</p>
        <h2>Liste des utilisateurs (debug):</h2>
        <?php

        $users = $db->getAllUserPseudos();

        for ($i = 0; $i < count($users); ++$i) {
            echo "<p><a href='profile.php?n=" . htmlentities($users[$i]["pseudo"]) . "'>" . htmlentities($users[$i]["pseudo"]) . "</a></p>";
        }

        ?>

    </div>
</div> <!-- End wrapper-content -->
<?php

if ($isLoggedIn) {
    createPopup("insertCommunityPopup", "
    <h1>Créer nouvelle communauté</h1>
        <form id='insertCommunityForm' name='insertCommunityForm' action='php/form/insertCommunityForm.php' method='post' enctype='multipart/form-data'>
            <p>Merci de saisir les informations concernants votre nouvelle communauté:</p>
            <!-- Name -->
            <p>
                <label for='name'>
                    <i class='material-icons'>label</i>
                </label>
                <input type='text' id='name' name='name' placeholder='Nom de la communauté*' pattern='[a-zA-Z0-9]{1,20}' required autofocus/>
            </p>

            <!-- Detail -->
            <p>
                <label for='detail'>
                    <i class='material-icons'>notes</i>
                </label>
                <textarea id='name' name='detail' placeholder='Description*' required></textarea>
            </p>
            
            <!-- Profile picture -->
            <p>
                <label>
                    <i class='material-icons'>photo</i>
                </label>
                <input name='files' type='file' placeholder='Photo de profil' accept='" . join(',', prefixStringArray(IMAGE_FORMATS, ".")) . "'/>
            </p>

            <p class='note'>*Obligatoires</p>
            <p><input type='submit' value='Créer'/></p>
        </form>");
}
include_once("php/include/footer.php");

?>
</body>
</html>