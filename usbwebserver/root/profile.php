<?php

/**
 * HEIG-VD
 * Authors: Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date: 20.12.2019
 * Summary: Profile of the current user
 */

include_once("php/include/header.php");
include_once("php/include/dbConnect.php");

$db = new db;

$isCurrentUser = false;
$isLoggedIn = checkIfLoggedIn();

if ($isLoggedIn && (empty($_GET["n"]) || $_GET["n"] == $_SESSION["pseudo"])) {
        //Display current user's profile
        $isCurrentUser = true;
        $user = $db->getUserByPseudo($_SESSION["pseudo"])[0];
} else if(!empty($_GET["n"])) {
    //Display other user's profile
    $user = $db->getUserByPseudo($_GET["n"])[0];
    if (empty($user)) {
        //Error: invalid user ID
        redirect(null);
    }
} else {
    redirect(null);
}
echo '<title>PICTURA - ' . htmlentities($user["pseudo"]) . '</title>';
?>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="/css/interface.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/profile.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/popup.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" />

    <title>Pictura</title>
</head>

<body>
    <!-- TOP BAR -->
    <div class="topPanel" id="topSearchPanel">
        <div>
            <button class="topPanelButton" id="openSideBarButton" onClick="openSidePanel()"></button>
            <img src="imgs/pictura_logo.png" style="height: 30px;" />
        </div>
        
        <div class="topPanelRight">
            <button class="topPanelButton" id="gridButton"></button>
            <button class="topPanelButton" id="nightmodeButton" onClick="switchNightMode()"></button>
        </div>

        <div class="shadow"></div>
    </div> 

    <!-- PROFILE PANEL -->
    <div class="leftpanel" id="community_panel">
        <?php
            echo '<div class="title_container" id="community_title_container">';
            
            // Titre
            if ($isCurrentUser) {   
                echo "My Profile";
            } else {
                echo htmlentities($user['pseudo']) . "'s Profile";
            } 

            echo '<div class="title_line" id="community_title_line"></div>
                </div>';

            // Pseudo
            echo "<div class='panel_text_container'>Username : <b>" . htmlentities($user['pseudo']) . "</b></div>";

            // Nom (si défini)
            if (!empty($user["nom"])) {
                echo "<div class='panel_text_container'>Surname : <b>" . htmlentities($user['nom']) . "</b></div>";
            }

            // Prénom (si défini)
            if (!empty($user["prenom"])) {
                echo "<div class='panel_text_container'>Name : <b>" . htmlentities($user['prenom']) . "</b></div>";
            }

            // Current user's privileges
            if ($isCurrentUser) {
                echo "<div class='panel_text_container'>Name : <b>" . htmlentities($user['email']) . "</b></div>";
                echo "<a href='editUser.php' class='panel_button'>Edit My Profile</a>";
                echo "<a href='php/form/deleteUserForm.php' class='panel_button' id='red_hover' onclick='return confirm(\"Do you really want to delete your account ?\n (this will remove all your pictures from PICTURA permanently.)\")'>
                        Delete my profile
                    </a>";
            }
        ?>
    </div>

<?php

include_once("php/include/footer.php");

?>
</body>

</html>
