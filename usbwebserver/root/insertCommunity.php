<?php

/**
 * HEIG-VD
 * Authors: Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date: 20.12.2019
 * Summary: Community creation page
 */

include_once("php/include/header.php");

// User muste be connected to create a new community
if (!checkIfLoggedIn())
    redirect("connexion.php");

?>
<title>PICTURA - créer communauté</title>

</head>
<body>
<?php

include_once("php/include/menu-h.php");

?>

<div id="wrapper-content">
    <div class="col-1-3 unique center-x" style="background-color: rgba(255, 255, 255, 0.8)">
        <h1>Créer nouvelle communauté</h1>

        <!-- Login form -->
        <form id='insertCommunityForm' name='loginUserForm' action="php/form/insertCommunityForm.php" method="post"
              enctype='multipart/form-data'>
            <p>Merci de saisir les informations concernants votre nouvelle communauté:</p>
            <!-- Name -->
            <p>
                <label for="name">
                    <i class="material-icons">label</i>
                </label>
                <input type="text" id="name" name="name" placeholder="Nom de la communauté*" pattern="[a-zA-Z0-9]{1,20}" required autofocus/>
            </p>

            <!-- Detail -->
            <p>
                <label for="detail">
                    <i class="material-icons">notes</i>
                </label>
                <textarea id="name" name="detail" placeholder="Description*" required></textarea>
            </p>


            <!-- Profile picture -->
            <p>
                <label>
                    <i class="material-icons">photo</i>
                </label>
                <input name='files' type='file' placeholder="Photo de profil" accept=' <?php echo join(',', prefixStringArray(IMAGE_FORMATS, ".")); ?>'/>
            </p>

            <p class="note">*Obligatoires</p>
            <p><input type="submit" value="Créer"/></p>
        </form>
    </div>
</div> <!-- End wrapper-content -->
<?php

include_once("php/include/footer.php");

?>
</body>

</html>