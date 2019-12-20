<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 01.05.2017
 * Summary: Create user account
 */

session_start();

$formCorrect = true; //If false, data won't be sent
include_once("../include/func.php");

//Login
if(!empty($_POST["regLogin"])) {
	$login = cleanifyString($_POST["regLogin"]);
} else { //Error: login not sent
	$formCorrect = false;
}

//First name
if(!empty($_POST["regFirstName"])) {
	$firstName = $_POST["regFirstName"];
} else { //Error: first name not sent
	$formCorrect = false;
}

//Last name
if(!empty($_POST["regLastName"])) {
	$lastName = $_POST["regLastName"];
} else { //Error: last name not sent
	$formCorrect = false;
}

//Email
if(!empty($_POST["regEmail"])) {
	$email = $_POST["regEmail"];
} else { //Error: email address not sent
	$formCorrect = false;
}

//Password
if(!empty($_POST["regPassword"])) {
	if(!empty($_POST["regPasswordConfirm"])) {
		//Password must be 8+ characters long
		if(strlen($_POST["password"]) >= 8) {
			if($_POST["regPassword"] == $_POST["regPasswordConfirm"]) {
				$password = password_hash($_POST["regPassword"], PASSWORD_DEFAULT);
			} else { //Error: passwords don't correspond
				$formCorrect = false;
			}
		} else {  //Error: password is too short
			$formCorrect = false;
		}
	} else {  //Error: password confirmation not sent
		$formCorrect = false;
	}
} else { //Error: password not sent
	$formCorrect = false;
}

//Verification before sending data
if($formCorrect == true) {
	//Connect to database
	include_once("../include/dbConnect.php");
	$db = new db;
	
	//Check if login already exists
	$loginExists = false;
	$allUsernames = $db->getAllUserLogins();		
	for($i = 0; $i < count($allUsernames); $i++) {
		if(strtolower($login) == strtolower($allUsernames[$i]["useLogin"])) {
			$loginExists = true;
		}
	}
	
	//Check if email already exists
	$emailExists = false;
	$allEmails = $db->getAllUserEmails();		
	for($i = 0; $i < count($allEmails); $i++){
		if(strtolower($email) == strtolower($allEmails[$i]["useEmail"])) {
			$emailExists = true;
		}
	}
	
	//Login and email address must be free
	if($emailExists == false) {
		if($loginExists == false) {
			//Create session variables
			$_SESSION["userId"] = $db->insertUser($login, $firstName, $lastName, $password, $email); //Insert user and store its ID
			$_SESSION["userName"] = $db->getUserById($_SESSION["userId"])[0]["useLogin"];
			$_SESSION['isAdmin'] = false;

			header("Location: ../../index.php");
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