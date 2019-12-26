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

if (empty($community)) {
    //Error: invalid community ID
    redirect(null);
}

?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <<link rel="stylesheet" href="/css/community.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="/css/picture.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" />

    <title>PICTURA -  <?php echo $community["nom"]; ?></title>
</head>

<body id="body">

    <div class="container">
        <!-- COMMUNITY PANEL -->
        <div class="leftpanel">
            <img src="../imgs/pictura_logo.png" style="width:80%; margin-top:10px; margin-bottom: 10px;" />

            <div class="community_cell_container_header">
                <div class="community_cell_icon_header" style='background-image: url(files/" . $community['imageDeProfil'] . ");'></div>  <?php echo $community["nom"]; ?>
                <a href="../index.php" class="community_exit_button_header"></a>
            </div>

            <div class="community_description"><?php echo $community["detail"]; ?></div>

            <div class="community_cell_container">
                <div class="community_cell_icon"></div> 99.9k followers
            </div>
            <div class="community_cell_container">
                <div class="community_cell_icon"></div> 99.9k pictures
            </div>
            <div class="community_cell_container">
                <div class="community_cell_icon"></div> @admin1 <br> @admin2 <br> @moderator1 <br> @moderator2
            </div>

        </div>

        <!-- PICTURE FEED -->
        <div class="rightpanel">
            <div class="homeFeed" id="homeFeed"></div>
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

        if ($community["imageDeProfil"]) {
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
        </div>";
        ?>
    </div>

    <!-- Content -->
    <!--<div class='col-1-2 unique center-x'>
        <p>Photos coming soon...</p>
    </div>
</div> <!-- End wrapper-content -->
<!--<?php

include_once("php/include/footer.php");

?>

<div id="gallery-viewer"></div>

</body>-->

<!-- Image viewer script -->
<script src="js/galleryViewer.js" type="text/javascript"></script>

</html>