<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 12.05.2017
 * Summary: Upload image or video to an event
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
        if(checkUserEventPermission($_GET["id"], true, false)) {
            $successCount = 0;

            //Browse all given files
            for($i = 0; $i < count($_FILES["files"]["name"]); $i++) {
                //Check remaining space
                if(getEventMediaSize($eventId)*1024*1024 + $_FILES["files"]["size"][$i] < MAX_EVENTMEDIA_SIZE) {
                    //File can be uploaded
                    $fileName = uploadFile(
                        null,
                        $_FILES["files"]["name"][$i],
                        $_FILES["files"]["tmp_name"][$i],
                        $_FILES["files"]["error"][$i])
                    ;

                    //Check for errors
                    if($fileName != false) {
                        $db->insertMedia($event[0]["idEvent"], $fileName);
                        $successCount++;
                    }
                } else {
                    //No space remaining
                    break; //Stop browsing sent files
                }
            }

            //Verify upload errors
            if($successCount != count($_FILES["files"]["name"])) {
                //Some files weren't uploaded
                $_SESSION["e"] = 7;
                $errorCount = count($_FILES["files"]["name"])-$successCount;

                if($errorCount > 1) {
                    $_SESSION["r"] = $errorCount." fichiers n'ont pas été uploadés.";
                } else {
                    $_SESSION["r"] = $errorCount." fichier n'a pas été uploadé.";
                }
            }
            header("Location: ../../displayEvent.php?id=".$eventId."&tab=3");
        } else { //Error: user doesn't have permission
            stopAndMove();
        }
    } else { //Error: event doesn't exists
        stopAndMove();
    }
} else { //Error: no event ID was given
    stopAndMove();
}

?>