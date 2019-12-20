<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 01.05.2017
 * Summary: General functions
 */

date_default_timezone_set("Europe/Zurich");

//File upload settings
include($_SERVER['DOCUMENT_ROOT']."/config/files_config.php");

/*
* Check if the user is connected with a valid account
* @return true/false wether the user is connected with a valid account or not
*/
function checkIfLoggedIn() {
    $isConnected = false;

    //Start session if not already done
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    //Check if session has a valid userId
    if(!empty($_SESSION['userId'])) {
        include_once($_SERVER['DOCUMENT_ROOT']."/php/include/dbConnect.php");
        $db = new db;

        //Verify user's account existence
        $user = $db->getUserById($_SESSION['userId']);
        if(!empty($user)) {
            $isConnected = true;
        }
    }
	
    return $isConnected;
}
/*
* Check if the user is connected as a global admin
* @return true/false
*/
function checkIfAdmin() {
    $isAdmin = false;

    //Start session if not already done
    if(session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    //Admin session variable must be true
    if(!empty($_SESSION["isAdmin"]) && $_SESSION["isAdmin"] == true) {
        include_once($_SERVER['DOCUMENT_ROOT']."/php/include/dbConnect.php");
        $db = new db;

        //Verify user's account existence and permission
        $user = $db->getUserById($_SESSION['userId']);
        if(!empty($user) && $user[0]["useIsAdmin"]) {
            $isAdmin = true;
        }
    }
    return $isAdmin;
}

/*
* Check if the user has access to an event
* @param $eventId: ID of the event the user wants to access
* @param $mustBeAdmin: defines if the user must be author of the event
* @param $mustHaveSubscription: defines if the user must have subscribed to the event or not
* @return true/false
*/
function checkUserEventPermission($eventId, $mustBeAdmin, $mustHaveSubscription) {
    $hasPermission = true;
    include_once($_SERVER['DOCUMENT_ROOT']."/php/include/dbConnect.php");
    $db = new db;

    $event = $db->getEventById($eventId);

    //Set user ID
    if(empty($_SESSION["userId"])) {
        $userId = null;
    } else {
        $userId = $_SESSION["userId"];

    }

	//If needed, check user subscription
    $userSubscription = $db->getSubscription($userId, $eventId);
    if($mustHaveSubscription && empty($userSubscription)) {
        $hasPermission = false;
    }

	//Verify user right (author or not)
    if($mustBeAdmin) {
        if(!empty($userSubscription)) {
            if($userSubscription[0]["eveuseIsAdmin"] != 1){
                $hasPermission = false;
            }
        } else {
            $hasPermission = false;
        }
    } elseif($event[0]["eveIsPublic"] != 1) { //Private event
        if(empty($userSubscription)) {
			//Event is private and user doesn't isn't subscribed
            $hasPermission = false;
        }
    }

    //If user is general admin -> permission always true
    if(checkIfAdmin()) {
        $hasPermission = true;
    }
    return $hasPermission;
}

/*
* Removes spaces and special chars (only lower/uppercases letters and numbers)
*/
function cleanifyString($string) {
    $newString = str_replace(" ", "", $string); //Remove spaces
    return preg_replace("/[^A-Za-z0-9]/", "", $newString); //Removes all special chars
}

/*
* Change format of a date
* @param $date: Input date
* @param $format: Requested output format
* @return: Formated date
*/
function formatDate($date, $format) {
    $newDate = new DateTime($date);
    $newDate = $newDate->format($format);

    return $newDate;
}


/*
* Returns a randomized string
* @param $length: Length of the result
* @return: Random string
*/
function randomString($length) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters)-1)];
    }
    return $randomString;
}

/*
 * Summary: Use this to stop script and move to root directory
 * @param $page: Specify a page to redirect to,
 * if null -> index.php which only works for files in root folder)
 */
function stopAndMove($page) {
    if(empty($page)) {
        $page = "index.php";
    }
    header("Location: ".$page);
    die();
}

/*
 * Summary: Use this to stop script and move to root directory
 * @param $string: Text to limit
 * @param $maxLength: Final string length (+ $endChars length!)
 * @param $endChars: Chars to add at the end of the string (ex: '...')
 */
function limitString($string, $maxLength, $endChars) {
	if($string != null && $string != "") {
		if (strlen($string) > $maxLength) {
			$string = substr($string, 0, $maxLength).$endChars;
		}
	} else {
		$string = "";
	}

    return $string;
}

/*
 * Handles the file upload via $_FILES[]
 * @param $acceptedExtensions: All the accepted extensions. Default value are in files_config.php
 * @param $fileUploadName: 'name' property of $_FILE
 * @param $fileUploadTempName: 'temp-name' property of $_FILE
 * @param $fileUploadError: 'error' property of $_FILE
 */
