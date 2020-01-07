<?php

/**
 * HEIG-VD
 * Authors: Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date: 20.12.2019
 * Summary: Make a user follow a community
 */

include_once("../include/func.php");

session_start();

// User must be logged in
if (!checkIfLoggedIn())
    redirect(null);

// Community and follow status
if (!empty($_POST["community"])) {
    $community = $_POST["community"];
    $follow = empty($_POST["follow"]) ? false : true; // If $_POST value equals 0, both empty() and isset() return true!
    // follow value 0 means un-following
} else { // Error: no community
    redirect(null);
}

// User
$user = $_SESSION["pseudo"]; // Login check already made above

// Connect to database
include_once($_SERVER['DOCUMENT_ROOT'] . "/php/include/dbConnect.php");
$db = new db;

// Follow/Unfollow community
$result = $follow ? ($db->followCommunity($user, $community)) : ($db->quitCommunity($user, $community));

if (!$result) {
    //$_SESSION["e"] = 1; // Store error code
    redirect(null);
}

redirect("community.php?n=" . $community);

?>