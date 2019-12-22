<?php

/**
 * HEIG-VD
 * Authors: Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date: 20.12.2019
 * Summary: Delete user account and all its content
 */

session_start();

include_once("../include/func.php");

//User must be logged in to access this page
if(!checkIfLoggedIn()) {
    redirect(null);
}

//Connect to database
include_once("../include/dbConnect.php");
$db = new db;

$db->deleteUser($_SESSION["pseudo"]);
redirect(null);

?>