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

if ($isLoggedIn && (empty($_GET["n"]) || $_GET["n"] == $_SESSION["pseudo"])) {
        //Display current user's profile
        $isCurrentUser = true;
        $user = $db->getUserByPseudo($_SESSION["pseudo"])[0];
} else if(!empty($_GET["n"])) {
    //Display other user's profile
    $user = $db->getUserByPseudo($_GET["n"])[0];
    if (empty($user)) {
        //Error: invalid user ID
        //redirect(null);
    }
} else {
    //redirect(null);
}

$searching = "";
if(!empty($_GET["s"]) && $_GET["s"] != "") {
    $searching = $_GET["s"];
}

?>
<title>PICTURA</title>

<?php

//include_once("php/include/menu-h.php");

?>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">

        <link rel="stylesheet" href="/css/interface.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="/css/picture.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="/css/popup.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" />

        <title>Pictura</title>

    <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body id="body" onload="init()">

    <div class="container" id="main_container">

        <!-- TOP BAR -->
        <?php include_once("php/include/topbar.php"); ?>

        <!-- COMMUNITY PANEL -->
        <div class="leftpanel" id="community_panel">
            <div class="title_container" id="community_title_container">
                Communities
                <div class="title_line" id="community_title_line"></div>
            </div>

			<?php
				$communities = $db->getAllCommunities();
				for ($i = 0; $i < count($communities); ++$i) {
				    echo "          
				    <a class='community_cell_container' href='community.php?n=" . htmlentities($communities[$i]["nom"]) . "' title='" . htmlentities($communities[$i]["detail"]) . "'>
				    	<div class='community_cell_icon' style='background-image: url(files/". htmlentities($communities[$i]["imageDeProfil"]) ."),  url(\"files/community_default.PNG\")'></div> " . htmlentities($communities[$i]["nom"]) . "
				    </a>";
				}
			?>
        </div>
		
        <!-- PICTURE FEED -->
        <div class="middlepanel" id="middle_panel">
            <div class="mainFeed" id="main_feed">
            <?php    
                if ($isLoggedIn) {
                    $feed = $db->getUserFeedPictures($user["pseudo"]);
                } else {
                    $feed = $db->getAllPictures();
                }
                
                if ($searching != "") {
                    $feed = $db->getPicturesByNameSearch($searching);
                }
                                
                for ($i = 0; $i < count($feed); ++$i) {
                    $post_community = $db->getCommunityByName($feed[$i]["nomCommunaute"])[0];

                    echo 
                    '<a href="picture_fullview.php?id=' . htmlentities($feed[$i]["id"]) . '" class="picturePreview" id='. htmlentities($feed[$i]["id"]) . ' style="background-image: url(files/'. htmlentities($feed[$i]["urlPhoto"]) .')" >
                        <div class="picturePreviewShadowTop"></div>
                        <div class="picturePreviewShadowBottom"></div>	
                        
                        <div class="picturePreviewHeader">
                            <div class="picturePreviewHeaderTitle">'. htmlentities($feed[$i]["titre"]) . '</div>
                            <div class="picturePreviewHeaderSubtitle">'. htmlentities($feed[$i]["pseudoUtilisateur"]) . ' • ' . formatDate($feed[$i]["dateHeureAjout"], "d.m.Y, H:i") . '</div>
                        </div>
                                    
                        <div class="picturePreviewFooter">
                            <div class="picturePreviewFooterButton" style="background-image: url(files/'. htmlentities($post_community["imageDeProfil"]) .'), url(files/community_default.PNG);"></div>	
                        ';

                    if($isLoggedIn) {
                        $userLiked = $db->checkIfUserLikedAPicture($user["pseudo"], $feed[$i]["id"]);
                        if($userLiked) {
                            echo '<button onclick="likePicture('.htmlentities($user["pseudo"]).','.htmlentities($feed[$i]["id"]).')" class="picturePreviewFooterButton" style="background-image: url(/imgs/like_on.png);"></button>';
                        } else {
                            echo '<button onclick="likePicture('.htmlentities($user["pseudo"]).','.htmlentities($feed[$i]["id"]).')" class="picturePreviewFooterButton" style="background-image: url(/imgs/like_off.png);"></button>';
                        }
                        
                    }

                    echo '</div></a>';
                }
                ?>
            </div>
        </div>
        
        <!-- PROFILE PANEL -->
        <?php include_once("php/include/profilePanel.php"); ?>

        <!-- Create a new community form -->
        <?php
        createPopup("createCommunityPopup", "
                <form id='insertCommunityForm' name='insertCommunityForm' action='php/form/insertCommunityForm.php' method='post' enctype='multipart/form-data'>
                    
                    <!-- Profile picture icon -->
                    <img id='profile_picture_popup' class='header_picture_popup'></img>

                    <!-- Name input-->
                    <label for='name'>Name*</label>
                    <input type='text' id='name' name='name' placeholder=\"Enter your community's public name...\" required>
                    
                    <!-- Description input-->
                    <label for='detail'>Description*</label>
                    <textarea id='name' name='detail' placeholder='Describe your community here...' required></textarea>

                    <!-- Profile Picture input-->
                    <label for='files'>Profile Picture  :  </label>                
                    <input id='ppinput' name='files' onchange=\"loadFile(event, 'profile_picture_popup');\" type='file' placeholder='Profile picture' accept=\"" . getFileFormats() ."\"/>
                    
                    <input type='submit' value='Create a new community'>

                    <div class='note'>*must be provided</div>
                </form>
        ");
        ?>
    </div>
</body>

<script src="js/interface.js"></script>
<script src="js/func.js"></script>

</html>