<?php

/**
 * HEIG-VD - Mini-Projet BDR
 * 
 * Authors:     Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date:        20.12.2019
 * 
 * Summary: 	Create user account
 */

include_once("../include/func.php");

session_start();

$formCorrect = true; // If false, data won't be sent

// Pseudo
if(!empty($_POST["regPseudo"])) {
	$pseudo = cleanifyString($_POST["regPseudo"]);
} else { // Error: pseudo not sent
	$formCorrect = false;
}

// First name
$firstName = $_POST["regFirstName"];

// Last name
$lastName = $_POST["regLastName"];

// Email
if(!empty($_POST["regEmail"])) {
	$email = $_POST["regEmail"];
} else { // Error: email address not sent
	$formCorrect = false;
}

// Password
if(!empty($_POST["regPassword"]) // Empty field
	|| !empty($_POST["regPasswordConfirm"]) // Empty field
	|| strlen($_POST["password"]) >= 8 // Password must be 8+ characters long
	|| $_POST["regPassword"] == $_POST["regPasswordConfirm"]) {

	$password = password_hash($_POST["regPassword"], PASSWORD_DEFAULT);
} else { // Error
	$formCorrect = false;
}

// Verification before sending data
if($formCorrect == true) {
	// Connect to database
	include_once("../include/dbConnect.php");
	$db = new db;

	if(!$db->insertUser($pseudo, $email, $password, $firstName, $lastName)) // Insert user
        previousPage(); // Go back to form page (keep form values)

    $_SESSION["pseudo"] = $pseudo; // Create session variables
	redirect(null);
} else { // Error: some form fields are empty
	//$_SESSION["e"] = 1; // Store error code
    previousPage(); // Go back to form page (keep form values)
}

?>