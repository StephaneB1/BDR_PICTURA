<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 19.05.2017
 * Summary: Returns the most popular events
 */

header("Content-type: text/json");

include("../include/func.php");
include("../include/dbConnect.php");
$db = new db;

if(!empty($_GET["n"]) && $_GET["n"] > 0) {
    $eventLimit = $_GET["n"];
} else {
    $eventLimit = 0;
}

//Type of data (per date or per participants)
if(!empty($_GET["t"]) && $_GET["t"] == 1) {
    $events = $db->getTopFutureEventsPerDate($eventLimit);
} else {
    $events = $db->getTopFutureEvents($eventLimit);
}

for($i = 0; $i < count($events); $i++) {
    //Add date to event's title
    $events[$i]["eveTitle"] = limitString($events[$i]['eveTitle'], 45, '...')
        ." (".formatDate($events[$i]['eveStartDate'], 'd.m.Y à G:i').")";
}

echo json_encode($events);

?>