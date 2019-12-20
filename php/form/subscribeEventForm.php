<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 08.05.2017
 * Summary: User's subscription to an event
 */

include_once("../include/func.php");
//User must be logged in to acces this page
if(!checkIfLoggedIn()) {
    stopAndMove("../../connexion.php");
}

//Connect to database
include_once("../include/dbConnect.php");
$db = new db;

//Check that an ID and a subscribe/unsubscribe bool has been sent
if(!empty($_GET["id"]) && isset($_GET["s"])) {
    $eventId = $_GET["id"];
    $event = $db->getEventById($eventId);
    //Verify event's existence
    if(!empty($event)) {
        if($_GET["s"] == 0 || $_GET["s"] == 1 || $_GET["s"] == 2 || $_GET["s"] == 3) { //Valid status value
            $participants = $db->getEventParticipants($_GET["id"], 1);

            //User can subscribe if there isn't a participants limit or if there's free place
            if($event[0]["eveMaxParticipants"] == 0 || count($participants) < $event[0]["eveMaxParticipants"]) {

                //Check event's privacy
                if($event[0]["eveIsPublic"] == 1) {
                    $db->subscribeEvent($_SESSION["userId"], $eventId, $_GET["s"], null);

                } else { //Event is private -> user must be invited
                    $subscription = $db->getSubscription($_SESSION["userId"], $eventId);

                    if(!empty($subscription) || checkIfAdmin()) {
                        $db->subscribeEvent($_SESSION["userId"], $eventId, $_GET["s"], null);
                    } else { //User doesn't have permission
                        stopAndMove("../../index.php");
                    }
                }

            } elseif($_GET["s"] == 0 || $_GET["s"] == 2) { //No places remaining -> user must be unsubscribing or "come maybe"

                //Check event's privacy
                if($event[0]["eveIsPublic"] == 1) {
                    $db->subscribeEvent($_SESSION["userId"], $eventId, $_GET["s"], null);

                } else { //Event is private -> user must be invited
                    $subscription = $db->getSubscription($_SESSION["userId"], $eventId);

                    if(!empty($subscription)) {
                        $db->subscribeEvent($_SESSION["userId"], $eventId, $_GET["s"], null);
                    } else { //User doesn't have permission
                        stopAndMove("../../index.php");
                    }
                }

            } else { //Error: participants limit reached! -> cannot subscribe
                $_SESSION["e"] = 6;
                stopAndMove("../../displayEvent.php?id=".$event[0]['idEvent']);
            }
        } else { //Error: incorrect status value
            die();
            stopAndMove("../../index.php");
        }
        header("Location: ../../displayEvent.php?id=".$event[0]["idEvent"]);
    } else { //Error: event doesn't exist
        stopAndMove("../../index.php");
    }
} else { //Error: no ID received
    stopAndMove("../../index.php");
}

?>