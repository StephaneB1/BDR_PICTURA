<?php

/**
 * HEIG-VD
 * Authors: Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date: 20.12.2019
 * Summary: General functions
 */

date_default_timezone_set("Europe/Zurich");

//File upload settings
include($_SERVER['DOCUMENT_ROOT'] . "/config/files_config.php");

/*
* Check if the user is connected with a valid account
* @return true/false wether the user is connected with a valid account or not
*/
function checkIfLoggedIn()
{
    $isConnected = false;

    //Start session if not already done
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    //Check if session has a valid userId
    if (!empty($_SESSION['pseudo'])) {
        include_once($_SERVER['DOCUMENT_ROOT'] . "/php/include/dbConnect.php");
        $db = new db;

        //Verify user's account existence
        $user = $db->getUserByPseudo($_SESSION['pseudo']);
        if (!empty($user)) {
            $isConnected = true;
        }
    }

    return $isConnected;
}

/*
* Removes spaces and special chars (only lower/uppercase letters and numbers)
*/
function cleanifyString($string)
{
    $newString = str_replace(" ", "", $string); //Remove spaces
    return preg_replace("/[^A-Za-z0-9]/", "", $newString); //Removes all special chars
}

/*
* Change format of a date
* @param $date: Input date
* @param $format: Requested output format
* @return: Formated date
*/
function formatDate($date, $format)
{
    $newDate = new DateTime($date);
    $newDate = $newDate->format($format);

    return $newDate;
}

/*
 * Adds the given prefix to all array's items
 */
function prefixStringArray($array, $prefix)
{
    $result = array();

    for ($i = 0; $i < count($array); ++$i) {
        $result[$i] = $prefix . $array[$i];
    }

    return $result;
}

/*
 * Summary: Use this to stop script and move to root directory
 * @param $page: Specify a page to redirect to,
 * if null -> index.php which only works for files in root folder)
 */
function redirect($page)
{
    if (empty($page)) {
        $page = "index.php";
    }

    header("Location: " . getHostUrl() . "/" . $page);
    die();
}

/*
 * Displays a popup with given HTML content in it
 */
function createPopup($id, $code)
{
    echo "
<div id='" . $id . "' class='popup-bg'>
    <div class='popup center-x'>
    <p style='padding: 5px;'><i class='material-icons clickable' style='float: right;' onclick=\"displayId(null, '" . $id . "')\">close</i></p>
            " . $code . "</div>
</div>";

    echo "<script type='text/javascript'>alert(popup created);</script>";

}

/*
 * Escapes apostrohpes by adding a backslash prefix
 */
function escApastrophes($text) {
    return str_replace("'", "\\'", $text);
}

function getFileFormats() {
    return join(',', prefixStringArray(IMAGE_FORMATS, "."));
}

/*
 * Go back to previous page within script tag (useful get back form values after sending)
 */
function previousPage()
{
    echo "<script>window.history.back();</script>";
}

/*
 * Handles the file upload via $_FILES[]
 * @param $acceptedExtensions: All the accepted extensions. Default value are in files_config.php
 * @param $fileUploadName: 'name' property of $_FILE
 * @param $fileUploadTempName: 'temp-name' property of $_FILE
 * @param $fileUploadError: 'error' property of $_FILE
 */
function uploadFile($acceptedExtensions, $fileUploadName, $fileUploadTempName, $fileUploadError)
{
    $result = false;

    //If no extensions parameter -> catch images and videos
    if (empty($acceptedExtensions)) {
        $acceptedExtensions = IMAGE_FORMATS;
    }

    //If no errors have occurred, proceed to verification
    if (empty($fileUploadError)) {
        //Get the file's extension
        $extension = strtolower(pathinfo($fileUploadName, PATHINFO_EXTENSION));

        //Verify if the file is from the correct format
        if (in_array($extension, $acceptedExtensions)) {
            $cleanedTempName = strtolower(cleanifyString(pathinfo($fileUploadName, PATHINFO_FILENAME)));
            //New name: original filename with 10 chars max + "_" + unique string
            $newName = substr($cleanedTempName, 0, 10) . "_" . uniqid() . "." . $extension;
            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/" . FILES_FOLDER . "/" . $newName; //Location where the file will be uploaded

            //Move file to folder
            if (move_uploaded_file($fileUploadTempName, $targetPath)) {
                $result = $newName; //If no error -> return name to insert it in database
            }
        }
    }
    return $result;
} //End uploadFile function

/*
 * Get the host's URL (ex: "http://localhost:8080")
 * @return: String
 */
function getHostUrl()
{
    if (isset($_SERVER["HTTPS"])) {
        $protocol = "https://";
    } else {
        $protocol = "http://";
    }
    return $protocol . $_SERVER["HTTP_HOST"];
}

?>