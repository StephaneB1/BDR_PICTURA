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
$picture = $db->getPictureById($_GET["id"])[0];

if (empty($picture)) {
    //Error: invalid picture ID
    redirect(null);
}

// Pour récuperer l'image de profil mais marche pas...
$community = $db->getCommunityByName($picture["nomCommunaute"])[0];

?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="/css/interface_fullview.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/popup.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" />

    <title>PICTURA -  <?php echo $picture["titre"]; ?></title>
</head>

<body id="body">
    <?php
        echo '
        <div class="blurred_container" style="background-image: url(files/'. htmlentities($picture['urlPhoto']) . ')"></div>
        <div class="container" style="background-image: url(files/'. htmlentities($picture['urlPhoto']) . ')">'
    ?>
        <div class="pictureFullShadowTop"></div>
        
        <div class="pictureHeader">
            <img src="../imgs/pictura_logo.png" style="float: left; width:250px; margin-top:10px; margin-bottom: 10px;" />

            <?php
                echo '
                    <div class="community_cell_container">
                        <div class="community_cell_icon" style="background-image: url(files/'. htmlentities($community["imageDeProfil"]) .'), url(files/community_default.PNG)"></div> ' . htmlentities($picture["nomCommunaute"]) . '
                    </div>

                    <div class="pictureHeaderRight">
                        <div class="pictureFullHeaderTitle">' . htmlentities($picture["titre"]) . '</div>
                        <div class="pictureFullHeaderSubtitle">' . htmlentities($picture["pseudoUtilisateur"]) . ' • ' . htmlentities($picture["dateHeureAjout"]) . '</div>
                        <button id="exitpopup">X</button>
                    </div>


                ';
            ?>
        </div>

        <?php
            echo '
            <div class="pictureFullShadowBottom"></div>
    
            <div class="pictureFullFooter">
                <button class="pictureFullFooterButton" id="commentsButton" onclick="toggleComments()"></button>
                <button class="pictureFullFooterButton"></button>
                <div class="textLikeContainer">' . $db->getTotalLikes($picture["id"]) . ' like(s)</div>
            </div>';

            echo '<div class="commentsContainer" id="commentsContainer">';

                $comments = $db->getPictureComments($picture["id"]);
                for ($i = 0; $i < count($comments); ++$i) {
                    echo '<div class="comment">' 
                            . $comments[$i]["commentaire"] .
                            '<br><div class="commentAuthor">- ' . $comments[$i]["pseudoUtilisateur"] .
                         '</div></div>';
                }              

            echo '</div>';

        ?>
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