<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 01.05.2017
 * Summary: This file prevents access to folder via the URL
 */

include_once($_SERVER['DOCUMENT_ROOT']."/php/include/func.php");
stopAndMove("../index.php");

?>