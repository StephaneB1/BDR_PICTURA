<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 01.05.2017
 * Summary: Detailled event's page
 */

include_once("php/include/header.php");

?>
<title>TPI</title>

<!-- Datepicker properties -->
<script src='js/datepickerFromTo.js' type='text/javascript'></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

</head>
<body>
<?php

include("php/include/receiveError.php");

include_once("php/include/menu-h.php");
include_once("php/include/dbConnect.php");
$db = new db;

//Check that an ID has been sent
if(!empty($_GET["id"])) {
    $event = $db->getEventById($_GET["id"]);
    //Verify event ID validity
    if(!empty($event)) {
        $eventId = $_GET["id"];
        $author = $db->getEventAuthor($event[0]["idEvent"]);
        $isAuthor = false;
        $isConnected = false;

        if(checkUserEventPermission($eventId, false, false)) {
            if(checkIfLoggedIn()) {
                $isConnected = true;
                //Get user's subscription
                $userSubscription = $db->getSubscription($_SESSION["userId"], $event[0]["idEvent"]);
                //Check if user is author
                if(!empty($userSubscription) && $userSubscription[0]["eveuseIsAdmin"] == 1) {
                    $isAuthor = true;
                }
                //if user is general admin -> all permissions given
                if($_SESSION["isAdmin"]) {
                    $isAuthor = true;
                }
            }
        } else { //Error: curent user doesn't have permission
            stopAndMove();
        }
    } else { //Error: event doesn't exists
        stopAndMove();
    }
} else { //Error: no event ID was given
    stopAndMove();
}

?>

