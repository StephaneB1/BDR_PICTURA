<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 12.05.2017
 * Summary: Delete a message
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
    $message = $db->getMessageById($_GET["id"]);
    //Check message existence
    if(!empty($message)) {
        $eventAuthor = $db->getEventAuthor($message[0]["fkEvent"]);
        $messageAuthor = $db->getUserById($message[0]["fkUser"]);
        //Verify user's permission (must be author of the message/event or general admin)
        if($message[0]["fkUser"] == $_SESSION["userId"] || $eventAuthor[0]["idUser"] == $_SESSION["userId"] || checkIfAdmin()) {
            //Event's creator (not general admin) cannot delete a general admin's message
            if($messageAuthor[0]["useIsAdmin"] && !checkIfAdmin()) {
                stopAndMove("../../index.php");
            } else {
                $db->deleteMessage($_GET["id"]);
                header("Location: ../../displayEvent.php?id=".$message[0]['fkEvent']."&tab=4");
            }
        } else { //Error: user doesn't have permission to delete
            stopAndMove("../../index.php");
        }
    } else { //Error: message's ID not valid
        stopAndMove("../../index.php");
    }
} else { //Error: no message ID received
    stopAndMove("../../index.php");
}

?>