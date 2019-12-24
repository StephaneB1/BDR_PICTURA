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

<title>PICTURA - <?php echo $community["nom"]; ?></title>

</head>
<body>
<?php

include_once("php/include/menu-h.php");

?>

<div id="wrapper-content">
    <!-- Header -->
    <div class='col-1-2 unique'>
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
    <div class='col-1-2 unique center-x'>
        <p>Photos coming soon...</p>
    </div>
</div> <!-- End wrapper-content -->
<?php

include_once("php/include/footer.php");

?>

<div id="gallery-viewer"></div>

</body>

<!-- Image viewer script -->
<script src="js/galleryViewer.js" type="text/javascript"></script>

</html>