<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 15.05.2017
 * Summary: Delete user account and all its content
 */

session_start();

include_once("../include/func.php");

//User must be logged in to acces this page
if(!checkIfLoggedIn()) {
    stopAndMove("../../index.php");
}

//Connect to database
include_once("../include/dbConnect.php");
$db = new db;

//User's ID must be sent
if(!empty($_GET["id"])) {
    $user = $db->getUserById($_GET["id"]);
    //Check user existence
    if(!empty($user)) {
        //Verify user's permission
        if($user[0]["idUser"] == $_SESSION["userId"]) {
            $db->deleteUserDefinitely($_GET["id"]);
            header("Location: ../../myEvents.php");
        } else { //Error: desired account isn't current user's one
            stopAndMove("../../index.php");
        }
    } else { //Error: user ID not valid
        stopAndMove("../../index.php");
    }
} else { //Error: no user ID received
    stopAndMove("../../index.php");
}

?>