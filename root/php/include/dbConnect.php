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

    /*
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
            //$this->connexion->exec("set names utf8");
        } catch(PDOException $e) { //Catch error
            print "Erreur: ".$e->getMessage();
            die();
        }
    }

    /* ------ Users ------ */

    /*
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

    /*
     * Get all the user logins
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

    /*
     * Insert new user account
	 * @return true or false wether the query was a success or not
     */
    public function insertUser($pseudo, $email, $password, $firstName, $lastName) {
        $query = $this->connexion->prepare("
          INSERT INTO Utilisateur (pseudo, email, motDePasse, prenom, nom)
		  VALUES ('$pseudo', '$email', '$password', '$firstName', '$lastName')
		");
		
        return $query->execute();
    }

    /*
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
} //db class

?>
