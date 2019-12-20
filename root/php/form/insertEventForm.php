<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 04.05.2017
 * Summary: Insert a new event
 */

session_start();

$formCorrect = true; //If false, data won't be sent
include_once("../include/func.php");
//User must be logged in to acces this page
if(!checkIfLoggedIn()) {
    stopAndMove("../../index.php");
}

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
    //Connect to database
    include_once("../include/dbConnect.php");
    $db = new db;

    //Check if title already exists
    $titleExists = false;
    $allTitles = $db->getAllEventTitles();
    for($i = 0; $i < count($allTitles); $i++) {
        if(strtolower($title) == strtolower($allTitles[$i]["eveTitle"])) {
            $titleExists = true;
        }
    }

    //Title must not be used yet
    if($titleExists == false) {
        //Datetime treatment
        $startDateTime = formatDate($startDate, "Y-m-j")." ".$startHour.":".$startMinute.":00";
        if($noEndDateTime == true) {
            $endDateTime = null;
        } elseif($startDate == $endDate
                && ($startHour > $endHour)
                || ($startHour == $endHour && $startMinute >= $endMinute)) {
                $_SESSION["e"] = 8; //Store error code
                header("Location: ../../events.php");
        } else {
            $endDateTime = formatDate($endDate, "Y-m-j")." ".$endHour.":".$endMinute.":00";
        }

        $id = $db->insertEvent($title, $description, $startDateTime, $endDateTime, $location, $price, $isPublic, $limit, $_SESSION["userId"]);
        header("Location: ../../displayEvent.php?id=".$id);
    } else { //Error: title is already taken
		$_SESSION["e"] = 4; //Store error code
        echo "<script>window.history.back();</script>"; //Go back to form page (keep form values)
    }
} else { //Error: uome form fields are empty
	$_SESSION["e"] = 1; //Store error code
    echo "<script>window.history.back();</script>"; //Go back to form page (keep form values)
}

?>