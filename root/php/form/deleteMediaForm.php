<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 29.05.2017
 * Summary: Delete an event's media definitely
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

//Task's ID must be sent
if(!empty($_GET["id"])) {
    $media = $db->getMediaById($_GET["id"]);
    //Check media existence
    if(!empty($media)) {
        //Verify user's permission (must be author of the event)
        if(checkUserEventPermission($media[0]["fkEvent"], true, false)) {
            $db->deleteMedia($_GET["id"]);
            header("Location: ../../displayEvent.php?id=".$media[0]["fkEvent"]."&tab=3");
        } else { //Error: user doesn't have permission to delete
            stopAndMove("../../index.php");
        }
    } else { //Error: event's ID not valid
        stopAndMove("../../index.php");
    }
} else { //Error: no event ID received
    stopAndMove("../../index.php");
}

?>