<div id="wrapper-content">
    <!-- Title -->
    <div class="col-3-4 nopadding unique">
        <div class="col-1-1  nopadding">
            <?php
			
			if($event[0]["eveIsPublic"] == 1) {
            	echo "<h1>".htmlentities($event[0]["eveTitle"])."</h1>";
			} else {
            	echo "<h1><i class='material-icons' title='Événement privé'>lock_outline</i>".htmlentities($event[0]["eveTitle"])."</h1>";
			}
            echo "<p>".htmlentities($event[0]["eveDescription"])."</p>";
            echo "<p class='note'>Créé le ".formatDate($event[0]["eveCreateDate"], "d.m.Y à G:i")."
            par <a href='profile.php?id=".$author[0]['idUser']."'>".htmlentities($author[0]["useFirstname"])." ".htmlentities($author[0]["useLastname"])." (".htmlentities($author[0]["useLogin"]).")</a>";

			if($isConnected) {
				echo "<p>
						<a href='php/form/printEvents.php?id=".$event[0]["idEvent"]."' target='_blank'>
					<i class='material-icons'>print</i> Imprimer
						</a>
					</p>";
			}

            if($isAuthor == true) {
                echo "<p>
					<a href='editEvent.php?id=".$event[0]["idEvent"]."'>
						<i class='material-icons'>edit</i>Modifier
					</a>
				</p>";
                echo "<p>
					<a href='php/form/deleteEventForm.php?id=".$event[0]["idEvent"]."' class='red' onclick='return confirm(\"Supprimer définitivement?\")'>
						<i class='material-icons'>delete_forever</i>Supprimer cet événement
					</a>
				</p>";
            }

            ?>
        </div>

    <div class="col-1-1 nopadding">
        <!-- Participation choices -->
        <p class="choice center-x">
            <?php

            if(!empty($userSubscription)) {
                //Highlight the button corresponding to user's status (coming, not coming, maybe)
                switch($userSubscription[0]["eveuseStatus"]) {
                    default: {
                        echo "<button class='choice-green' onclick='document.location=\"php/form/subscribeEventForm.php?id=".$event[0]['idEvent']."&s=1\"'>Je participe</button><!--
			--><button class='choice-orange' onclick='document.location=\"php/form/subscribeEventForm.php?id=".$event[0]['idEvent']."&s=2\"'>Peut-être</button><!--
			--><button class='choice-red'>Je ne participe pas</button>
			";
                        break;
                    }
                    case 1: {
                        echo "<button class='choice-green active'>Je participe</button><!--
			--><button class='choice-orange' onclick='document.location=\"php/form/subscribeEventForm.php?id=".$event[0]['idEvent']."&s=2\"'>Peut-être</button><!--
			--><button class='choice-red' onclick='document.location=\"php/form/subscribeEventForm.php?id=".$event[0]['idEvent']."&s=0\"'>Je ne participe plus</button>
			";
                        break;
                    }
                    case 2:
                    case 3: {
                        echo "<button class='choice-green' onclick='document.location=\"php/form/subscribeEventForm.php?id=".$event[0]['idEvent']."&s=1\"'>Je participe</button><!--
			--><button class='choice-orange active'>Peut-être</button><!--
			--><button class='choice-red' onclick='document.location=\"php/form/subscribeEventForm.php?id=".$event[0]['idEvent']."&s=0\"'>Je ne participe pas</button>
			";
                        break;
                    }
                }
            } else { //If no subscription -> display standard buttons
                echo "<button class='choice-green' onclick='document.location=\"php/form/subscribeEventForm.php?id=".$event[0]['idEvent']."&s=1\"'>Je participe</button><!--
			--><button class='choice-orange' onclick='document.location=\"php/form/subscribeEventForm.php?id=".$event[0]['idEvent']."&s=2\"'>Peut-être</button><!--
			--><button class='choice-red' onclick='document.location=\"php/form/subscribeEventForm.php?id=".$event[0]['idEvent']."&s=0\"'>Je ne participe pas</button>
			";

            }
			
			?>
        </p>

        <!-- Tabs selectors -->
        <?php

        //Get all tab's data to display the count in the tab selectors

        $tasks = $db->getEventTasks($eventId);
        $assignedTasksCount = $db->getEventAssignedTasksCount($eventId)[0]["assignedTasks"];

        $messages = $db->getEventMessages($eventId);
        $medias = $db->getEventMedias($eventId);

        ?>
        <ul class="tab-selectors-container">
            <li>
                <a onclick="displayTab('tab1', this)" class="tab-selector" id="tabSelector1">
                    <i class="material-icons">info_outline</i> Détails</a>
            </li>
            <li>
                <a onclick="displayTab('tab2', this)" class="tab-selector" id="tabSelector2">
                    <i class="material-icons">format_list_bulleted</i> Tâches (<?php echo $assignedTasksCount."/".count($tasks); ?>)</a>
            </li>
            <li>
                <a onclick="displayTab('tab3', this)" class="tab-selector" id="tabSelector3">
                    <i class="material-icons">landscape</i> Galerie (<?php echo count($medias); ?>)</a>
            </li>
            <li>
                <a onclick="displayTab('tab4', this)" class="tab-selector" id="tabSelector4">
                    <i class="material-icons">message</i> Messages (<?php echo count($messages); ?>)</a>
            </li>
        </ul>
    </div>

    <!-- Details tab -->
    <div id="tab1" class="tab-content">
        <div class="col-1-1 center-x">
            <?php

            if($isAuthor) {
                echo "<h2>Inviter des personnes:</h2>
                    <p>Pour inviter des personnes, saisissez leur nom d'utilisateur.<br/>
                    Si vous désirez inviter une personne n'ayant pas de compte, saisissez son adresse mail.</p>
                    <p>Pour inviter plusieurs personne, veuillez <span class='bold'> les séparer par des virgules</span>.</p>
                    <form id='inviteUserForm' name='inviteUserForm' action='php/form/inviteUserForm.php?id=".$event[0]['idEvent']."' method='post'>
                        <p>
                            <textarea id='users' name='users' type='text' placeholder='adresse@mail.com,utilisateur,...' required></textarea>
                        </p>
                        <input type='submit' value='Envoyer'/>
                    </form>
                ";
            }

            ?>
        </div>

        <div class="col-3-4 unique">
			<!-- Where/when -->
			<div class="col-1-2 nopadding center-x">
				<h2>Détails</h2>
				<p>
					<i class="material-icons">location_on</i>
					<span> <?php echo htmlentities($event[0]["eveLocation"]); ?></span>
				</p>
				<p>
					<span class="bold">Du: </span><span><?php echo formatDate($event[0]["eveStartDate"], "d.m.Y à G:i"); ?></span>
				</p>
				<p>
					<span class="bold">au: </span>
					<span>
						<?php
						if($event[0]["eveEndDate"] != 0) {
							echo formatDate($event[0]["eveEndDate"], "d.m.Y à G:i");
						} else {
							echo "-";
						}
						?>
					</span>
				</p>

                <p>
                    <span class="bold">Prix: </span>
                    <span>
                        <?php

                        if($event[0]["evePrice"] > 0) {
                            echo $event[0]["evePrice"].".-";
                        } else {
                            echo "Gratuit";
                        }

                        ?>
                    </span>
                </p>
			</div>

			<!-- Participants -->
			<div class="col-1-2 nopadding center-x">
				<?php

				$participants = $db->getEventParticipants($eventId, null);
				$comingParticipants = $db->getEventParticipants($eventId, 1);

				//Display number of participants with limit
				if($event[0]["eveMaxParticipants"] == 0) {
					echo "<h2>Participants (".(count($comingParticipants)).")</h2>";
				} else {
					echo "<h2>Participants (".(count($comingParticipants))."/".$event[0]['eveMaxParticipants'].")</h2>";
				}

				for($i = 0; $i < count($participants); $i++) {
					switch($participants[$i]["eveuseStatus"]) {
						default: {					
							break;
						}
						case 1: {
							echo "
							<p>- <a href='profile.php?id=".$participants[$i]['idUser']."'>
								".htmlentities($participants[$i]['useFirstname'])." ".
								limitString(htmlentities($participants[$i]['useLastname']), 1, '.')." (".
								htmlentities($participants[$i]['useLogin']).")
							</a></p>";
							break;
						}
						case 2:
						case 3: {
							echo "
							<p>- <a class='orange' href='profile.php?id=".$participants[$i]['idUser']."'>
								".htmlentities($participants[$i]['useFirstname'])." ".
								limitString(htmlentities($participants[$i]['useLastname']), 1, '.')." (".
								htmlentities($participants[$i]['useLogin']).")
							</a></p>";
							break;
						}
					}
				}

				?>
			</div>
		</div>
    </div> <!-- End details tab -->

    <!-- Tasks tab -->
    <div id="tab2" class="tab-content">
        <div class="col-3-4 unique nopadding">
            <h2>Tâches publiques à réaliser</h2>
            <?php

            if($isAuthor == false && !empty($userSubscription)) {
                echo "<p class='note'>
                <i class='material-icons'>warning</i> Si vous quitter l'événement, vos tâches ne vous seront plus assignées.
            </p>";
            }

            //Display tasks
            for($i = 0; $i < count($tasks); $i++) {
                $user = $db->getUserById($tasks[$i]['fkUser']);

                if($isAuthor) {
                    //If user is autho -> display delete button
                    echo "<p>-
                    <a href='php/form/deleteTaskForm.php?id=".$tasks[$i]['idTask']."' title='Supprime cette tâche'onclick='return confirm(\"Supprimer cette tâche?\")'>
                    <i class='material-icons red'>delete_forever</i></a>".$tasks[$i]['tasTitle'].": ";
                } else {

                    echo "<p>- ".htmlentities($tasks[$i]['tasTitle']).": ";
                }

                if(!empty($tasks[$i]['fkUser'])) {
                    if($tasks[$i]['tasIsApproved'] == 1) {
                        //Task is assigned and approved
                        echo"<span class='green'>
                                <span class='bold'>".htmlentities($user[0]['useFirstname'])." (".htmlentities($user[0]['useLogin']).")</span> s'en occupe
                             </span>
                        </p>";
                    } else {
                        //Task is assigned or requested
                        if($isAuthor) {
                            echo"<span class='orange'>demandé par
                                    <span class='bold'>".htmlentities($user[0]['useFirstname'])." (".htmlentities($user[0]['useLogin']).")</span>
                                </span>
                                <a href='php/form/approveTaskForm.php?id=".$tasks[$i]['idTask']."&s=1'>
                                    <i class='material-icons' title='Approuver'>done</i>
                                </a>
                                <a href='php/form/approveTaskForm.php?id=".$tasks[$i]['idTask']."&s=0'>
                                    <i class='material-icons' title='Refuser'>block</i>
                                </a>
                            </p>";
                        } else {
                            echo"<span class='orange'>demandé par
                                    <span class='bold'>".htmlentities($user[0]['useFirstname'])." (".htmlentities($user[0]['useLogin']).")</span>
                                </span>
                            </p>";
                        }
                    }
                } else {
                    //Task is free
                    echo"<a href='php/form/requestTaskForm.php?id=".$tasks[$i]['idTask']."'>Prendre</a>
                    </p>";
                }
            } //Browse all tasks

            //Display the Insert task form for the author
            if($isAuthor) {
                echo "
                <!-- Insert task form -->
                <form id='insertTaskForm' name='insertTaskForm' action='php/form/insertTaskForm.php?id=".$_GET['id']."' method='post'>
                    <input type='text' id='description' name='description' placeholder='Titre de la tâche (max. 50 caractères)' maxlength='50' required/>
                    <input type='submit' value='Ajouter tâche'/>
                </form>
                <br/>
                ";
            }

            ?>
        </div>
    </div> <!-- End tasks tab -->

    <!-- Gallery tab -->
    <div id="tab3" class="tab-content">
        <div class="col-1-1">
            <div class="col-3-4 unique nopadding">
                <h2>Galerie des médias
                    <?php

                    //Display remaining space
                    if($isAuthor) {
                        $maxSize = round(MAX_EVENTMEDIA_SIZE/1024/1024, 2);

                        $mediaSize = getEventMediaSize($eventId);
                        //If 80% of quota is used -> display in orange
                        if($mediaSize < $maxSize*0.8) {
                            //Quota is used at less than 80%
                            echo "<span class='note'>(".getEventMediaSize($eventId)."MB sur ".$maxSize."MB)</span>";
                        } elseif($mediaSize >= $maxSize*0.95) {
                            //Quota is used at 95% or more
                            echo "<span class='note red'>(".getEventMediaSize($eventId)."MB sur ".$maxSize."MB)</span>";
                        } else {
                            //Quota is used at 80% or more
                            echo "<span class='note orange'>(".getEventMediaSize($eventId)."MB sur ".$maxSize."MB)</span>";
                        }
                    }

                    ?>
                </h2>
                <p>Toutes les photos ou vidéos que le créateur poste sur cet événement.</p>
                <?php

                for($i = 0; $i < count($medias); $i++) {
                    if(in_array(explode(".", $medias[$i]["medFilename"])[1], IMAGE_FORMATS)) {
                        //File is an image
                        echo "<div class='gallery-tile viewer-item'
                    style='background-image: url(files/".$medias[$i]['medFilename'].");' title='".$medias[$i]['medFilename']."'>";
						//Display delete button
						if($isAuthor) {
							echo "<a href='php/form/deleteMediaForm.php?id=".$medias[$i]['idMedia']."' class='delete' onclick='return confirm(\"Supprimer ce fichier?\")'>
								<i class='material-icons'>delete_forever</i>
							</a>";
						}
                    echo "</div>";

                    } else { //File is a video
                        echo "<div class='gallery-tile viewer-item video'
                    value='files/".$medias[$i]['medFilename']."' title='".$medias[$i]['medFilename']."'>";						
						//Display delete button
						if($isAuthor) {
							echo "<a href='php/form/deleteMediaForm.php?id=".$medias[$i]['idMedia']."' class='delete' onclick='return confirm(\"Supprimer ce fichier?\")'>
								<i class='material-icons'>delete_forever</i>
							</a>";
						}
					echo "</div>";
                    }
                }

                ?>
            </div>
        </div>

        <?php

        //Display insertion form for the author/admin
        if($isAuthor) {
            echo "<div class='col-1-1'>
                <div class='col-1-2 unique center-x'>
                    <form action='php/form/insertMediaForm.php?id=".$eventId."' method='post' enctype='multipart/form-data'>
                        <input name='files[]' type='file' multiple='multiple'
                        accept='.".join(',.', IMAGE_FORMATS).",.".join(',.', VIDEO_FORMATS)."' required/>

                        <button type='submit'>Ajouter</button>
                        <p class='note'>Formats acceptés: ".join(', ', IMAGE_FORMATS).", ".join(', ', VIDEO_FORMATS)."</p>
                    </form>
                </div>
            </div>
            ";
        }

        ?>

    </div> <!-- End gallery tab -->

    <!-- Discussions tab -->
    <div id="tab4" class="tab-content">
        <div class="col-3-4 unique nopadding message-container" style="margin-bottom: 100px;">
            <h2>Discussions publiques</h2>
            <p>Tous les messages postés par les utilisateurs ou le créateur de l'événement (des plus récents aux plus anciens).</p>

            <!-- Insert message form -->
            <?php

            //Display the insert message's form if user is connected
            if($isConnected) {
                echo "<div class='col-1-1'>
                    <form id='insertMessageForm' name='insertMessageForm' class='center-x' action='php/form/insertMessageForm.php?id=".$event[0]['idEvent']."' method='post'>
                        <p>
                            <label for='content'><i class='material-icons'>comment</i></label>
                            <textarea id='content' name='content' type='text' maxlength='1000' placeholder='Message (max. 1000 caractères)' required></textarea>
                        </p>
                        <input type='submit' value='Envoyer'/>
                    </form>
                </div>
                ";
            }


            ?>
        <!-- All messages -->
		<?php

        for($i = 0; $i < count($messages); $i++) {
            $messageAuthor = $db->getUserById($messages[$i]["fkUser"]);

            echo "<div class='col-1-1 unique nopadding message'";

            //If general admin -> highlight message
            if($messageAuthor[0]["useIsAdmin"] == 1) {
                echo " style='background-color: rgba(145, 157, 173, 0.3);'";
            }

            echo">
            <div class='col-1-4 nopadding'>
            <a href='profile.php?id=".$messageAuthor[0]['idUser']."' class='message-user";
			//Display icon if it's the event's author
            if($messages[$i]["fkUser"] == $author[0]["idUser"]) {
                echo " bold' title=\"Auteur de l'événement\">";
            } else {
                echo "'>";				
			}

            //Profile picture
            echo "<div class='userpicture small' style='background-image: url(files/".$messageAuthor[0]['usePicture'].");'></div>
			".htmlentities($messageAuthor[0]['useFirstname'])." (".htmlentities($messageAuthor[0]['useLogin']).")</a>";

            //Display admin logo
            if($messageAuthor[0]["useIsAdmin"] == 1) {
                echo "<i class='material-icons' title='Administrateur'>security</i>";
            }

            //Date
            echo"<br/>
                <span class='note'>".formatDate($messages[$i]["mesCreateDate"], 'd.m.Y à G:i')."</span>";
			
			//If user's message/event creator -> display delete button (cannot delete general admin's message)
			if(!empty($_SESSION["userId"]) && ($messageAuthor[0]['idUser'] == $_SESSION["userId"] || $isAuthor)) {
                //Event's creator cannot delete general admin's message
                if($messageAuthor[0]['useIsAdmin'] == 1) {
                    if(checkIfAdmin()) {
                        echo "<br/>
				<a href='php/form/deleteMessageForm.php?id=".$messages[$i]['idMessage']."' class='note' onclick='return confirm(\"Supprimer ce message?\")'>
				    <i class='material-icons'>clear</i>Supprimer
				</a>";
                    }
                } else {
                    echo "<br/>
				<a href='php/form/deleteMessageForm.php?id=".$messages[$i]['idMessage']."' class='note' onclick='return confirm(\"Supprimer ce message?\")'>
				    <i class='material-icons'>clear</i>Supprimer
				</a>";
                }
			}

            //Display message's content
            echo "</div>
            <div class='col-3-4 nopadding'>
                <span>".htmlentities($messages[$i]['mesContent'])."</span>
            </div>
        </div>";
        }

		?>
        </div>
    </div> <!-- End discussions tab -->
	</div>
</div> <!-- End wrapper-content -->
<?php
include_once("php/include/footer.php");
?>
<div id="gallery-viewer"></div>

</body>

<!-- Image viewer script -->
<script src="js/galleryViewer.js" type="text/javascript"></script>

<!-- Display default tab -->
<?php

if(!empty($_GET["tab"])) {
    echo "<script>document.getElementById('tabSelector".$_GET['tab']."').click();</script>";
} else {
    echo "<script>document.getElementById('tabSelector1').click();</script>";
}

?>

</html>