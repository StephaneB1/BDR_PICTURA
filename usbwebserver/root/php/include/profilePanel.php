<!--
/**
 * HEIG-VD - Mini-Projet BDR
 * 
 * Authors:     Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date:        20.12.2019
 * 
 * Summary:     HTML for the profile panel (Login, register and profile infos)
 */
-->

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
				    	<div class='community_cell_icon' style='background-image: url(files/". htmlentities($userCommunities[$i]["imageDeProfil"]) ."),  url(\"files/community_default.PNG\")'></div> " . htmlentities($userCommunities[$i]["nom"]) . "
				    </a>";
                }

                echo "
                    <a class='panel_button' onclick=\"displayId('createCommunityPopup', null)\">Create a new community</a>
                </div>";

	        } else {
				echo '
				<!-- Login form -->		
                <form id="loginUserForm" name="loginUserForm" action="php/form/loginUserForm.php" method="post">
                    
                    <!-- Pseudo -->
                    <input type="text" id="pseudo" name="pseudo" placeholder="Username" required autofocus/>
                    
                    <!-- Password -->
                    <input type="password" id="password" name="password" placeholder="Password" required/>
        
                    <input type="submit" value="Se connecter"/>
				
                </form>

                <button id="registerButton" onclick="enableRegisterForm()">Register</button>
                
                <!-- Register form -->
                <form id="registerUserForm" name="registerUserForm" class="hidden" action="php/form/insertUserForm.php" method="post">
                    
                    <!-- Pseudo -->
                    <input type="text" id="regPseudo" name="regPseudo" pattern="[a-zA-Z0-9]{1,20}" placeholder="Username*" title="Letters and numbers only (max. 20 characters)" required/>
                    
                    <!-- Email address -->
                    <input type="email" id="regEmail" name="regEmail" pattern="[a-zA-Z0-9\._-]{1,}[@]{1}[a-zA-Z0-9\._-]{1,}[.]{1}[a-zA-Z0-9\._-]{1,}" placeholder="Adresse email*" required/>

                    <!-- First name -->
                    <input type="text" id="regFirstName" name="regFirstName" pattern="[a-zA-ZàáâäèéêëìíîïòóôöùúûüÀÁÂÄÈÉÊËÌÍÎÏÒÓÔÖÙÚÛÜ]{1,50}" placeholder="First Name" title="(max. 50 characters)"/>

                    <!-- Last name -->
                    <input type="text" id="regLastName" name="regLastName" pattern="[a-zA-Z- ]{1,50}" placeholder="Last Name" title="(max. 50 characters)"/>

                    <!-- Password -->
                    <input type="password" id="regPassword" name="regPassword" minlength="8" placeholder="Password*" title="(min. 8 characters)" required/>

                    <!-- Password confirm -->
                    <input type="password" id="regPasswordConfirm" name="regPasswordConfirm" minlength="8" placeholder="Confirm Password*" title="(min. 8 characters)" required/>
        
                    <input type="submit" value="Create a New Account"/>
                </form>';
            }
       		?>

</div>