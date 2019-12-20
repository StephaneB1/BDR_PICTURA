<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 18.05.2017
 * Summary: Export the given event list to a PDF document
 */

date_default_timezone_set("Europe/Zurich");
require_once("../../lib/html2pdf/html2pdf.class.php");
include_once("../include/func.php");

include_once("..//include/dbConnect.php");
$db = new db;

//User must be logged in to access this page
if(!checkIfLoggedIn()) {
    stopAndMove();
}

if(!empty($_GET["id"])) {
    //Check if current user has permission to print event
    if(!checkUserEventPermission($_GET["id"], false, false)) {
        stopAndMove();
    }

    //One specific event will be displayed
    $events = $db->getEventById($_GET["id"]);
} elseif(!empty($_GET["c"])) {
    //List of event will be displayed
    switch($_GET["c"]) {
        default: { //Error: no content to display
            stopAndMove();
            break;
        }
        //All public events
        case 1: {
            $title = "Événements publics";
            $events = $db->getAllPublicEvents();
            break;
        }
        //Subscribed events
        case 2: {
            $title = "Événements auxquels vous participez";
            $events = $db->getSubscribedEvents($_SESSION["userId"]);
            break;
        }
        //Created events
        case 3: {
            $title = "Événements créés";
            $events = $db->getAllCreatedEvents($_SESSION["userId"]);
            break;
        }
        //Invited events
        case 4: {
            $title = "Invitations";
            $events = $db->getInvitedEvents($_SESSION["userId"]);
            break;
        }
    }
} else { //Error: no content to display
    stopAndMove();
}

ob_start();

$taskBullet = "-";

//Initialize content and set some CSS style
echo "
<style>
    table {
        width: 100%; margin-top: 50px;
        border-collapse: collapse;
        border-spacing: 0;
    }
    td {
        width: 50%;
        padding: 5px;
    }
    th {
        background-color: #5B91D6;
        color: white;
        border: none;
        width: 100%;
        text-align: center;
        font-weight: bold;
        font-size: 30px;
    }
    td.subtitle {
        font-weight: bold;
        border-bottom: 1px solid #3F6595;
        color: #3F6595;
    }
    h1 {
        text-align: center;
    }
    .bold {
        font-weight: bold;
    }
    .orange {
        color: #f78f45;
    }
</style>
";

//Display title if there's more that 1 event
if(empty($_GET["id"])) {
    echo "<h1>".$title."</h1>";
}

//Browse events
for($i = 0; $i < count($events); $i++) {
    $eventId = $events[$i]["idEvent"];

    $price = ($events[$i]["evePrice"] > 0 ? $events[$i]["evePrice"].".-" : "Gratuit");

    echo "
<table>
    <tr>
        <th colspan='2'>".htmlentities($events[$i]['eveTitle'])."</th>
    </tr>
    <tr>
        <td>".htmlentities($events[$i]['eveDescription'])."</td>
        <td>
            Lieu: ".$events[$i]['eveLocation']."<br/><br/>
            Du: ".formatDate($events[$i]['eveStartDate'], 'd.m.Y à G:i')."<br/>
            Au: ";

    if($events[$i]["eveEndDate"] != 0) {
        echo formatDate($events[$i]["eveEndDate"], "d.m.Y à G:i");
    } else {
        echo "-";
    }

    echo "<br/><br/>
    Prix: ".$price;

    echo "</td>
    </tr>
    <tr>
        <td class='subtitle'>Participants</td>
        <td class='subtitle'>Tâches</td>
    </tr>
    ";

    //Author
    $author = $db->getEventAuthor($eventId);
    $authorTasks = $db->getEventUserTask($author[0]["idUser"], $eventId);

    echo "
    <tr>
        <td class='bold'>".htmlentities($author[0]['useFirstname'])." ".htmlentities($author[0]['useLastname'])." (".htmlentities($author[0]['useLogin']).")</td>
        <td>
    ";
    //Author's tasks
    for($j = 0; $j < count($authorTasks); $j++) {
        echo
            $taskBullet." ".htmlentities($authorTasks[$j]['tasTitle'])."<br/>"
        ;
    }
    echo "
        </td>
    </tr>
        ";

    //Browse participants
    $participants = $db->getEventParticipants($eventId, 1);
    $count = 0;
    for($j = 0; $j < count($participants); $j++) {
        //Display user only if it's not the author (because author has already been displayed)
        if($participants[$j]["idUser"] != $author[0]["idUser"]) {
            $count ++;
            $userTasks = $db->getEventUserTask($participants[$j]["idUser"], $eventId);

            echo "
    <tr";
            if($count % 2 != 0) {
                echo " style='background-color: lightgrey'";
            }

            echo "><td>
        ".$participants[$j]['useFirstname']." ".htmlentities($participants[$j]['useLastname'])." (".htmlentities($participants[$j]['useLogin']).")
        </td>
        <td>
            ";

            //Display user's tasks
            for($k = 0; $k < count($userTasks); $k++) {
                if($userTasks[$k]["tasIsApproved"] != 1) {
                    echo
                        "<span class='orange'>".$taskBullet." ".htmlentities($userTasks[$k]['tasTitle'])."</span><br/>"
                    ;
                } else {
                    echo
                        "<span>".$taskBullet." ".htmlentities($userTasks[$k]['tasTitle'])."</span><br/>"
                    ;
                }
            }

            echo "
        </td></tr>";
        }
    } //End participants browsing

    //Display unassigned tasks
    $unassignedTasks = $db->getEventUnAssignedTasks($eventId);
    echo "
    <tr style='background-color: #3F6595; color: white;'>
        <td class='bold'>Tâches non-assignées</td>
        <td>
    ";
    for($j = 0; $j < count($unassignedTasks); $j++) {
        echo $taskBullet." ".htmlentities($unassignedTasks[$j]['tasTitle'])."<br/>";
    }

    echo "
        </td>
    </tr>
    ";

    echo "
</table>
    ";
} //End events browsing

$html2pdf = new HTML2PDF('P','A4','fr');
$html2pdf->pdf->SetTitle("EventManager - export PDF");
$html2pdf->WriteHTML(ob_get_clean());
$html2pdf->Output('EM-Export.pdf');

die();

?>