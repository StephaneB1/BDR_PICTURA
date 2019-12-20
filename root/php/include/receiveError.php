<?php
/**
 * ETML
 * Author: Robin Demarta
 * Date: 01.05.2017
 * Summary: Include this code to display an error corresponding to the "e" SESSION value
 */

if(!empty($_SESSION["e"])) {
    $message = "Une erreur est survenue."; //Default error message

    switch($_SESSION["e"]) {
        default: {
            $message = "Une erreur s'est produite.";
            break;
        }
        case 1: {
            $message = "Un ou plusieurs champs sont incorrects.";
            break;
        }
        case 2: {
            $message = "Cette adresse email est déjà liée à un compte.";
            break;
        }
        case 3: {
            $message = "Ce nom d'utilisateur existe déjà.";
            break;
        }
        case 4: {
            $message = "Ce titre est déjà pris.";
            break;
        }
        case 5: {
            $message = "Vous devez participer à l'événement pour prendre une tâche.";
            break;
        }
        case 6: {
            $message = "Il n'y a plus de place dans cette événement.";
            break;
        }
        case 7: {
            $message = $_SESSION["r"];
            unset($_SESSION["r"]); //Destroy session variable
            break;
        }
        case 8: {
            $message = "La date/heure de début doit être inférieure à celle de fin.";
            break;
        }
    }

    echo "
        <div id='errorbox' onclick='displayId(null, \"errorbox\")'>
            <span>".$message."</span>
        </div>";
	unset($_SESSION["e"]); //Destroy session variable
	
	echo "<script>$('#errorbox').delay(5000).slideUp(200);</script>";
}

?>