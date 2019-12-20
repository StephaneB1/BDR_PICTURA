<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 01.05.2017
 * Summary: Profile of the current user
 */

include_once("php/include/header.php");

?>
    <title>TPI</title>
</head>
<body>
<?php

include_once("php/include/menu-h.php");
include_once("php/include/dbConnect.php");

$db = new db;

//User must be logged in to acces this page
if(checkIfLoggedIn()) {
	$isCurrentUser = false;
	
	if(empty($_GET["id"]) || $_GET["id"] == $_SESSION["userId"]) {
		//Display current user's profile
		$isCurrentUser = true;
		$user = $db->getUserById($_SESSION["userId"]);
	} else {
		//Display other user's profile	
		$user = $db->getUserById($_GET["id"]);
		if(!empty($user)) {
			
		} else { //Error: invalid user ID
			stopAndMove();
		}
	}
} else { //Error: user not logged in
	stopAndMove("connexion.php");
}

?>

<div id="wrapper-content">
    <!-- First -->
	<div class="col-1-2 unique">
        <?php
		if($isCurrentUser) {
			echo "<h1>Mon profil</h1>";
		} else {
			echo "<h1>Profil de ".htmlentities($user[0]['useLogin'])."</h1>";
		}

        //If user has a profile picture, display it
        if($user[0]["usePicture"]) {
            echo "<p>
            <div class='userpicture viewer-item' style='background-image: url(files/".$user[0]['usePicture'].");'></div>
        </p>";
        }

        ?>
		<p>
			<span class="bold">Nom d'utilisateur: </span><?php echo htmlentities($user[0]["useLogin"]); ?>
		</p>
        <p>
			<span class="bold">Prénom: </span><?php echo htmlentities($user[0]["useFirstname"]); ?>
		</p>
		<p>
			<span class="bold">Nom: </span><?php echo htmlentities($user[0]["useLastname"]); ?>
		</p>
		<?php
		
		//Display email address only if it's the current user's profile
		if($isCurrentUser) {
			echo "
			<p>
				<span class='bold'>Email: </span>".htmlentities($user[0]["useEmail"])."
			</p>
			<p>
				<a href='editUser.php'>
					<i class='material-icons'>edit</i>Modifier mon profil
				</a>
			</p>
			<p>
				<a href='php/form/deleteUserForm.php?id=".$_SESSION["userId"]."' class='red' onclick='return confirm(\"Supprimer définitivement votre compte?\")'>
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
    <div id="gallery-viewer"></div>
</body>

<!-- Image viewer script -->
<script src="js/galleryViewer.js" type="text/javascript"></script>

</html>
