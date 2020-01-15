<?php

/**
 * HEIG-VD
 * Authors: Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date: 20.12.2019
 * Summary: Page of a community
 */

include_once("php/include/header.php");
include_once("php/include/dbConnect.php");

$db = new db;

if (!isset($_GET["n"])) {
    // Error: no community specified
    redirect(null);
}

$community = $db->getCommunityByName($_GET["n"])[0];
$isLoggedIn = checkIfLoggedIn();

if($isLoggedIn) {
    $pseudo = $_SESSION["pseudo"];
    $following = !empty($db->isUserFollowing($pseudo, $community["nom"]));
} else {
    $following = false;
}

?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<link rel="stylesheet" href="/css/interface.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/css/community.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/css/picture.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="/css/popup.css" type="text/css" media="screen"/>
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"/>

<title>PICTURA - <?php echo htmlentities($community["nom"]); ?></title>
</head>

<body id="body">

<div class="container">

    <!-- TOP BAR -->
    <div class="topPanel" id="topSearchPanel">
        <div>
            <button class="topPanelButton" id="openSideBarButton" onClick="openSidePanel()"></button>
            <img src="imgs/pictura_logo.png" style="height: 30px;" />
        </div>
        
        <div class="topPanelRight">
            <button class="topPanelButton" id="gridButton"></button>
            <button class="topPanelButton" id="nightmodeButton" onClick="switchNightMode()"></button>
            <button class="topPanelButton" id="profileButton"></button>
            <a href="../index.php" class="topPanelButton" id="exitButton">X</a>
        </div>

        <div class="shadow"></div>
    </div> 
		
    <!-- COMMUNITY PANEL -->
    <div class="leftpanel">
        <?php
        echo "<div class='community_cell_container_header'>
                    <div class='community_cell_icon_header' style='background-image: url(files/" . htmlentities($community["imageDeProfil"]) . "),  url(\"files/community_default.PNG\")'></div>"
            . htmlentities($community["nom"]) . "
                </div>

                <div class='community_description'>" . htmlentities($community["detail"]) . "</div>";

        if($isLoggedIn)
        echo "
        <div class='myprofile_container'>
            <form id='followCommunityForm' name='followCommunityForm' action='php/form/followCommunityForm.php' method='post'>
                <input type='hidden' name='community' value='". htmlentities($community['nom']) . "'/>
                <input type='hidden' name='follow' value='" . ($following ? 0 : 1) . "'/>
                <button  onclick=\"document.getElementById('followCommunityForm').submit()\" class='panel_button'>" . ($following ? "Leave this community" : "Follow this community") . "</button>
            </form>
        </div>

        <div class='myprofile_container'>
        <button  onclick=\"displayId('postPicturePopup', null)\" class='panel_button'>Post a new picture</button>
        </div>
        ";
        ?>

        <div class="community_info_cell">
            <div class="community_cell_icon" id="total_members"></div>
            <?php
            echo $db->getCommunityTotalMembers($community["nom"]);
            ?> follower(s)
        </div>
        <div class="community_info_cell">
            <div class="community_cell_icon" id="total_pictures"></div>
            <?php
            echo $db->getCommunityTotalPictures($community["nom"]);
            ?> picture(s)
        </div>

        <div class="title_container" id="profile_title_container">
            Management
            <div class="title_line" id="profile_title_line"></div>
        </div>

        <?php
        $admins = $db->getAllCommunityAdmins($community["nom"]);
        for ($i = 0; $i < count($admins); ++$i) {
			if($admins[$i]["niveauPrivilege"] == 1) {
				$adminStatus = "ADMIN";
			} else {
				$adminStatus = "MOD";
			}
		
            echo "          
                    <div class='community_info_cell'>
                        [" . htmlentities($adminStatus) . "] " . htmlentities($admins[$i]["pseudoUtilisateur"]) . "
                    </div>";
        }
        ?>

    </div>

    <!-- PICTURE FEED -->
    <div class="middlepanel" id="community_feed_panel">
        <div class="mainFeed">
            <?php
            $community_feed_posts = $db->getCommunityFeedPictures($community["nom"]);

            for ($i = 0; $i < count($community_feed_posts); ++$i) {
                echo
                    '<a href="html/picture_fullview.html" class="picturePreview" id=' . htmlentities($community_feed_posts[$i]["id"]) . ' style="background-image: url(files/' . htmlentities($community_feed_posts[$i]["urlPhoto"]) . ')" >
                        <div class="picturePreviewShadowTop"></div>
                        <div class="picturePreviewShadowBottom"></div>	
                        
                        <div class="picturePreviewHeader">
                            <div class="picturePreviewHeaderTitle">' . htmlentities($community_feed_posts[$i]["titre"]) . '</div>
                            <div class="picturePreviewHeaderSubtitle">' . htmlentities($community_feed_posts[$i]["pseudoUtilisateur"]) . ' • ' . htmlentities($community_feed_posts[$i]["dateHeureAjout"]) . '</div>
                            <button class="picturePreviewOptionsButton"></button>
                        </div>
                                    
                        <div class="picturePreviewFooter">
                            <button class="picturePreviewFooterButton"></button>	
                            <button class="picturePreviewFooterButton"></button>
                        </div>
                    </a>';
            }
            ?>
        </div>
    </div>

