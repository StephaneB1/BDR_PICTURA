<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 15.05.2017
 * Summary: Invite users by their email address or login
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
    $eventAuthor = $db->getEventAuthor($eventId);
    $participants = $db->getEventParticipants($eventId, 1);

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

//Description
if(!empty($_POST["users"])) {
    $data = explode(",", $_POST["users"]); //Escape apostrophes
} else { //No description -> ok
    $formCorrect = false;
}

//Verification before sending data
if($formCorrect == true) {
	$errorCount = 0;
	$hostname = getHostUrl();

    for($i = 0; $i < count($data); $i++) {
		//User can subscribe if there isn't a participants limit or if there's free place
		if($event[0]["eveMaxParticipants"] == 0 || count($participants) < $event[0]["eveMaxParticipants"]) {
			//Get user by email and login (to check if it is an email or login)
			$userByEmail = $db->getUserByEmail($data[$i]);
			$userByLogin = $db->getUserByLogin($data[$i]);
			
			if(empty($userByEmail) && empty($userByLogin)) {
				/* ------ Non-existing user ------*/

                //Validate email address
                if (filter_var($data[$i], FILTER_VALIDATE_EMAIL)) {
                    $password = randomString(20); //Create random password
                    $login = limitString(cleanifyString($data[$i]), 5, uniqid()); //Create unique login

                    //Create user's account
                    $newUserId = $db->insertUser($login, "", "", password_hash($password, PASSWORD_DEFAULT), $data[$i]);

                    $db->subscribeEvent($newUserId, $eventId, 3, 0); //Subscribe to event

                    $to = $data[$i]; //Mail recepient
                    //Mail content
                    $message = "
                    <html>
                        <head>
                            <link rel='stylesheet' type='text/css' href='".$hostname."/css/main.css'/>
                        </head>
                        <body>
                            <h1>Invitation à un événement</h1>
                            <p>
                                ".$eventAuthor[0]['useFirstname']." ".$eventAuthor[0]['useLastname']." (".$eventAuthor[0]['useLogin'].") vous a invité à son événement: ".$event[0]['eveTitle'].".
                            </p>
                            <br/>
                            <p>
                                Votre nom d'utilisateur: <span class='red'>".$login."</span>
                            <br/>
                                Votre mot de passe: <span class='red'>".$password."</span>
                            </p>

                            <p>
                                Accepter/refuser invitation: <a href='".$hostname."/displayEvent.php?id=".$eventId."'>".$hostname."/displayEvent.php?id=".$eventId."</a>
                            </p>
                            <br/>
                            <p>
                                Si vous désirez supprimer ou modifier votre compte EventManager (qui vous a été créé automatiquement), suivez ce lien: <a href='".$hostname."/profile.php'>".$hostname."/profile.php</a>
                            </p>
                        </body>
                    </html>
                    ";
				} //Validate email address
			} else { //Email address corresponds to an existing account
				/* ------ Existing user ------ */

				if(empty($userByEmail)) {
					$userId = $userByLogin[0]["idUser"];
				} else {
					$userId = $userByEmail[0]["idUser"];
				}

				//Check for eventual existing subscription
				$subscription = $db->getSubscription($userId, $eventId);
				if(empty($subscription)) {
					//User not subscribed yet -> invite
					
					$db->subscribeEvent($userId, $eventId, 3, 0);
			
					$user = $db->getUserById($userId);
					
					//Users cannot invite a global admin
					if($user[0]["isAdmin"] != 1) {					
						$to = $user[0]["useEmail"]; //Mail recepient
						//Mail content
						$message = "
						<html>
							<head>
								<link rel='stylesheet' type='text/css' href='".$hostname."/css/main.css'/>
							</head>
							<body>
								<h1>Invitation à un événement</h1>
								<p>
									".$eventAuthor[0]['useFirstname']." ".$eventAuthor[0]['useLastname']." (".$eventAuthor[0]['useLogin'].") vous a invité à son événement: ".$event[0]['eveTitle'].".
								</p>
								<br/>
								<p>
									Accepter/refuser invitation: <a href='".$hostname."/displayEvent.php?id=".$eventId."'>".$hostname."/displayEvent.php?id=".$eventId."</a>
								</p>
							</body>
						</html>
						";						
					}
				} //Check if subscription exists
			} //Existing user
			
			if(!empty($message)) {
				//Send mail
				$subject = "Invitation à: ".$event[0]["eveTitle"];
				//Set header informations
				//$headers = "MIME-Version: 1.0"."\r\n";
				$headers = "Content-type:text/html;charset=UTF-8"."\r\n"; //Define type (HTML)
				$headers .= 'From: <noreply@eventmanager.ch>'."\r\n"; //Define sender
				
				if(!mail($to,$subject,$message,$headers)) {
					echo "Erreur: mail de '".$data[$i]."' invalide<br/>";
				}
			} else {
				$errorCount ++;
			}
		} else {
			$_SESSION["e"] = 6; //Store error code
			break;
		} //Check event's participant limit
    }
	
    if(!empty($errorCount)) {
        $_SESSION["e"] = 7; //Store error code

        if($errorCount > 1) {
            $_SESSION["r"] = "Erreur: ".$errorCount." personnes n'ont pas été invitées.";
        } else {
            $_SESSION["r"] = "Erreur: ".$errorCount." personne n'a pas été invitée.";
        }
    }

    header("Location: ../../displayEvent.php?id=".$eventId);
} else { //Error: some form fields are empty
	$_SESSION["e"] = 1; //Store error code
    header("Location: ../../displayEvent.php?id=".$eventId);
}

?>