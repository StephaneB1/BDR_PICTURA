<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 28.04.2017
 * Summary: This class handles all database interactions via specific public functions
 */

class db
{
    /* ------ General ------ */

    private $connexion;

    /*
     * This function is automatically executed on class instantiation
     */
    function __construct() {
        date_default_timezone_set("Europe/Zurich"); //Define timezone

        //Extract database login informations from PHP config file
        $config = include($_SERVER['DOCUMENT_ROOT']."/config/db_config.php");
        try {
            $this->connexion = new pdo(
                "mysql:host=".$config["hostname"].
                ";port=".$config["port"].
                ";dbname=".$config["database"],
                $config["user"],
                $config["password"]);
            $this->connexion->exec("set names utf8");
        } catch(PDOException $e) { //Catch error
            print "Erreur: ".$e->getMessage();
            die();
        }
    }

    /*
     * Get the ID of the last inserted entry
     */
    public function getLastId() {
        return $this->connexion->lastInsertId();
    }

    /* ------ Users ------ */

    /*
     * Get all the user logins
     */
    public function getAllUserLogins() {
        $query = $this->connexion->prepare("
          SELECT useLogin, idUser FROM t_user
        ");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get all the user email addresses
     */
    public function getAllUserEmails() {
        $query = $this->connexion->prepare("
          SELECT useEmail, idUser FROM t_user
        ");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
	
	/*
	* Summary: Get user entry with his ID
	*/
	public function getUserById($id) {
		$query = $this->connexion->prepare("
          SELECT * FROM t_user
          WHERE idUser = $id
		");
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}
	
	/*
	* Summary: Get user entry with his login
	*/
	public function getUserByLogin($login) {
		$query = $this->connexion->prepare("
          SELECT * FROM t_user
		  WHERE useLogin = '$login'
		");
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	/*
	* Summary: Get user entry with his login
	*/
	public function getUserByEmail($email) {
		$query = $this->connexion->prepare("
          SELECT * FROM t_user
		  WHERE useEmail = '$email'
		");
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

	/*
	* Summary: Get user entry with his login
	*/
	public function getUserPicture($userId) {
		$query = $this->connexion->prepare("
          SELECT usePicture FROM t_user
		  WHERE idUser = '$userId'
		");
		$query->execute();
		$result = $query->fetchAll(PDO::FETCH_ASSOC);
		return $result;
	}

    /*
     * Insert new user account
     */
    public function insertUser($login, $firstName, $lastName, $password, $email) {
        $query = $this->connexion->prepare("
          INSERT INTO t_user (useLogin, useFirstname, useLastname, usePassword, useEmail)
		  VALUES ('$login', '$firstName', '$lastName', '$password', '$email')
		");
        $query->execute();
		
		$lastId = $this->connexion->lastInsertId();
		return $lastId;
    }

    /*
     * Edit existing user account
     */
    public function editUser($userId, $login, $firstName, $lastName, $password, $email) {
        if(empty($password)) { //No password modification
            $sql = "
            UPDATE t_user
            SET useLogin = '$login',
              useFirstname = '$firstName',
              useLastname = '$lastName',
              useEmail = '$email'
            WHERE idUser = $userId";
        } else { //Password has changed
            $sql = "
            UPDATE t_user
            SET useLogin = '$login',
              useFirstname = '$firstName',
              useLastname = '$lastName',
              usePassword = '$password',
              useEmail = '$email'
            WHERE idUser = $userId";
        }

        $query = $this->connexion->prepare($sql);
        $query->execute();
    }

    /*
     * Edit a user's profile picture
     */
    public function editUserPicture($userId, $fileName) {
        $query = $this->connexion->prepare("
        UPDATE t_user
            SET usePicture = '$fileName'
            WHERE idUser = $userId
        ");
        $query->execute();
    }
		
	/*
	* Summary: Delete a specific user
	*/
	public function deleteUserDefinitely($userId) {
        //Delete user's messages
        $query = $this->connexion->prepare("
          DELETE FROM t_message
          WHERE fkUser = $userId
		");
        $query->execute();

        //Delete all user's events
        $events = $this->getAllCreatedEvents($userId);
        for($i = 0; $i < count($events); $i++) {
            $this->deleteEventDefinitely($events[$i]["idEvent"]);
        }

        //Delete user's subscriptions
        $query = $this->connexion->prepare("
          DELETE FROM t_event_has_user
          WHERE idxUser = $userId
		");
        $query->execute();

        //Unassign user's tasks
        $query = $this->connexion->prepare("
          UPDATE t_task
            SET fkUser = NULL
            WHERE fkUser = $userId
        ");
        $query->execute();

        //Delete user account
		$query = $this->connexion->prepare("
          DELETE FROM t_user
          WHERE idUser = $userId
		");
		$query->execute();
	}

    /* ------ Events ------ */

    /*
     * Get all the public events
     */
    public function getAllPublicEvents() {
        $query = $this->connexion->prepare("
          SELECT * FROM t_event
		  WHERE eveIsPublic = 1
          ORDER BY (CASE WHEN DATE(eveStartDate) < DATE(CURDATE())
              THEN 0
              ELSE 1
            END) DESC, eveStartDate ASC;
        ");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get all the events, public + private ( global admin only)
     */
    public function getAllEvents() {
        $query = $this->connexion->prepare("
          SELECT
            idEvent,
            eveTitle,
            eveStartDate,
            eveMaxParticipants,
            eveIsPublic
            FROM t_event
          ORDER BY
            (CASE WHEN DATE(eveStartDate) < DATE(CURDATE())
              THEN 0
              ELSE 1
            END) DESC, eveStartDate ASC;
        ");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get all the public events whose start date hasn't passed yet
     */
    public function getAllFuturePublicEvents() {
        $query = $this->connexion->prepare("
          SELECT * FROM t_event
		  WHERE eveIsPublic = 1
		  AND eveStartDate > CURDATE()
		  ORDER BY eveStartDate;
        ");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get all the public events whose start date hasn't passed yet
     */
    public function getAllCreatedEvents($userId) {
        $query = $this->connexion->prepare("
          SELECT t_event.* FROM t_event_has_user
          INNER JOIN t_event
            ON t_event_has_user.idxEvent = t_event.idEvent
		  WHERE idxUser = $userId
            AND eveuseIsAdmin = 1
		  ORDER BY (CASE WHEN DATE(eveStartDate) < DATE(CURDATE())
              THEN 0
              ELSE 1
            END) DESC, eveStartDate ASC;
        ");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get all events' titles
     */
    public function getAllEventTitles() {
        $query = $this->connexion->prepare("
          SELECT idEvent, eveTitle FROM t_event
        ");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get the author of an event
     */
    public function getEventAuthor($eventId) {
        $query = $this->connexion->prepare("
          SELECT t_user.idUser, t_user.useLogin, t_user.useFirstname, t_user.useLastname FROM t_event_has_user
          INNER JOIN t_user
            ON t_event_has_user.idxUser = t_user.idUser
		  WHERE eveuseIsAdmin = 1
		    AND idxEvent = $eventId
        ");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get a specific event by its ID
     */
    public function getEventById($eventId) {
        $query = $this->connexion->prepare("
          SELECT * FROM t_event
          WHERE idEvent = $eventId
        ");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Insert new event
     */
    public function insertEvent($title, $description, $startDateTime, $endDateTime, $location, $price, $isPublic, $limit, $userId) {
        //Insert event
        $query = $this->connexion->prepare("
          INSERT INTO t_event (eveTitle, eveDescription, eveStartDate, eveEndDate, eveLocation, evePrice, eveIsPublic, eveMaxParticipants)
		  VALUES ('$title', '$description', '$startDateTime', '$endDateTime', '$location', $price, $isPublic, $limit)
		");
        $query->execute();
        $eventId = $this->connexion->lastInsertId();

        //Insert author's participation entry (he's coming to the event by default)
        $this->subscribeEvent($userId, $eventId, 1, 1);

        return $eventId;
    }

    /*
     * Edit existing user account
     */
    public function editEvent($eventId, $title, $description, $startDateTime, $endDateTime, $location, $price, $isPublic, $limit) {
        $query = $this->connexion->prepare("
          UPDATE t_event
          SET eveTitle = '$title',
            eveDescription = '$description',
            eveStartDate = '$startDateTime',
            eveEndDate = '$endDateTime',
            eveLocation = '$location',
            evePrice = $price,
            eveIsPublic = $isPublic,
            eveMaxParticipants = $limit
          WHERE idEvent = $eventId
        ");
        $query->execute();
    }

    /*
     * Delete an existing event
     */
    public function deleteEventDefinitely($eventId) {
        //Delete event's tasks
        $tasks = $this->getEventTasks($eventId);
        for($i = 0; $i < count($tasks); $i++) {
            $this->deleteTask($tasks[$i]["idTask"]);
        }

        //Delete event's message
        $messages = $this->getEventMessages($eventId);
        for($i = 0; $i < count($messages); $i++) {
            $this->deleteMessage($messages[$i]["idMessage"]);
        }

        //Delete event's medias
        $medias = $this->getEventMedias($eventId);
        for($i = 0; $i < count($medias); $i++) {
            $this->deleteMedia($medias[$i]["idMedia"]);
        }

        //Delete subscriptions
        $subscriptions = $this->getEventParticipants($eventId, null);
        for($i = 0; $i < count($subscriptions); $i++) {
            $this->deleteSubscription($subscriptions[$i]["idUser"], $subscriptions[$i]["idEvent"]);
        }

        //Delete event
        $query = $this->connexion->prepare("
          DELETE FROM t_event
          WHERE idEvent = $eventId
        ");
        $query->execute();
    }

    /* ------ Subscriptions ------ */

    /*
	 * Subscribe user to event
	 */
    public function subscribeEvent($userId, $eventId, $status, $isAdmin) {
        if(!isset($status)) {
            $status = 3; //Default is "invited"
        }
        if(!isset($isAdmin)) {
            $isAdmin = 0; //By default, user is not admin
        }

        $subscription = $this->getSubscription($userId, $eventId);

        if($subscription == null) { //Make new one
            $sql = "
            INSERT INTO t_event_has_user (idxEvent, idxUser, eveuseStatus, eveuseIsAdmin)
              VALUES ($eventId, $userId, $status, $isAdmin)";
        } else { //Update existing
            switch($status) {
                case 0: { //Denied/delete
                    //If user is author -> update instead of deleting
                    if($subscription[0]["eveuseIsAdmin"] == 1) {
                        $sql = "UPDATE t_event_has_user
						SET eveuseStatus = $status
						WHERE idxUser = $userId
						AND idxEvent = $eventId";
                    } else {
                        //Delete user's subscription and unassign his tasks

                        $query = $this->connexion->prepare("
                        UPDATE t_task
						  SET tasIsApproved = 0,
						  fkUser = NULL
						WHERE fkUser = $userId
						  AND fkEvent = $eventId");
                        $query->execute();

                        $sql = "DELETE FROM t_event_has_user
						WHERE idxUser = $userId
						AND idxEvent = $eventId";
                    }
                    break;
                }
                case 1: { //Coming
                    $sql = "UPDATE t_event_has_user
						SET eveuseStatus = $status
						WHERE idxUser = $userId
						AND idxEvent = $eventId";
                    break;
                }
                case 2: { //Maybe
                    $sql = "UPDATE t_event_has_user
						SET eveuseStatus = $status
						WHERE idxUser = $userId
						AND idxEvent = $eventId";
                    break;
                }
                case 3: { //Invited (pending)
                    $sql = "UPDATE t_event_has_user
						SET eveuseStatus = $status
						WHERE idxUser = $userId
						AND idxEvent = $eventId";
                    break;
                }
            }
        }

        $query = $this->connexion->prepare($sql);
        $query->execute();
    }

    /*
    * Force delete of a subscription
    */
    public function deleteSubscription($userId, $eventId) {
        $query = $this->connexion->prepare("
            DELETE FROM t_event_has_user
            WHERE idxUser = $userId
              AND idxEvent = $eventId");
        $query->execute();
    }

    /*
    * Force delete of all "maybe" status subscription
    */
    public function deleteMaybeSubscriptions($eventId) {	
		$authorId = $this->getEventAuthor($eventId)[0]["idUser"];
	
        $query = $this->connexion->prepare("
            DELETE FROM t_event_has_user
            WHERE idxUser <> $authorId
              AND eveuseStatus = 2
              AND idxEvent = $eventId");
        $query->execute();
    }

    /*
    * Get subscribed event of a user
    */
    public function getSubscribedEvents($userId) {
        $sql = "SELECT t_event.*, t_event_has_user.eveuseStatus FROM t_event_has_user
			INNER JOIN t_event ON t_event_has_user.idxEvent = t_event.idEvent
			WHERE t_event_has_user.idxUser = $userId
			  AND t_event_has_user.eveuseStatus <> 0
              AND t_event_has_user.eveuseStatus <> 3
			ORDER BY (CASE WHEN DATE(eveStartDate) < DATE(CURDATE())
                THEN 0
                ELSE 1
              END) DESC, eveStartDate ASC";

        $query = $this->connexion->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
    * Get subscribed event of a user
    */
    public function getInvitedEvents($userId) {
        $sql = "SELECT t_event.*, t_event_has_user.eveuseStatus FROM t_event_has_user
			INNER JOIN t_event ON t_event_has_user.idxEvent = t_event.idEvent
			WHERE t_event_has_user.idxUser = $userId
			  AND t_event_has_user.eveuseStatus = 3
			ORDER BY (CASE WHEN DATE(eveStartDate) < DATE(CURDATE())
                THEN 0
                ELSE 1
              END) DESC, eveStartDate ASC";

        $query = $this->connexion->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
    * Summary: Get a specific subscription
    */
    public function getSubscription($userId, $eventId) {
        $sql = "SELECT * FROM t_event_has_user
			WHERE idxUser = $userId
			AND idxEvent = $eventId";

        $query = $this->connexion->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /*
    * Summary: Get the participants of an event
    */
    public function getEventParticipants($eventId, $status) {
        if($status == null) {
            $sql = "SELECT idUser, useLogin, useFirstname, useLastname, eveuseStatus, idEvent FROM t_event_has_user
			INNER JOIN t_user ON t_event_has_user.idxUser = t_user.idUser
			INNER JOIN t_event ON t_event_has_user.idxEvent = t_event.idEvent
			WHERE t_event_has_user.idxEvent = $eventId
			ORDER BY eveuseStatus, useLogin ASC";
        } else {
            $sql = "SELECT idUser, useLogin, useFirstname, useLastname, eveuseStatus FROM t_event_has_user
			INNER JOIN t_user ON t_event_has_user.idxUser = t_user.idUser
			WHERE t_event_has_user.idxEvent = $eventId
			  AND t_event_has_user.eveuseStatus = $status
			ORDER BY eveuseStatus ASC";
        }

        $query = $this->connexion->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    /* ------ Tasks ------ */

    /*
     * Get all the tasks of an event
     */
    public function getEventTasks($eventId) {
        $query = $this->connexion->prepare("
          SELECT * FROM t_task
		  WHERE fkEvent = $eventId
		  ORDER BY tasIsApproved, fkUser ASC
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get all the tasks of an event
     */
    public function getEventUserTask($userId, $eventId) {
        $query = $this->connexion->prepare("
          SELECT * FROM t_task
		  WHERE fkEvent = $eventId
		    AND fkUser = $userId
		  ORDER BY tasIsApproved, fkUser ASC
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get unassigned tasks of an event
     */
    public function getEventUnAssignedTasks($eventId) {
        $query = $this->connexion->prepare("
          SELECT * FROM t_task
		  WHERE fkEvent = $eventId
		    AND tasIsApproved = 0
		    AND fkUser IS NULL
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get all the tasks of an event
     */
    public function getTaskById($taskId) {
        $query = $this->connexion->prepare("
          SELECT * FROM t_task
		  WHERE idTask = $taskId
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get number of assigned tasks of an event
     */
    public function getEventAssignedTasksCount($eventId) {
        $query = $this->connexion->prepare("
          SELECT COUNT(idTask) AS assignedTasks FROM t_task
		  WHERE fkEvent = $eventId
		    AND tasIsApproved = 1
		    AND fkUser IS NOT NULL
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Insert new task to an event
     */
    public function insertTask($eventId, $description) {
        $query = $this->connexion->prepare("
          INSERT INTO t_task (tasTitle, fkEvent)
            VALUES ('$description', $eventId)
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Request a task to be assigned
     */
    public function requestTask($taskId, $userId) {
        $query = $this->connexion->prepare("
          UPDATE t_task
          SET fkUser = $userId,
            tasIsApproved = 0
          WHERE idTask = $taskId
        ");
        $query->execute();
    }

    /*
     * Request a task to be assigned
     */
    public function approveTask($taskId, $approve) {
        if($approve == 1) { //Approved
            $sql = "
          UPDATE t_task
          SET tasIsApproved = 1
          WHERE idTask = $taskId
        ";
        } else { //Refused
            $sql = "
          UPDATE t_task
          SET tasIsApproved = 0,
            fkUser = NULL
          WHERE idTask = $taskId
        ";
        }

        $query = $this->connexion->prepare($sql);
        $query->execute();
    }

    /*
     * Delete a task
     */
    public function deleteTask($taskId) {
        $query = $this->connexion->prepare("
          DELETE FROM t_task
          WHERE idTask = $taskId
        ");
        $query->execute();
    }

    /* ------ Messages ------ */

    /*
     * Get all the messages of an event
     */
    public function getEventMessages($eventId) {
        $query = $this->connexion->prepare("
          SELECT * FROM t_message
		  WHERE fkEvent = $eventId
		  ORDER BY mesCreateDate DESC
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get all the messages of an event
     */
    public function getMessageById($messageId) {
        $query = $this->connexion->prepare("
          SELECT * FROM t_message
		  WHERE idMessage = $messageId
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Insert new message to an event
     */
    public function insertMessage($userId, $eventId, $content) {
        $query = $this->connexion->prepare("
          INSERT INTO t_message (mesContent, fkEvent, fkUser)
            VALUES ('$content', $eventId, $userId)
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
	}

    /*
     * Insert new message to an event
     */
    public function deleteMessage($messageId) {
        $query = $this->connexion->prepare("
          DELETE FROM t_message
          WHERE idMessage = $messageId
        ");
        $query->execute();
	}

    /* ------ Media ------ */

    /*
     * Get all the medias of an event
     */
    public function getEventMedias($eventId) {
        $query = $this->connexion->prepare("
          SELECT * FROM t_media
		  WHERE fkEvent = $eventId
		  ORDER BY medUploadDate DESC
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get a specific media
     */
    public function getMediaById($mediaId) {
        $query = $this->connexion->prepare("
          SELECT * FROM t_media
		  WHERE idMedia = $mediaId
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Insert new message to an event
     */
    public function insertMedia($eventId, $fileName) {
        $query = $this->connexion->prepare("
          INSERT INTO t_media (fkEvent, medFilename)
            VALUES ($eventId, '$fileName')
		");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
	}

    /*
     * Insert new message to an event
     */
    public function deleteMedia($mediaId) {
		$fileName = $this->getMediaById($mediaId)[0]["medFilename"];
	
        $query = $this->connexion->prepare("
          DELETE FROM t_media
          WHERE idMedia = $mediaId
        ");
		
        $query->execute();
		
		//Delete file
        unlink("../../files/".$fileName);
    }

    /* ------ Charts / get diverse data ------ */
	
    /*
     * Get events with the most participants
     */
    public function getTopFutureEvents($limit) {
        $sql = "
          SELECT t_event.idEvent, t_event.eveTitle, t_event.eveStartDate, COUNT(t_event_has_user.idxUser) AS countEvent
			FROM t_event_has_user
			LEFT JOIN t_event
				ON t_event_has_user.idxEvent = t_event.idEvent
			WHERE t_event_has_user.eveuseStatus = 1
				AND t_event.eveIsPublic = 1
		        AND eveStartDate > CURDATE()
			GROUP BY t_event.idEvent
			ORDER BY countEvent DESC
        ";

        if($limit > 0) {
            $sql .= "
             LIMIT $limit";
        }

        $query = $this->connexion->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * Get events with the most participants and class it per start date
     */
    public function getTopFutureEventsPerDate($limit) {
        $sql = "
          SELECT t_event.idEvent, t_event.eveTitle, t_event.eveStartDate, COUNT(t_event_has_user.idxUser) AS countEvent
			FROM t_event_has_user
			LEFT JOIN t_event
				ON t_event_has_user.idxEvent = t_event.idEvent
			WHERE t_event_has_user.eveuseStatus = 1
				AND t_event.eveIsPublic = 1
		        AND eveStartDate > CURDATE()
			GROUP BY t_event.idEvent
			ORDER BY eveStartDate ASC
        ";

        if($limit > 0) {
            $sql .= "
             LIMIT $limit";
        }
        $query = $this->connexion->prepare($sql);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
	
} //db class

?>