function uploadFile($acceptedExtensions, $fileUploadName, $fileUploadTempName, $fileUploadError) {
    //If no extensions parameter -> catch images and videos
    if(empty($acceptedExtensions)) {
        $acceptedExtensions = array_merge(IMAGE_FORMATS, VIDEO_FORMATS);
    }
	
    //If no errors have occured, proceed to verification
    if(empty($fileUploadError)) {
        //Get the file's extension
        $extension = strtolower(pathinfo($fileUploadName, PATHINFO_EXTENSION));

        //Verify if the file is from the correct format
        if(in_array($extension, $acceptedExtensions)) {
            $cleanedTempName = strtolower(cleanifyString(pathinfo($fileUploadName, PATHINFO_FILENAME)));
            //New name: original filename with 10 chars max + "_" + unique string
            $newName = substr($cleanedTempName, 0, 10)."_".uniqid().".".$extension;
            $targetPath = "../../files/".$newName; //Location where the file will be uploaded
			
            //Move file to folder
            if(move_uploaded_file($fileUploadTempName, $targetPath)) {
                $result = $newName; //If no error -> return name to insert it in database
            } else { //Error: file note uploaded
                $result = false;
            }
        } else { //Error: file does not have the correct extension
            $result = false;
        }
    } else { //Error: no file were sent
        $result = false;
    }
    return $result;
} //End uploadFile function

/*
 * Display a table with all the given events listed in it
 * @param $eventsArray: array containing all the events to list
 * @param $displayAuthor: Defines if the author must be displayed
 * @param $displayPublic: Defines if the public/private status must be shown
 * @param $displayTasks: Defines if the number of tasks must be shown
 */
function displayEventsList($eventsArray, $displayAuthor, $displayPublic, $displayTasks) {
	include_once($_SERVER['DOCUMENT_ROOT']."/php/include/dbConnect.php");
	$db = new db;

    if(!empty($eventsArray)) {
        //Display headers
        echo "
    <table class='datatable center-x'>
        <tr>
            <th>Titre</th>
            <th>Date de début</th>
            <th class='mobile-hide'>Participants</th>";
        if ($displayTasks) {
            echo "<th class='mobile-hide'>Tâches</th>";
        }
        if ($displayAuthor) {
            echo "<th>Auteur</th>";
        }
        if ($displayPublic) {
            echo "<th>Privé</th>";
        }
        echo "</tr>";

        //Browse events
        for ($i = 0; $i < count($eventsArray); $i++) {
            $author = $db->getEventAuthor($eventsArray[$i]["idEvent"]);
            $participants = $db->getEventParticipants($eventsArray[$i]["idEvent"], 1); //Coming users

            echo "
        <tr class='link";
            if(new DateTime($eventsArray[$i]["eveStartDate"]) < new DateTime()) {
                //Event has passed
                echo " old";
            }
            echo"' onclick=\"document.location='displayEvent.php?id=" . $eventsArray[$i]['idEvent'] . "'\">
            <td>" . htmlentities(limitString($eventsArray[$i]['eveTitle'], 20, '...')) . "</td>
            <td>" . formatDate($eventsArray[$i]['eveStartDate'], 'd.m.Y') . "</td>";

            //Display particitants (with limit if there's one)
            if ($eventsArray[$i]["eveMaxParticipants"] != 0) {
                echo "<td class='mobile-hide'>" . count($participants) . "/" . $eventsArray[$i]['eveMaxParticipants'] . "</td>";
            } else {
                echo "<td class='mobile-hide'>" . count($participants) . "</td>";
            }

            //Display the number of tasks
            if ($displayTasks) {
                $tasks = $db->getEventTasks($eventsArray[$i]["idEvent"]);
                if (empty($tasks)) {
                    //No tasks
                    echo "<td class='mobile-hide'>-</td>";

                } else {
                    $assignedTasksCount = $db->getEventAssignedTasksCount($eventsArray[$i]["idEvent"])[0]["assignedTasks"];
                    echo "<td class='mobile-hide'>" . $assignedTasksCount . "/" . count($tasks) . "</td>";
                }
            }

            //Display the login of the author
            if ($displayAuthor) {
                echo "<td>" . limitString(htmlentities($author[0]['useLogin']), 20, '...') . "</td>";
            }

            //Display an icon wether the event is private or not (if public -> "-")
            if ($displayPublic) {
                if ($eventsArray[$i]["eveIsPublic"] == 1) {
                    echo "<td>-</td>";
                } else {
                    echo "<td><i class='material-icons' title='Événement privé'>lock_outline</i></td>";
                }
            }
            echo "</tr>";
        }

        echo "
	</table>
	";
    } else { //No data -> display message
        echo "<p>Aucun événement</p>";
    }
} //End displayEventsList function

/*
 * Return the total space used by an event's media
 * @param $eventId: ID of the event
 * @return: Size in bytes
 */
function getEventMediaSize($eventId) {
    include_once($_SERVER['DOCUMENT_ROOT']."/php/include/dbConnect.php");
    $db = new db;
    $media = $db->getEventMedias($eventId);

    $totalSize = 0;

    for($i = 0; $i < count($media); $i++) {
        $totalSize += filesize($_SERVER['DOCUMENT_ROOT']."/files/".$media[$i]["medFilename"]);
    }

    return round($totalSize/1024/1024, 2); //Return total size in MB rounded to 2 decimals
}

/*
 * Get the host's URL (ex: "http://localhost:8080")
 * @return: String
 */
function getHostUrl() {
	if(isset($_SERVER["HTTPS"])) {
		$protocol = "https://";
	} else {
		$protocol = "http://";
	}
	return $protocol.$_SERVER["HTTP_HOST"];
}








?>