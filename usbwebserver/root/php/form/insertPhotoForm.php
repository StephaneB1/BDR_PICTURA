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

// Title
if (!empty($_POST["title"])) {
    $title = escApastrophes($_POST["title"]);
} else { // Error: title not sent
    $formCorrect = false;
}

// Detail
if (!empty($_POST["detail"])) {
    $detail = escApastrophes($_POST["detail"]); //Escape apostrophes
} else {
    $detail = null;
}

// Author
$user = $_SESSION["pseudo"]; // Login check already made above

// Community
if (!empty($_POST["community"])) {
    $community = $_POST["community"];
} else { // Error: community not sent
    $formCorrect = false;
}

// Tags
if (!empty($_POST["tags"])) {
    // Split tags into array
    $tags = explode(" ", $_POST["tags"]);

    // Remove special chars
    for($i = 0; $i < count($tags); ++$i) {
        $tags[$i] = cleanifyString($tags[$i]);
    }

} else {
    $tags = null;
}

// Picture file
if (!empty($_FILES["files"]["name"])) {
    $fileName = uploadFile(
        null, // Default values will be applied
        $_FILES["files"]["name"],
        $_FILES["files"]["tmp_name"],
        $_FILES["files"]["error"]);
} else {
    $fileName = null;
}

// Verification before sending data
if ($formCorrect == true) {
    // Connect to database
    include_once($_SERVER['DOCUMENT_ROOT'] . "/php/include/dbConnect.php");
    $db = new db;

    if (!$db->insertPhoto($title, $detail, $user, $community, $fileName, $tags)) { // Insert photo
        //$_SESSION["e"] = 1; // Store error code
        previousPage(); // Go back to form page (keep form values)
    }

    redirect("community.php?n=" . $community);
} else { // Error: some form fields are empty
    //$_SESSION["e"] = 1; // Store error code
    previousPage(); // Go back to form page (keep form values)
}

?>