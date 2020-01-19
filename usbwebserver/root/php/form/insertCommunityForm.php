<?php

/**
 * HEIG-VD - Mini-Projet BDR
 * 
 * Authors:     Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date:        20.12.2019
 * 
 * Summary:     Create user account
 */

include_once("../include/func.php");

session_start();

// User must be logged in
if (!checkIfLoggedIn())
    redirect(null);

$formCorrect = true; // If false, data won't be sent

// Name
if (!empty($_POST["name"])) {
    $name = cleanifyString($_POST["name"]);
} else { // Error: name not sent
    $formCorrect = false;
}

// Detail
if (!empty($_POST["detail"])) {
    $detail = escApastrophes($_POST["detail"]); //Escape apostrophes
    //$detail = $_POST["detail"];
} else { // Error: detail not sent
    $formCorrect = false;
}

// Profile picture
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

    if (!$db->insertCommunity($name, $detail, $fileName, $_SESSION["pseudo"])) { // Insert community
        //$_SESSION["e"] = 1; // Store error code
        previousPage(); // Go back to form page (keep form values)
    }

    redirect("community.php?n=" . $name);
} else { // Error: some form fields are empty
    //$_SESSION["e"] = 1; // Store error code
    previousPage(); // Go back to form page (keep form values)
}

?>