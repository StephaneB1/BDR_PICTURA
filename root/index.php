<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 28.04.2017
 * Summary: Home page of the application
 */

include_once("php/include/header.php");
include_once("php/include/dbConnect.php");
$db = new db;

$isConnected = checkIfLoggedIn();

?>
	<title>TPI</title>

	<!-- Datepicker properties -->
	<script src='js/datepickerFromTo.js' type='text/javascript'></script>
    <link rel='stylesheet' type='text/css' href='css/datepicker.css'/>

    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.src.js"></script>
    <script src="http://code.highcharts.com/modules/exporting.js"></script>
	
</head>
<body>
	<?php

    include_once("php/include/menu-h.php");

    ?>

	<div id="wrapper-content">
		<!-- First -->
        <div class="col-1-2 unique">
			<h1>Bienvenue,</h1>

            <?php
			
            if($isConnected) {
                echo "
                <p>
                    Pour débuter, vous pouvez accéder à <a href='myEvents.php'>vos événements</a> ou <a href='events.php'>parcourir les événements publics</a>.
                </p>";
            } else {
                echo "
                <p>
                    Pour débuter, vous pouvez vous <a href='connexion.php'>authentifier / inscrire</a> ou <a href='events.php'>parcourir les événements publics</a>.
                </p>";
            }

            ?>

            <!-- Chart -->
            <h2>Les 10 événements publics les plus populaires: </h2>
            <button id="dataSwitchButton">Classer par date de début</button>
            <div id="eventsChart"></div>
        </div>
	</div> <!-- End wrapper-content -->
	<?php

    include_once("php/include/footer.php");

	?>
</body>

<!-- Include chart -->
<?php

include_once("php/include/eventsChart.php");

?>

</html>