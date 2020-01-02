<?php

/**
 * HEIG-VD
 * Authors: Stéphane Bottin, Robin Demarta, Simon Mattei
 * Date: 20.12.2019
 * Summary: This class handles all database interactions via specific public functions
 */

class db
{
    /* ------ General ------ */

    private $connexion;

    /**
     * This function is automatically executed on class instantiation
     */
    function __construct() {
        date_default_timezone_set("Europe/Zurich"); //Define timezone

        //Extract database login informations from PHP config file
        $config = include($_SERVER['DOCUMENT_ROOT']."/config/db_config.php");
        try {
            $this->connexion = new pdo(
                "mysql:host=".$config["hostname"].
                ";port=".$config["port"].
                ";dbname=".$config["database"],
                $config["user"],
                $config["password"]);
            $this->connexion->exec("set names utf8");
        } catch(PDOException $e) { //Catch error
            print "Erreur: ".$e->getMessage();
            die();
        }
    }

    /**
     * If the given string is empty (""), it will be corrected to "NULL"
     */
    public function correctNullString(&$var) {
        $var = !empty($var) ? "'$var'" : "NULL";
    }

    /* ------ Users ------ */

    /**
     * Get all the user logins
	 * return false if an error occured
     */
    public function getAllUserPseudos() {
        $query = $this->connexion->prepare("
          SELECT pseudo FROM Utilisateur
        ");

        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }

    /**
     * Get a specific user
	 * return false if an error occured
     */
    public function getUserByPseudo($pseudo) {
        $query = $this->connexion->prepare("
          SELECT * FROM Utilisateur
          WHERE pseudo = '$pseudo'
        ");

        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }

    /**
     * Insert new user account
	 * @return true or false wether the query was a success or not
     */
    public function insertUser($pseudo, $email, $password, $firstName, $lastName) {
        $this->correctNullString($firstName);
        $this->correctNullString($lastName);

        $query = $this->connexion->prepare("
          INSERT INTO Utilisateur (pseudo, email, motDePasse, prenom, nom)
		  VALUES ('$pseudo', '$email', '$password', $firstName, $lastName)
		");
		
        return $query->execute();
    }

    /**
     * Insert new user account
	 * @return true or false wether the query was a success or not
     */
    public function deleteUser($pseudo) {
        $query = $this->connexion->prepare("
          DELETE FROM Utilisateur
          WHERE pseudo = '$pseudo'
		");

        return $query->execute();
    }

    /* ------ Communities ------ */

    /**
     * Get all the communities
	 * return false if an error occured
     */
    public function getAllCommunities() {
        $query = $this->connexion->prepare("
          SELECT * FROM Communaute
        ");

        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }

    /**
     * Get a specific community
	 * return false if an error occured
     */
    public function getCommunityByName($name) {
        $query = $this->connexion->prepare("
          SELECT * FROM Communaute
          WHERE nom = '$name'
        ");

        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }

    /**
     * Get a specific community
	 * return false if an error occured
     */
    public function getCommunityPhotos($name) {
        $query = $this->connexion->prepare("
          SELECT * FROM Communaute
          WHERE nom = '$name'
          ORDER BY dateHeureAjout
        ");

        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }

    /**
     * Insert new community
	 * return true or false wether the query was a success or not
     */
    public function insertCommunity($name, $detail, $picture) {
        $this->correctNullString($picture);

        $query = $this->connexion->prepare("
          INSERT INTO Communaute (nom, detail, imageDeProfil)
		  VALUES ('$name', '$detail', $picture)
		");

        return $query->execute();
    }

    public function getUserCommunities($username) {
        $query = $this->connexion->prepare("
            SELECT * FROM Communaute
                INNER JOIN utilisateur_suit_communaute
                    ON Communaute.nom = utilisateur_suit_communaute.nomCommunaute
            WHERE pseudoUtilisateur = '$username'
        ");

        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }

    public function followCommunity($username, $community) {
        $query = $this->connexion->prepare("
            INSERT INTO utilisateur_suit_communaute (pseudoUtilisateur, nomCommunaute)
            VALUES ('$username', '$community');
        ");

        return $query->execute();
    }

    public function getUserFeedPictures($username) {
        $query = $this->connexion->prepare("
            SELECT * FROM photo
            WHERE nomCommunaute = ANY(SELECT nomCommunaute FROM utilisateur_suit_communaute WHERE pseudoUtilisateur = '$username');
        ");

        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }

    public function getCommunityFeedPictures($community_name) {
        $query = $this->connexion->prepare("
            SELECT * FROM photo
            WHERE nomCommunaute = '$community_name';
        ");

        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }

    public function getCommunityTotalMembers($community_name) {
        $query = $this->connexion->prepare("
            SELECT COUNT(*) AS total FROM utilisateur_suit_communaute
            WHERE nomCommunaute = '$community_name';
        ");

        if($query->execute()) {
            $total_members = $query->fetch(PDO::FETCH_ASSOC);
            return $total_members["total"];
        }
        return false; // Query error
    }

    public function getCommunityTotalPictures($community_name) {
        $query = $this->connexion->prepare("
            SELECT COUNT(*) AS total FROM photo
            WHERE nomCommunaute = '$community_name';
        ");

        if($query->execute()) {
            $total_pictures = $query->fetch(PDO::FETCH_ASSOC);
            return $total_pictures["total"];
        }
        return false; // Query error
    }
    
    // TODO
    /*public function addNewPicture() {
        INSERT INTO photo (titre, detail, dateHeureAjout, masquee, pseudoUtilisateur, nomCommunaute, urlPhoto) 
        VALUES (...);
    }*/

} //db class

?>
