<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 04.04.2017
 * Summary: Task creation form. Include this file to display the form
 */


?>

<!-- Insert task form -->
<form id="insertEventForm" name="insertEventForm" action="php/form/insertEventForm.php" method="post">
    <h2>Informations générales:</h2>
	<!-- Task category -->
    <p>
		<label for="task">
            <i class="material-icons">favorite_border</i>
		</label>
        <select id="task">
            <?php



            ?>
        </select>
	</p>

    <p class="note">*Obligatoires</p>
    <p><input type="submit" value="+Valider"/></p>
    <hr>
</form>