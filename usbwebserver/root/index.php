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
        <p>
            <a href="insertCommunity.php">+ Créer</a>
        </p>
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

include_once("php/include/footer.php");

?>
</body>
</html>