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
$community = $db->getCommunityByName($_GET["n"])[0];
$follow = $_GET["follow"];
$username = $_GET["user"];

if($follow) {
    $db->followCommunity($username, $community["nom"]);
} else {
    //$db->quitCommunity($user["pseudo"], $community["nom"]);
}


if (empty($community)) {
    //Error: invalid community ID
    redirect(null);
}

?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="/css/interface.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/community.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/picture.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" />

    <title>PICTURA -  <?php echo $community["nom"]; ?></title>
</head>

<body id="body">

    <div class="container">
        <!-- COMMUNITY PANEL -->
        <div class="leftpanel">
            <img src="../imgs/pictura_logo.png" style="width:80%; margin-top:10px; margin-bottom: 10px;" />

            <?php
               echo "<div class='community_cell_container_header'>
                    <div class='community_cell_icon_header' style='background-image: url(files/". htmlentities($community["imageDeProfil"]) ."),  url(\"files/community_default.PNG\")'></div>" 
                    . htmlentities($community["nom"]) . "
                    <a href='../index.php' class='community_exit_button_header'></a>
                </div>

                <div class='community_description'>" . htmlentities($community["detail"]) . "</div>";

                if($follow) {
                    echo '
                    <div class="myprofile_container">
                        <button class="panel_button" onclick="location.href="community.php?n=' . htmlentities($community["nom"]) . '&follow=0&user=' . htmlentities($username) . '">Leave this community</button>
                    </div>';
                } else {
                    echo '
                    <div class="myprofile_container">
                        <button class="panel_button" onclick="location.href="community.php?n=' . htmlentities($community["nom"]) . '&follow=1&user=' . htmlentities($username) . '">Join this community</button>
                    </div>';
                }
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
                    echo "          
                    <div class='community_info_cell'>
                        " . htmlentities($admins[$i]["pseudoUtilisateur"]) . "
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
                    '<a href="html/picture_fullview.html" class="picturePreview" id='. htmlentities($community_feed_posts[$i]["id"]) . ' style="background-image: url(files/'. htmlentities($community_feed_posts[$i]["urlPhoto"]) .')" >
                        <div class="picturePreviewShadowTop"></div>
                        <div class="picturePreviewShadowBottom"></div>	
                        
                        <div class="picturePreviewHeader">
                            <div class="picturePreviewHeaderTitle">'. htmlentities($community_feed_posts[$i]["titre"]) . '</div>
                            <div class="picturePreviewHeaderSubtitle">'. htmlentities($community_feed_posts[$i]["pseudoUtilisateur"]) . ' • ' . htmlentities($community_feed_posts[$i]["dateHeureAjout"]) . '</div>
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
</body>
<!--<body>
<?php

include_once("php/include/menu-h.php");

?>

<div id="wrapper-content">-->
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

<!-- Image viewer script -->
<script src="js/galleryViewer.js" type="text/javascript"></script>

</html>