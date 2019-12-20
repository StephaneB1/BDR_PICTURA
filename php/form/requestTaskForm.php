<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 10.05.2017
 * Summary: Ask for a task to be assigned
 */

session_start();

$formCorrect = true; //If false, data won't be sent
include_once("../include/func.php");

//Connect to database
include_once("../include/dbConnect.php");
$db = new db;

//User must be logged in to acces this page
if(!checkIfLoggedIn()) {
    stopAndMove("../../connexion.php");
}

//Check that an ID has been sent
if(!empty($_GET["id"])) {
    $taskId = $_GET["id"];
    $task = $db->getTaskById($taskId);
    //Verify event ID validity
    if(!empty($task)) {
        //Check if task isn't already taken
        if(empty($task[0]["fkUser"])) {
            //Check general event permission
            if(checkUserEventPermission($task[0]["fkEvent"], false, true)) {
                $userSubscription = $db->getSubscription($_SESSION["userId"], $task[0]["fkEvent"]);
                //Event's author can take tasks without participating
                if($userSubscription[0]["eveuseIsAdmin"] == 1) {
                    //Current user is the author -> skip approval
                    $db->requestTask($taskId, $_SESSION["userId"]);
                    $db->approveTask($taskId, 1);
                } else {
                    //User must be coming to the event to assign tasks
                    if($userSubscription[0]["eveuseStatus"] == 1) {
                        $db->requestTask($taskId, $_SESSION["userId"]);
                    } else { //Error: user must participate to the event
                        $_SESSION["e"] = 5; //Store error code
                        stopAndMove("../../displayEvent.php?id=".$task[0]['fkEvent']."&tab=2");
                    }
                }
                //Redirect to event's page
                header("Location: ../../displayEvent.php?id=".$task[0]["fkEvent"]."&tab=2");
            } else { //Error: user hasn't subscribed
                $_SESSION["e"] = 5; //Store error code
                stopAndMove("../../displayEvent.php?id=".$task[0]['fkEvent']."&tab=2");
            }
        } else { //Error: task already taken by an user
            stopAndMove();
        }
    } else { //Error: event doesn't exists
        stopAndMove();
    }
} else { //Error: no event ID was given
    stopAndMove();
}

?>