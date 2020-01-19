<?php

/**
 * HEIG-VD - Mini-Projet BDR
 * 
 * Authors:     Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date:        20.12.2019
 * 
 * Summary :    End session and delete sessions variables
 */

session_start();
session_unset(); //Destroy all session variables
session_destroy(); //Destroy the session itself
header("Location: ../../index.php"); //Redirect to home page
die(); //End script

?>
