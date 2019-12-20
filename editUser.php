<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 28.04.2017
 * Summary: User profile edition page
 */

include_once("php/include/header.php");

?>
    <title>TPI</title>
</head>
<body>
    <?php
	
    include("php/include/receiveError.php");

    include_once("php/include/menu-h.php");
    include_once("php/include/dbConnect.php");

    $db = new db;

    //User must be logged in to acces this page
    if(!checkIfLoggedIn()) {
        stopAndMove();
    } else {
        $user = $db->getUserById($_SESSION["userId"]);

        //Check if user had a picture
        if(!empty($user[0]['usePicture'])) {
            $userHadPicture = true;
        } else {
            $userHadPicture = false;
        }
    }

    ?>

<div id="wrapper-content">
	<div class="col-1-2 unique center-x">
		<h1>Editer mon profil</h1>
		<h2>Informations générales:</h2>
        <!-- Edition form -->
        <form id="editUserForm" name="editUserForm" action="php/form/editUserForm.php" method="post" enctype="multipart/form-data">
            <!-- Login -->
            <p>
				<label for="login">
					<i class="material-icons">account_circle</i>
				</label>
				<input value="<?php echo $user[0]['useLogin']; ?>" type="text" id="login"name="login" pattern="[a-zA-Z0-9]{1,20}" placeholder="Nom d'utilisateur*" title="Seulement lettres et chiffres / Long. max.: 20" required/>
			</p>
        	<!-- First name -->
            <p>
				<label for="firstName">
					<i class="material-icons">face</i>
				</label>
				<input value="<?php echo $user[0]['useFirstname']; ?>" type="text" id="firstName" name="firstName" pattern="[a-zA-Z- ]{1,20}" placeholder="Prénom*" title="Max. 20 caractères" required/>
			</p>
        	<!-- Last name -->
            <p>
				<label for="lastName">
					<i class="material-icons blank">face</i>				
				</label>
				<input value="<?php echo $user[0]['useLastname']; ?>" type="text" id="lastName" name="lastName" pattern="[a-zA-Z- ]{1,30}" placeholder="Nom*" title="Max. 30 caractères" required/>
			</p>
        	<!-- Email address -->
            <p>
				<label for="email">
					<i class="material-icons">mail</i>
				</label>
				<input value="<?php echo $user[0]['useEmail']; ?>" type="email" id="email" name="email" pattern="[a-zA-Z0-9\._-]{1,}[@]{1}[a-zA-Z0-9\._-]{1,}[.]{1}[a-zA-Z0-9\._-]{1,}" placeholder="Adresse email*" required/>
			</p>
			
            <h2>Modifier mot de passe:</h2>
        	<!-- Old password -->
            <p>
				<label for="passwordOld">
					<i class="material-icons">lock_outline</i>
				</label>
				<input type="password" id="passwordOld" name="passwordOld" placeholder="Ancien mot de passe" title="Min. 8 caractères"/>
			</p>
        	<!-- New password -->
            <p>
				<label for="password">
					<i class="material-icons">lock</i>
				</label>
				<input type="password" id="password" name="password" minlength="8" placeholder="Nouveau mot de passe" title="Min. 8 caractères"/>
			</p>
        	<!-- New password confirm -->
            <p>
				<label for="passwordConfirm">
					<i class="material-icons blank">lock</i>
				</label>
				<input type="password" id="passwordConfirm" name="passwordConfirm" minlength="8" placeholder="Confirmer nouveau mot de passe" title="Min. 8 caractères"/>
			</p>
			
        	<!-- Picture -->
            <h2>Modifier photo de profil:</h2>
            <label for="picture">
                <div id="picturePreview" class="userpicture clickable" style="background-image: url('files/<?php echo $user[0]['usePicture']; ?>'); margin: auto;">
                    <div class="editoverlay center-y">
                       <i class="material-icons">add_a_photo</i>
                    </div>
                </div>
            </label>
            <input id="picture" name="files" type="file" accept=".jpg,.jpeg,.png,.gif,.tif,.tiff" class="hidden"/>
            <?php

            //If user had a picture, display checkbox to delete it
            if($userHadPicture) {
                echo "<p><label><input name='deletePicture' type='checkbox'/>Supprimer photo de profil</label></p>";
            }

            ?>
			
            <p class="note">*Obligatoires</p>
            <p><a href="profile.php">Annuler</a> - <input type="submit" value="Enregistrer"/></p>
        </form>
    </div>
</div> <!-- End wrapper-content -->
    <?php
        include_once("php/include/footer.php");
    ?>
</body>

<!-- Profile picture preview -->
<script>
    //Script from http://stackoverflow.com/questions/4459379/preview-an-image-before-it-is-uploaded
    $("#picture").change(function(){ //Catch file input's change event
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                //Apply file path to preview image
                $('#picturePreview').attr("style", "background-image: url("+e.target.result+"); margin: auto;");
            }

            reader.readAsDataURL(this.files[0]);
        }
    });
</script>

</html>
