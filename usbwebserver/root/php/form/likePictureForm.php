<?php

/**
 * HEIG-VD - Mini-Projet BDR
 * 
 * Authors:     Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date:        20.12.2019
 * 
 * Summary:     Upload a new photo
 */


include_once("../include/func.php");

session_start();

// User must be logged in
if (!checkIfLoggedIn())
    redirect(null);

$formCorrect = true; // If false, data won't be sent

// Picture
if (!empty($_POST["picture"])) {
    $photoId = $_POST["picture"];
} else { // Error: title not sent
    $formCorrect = false;
}

// Author
$user = $_SESSION["pseudo"]; // Login check already made above

// Verification before sending data
if ($formCorrect == true) {
    // Connect to database
    include_once($_SERVER['DOCUMENT_ROOT'] . "/php/include/dbConnect.php");
    $db = new db;

    if (!$db->likeThisPicture($user, $photoId)) {
        //$_SESSION["e"] = 1; // Store error code
        previousPage(); // Go back to form page (keep form values)
    }

    redirect("picture_fullview.php?id=" . $photoId);
} else { // Error: some form fields are empty
    //$_SESSION["e"] = 1; // Store error code
    previousPage(); // Go back to form page (keep form values)
}

?>