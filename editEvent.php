<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 04.05.2017
 * Summary: Event's edition page
 */

include_once("php/include/header.php");

?>
	<title>TPI</title>

    <!-- Datepicker properties -->
    <script src='js/datepickerFromTo.js' type='text/javascript'></script>
    <link rel='stylesheet' type='text/css' href='css/datepicker.css'/>
</head>
<body>
	<?php
    include_once("php/include/menu-h.php");
	
    include_once("php/include/dbConnect.php");
    $db = new db;

    //User must be logged in to acces this page
    if(!checkIfLoggedIn()) {
        stopAndMove();
    }

    //Check that an ID has been sent
    if(!empty($_GET["id"])) {
        $event = $db->getEventById($_GET["id"]);
        //Verify event ID validity
        if(!empty($event)) {
            //Check if current user is the author of the event
            if(!checkUserEventPermission($_GET["id"], true, false)) {
                stopAndMove();
            }
        } else { //Event doesn't exists
            stopAndMove();
        }
    } else { //No event ID was given
        stopAndMove();
    }

	?>
	
	<div id="wrapper-content">
		<!-- List -->
		<div class="col-1-1 unique center-x">
            <h1>Modifier l'événement</h1>
            <form id="insertEventForm" name="insertEventForm" action="php/form/editEventForm.php?id=<?php echo $event[0]['idEvent']; ?>" method="post">
                <h2>Informations générales:</h2>
                <p><label><i class="material-icons">bookmark_border</i><input value="<?php echo htmlentities($event[0]['eveTitle']); ?>" type="text" name="title" placeholder="Titre*" maxlength="60" required/></label></p>
                <p><label><i class="material-icons">description</i><textarea name="description" placeholder="Description" ><?php echo htmlentities($event[0]['eveDescription']); ?></textarea></label></p>
                <p><label><i class="material-icons">location_on</i><input value="<?php echo htmlentities($event[0]['eveLocation']); ?>" type="text" name="location" title="Lieu" placeholder="Lieu*" maxlength="100" required/></label></p>
                <p><label><i class="material-icons">attach_money</i><input value="<?php if($event[0]['evePrice'] != 0){echo $event[0]['evePrice'];} ?>" type="number" name="price" step="any" title="Prix" placeholder="Prix" min="0"/></label></p>
                <p><label><i class="material-icons">group</i><input value="<?php if($event[0]['eveMaxParticipants'] != 0){echo $event[0]['eveMaxParticipants'];} ?>" type="number" name="limit" title="Limite de participant" placeholder="Limite de participant" min="0"/></label></p>

                <!-- startDatepicker + time -->
                <h2>Dates et heures:</h2>
                <p><label><i class="material-icons">date_range</i>
                    <input id="startDatepicker"
                           value="<?php echo formatDate($event[0]['eveStartDate'], 'd.m.Y'); ?>"
                           class="datepicker"
                           type="text"
                           name="startDate"
                           placeholder="Date et début*"
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

                        $startHour = intval(formatDate($event[0]["eveStartDate"], "G"));
                        //Write all options
                        for($i = 0; $i < 24; $i++) {
                            echo "<option value='".$i."'";
                            //Select old minute
                            if($i == $startHour) {
                                echo " selected";
                            }
                            echo ">";

                            //If is a one-number value, add zero to display (ex: 5 becomes 05)
                            if($i < 10) {
                                echo "0".$i."</option>";
                            } else {
                                echo $i."</option>";
                            }
                        }
                        ?>
                    </select><select id="startMinute" name="startMinute" class="select-time" required>
                        <option value="" disabled selected>Minutes*</option>
                        <?php

                        $startMinutes = intval(formatDate($event[0]["eveStartDate"], "i"));
                        //Write all options
                        for($i = 0; $i < 60; $i += 5) {
                            echo "<option value='".$i."'";
                            //Select old minute
                            if($i == $startMinutes) {
                                echo " selected";
                            }
                            echo ">";

                            //If is a one-number value, add zero to display (ex: 5 becomes 05)
                            if($i < 10) {
                                echo "0".$i."</option>";
                            } else {
                                echo $i."</option>";
                            }
                        }
                        ?>
                    </select>
                </p></label>
                <!-- endDatepicker + time -->
                <p><label><i class="material-icons">date_range</i>
                    <input id="endDatepicker"
                           <?php

                           //Write default value only if there was one
                           if($event[0]['eveEndDate'] != "0000-00-00 00:00:00") {
                               echo "value='".formatDate($event[0]['eveEndDate'], 'd.m.Y')."'";
                           }

                           ?>
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

                        $endHour = intval(formatDate($event[0]["eveEndDate"], "G"));
                        //Write all options
                        for($i = 0; $i < 24; $i++) {
                            echo "<option value='".$i."'";
                            //Select old minute
                            if($i == $endHour) {
                                echo " selected";
                            }
                            echo ">";

                            //If is a one-number value, add zero to display (ex: 5 becomes 05)
                            if($i < 10) {
                                echo "0".$i."</option>";
                            } else {
                                echo $i."</option>";
                            }
                        }

                        ?>
                    </select><select id="endMinute" name="endMinute" class="select-time">
                        <option value="" disabled>Minutes</option>
                        <?php

                        $endMinutes = intval(formatDate($event[0]["eveEndDate"], "i"));
                        //Write all options
                        for($i = 0; $i < 60; $i += 5) {
                            echo "<option value='".$i."'";
                            //Select old minute
                            if($i == $endMinutes) {
                                echo " selected";
                            }
                            echo ">";

                            //If is a one-number value, add zero to display (ex: 5 becomes 05)
                            if($i < 10) {
                                echo "0".$i."</option>";
                            } else {
                                echo $i."</option>";
                            }
                        }

                        ?>
                    </select>
                </p></label>
                <p><label name="isPublic"><input name="isPublic" type="checkbox" <?php if($event[0]["eveIsPublic"] == 1) {echo" checked";} ?>/>Événement public</label></p>

                <p class="note">*Obligatoires</p>
                <p>
					<a href="displayEvent.php?id=<?php echo $event[0]['idEvent'];?>">Annuler</a> - <input type="submit" value="Enregistrer">
				</p>
            </form>
		</div>

	</div> <!-- Wrapper -->
	<?php
		include_once("php/include/footer.php");
	?>
</body>

<script src='js/galleryViewer.js' type='text/javascript'></script>

<!-- Get dates from data base and set them to the datepickers' values -->
<script>
    $("#startDatepicker").datepicker("setDate", "<?php echo formatDate($event[0]['eveStartDate'], 'd.m.Y'); ?>");
    <?php

    //Set end date only if there was one
    if($event[0]['eveEndDate'] != "0000-00-00 00:00:00") {
        echo"$('#endDatepicker').datepicker('setDate', '".formatDate($event[0]['eveEndDate'], 'd.m.Y')."')";

    }

    ?>
</script>

</html>