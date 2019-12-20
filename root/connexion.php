<?php

/**
 * ETML
 * Author: Robin Demarta
 * Date: 28.04.2017
 * Summary: User authentification page
 */

include_once("php/include/header.php");

?>
<title>TPI - connexion</title>

</head>
<body>
<?php

include_once("php/include/menu-h.php");

?>

<div id="wrapper-content">
    <div class="col-1-3 unique center-x" style="background-color: rgba(255, 255, 255, 0.8)">
        <h1>Connexion</h1>

        <!-- Login form -->		
        <form id='loginUserForm' name='loginUserForm' action="php/form/loginUserForm.php" method="post">
            <p>Bienvenue, veuillez entrer vos identifiants:</p>
        	<!-- Login -->
            <p>
				<label for="login">
					<i class="material-icons">account_circle</i>
				</label>
				<input type="text" id="login" name="login" placeholder="Nom d'utilisateur" required autofocus/>
			</p>
        	<!-- Password -->
            <p>
				<label for="password">
					<i class="material-icons">lock</i>
				</label>
				<input type="password" id="password" name="password" placeholder="Mot de passe" required/>
			</p>

            <p><input type="submit" value="Se connecter"/></p>
            <p>Ou <a onclick="displayId('registerUserForm', 'loginUserForm')">je n'ai pas encore de compte</a></p>
        </form>

        <!-- Register form -->
		
        <form id='registerUserForm' name='registerUserForm' class='hidden' action="php/form/insertUserForm.php" method="post">
            <p>Création d'un nouveau compte:</p>
        	<!-- Login -->
            <p>
				<label for="regLogin">
					<i class="material-icons">account_circle</i>
				</label>
				<input type="text" id="regLogin" name="regLogin" pattern="[a-zA-Z0-9]{1,20}" placeholder="Nom d'utilisateur*" title="Seulement lettres et chiffres / Long. max.: 20" required/>
			</p>
        	<!-- First name -->
            <p>
				<label for="regFirstName">
					<i class="material-icons">face</i>
				</label>
				<input type="text" id="regFirstName" name="regFirstName" pattern="[a-zA-ZàáâäèéêëìíîïòóôöùúûüÀÁÂÄÈÉÊËÌÍÎÏÒÓÔÖÙÚÛÜ]{1,20}" placeholder="Prénom*" title="Max. 20 caractères" required/>
			</p>
        	<!-- Last name -->
            <p>
				<label for="regLastName">
					<i class="material-icons" style="opacity: 0;">face</i>				
				</label>
				<input type="text" id="regLastName" name="regLastName" pattern="[a-zA-Z- ]{1,30}" placeholder="Nom*" title="Max. 30 caractères" required/>
			</p>
        	<!-- Password -->
            <p>
				<label for="regPassword">
					<i class="material-icons">lock</i>
				</label>
				<input type="password" id="regPassword" name="regPassword" minlength="8" placeholder="Mot de passe*" title="Min. 8 caractères" required/>
			</p>
        	<!-- Password confirm -->
            <p>
				<label for="regPasswordConfirm">
					<i class="material-icons blank">lock</i>
				</label>
				<input type="password" id="regPasswordConfirm" name="regPasswordConfirm" minlength="8" placeholder="Confirmer mot de passe*" title="Min. 8 caractères" required/>
			</p>
        	<!-- Email address -->
            <p>
				<label for="regEmail">
					<i class="material-icons">mail</i>
				</label>
				<input type="email" id="regEmail" name="regEmail" pattern="[a-zA-Z0-9\._-]{1,}[@]{1}[a-zA-Z0-9\._-]{1,}[.]{1}[a-zA-Z0-9\._-]{1,}" placeholder="Adresse email*" required/>
			</p>
            <p class="note">*Obligatoires</p>

            <p><input type="submit" value="Créer compte"/></p>
            <p>Ou <a onclick="displayId('loginUserForm', 'registerUserForm')"> j'ai déjà un compte</a></p>
        </form>
    </div>
</div> <!-- End wrapper-content -->
<?php

include_once("php/include/footer.php");

?>
</body>

</html>