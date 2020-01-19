<?php

/**
 * HEIG-VD - Mini-Projet BDR
 * 
 * Authors:     Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date:        20.12.2019
 * 
 * Summary :    Login user
 */

include_once("../include/func.php");

session_start();

$formCorrect = true; //If false, stop

//Login
if(!empty($_POST["pseudo"])) {
    $login = $_POST["pseudo"];
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

    $user = $db->getUserByPseudo($login); //Get user

    //Check if user exists
    if(!empty($user)) {
        //Verify password
        if(password_verify($password, $user[0]["motDePasse"])) {
            //Save user ID and user name in the session variable
            $_SESSION["pseudo"] = $user[0]["pseudo"];

            redirect(null);
        } else { //Error: Incorrect password
			//$_SESSION["e"] = 1; //Store error code
            redirect("index.php");
        }

    } else { //Error: User doesn't exists
		//$_SESSION["e"] = 1; //Store error code
        redirect("index.php");
    }

} else { //Error: Some form fields are empty
    //$_SESSION["e"] = 1; //Store error code
    redirect("index.php");
}

?>