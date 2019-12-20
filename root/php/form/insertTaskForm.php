<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 10.05.2017
 * Summary: Add a new task to an event
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
        if(!checkUserEventPermission($_GET["id"], true, false)) {
            stopAndMove();
        }
    } else { //Error: event doesn't exists
        stopAndMove();
    }
} else { //Error: no event ID was given
    stopAndMove();
}

//Description
if(!empty($_POST["description"])) {
    $description = str_replace("'", "\\'", $_POST["description"]); //Escape apostrophes
} else { //No description -> ok
    $formCorrect = false;
}

//Verification before sending data
if($formCorrect == true) {
    $db->insertTask($eventId, $description);
    header("Location: ../../displayEvent.php?id=".$eventId."&tab=2");

} else { //Error: some form fields are empty
	$_SESSION["e"] = 1; //Store error code
    header("Location: ../../displayEvent.php?id=".$eventId."&tab=2");
}

?>