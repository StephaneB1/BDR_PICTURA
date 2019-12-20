<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 11.05.2017
 * Summary: Add a new message to an event
 */

session_start();

$formCorrect = true; //If false, data won't be sent
include_once("../include/func.php");

//Connect to database
include_once("../include/dbConnect.php");
$db = new db;

//User must be logged in to acces this page
if(!checkIfLoggedIn()) {
    stopAndMove("../../index.php");
}

//Check that an ID has been sent
if(!empty($_GET["id"])) {
    $eventId = $_GET["id"];
    $event = $db->getEventById($eventId);
    //Verify event ID validity
    if(!empty($event)) {
        //Check if current user is the author of the event
        if(!checkUserEventPermission($_GET["id"], false, false)) {
            stopAndMove();
        }
    } else { //Error: event doesn't exists
        stopAndMove();
    }
} else { //Error: no event ID was given
    stopAndMove();
}

//Content
if(!empty($_POST["content"])) {
    $content = str_replace("'", "\\'", $_POST["content"]); //Escape apostrophes
} else { //Error: no message
    $formCorrect = false;
}

//Verification before sending data
if($formCorrect == true) {
    $db->insertMessage($_SESSION["userId"], $eventId, $content);
    header("Location: ../../displayEvent.php?id=".$eventId."&tab=4");
} else { //Error: some form fields are empty
	$_SESSION["e"] = 1; //Store error code
    header("Location: ../../displayEvent.php?id=".$eventId."&tab=4");
}

?>