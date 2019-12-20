<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 05.05.2017
 * Summary: Delete an event (+messages, participations and tasks) definitely
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

//Event's ID must be sent
if(!empty($_GET["id"])) {
    $event = $db->getEventById($_GET["id"]);
//Check event existence
    if(!empty($event)) {
        //Verify user's permission (must be author of the event)
        if(checkUserEventPermission($_GET["id"], true, false)) {
            $db->deleteEventDefinitely($_GET["id"]);
            header("Location: ../../myEvents.php");
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