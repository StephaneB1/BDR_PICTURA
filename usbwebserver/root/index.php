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

//include_once("php/include/menu-h.php");

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="css/interface.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="css/picture.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" />

    <title>Pictura</title>
</head>

<body id="body" onLoad="initHomeFeed();">

    <div class="container" id="main_container">
        <!-- COMMUNITY PANEL -->
        <div class="leftpanel" id="community_panel">
            <img src="imgs/pictura_logo.png" style="width:80%; margin-top:10px; margin-bottom: 20px;" />
            <div class="title_container">
                Communities
                <div class="title_line" id="community_title_line"></div>
            </div>

			<?php
				$communities = $db->getAllCommunities();
				
				for ($i = 0; $i < count($communities); ++$i) {
				    echo "          
				    <a class='community_cell_container' href='community.php?n=" . htmlentities($communities[$i]["nom"]) . "' title='" . htmlentities($communities[$i]["detail"]) . "'>
				    	<div class='community_cell_icon'></div> " . htmlentities($communities[$i]["nom"]) . "
				    </a>";
				}
			?>

        </div>
        <!-- PICTURE FEED -->
        <div class="middlepanel" id="feed_panel">
            <div class="homeFeed" id="homeFeed"></div>
        </div>
        
        <!-- PROFILE PANEL -->
        <div class="rightpanel" id="user_panel">
            <div class="option_buttons_container">
                <button type="image" class="option_button" onClick="switchNightMode()">N</button>
                <button class="option_button" onClick=""></button>
                <button class="option_button" onClick=""></button>
                <button class="option_button" onClick=""></button>                
            </div>
        </div>
    </div>
</body>

<script src="js/home.js"></script>
<script src="js/interface.js"></script>

<!--<div id="wrapper-content">
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
</div>--> <!-- End wrapper-content -->
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