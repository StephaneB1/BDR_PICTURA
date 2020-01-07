<?php

/**
 * HEIG-VD
 * Authors: Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date: 20.12.2019
 * Summary: Insert a comment (or response) on a photo
 */

include_once("../include/func.php");

session_start();

// User must be logged in
if (!checkIfLoggedIn())
    redirect(null);

$formCorrect = true; // If false, data won't be sent

// Comment content
if (!empty($_POST["comment"])) {
    $comment = escApastrophes($_POST["comment"]);
} else { // Error: comment not sent
    $formCorrect = false;
}

// Author
$user = $_SESSION["pseudo"]; // Login check already made above

// Photo
if (!empty($_POST["photo"])) {
    $photoId = $_POST["photo"];
} else { // Error: photo id not sent
    $formCorrect = false;
}

// Parent (dateHeureAjout)
// (if not null, we are inserting a response)
if (!empty($_POST["parent"])) {
    $parent = $_POST["parent"];
} else { // Not a response
    $parent = null;
}

// Verification before sending data
if ($formCorrect == true) {
    // Connect to database
    include_once($_SERVER['DOCUMENT_ROOT'] . "/php/include/dbConnect.php");
    $db = new db;

    if (!$db->insertComment($photoId, $user, $comment, $parent)) { // Insert comment
        //$_SESSION["e"] = 1; // Store error code
        previousPage(); // Go back to form page (keep form values)
    }

    redirect("community.php?n=" . $community);
} else { // Error: some form fields are empty
    //$_SESSION["e"] = 1; // Store error code
    previousPage(); // Go back to form page (keep form values)
}

?>