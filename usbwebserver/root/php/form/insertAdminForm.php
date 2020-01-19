<?php

/**
 * HEIG-VD - Mini-Projet BDR
 * 
 * Authors:     Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date:        20.12.2019
 * 
 * Summary:     Set a user as admin/moderator of a community
 */

include_once("../include/func.php");

session_start();

// User must be logged in
if (!checkIfLoggedIn())
    redirect(null);

$formCorrect = true; // If false, data won't be sent

// User
if (!empty($_POST["pseudo"])) {
    $user = $_POST["pseudo"];
} else { // Error: pseudo not sent
    $formCorrect = false;
}

// Community
if (!empty($_POST["community"])) {
    $community = $_POST["community"];
} else { // Error: name not sent
    $formCorrect = false;
}


// Connect to database
include_once($_SERVER['DOCUMENT_ROOT'] . "/php/include/dbConnect.php");
$db = new db;

// User must be admin of the community in order to promote a user
if(!$db->isAdmin($user, $community, 1)) {
    $formCorrect = false;
}

// Privilege
if (!empty($_POST["privilege"])) {
    $privilege = $_POST["privilege"];
} else { // Error: name not sent
    $formCorrect = false;
}

// Verification before sending data
if ($formCorrect == true) {

    if (!$db->insertAdmin($user, $community, $privilege)) { // Insert community
        //$_SESSION["e"] = 1; // Store error code
        previousPage(); // Go back to form page (keep form values)
    }

    redirect("community.php?n=" . $community);
} else { // Error: some form fields are empty
    //$_SESSION["e"] = 1; // Store error code
    previousPage(); // Go back to form page (keep form values)
}

?>