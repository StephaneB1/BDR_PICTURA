<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 11.05.2017
 * Summary: Delete an event's task definitely
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
    $task = $db->getTaskById($_GET["id"]);
    //Check task existence
    if(!empty($task)) {
        //Verify user's permission (must be author of the event)
        if(checkUserEventPermission($task[0]["fkEvent"], true, false)) {
            $db->deleteTask($_GET["id"]);
            header("Location: ../../displayEvent.php?id=".$task[0]["fkEvent"]."&tab=2");
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