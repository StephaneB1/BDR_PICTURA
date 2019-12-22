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
        <div class="col-1-2 unique">
			<h1>Bienvenue,</h1>
			<h2>Liste des utilisateurs:</h2>
            <?php
			
            $users = $db->getAllUserPseudos();
			
			for ($i = 0; $i < count($users); $i++) {
				echo "<p>" . htmlentities($users[$i]["pseudo"]) . "</p>";
			}

            ?>
			
        </div>
	</div> <!-- End wrapper-content -->
	<?php

    include_once("php/include/footer.php");

	?>
</body>
</html>