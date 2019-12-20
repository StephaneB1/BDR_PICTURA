<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 28.04.2017
 * Summary: Header of all pages
 */

session_start();
include_once("php/include/func.php");
?>

<!doctype html>
<html>
	<head>
		<!--
		ETML
		Author: Robin Demarta
		Date: 28.04.2017
		-->
		
		<meta charset="utf-8"/>
		<meta name="author" content="Robin Demarta"/>
		<link rel="icon" href="assets/favicon.png"/>

		<!-- Material Icon Font -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/main.css"/>
		<!-- Hosted libraries (Google) -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<!-- JS -->
		<script src="js/func.js" type="text/javascript"></script>
		
<?php

include_once("php/include/receiveError.php"); //Check for errors on display message if necessary
	
?>