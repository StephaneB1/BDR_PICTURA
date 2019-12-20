<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 04.05.2017
 * Summary: All events related to the current user
 */

include_once("php/include/header.php");

?>
<title>TPI - Mes événements</title>

<!-- Datepicker properties -->
<script src='js/datepickerFromTo.js' type='text/javascript'></script>
<link rel='stylesheet' type='text/css' href='css/datepicker.css'/>
</head>
<body>
<?php

if(!checkIfLoggedIn()) {
    stopAndMove();
}

include_once("php/include/menu-h.php");
include_once("php/include/dbConnect.php");
$db = new db;

?>

    <div id="wrapper-content">
        <div class="col-2-3 unique center-x nopadding">
            <?php

            include("php/include/insertEventFormInc.php");

            //All events to display
            $allCreatedEvents = $db->getAllCreatedEvents($_SESSION["userId"]);
            $allSubscribedEvents = $db->getSubscribedEvents($_SESSION["userId"]);
            $invitedEvents = $db->getInvitedEvents($_SESSION["userId"]);

            ?>
            <p>Les événement de couleurs foncées sont terminés.</p>

            <!-- Created events -->
			<div class="col-1-1 nopadding">
				<h2 class="center-x">
					<a onclick="slideById(this, 'createdEvents', false, 200)">
						Mes événements créés (<?php echo count($allCreatedEvents); ?>)
						<i class="material-icons">arrow_drop_down</i>
					</a>
				</h2>
			</div>
            <div id="createdEvents" class="col-1-1 nopadding">
				<?php

                //If event's list isn't empty -> display print button
                if(!empty($allCreatedEvents)) {
                    echo "<a href='php/form/printEvents.php?c=3' target='_blank' class='center-x'>
                        <i class='material-icons'>print</i> Imprimer la liste
                    </a>";
                }

				displayEventsList($allCreatedEvents, false, true, true); //Without author's name, with tasks

				?>
            </div>

            <!-- Subscribed events -->
			<div class="col-1-1 nopadding">
				<h2 class="center-x">
					<a onclick="slideById(this, 'subscribedEvents', false, 200)">
						Événements auxquels je participe (<?php echo count($allSubscribedEvents); ?>)
						<i class="material-icons">arrow_drop_down</i>
					</a>
				</h2>
			</div>
            <div id="subscribedEvents" class="col-1-1 nopadding">
                <?php

                //If event's list isn't empty -> display print button
                if(!empty($allSubscribedEvents)) {
                    echo "<a href='php/form/printEvents.php?c=2' target='_blank' class='center-x'>
                        <i class='material-icons'>print</i> Imprimer la liste
                    </a>";
                }

				displayEventsList($allSubscribedEvents, true, true, true); //With authour name, public status ans tasks

				?>
            </div>

            <!-- Pending invitations -->
			<div class="col-1-1 nopadding">
				<h2 class="center-x">
					<a onclick="slideById(this, 'invitedEvents', false, 200)">
						Mes invitations (<?php echo count($invitedEvents); ?>)
						<i class="material-icons">arrow_drop_down</i>
					</a>
				</h2>
			</div>
            <div id="invitedEvents" class="col-1-1 nopadding">
				<?php

                //If event's list isn't empty -> display print button
                if(!empty($invitedEvents)) {

                    echo "<a href='php/form/printEvents.php?c=4' target='_blank' class='center-x'>
                        <i class='material-icons'>print</i> Imprimer la liste
                    </a>";

                }

				displayEventsList($invitedEvents, true, true, false); //With authour name, public status and tasks

				?>
            </div>
        </div>

    </div> <!-- Wrapper -->
    <?php
    include_once("php/include/footer.php");
    ?>
</body>

<script src='js/galleryViewer.js' type='text/javascript'></script>

</html>