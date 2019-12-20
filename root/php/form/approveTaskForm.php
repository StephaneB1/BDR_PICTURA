<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 11.05.2017
 * Summary: Approve user to take a task
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

//Check that an ID and astatus have been sent
if(!empty($_GET["id"]) && ($_GET["s"] == 0 || $_GET["s"] == 1)) {
    $taskId = $_GET["id"];

    $task = $db->getTaskById($taskId);
    //Verify event ID validity
    if(!empty($task)) {
        //User must be admin to approve tasks
        if(checkUserEventPermission($task[0]["fkEvent"], true, true)) {
            //Current user is the author -> skip approval
            $db->approveTask($taskId, $_GET["s"]);
            header("Location: ../../displayEvent.php?id=".$task[0]["fkEvent"]."&tab=2");
        } else { //Error: user isn't allowed to this event
            stopAndMove();
        }
    } else { //Error: event doesn't exists
        stopAndMove();
    }
} else { //Error: no event ID was given
    stopAndMove();
}

?>