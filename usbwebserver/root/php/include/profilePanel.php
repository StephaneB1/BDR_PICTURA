<div class="profileContainer" id="user_panel">
            <div class="arrow-up" id="profileArrow"></div>
            <div class="title_container" id="profile_title_container"> 
                My Profile
                <div class="title_line" id="profile_title_line"></div>
            </div>
			<?php
	        if ($isLoggedIn) {
                echo '
                    <div class="myprofile_container">
                        <div class="login_welcome">
                            Welcome back 
                            <div class="username_highlight" id="username_highlight">' . htmlentities($user["pseudo"]) . '</div>!
                        </div>

                        <a href="profile.php?p=' . htmlentities($user["pseudo"]) . '" class="panel_button">Open my profile</a>
                        <button class="panel_button">Admin page</button>
                        <button class="panel_button" id="red_hover" onclick="location.href=\''. getHostUrl() . '/php/form/logoutUserForm.php\';">Logout</button>
                    </div>

                    <div class="title_container" id="profile_title_container"> 
                        My Communities
                        <div class="title_line" id="profile_title_line"></div>
                    </div>
					';
					
				$userCommunities = $db->getUserCommunities($user["pseudo"]);

                if(count($userCommunities) == 0) {
                    echo "<div class='panel_text_container'><i>You are not following any communities</i></div>";
                } else {
                    echo "<div class=communities_bubble_container>";
                }

                for ($i = 0; $i < count($userCommunities); ++$i) {
                    echo "          
				    <a class='community_cell_container' href='community.php?n=" . htmlentities($userCommunities[$i]["nom"]) . "' title='" . htmlentities($userCommunities[$i]["detail"]) . "'>
				    	<div class='community_cell_icon' style='background-image: url(files/". htmlentities($userCommunities[$i]["imageDeProfil"]) ."),  url(\"files/community_default.PNG\")'></div> " . htmlentities($communities[$i]["nom"]) . "
				    </a>";
                }

                echo "
                    <a class='panel_button' onclick=\"displayId('createCommunityPopup', null)\">Create a new community</a>
                </div>";

	        } else {
				echo '
				<!-- Login form -->		
        		<form id="loginUserForm" name="loginUserForm" action="php/form/loginUserForm.php" method="post">
	        	<!-- pseudo -->
	            <p>
					<input type="text" id="pseudo" name="pseudo" placeholder="Username" required autofocus/>
				</p>
	        	<!-- Password -->
	            <p>
					<input type="password" id="password" name="password" placeholder="Password" required/>
				</p>
	
	            <input type="submit" value="Se connecter"/>
				
	        	</form>
				<a href="connexion.php">register</a>	';
            }
       		?>

        <div>