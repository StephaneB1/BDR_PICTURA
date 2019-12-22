<?php

/**
 * HEIG-VD
 * Authors: Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date: 20.12.2019
 * Summary: Profile of the current user
 */

include_once("php/include/header.php");

?>
    <title>PITCURA - Profil</title>
</head>
<body>
<?php

include_once("php/include/menu-h.php");
include_once("php/include/dbConnect.php");

$db = new db;

//User must be logged in to acces this page
if(checkIfLoggedIn()) {
	$isCurrentUser = false;
	
	if(empty($_GET["id"]) || $_GET["id"] == $_SESSION["pseudo"]) { // If no user id param => display own profile
		//Display current user's profile
		$isCurrentUser = true;
		$user = $db->getUserByPseudo($_SESSION["pseudo"]);
	} else {
		//Display other user's profile	
		$user = $db->getUserByPseudo($_GET["id"]);
		if(empty($user)) {
			//Error: invalid user ID
			redirect(null);
		}
	}
} else { //Error: user not logged in
	redirect("connexion.php");
}

?>

<div id="wrapper-content">
    <!-- First -->
	<div class="col-1-2 unique">
        <?php

        // Title

		if($isCurrentUser) {
			echo "<h1>Mon profil</h1>";
		} else {
			echo "<h1>Profil de ".htmlentities($user[0]['pseudo'])."</h1>";
		}

		// Infos

        if(!empty($user[0]["nom"])) {
            echo "
            <p>
                <span class='bold'>Nom: </span>".htmlentities($user[0]["nom"])."
            </p>";
        }

        if(!empty($user[0]["prenom"])) {
            echo "
            <p>
                <span class='bold'>Prénom: </span>".htmlentities($user[0]["prenom"])."
            </p>";
        }
		
		//Display email address only if it's the current user's profile
		if($isCurrentUser) {
			echo "
			<p>
				<span class='bold'>Email: </span>".htmlentities($user[0]["email"])."
			</p>
			<p>
				<a href='editUser.php'>
					<i class='material-icons'>edit</i>Modifier mon profil
				</a>
			</p>
			<p>
				<a href='php/form/deleteUserForm.php' class='red' onclick='return confirm(\"Supprimer définitivement votre compte?\")'>
					<i class='material-icons'>delete_forever</i>Supprimer mon profil
				</a>
			</p>
			";
		}
		
		?>
		
		
	</div>
</div> <!-- End wrapper-content -->
    <?php

    include_once("php/include/footer.php");

    ?>
</body>

</html>
