<?php

/**
 * ETML
 * Author : Robin Demarta
 * Date : 01.05.2017
 * Summary : Login user
 */

session_start();

$formCorrect = true; //If false, stop

//Login
if(!empty($_POST["login"])) {
    $login = $_POST["login"];
} else {
    $formCorrect = false;
}
//Password
if(!empty($_POST["password"])) {
    $password = $_POST["password"];
} else {
    $formCorrect = false;
}

//Verification
if($formCorrect == true) {
    //Connect to database
    include_once("../include/dbConnect.php");
    $db = new db;

    $user = $db->getUserByLogin($login); //Get user

    //Check if user exists
    if(!empty($user)) {
        //Verify password
        if(password_verify($password, $user[0]["usePassword"])) {
            //Save user ID and user name in the session variable
            $_SESSION["userId"] = $user[0]["idUser"];
            $_SESSION["userName"] = $user[0]["useLogin"];
			//If user has a picture, store its name
            if(!empty($user[0]["usePicture"])) {
                $_SESSION["userPicture"] = $user[0]["usePicture"];
            }
            if($user[0]["useIsAdmin"] == true) {
                $_SESSION["isAdmin"] = true;
            } else {
                $_SESSION["isAdmin"] = false;
            }

            header("Location: ../../myEvents.php"); //Redirect to myEvent page
        } else { //Error: Incorrect password
			$_SESSION["e"] = 1; //Store error code
            header("Location: ../../connexion.php");
        }

    } else { //Error: User doesn't exists
		$_SESSION["e"] = 1; //Store error code
        header("Location: ../../connexion.php");
    }

} else { //Error: Some form fields are empty
	$_SESSION["e"] = 1; //Store error code
    header("Location: ../../connexion.php");
}

?>