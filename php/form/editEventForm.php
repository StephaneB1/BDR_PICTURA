<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 05.05.2017
 * Summary: Modification of an event
 */

session_start();

include_once("../include/func.php");

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

$formCorrect = true; //If false, data won't be sent

//Title
if(!empty($_POST["title"])) {
    $title = str_replace("'", "\\'", $_POST["title"]); //Escape apostrophes
} else { //Error: login not sent
    $formCorrect = false;
}

//Location
if(!empty($_POST["location"])) {
    $location = str_replace("'", "\\'", $_POST["location"]); //Escape apostrophes
} else { //Error: login not sent
    $formCorrect = false;
}

//Price
if(!empty($_POST["price"])) {
    $price = $_POST["price"];
    if(intval($price) < 0) {
        $price = 0;
    }
} else { //No price
    $price = 0;
}

//Limit
if(!empty($_POST["limit"])) {
    $limit = $_POST["limit"];
} else { //No limit
    $limit = 0;
}

//Description
if(!empty($_POST["description"])) {
    $description = str_replace("'", "\\'", $_POST["description"]); //Escape apostrophes
} else { //No description -> ok
    $description = null;
}

//Is public
if(!empty($_POST["isPublic"])) {
    $isPublic = 1;
} else {
    $isPublic = 0;
}

//Start date
if(!empty($_POST["startDate"])) {
    $startDate = $_POST["startDate"];
} else { //Error: start date not sent
    $formCorrect = false;
}

//Start hour (default is 0)
if(isset($_POST["startHour"])) {
    $startHour = $_POST["startHour"];
} else { //Error: start hour not sent
    $startHour = 0;
}

//Start minute (default is 0)
if(isset($_POST["startMinute"])) {
    $startMinute = $_POST["startMinute"];
} else { //Error: start minute not sent
    $startMinute = 0;
}

//End date
if(!empty($_POST["endDate"])) {
    $endDate = $_POST["endDate"];
    $noEndDateTime = false;
} else { //No end date
    $noEndDateTime = true;
}

//End hour (default is 0)
if(isset($_POST["endHour"])) {
    $endHour = $_POST["endHour"];
} else {
    $endHour = 0;
}

//End minute (default is 0)
if(isset($_POST["endMinute"])) {
    $endMinute = $_POST["endMinute"];
} else {
    $endMinute = 0;
}

//Verification before sending data
if($formCorrect == true) {
    //Check if title already exists
    $titleExists = false;
    $allTitles = $db->getAllEventTitles();
    for($i = 0; $i < count($allTitles); $i++) {
        if(strtolower($title) == strtolower($allTitles[$i]["eveTitle"]) && $allTitles[$i]["idEvent"] != $eventId) {
            $titleExists = true;
        }
    }

    //Title must not be used yet
    if($titleExists == false) {
        //Datetime treatment
        $startDateTime = formatDate($startDate, "Y-m-j")." ".$startHour.":".$startMinute.":00";
        if($noEndDateTime == true) {
            $endDateTime = null;
        //Start datetime cannot be after end datetime
        } elseif($startDate == $endDate
            && ($startHour > $endHour)
            || ($startHour == $endHour && $startMinute >= $endMinute)) {
            $_SESSION["e"] = 8; //Store error code
            echo"<script>window.history.back();</script>"; //Go back to form page (keep form values)
            die();
        } else {
            $endDateTime = formatDate($endDate, "Y-m-j")." ".$endHour.":".$endMinute.":00";
        }

        $db->editEvent($eventId, $title, $description, $startDateTime, $endDateTime, $location, $price, $isPublic, $limit);
        header("Location: ../../displayEvent.php?id=".$eventId);
        die();
    } else { //Error: title is already taken
		$_SESSION["e"] = 4; //Store error code
        echo"<script>window.history.back();</script>"; //Go back to form page (keep form values)
        die();
    }
} else { //Error: uome form fields are empty
	$_SESSION["e"] = 1; //Store error code
    echo"<script>window.history.back();</script>"; //Go back to form page (keep form values)
    die();
}

?>