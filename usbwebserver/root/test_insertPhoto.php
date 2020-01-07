<?php


include_once("php/include/header.php");
include_once("php/include/dbConnect.php");

$db = new db;
$isLoggedIn = checkIfLoggedIn();

// This form should be on the community's page as we are using $_GET["n"] (the community name)

// User must be logged in to insert a photo
if(!$isLoggedIn) {
    echo "<h1>YOU ARE NOT LOGGED IN THIS IS AN ERROR FUCK YOU</h1>";
    die();
}

// Community name must be provided
if(!isset($_GET["n"])) {
    echo "\$_GET[\"n\"] is missing";
    die();
}

?>

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
        <label for='title'>
            <i class='material-icons'>label</i>
        </label>
        <input type='text' id='title' name='title' placeholder='Titre*' required autofocus/>
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

    <!-- Community (hidden) -->
    <input type="hidden" name="community" value="<?php echo $_GET["n"] ?>"/>

    <p class='note'>*Obligatoires</p>
    <p><input type='submit' value='Ajouter'/></p>
</form>

</body>
</html>