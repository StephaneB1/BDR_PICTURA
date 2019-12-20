<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 01.05.2017
 * Summary: User modification (login, first/last name, password, email, profile picture)
 */
 
session_start();
include_once("../include/func.php");
include_once("../include/dbConnect.php");
$db = new db;

//User must be logged in to acces this page
if(!checkIfLoggedIn()) {
    stopAndMove("../../index.php");
} else {
    $user = $db->getUserById($_SESSION["userId"]);
}

$formCorrect = true; //If false, data won't be sent

//Login
if(!empty($_POST["login"])) {
    $login = cleanifyString($_POST["login"]);
} else { //Error: login not sent
    $formCorrect = false;
}

//First name
if(!empty($_POST["firstName"])) {
    $firstName = $_POST["firstName"];
} else { //Error: first name not sent
    $formCorrect = false;
}

//Last name
if(!empty($_POST["lastName"])) {
    $lastName = $_POST["lastName"];
} else { //Error: last name not sent
    $formCorrect = false;
}

//Email
if(!empty($_POST["email"])) {
    $email = $_POST["email"];
} else { //Error: email address not sent
    $formCorrect = false;
}

//Password: check if it's been modified
if(!empty($_POST["password"]) || !empty($_POST["passwordConfirm"]) || !empty($_POST["passwordOld"])) {
	//All fields must be filled
	if(!empty($_POST["password"]) && !empty($_POST["passwordConfirm"]) && !empty($_POST["passwordOld"])) {
		//Password must be 8+ characters long
		if(strlen($_POST["password"]) >= 8) {
			//Passwords must correspond + old password confirmation
			if($_POST["password"] == $_POST["passwordConfirm"] && password_verify($_POST["passwordOld"], $user[0]["usePassword"])) {			
				$password = password_hash($_POST["password"], PASSWORD_DEFAULT);
			} else { //Error: passwords don't correspond
				$formCorrect = false;
			}
		} else { //Error: Password too short
		$formCorrect = false;
	}
	} else { //Error: Not all password fields were filled
		$formCorrect = false;
	}
} else { //No password fields were filled -> no modification
    $password = null;
}

//Verification before sending data
if($formCorrect == true) {
    //Connect to database
    include_once("../include/dbConnect.php");
    $db = new db;

    //Check if login already exists
    $loginExists = false;
    $allUsernames = $db->getAllUserLogins();
    for($i = 0; $i < count($allUsernames); $i++){
        if(strtolower($login) == strtolower($allUsernames[$i]["useLogin"]) && $_SESSION["userId"] != $allUsernames[$i]["idUser"]) {
            $loginExists = true;
        }
    }

    //Check if email already exists
    $emailExists = false;
    $allEmails = $db->getAllUserEmails();
    for($i = 0; $i < count($allEmails); $i++){
        if(strtolower($email) == strtolower($allEmails[$i]["useEmail"]) && $_SESSION["userId"] != $allEmails[$i]["idUser"]) {
            $emailExists = true;
        }
    }

    //Login and email address must be free
    if($emailExists == false) {
        if($loginExists == false) {
            //Edit user
            $db->editUser($_SESSION["userId"], $login, $firstName, $lastName, $password, $email);
            $_SESSION["userName"] = $login; //Update login's session variable

            /* ------ Picture ------ */

            $oldPicture = $db->getUserPicture($_SESSION["userId"])[0]["usePicture"]; //Get old picture
            $fileNewName = ""; //New filename
			
            //Check if picture has been deleted (without new one)
            if(!empty($_POST["deletePicture"])){
                //Update database
                $db->editUserPicture($_SESSION["userId"], null);

                //Delete old picture
                if(!empty($oldPicture)) {
                    unlink("../../files/".$oldPicture);
            		$_SESSION["userPicture"] = ""; //Update picture's session variable
                }
            } else {
                //Check if picture has changed
                if($oldPicture != $_FILES["files"]["name"]){
                    //Insert new profile picture
                    $fileNewName = uploadFile(IMAGE_FORMATS, $_FILES["files"]["name"], $_FILES["files"]["tmp_name"], $_FILES["files"]["error"]);

                    //Check if file upload had any errors, if not -> update database
                    if($fileNewName != false) {
                        //Insert in db
                        $db->editUserPicture($_SESSION["userId"], $fileNewName);
            			$_SESSION["userPicture"] = $fileNewName; //Update session variable
						
                        //Delete old picture
                        if(!empty($oldPicture)) {
                            unlink("../../files/".$oldPicture);
                        }
                    }
                }
            }
			
            header("Location: ../../profile.php"); //Redirect to profile edit page
        } else { //Error: username is already taken
			$_SESSION["e"] = 3; //Store error code
            echo"<script>window.history.back();</script>"; //Go back to form page (keep form values)
        }
    } else { //Error: email is already taken
		$_SESSION["e"] = 2; //Store error code
        echo"<script>window.history.back();</script>"; //Go back to form page (keep form values)
    }
} else { //Error: uome form fields are empty
	$_SESSION["e"] = 1; //Store error code
    echo"<script>window.history.back();</script>"; //Go back to form page (keep form values)
}
?>