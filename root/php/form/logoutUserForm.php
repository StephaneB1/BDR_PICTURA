<?php

/**
 * ETML
 * Author : Robin Demarta
 * Date : 01.05.2017
 * Summary : End session and delete sessions variables
 */

session_start();
session_unset(); //Destroy all session variables
session_destroy(); //Destroy the session itself
header("Location: ../../index.php"); //Redirect to home page
die(); //End script

?>