</div>

<!-- Post a new picture form -->
<?php

createPopup("postPicturePopup", "
        <form id='postPictureForm' name='postPictureForm' action='php/form/insertPhotoForm.php' method='post' enctype='multipart/form-data'>

            <div class='pictureFormContainer'>
                <div class='picturePreviewContainer'>
                    <!-- Profile picture icon -->
                    <img id='picture_popup' class='header_picture_popup'/>
                </div>

                <div class='pictureInfos'>
                    <!-- Title input-->
                    <label for='title'>Title*</label>
                    <input type='text' id='title' name='title' placeholder='Enter a title...' required autofocus/>

                    <!-- Description input-->
                    <label for='detail'>Description</label>
                    <textarea id='name' name='detail' placeholder='Describe your picture here...'></textarea>

                    <!-- Tags -->
                    <label for='tags'>Balise(s)</label>
                    <input type='text' id='tags' name='tags' placeholder='Tags (seperated with a space)' pattern='[a-zA-Z0-9]{1,20}( [a-zA-Z0-9]{1,20})*'/>

                    <!-- Picture input-->
                    <label for='files'>Picture* : </label>
                    <input id='ppinput' name='files' onchange=\"loadFile(event, 'picture_popup')\" type='file' placeholder='Picture' accept='" . getFileFormats() . "'/>

                    <!-- Community (hidden) -->
                    <input type='hidden' name='community' value='" . $_GET["n"] . "'/>

                    <input type='submit' value='Post a new picture'>

                    <div class='note'>*must be provided</div>
                </div>
            </div>
        </form>
");

?>

</body>

<!-- Header -->
<!--<div class='col-1-2 unique'>
        <?php

/*if ($community["imageDeProfil"]) {
    echo "
<div class='col-1-4'><p>
        <div class='userpicture viewer-item' style='background-image: url(files/" . $community['imageDeProfil'] . ");'></div>
    </p>
</div>
<div class='col-3-4'>";
} else {
    echo "<div class='col-1-2 unique'>";
}

echo "<h1>" . $community["nom"] . "</h1>";
echo "<p>" . htmlentities($community["detail"]) . "</p>
</div>";*/
?>
    </div>-->

<!-- Content -->
<!--<div class='col-1-2 unique center-x'>
    <p>
        <a onclick="displayId('insertPhotoPopup', null)">+ Ajouter photo</a>
    </p>
</div>
</div>-->
<!-- End wrapper-content -->

<?php

//include_once("php/include/footer.php");

/*createPopup("insertPhotoPopup", "
    <h1>Ajouter une photo</h1>
        <form id='insertPhotoForm' name='loginUserForm' action='php/form/insertPhotoForm.php' method='post' enctype='multipart/form-data'>
            <!-- Picture -->
            <p>
                <label>
                    <i class='material-icons'>photo</i>
                </label>
                <input name='files' type='file' placeholder='Photo de profil' accept='" . join(',', prefixStringArray(IMAGE_FORMATS, ".")) . "'/>
            </p>
            
            <!-- Name -->
            <p>
                <label for='name'>
                    <i class='material-icons'>label</i>
                </label>
                <input type='text' id='name' name='name' placeholder='Titre*' pattern='[a-zA-Z0-9]{1,20}' required autofocus/>
            </p>

            <!-- Detail -->
            <p>
                <label for='detail'>
                    <i class='material-icons'>notes</i>
                </label>
                <textarea id='name' name='detail' placeholder='Description'></textarea>
            </p>

            <!-- Tags -->
            <p>
                <label for='tags'>
                    <i class='material-icons'>tag</i>
                </label>
                <input type='text' id='tags' name='tags' placeholder='Balises (séparées avec un espace)' pattern='[a-zA-Z0-9]{1,20}( [a-zA-Z0-9]{1,20})*' autofocus/>
            </p>

            <p class='note'>*Obligatoires</p>
            <p><input type='submit' value='Ajouter'/></p>
        </form>");
*/
?>


<!--<div id="gallery-viewer"></div>

</body>-->

</html>