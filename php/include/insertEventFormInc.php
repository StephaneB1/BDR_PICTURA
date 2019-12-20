<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 04.04.2017
 * Summary: Event creation form. Include this file to display the form
 */

?>

<!-- Insert event form -->
<h1><a onclick="slideById(this, 'insertEventForm', true, 500)">+Créer un événement</a></h1>
<form id="insertEventForm" class='hidden' name="insertEventForm" action="php/form/insertEventForm.php" method="post">
    <h2>Informations générales:</h2>
	<!-- Title -->
    <p>
		<label for="title">
			<i class="material-icons">bookmark_border</i>
		</label>
		<input type="text" id="title" name="title" placeholder="Titre*" maxlength="60" required/>
	</p>
	<!-- Description -->
    <p>
		<label for="description">
			<i class="material-icons">description</i>
		</label>
		<textarea id="description" name="description" placeholder="Description"></textarea>
	</p>
	<!-- Location -->
    <p>
		<label for="location">
			<i class="material-icons">location_on</i>
		</label>
		<input type="text" id="location" name="location" placeholder="Lieu*" maxlength="100" required/>
	</p>
	<!-- Price -->
    <p>
		<label for="price">
			<i class="material-icons">attach_money</i>
		</label>
		<input type="number" id="price" name="price" step="any" placeholder="Prix (sans devise)" min="0"/>
	</p>
	<!-- Participants limit -->
    <p>
		<label for="limit">
			<i class="material-icons">group</i>
		</label>
		<input type="number" id="limit" name="limit" placeholder="Limite de participant" min="0"/>
	</p>

    <!-- Start date + time -->
    <h2>Dates et heures:</h2>
    <p>
		<label for="startDatepicker">
			<i class="material-icons">date_range</i>
		</label>
        <input id="startDatepicker"
               class="datepicker"
               type="text"
               name="startDate"
               placeholder="Date de début*"
               readonly="readonly"
               required/>
    </p>
    <p>
        <label for="startHour">
            <i class="material-icons">schedule</i>
        </label>
        <select id="startHour" name="startHour" class="select-time" required>
            <option value="" disabled selected>Heure*</option>
            <?php

            for($i = 0; $i < 24; $i++) {
                //If is a one-number value, add zero to display (ex: 5 becomes 05)
                if($i < 10) {
                    echo "<option value='".$i."'>0".$i."</option>";
                } else {
                    echo "<option value='".$i."'>".$i."</option>";
                }
            }

            ?>
        </select><select id="startMinute" name="startMinute" class="select-time" required>
            <option value="" disabled selected>Minutes*</option>
            <?php
            //Write all options
            for($i = 0; $i < 60; $i += 5) {
                //If is a one-number value, add zero to display (ex: 5 becomes 05)
                if($i < 10) {
                    echo "<option value='".$i."'>0".$i."</option>";
                } else {
                    echo "<option value='".$i."'>".$i."</option>";
                }
            }
            ?>
        </select></p>
    <br/>
    <!-- endDatepicker + time -->
    <p>
		<label for="endDatepicker">
			<i class="material-icons">date_range</i>
		</label>
        <input id="endDatepicker"
               class="datepicker"
               type="text"
               name="endDate"
               placeholder="Date de fin"
               pattern="[0-9]{2}\.[0-9]{2}\.[0-9]{4}"
               readonly="readonly"/>
    </p>
    <p>
        <label for="endHour">
            <i class="material-icons">schedule</i>
        </label>
        <select id="endHour" name="endHour" class="select-time">
            <option value="" disabled selected>Heure</option>
            <?php
            for($i = 0; $i < 24; $i++) {
                //If is a one-number value, add zero to display (ex: 5 becomes 05)
                if($i < 10) {
                    echo "<option value='".$i."'>0".$i."</option>";
                } else {
                    echo "<option value='".$i."'>".$i."</option>";
                }
            }
            ?>
        </select><select id="endMinute" name="endMinute" class="select-time">
            <option value="" disabled selected>Minutes</option>
            <?php
            //Write all options
            for($i = 0; $i < 60; $i += 5) {
                //If is a one-number value, add zero to display (ex: 5 becomes 05)
                if($i < 10) {
                    echo "<option value='".$i."'>0".$i."</option>";
                } else {
                    echo "<option value='".$i."'>".$i."</option>";
                }
            }
            ?>
        </select>
    </p>
    <!-- Event is/isn't public -->
    <p>
		<input id="isPublic" name="isPublic" type="checkbox"/>
		<label for="isPublic">Événement public</label>
	</p>

    <p class="note">*Obligatoires</p>
    <p><input type="submit" value="+Valider"/></p>
    <hr>
</form>