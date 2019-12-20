<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 28.04.2017
 * Summary: Main events' page. All events all listed here
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

    include("php/include/receiveError.php");

	?>
	
	<div id="wrapper-content">
		<!-- List -->
		<div class="col-1-1 center-x nopadding">
            <?php

            if(checkIfLoggedIn()) {
                include("php/include/insertEventFormInc.php");
            }

            ?>
        </div>

			<div class="col-2-3 unique nopadding">
				<?php

                if(checkIfAdmin()) {
                    echo "<h1 class='center-x'>Tous les événements</h1>
					<p class='center-x'>Les événement de couleurs foncées sont terminés.</p>";
                    $allEvents = $db->getAllEvents();

                    displayEventsList($allEvents, true, true, false); //List public event with author's name and public status

                } else {
                    echo "<h1 class='center-x'>Événements publics</h1>
					<p class='center-x'>Les événement de couleurs <span>foncées</span> sont terminés.</p>";
                    $allEvents = $db->getAllPublicEvents();

                    displayEventsList($allEvents, true, false, false); //List public event with author's name
                }

				?>
                <br/>
			</div>

	</div> <!-- Wrapper -->
	<?php
		include_once("php/include/footer.php");
	?>
</body>

<script src='js/galleryViewer.js' type='text/javascript'></script>

</html>