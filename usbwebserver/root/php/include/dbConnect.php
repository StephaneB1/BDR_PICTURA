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
     * Get the ID of the last inserted entry
     */
    public function getLastId() {
        return $this->connexion->lastInsertId();
    }

    /**
     * If the given string is empty (""), it will be corrected to "NULL"
     * Attention: this function automatically adds two ' if not null
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
    public function insertCommunity($name, $detail, $picture, $creator) {
        $this->correctNullString($picture);

        $query = $this->connexion->prepare("
          INSERT INTO Communaute (nom, detail, imageDeProfil)
		  VALUES ('$name', '$detail', $picture)
		");

        $result = $query->execute();

        // Creator is set as the community's administrator
        if($result) {
            $result = $this->insertAdmin($creator, $name, 1);
        }

        return $result;
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

    public function quitCommunity($username, $community) {
        $query = $this->connexion->prepare("
            DELETE FROM utilisateur_suit_communaute
                WHERE pseudoUtilisateur = '$username'
                    AND nomCommunaute = '$community';
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

    public function getAllCommunityAdmins($community_name) {
        $query = $this->connexion->prepare("
            SELECT * FROM utilisateur_modere_communaute
            WHERE nomCommunaute = '$community_name';
        ");

        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }

    public function isUserFollowing($username, $community_name) {
        $query = $this->connexion->prepare("
            SELECT COUNT(1) AS userIsFollowing
            FROM utilisateur_suit_communaute
            WHERE pseudoUtilisateur = '$username' AND nomCommunaute = '$community_name';
        ");

        if($query->execute()) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            if($result["userIsFollowing"] == 1)
                return true;
        }

        return false; // Query error
    }

   public function insertPhoto($title, $detail, $user, $community, $fileName, $tags) {
       $this->correctNullString($detail);
       $result = true;

       // Insert photo
       $query = $this->connexion->prepare("
                INSERT INTO Photo (titre, detail, dateHeureAjout, masquee, urlPhoto, pseudoUtilisateur, nomCommunaute)
                    VALUES ('$title', $detail, NOW(), 0, '$fileName', '$user', '$community');
		");
       $result = $query->execute();

       // Tags (only if photo was successfully inserted)
       if ($result && !empty($tags)) {
           $photoId = $this->getLastId();

           for ($i = 0; $i < count($tags); ++$i) {
               if($this->insertTagSafe($tags[$i])) { // Insert tag
                   // Link tag
                   $this->linkPhotoTag($photoId, $tags[$i]);
               }
           }
       }

       return $result;
    }

    public function getPictureById($picture_id) {
        $query = $this->connexion->prepare("
            SELECT * FROM photo
            WHERE id = '$picture_id';
        ");

        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }

    /* ------ Comments ------ */

    public function insertComment($photoId, $user, $comment, $parent) {
        // Define exact same datetime for both INSERT (for table Commentaire and Reponse_Commentaire)
        $dateTime = date("Y-m-d H-i-s");

        $query = $this->connexion->prepare("
          INSERT INTO Commentaire (dateHeureAjout, idPhoto, pseudoUtilisateur, commentaire)
		  VALUES ('$dateTime', $photoId, '$user', '$comment');
		");

        $result = $query->execute();

        if($result && !empty($parent)){
            // Comment is a response
            $query = $this->connexion->prepare("
              INSERT INTO Reponse_Commentaire (dateHeureAjout_Reponse, idPhoto_Reponse, dateHeureAjout_Parent, idPhoto_Parent)
              VALUES ('$dateTime', $photoId, '$parent', $photoId)
            ");

            $result = $result && $query->execute();
        }

        return $result;
    }

    /* ------ Tags ------ */

    /**
     * Inserts a new tag
     * @param $label
     * @return bool true if the tag exists or if it's been successfully created (false only on error)
     */
    public function insertTagSafe($label) {
        $tag = $this->getTagByLabel($label);

        if(!empty($tag)) // Tag already exists, do not insert (no error)
            return true;

        $query = $this->connexion->prepare("
          INSERT INTO Balise (label)
		  VALUES ('$label')
		");

        return $query->execute();
    }

    public function getTagByLabel($label) {
        $query = $this->connexion->prepare("
            SELECT * FROM Balise
            WHERE label = '$label';
        ");

        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }

    public function linkPhotoTag($photoId, $label) {
        $query = $this->connexion->prepare("
          INSERT INTO Photo_Balise (idPhoto, labelBalise)
		  VALUES ($photoId, '$label')
		");

        return $query->execute();
    }

    public function checkIfUserLikedAPicture($username, $photoId) {
        $query = $this->connexion->prepare("
            SELECT COUNT(1) AS userLikedThatPicture
            FROM utilisateur_like_photo
            WHERE pseudoUtilisateur = '$username' AND idPhoto = '$photoId';
        ");

        if($query->execute()) {
            $result = $query->fetch(PDO::FETCH_ASSOC);
            if($result["userLikedThatPicture"] == 1)
                return true;
        }

        return false; // Query error
    }

    public function likeThisPicture($username, $photoId) {
        $query = $this->connexion->prepare("
          INSERT INTO utilisateur_like_photo (pseudoUtilisateur, idPhoto)
		  VALUES ('$username', '$photoId');
		");

        return $query->execute();
    }

    public function unlikeThisPicture($username, $photoId) {
        $query = $this->connexion->prepare("
          DELETE FROM utilisateur_like_photo
		  WHERE pseudoUtilisateur = '$username' AND idPhoto = '$photoId');
		");

        return $query->execute();
    }

    public function getTotalLikes($photoId) {
        $query = $this->connexion->prepare("
            SELECT COUNT(*) AS total FROM utilisateur_like_photo
            WHERE idPhoto = '$photoId';
        ");

        if($query->execute()) {
            $total_pictures = $query->fetch(PDO::FETCH_ASSOC);
            return $total_pictures["total"];
        }

        return false; // Query error
    }

    public function getPictureComments($photoId) {
        $query = $this->connexion->prepare("
            SELECT * FROM commentaire
            WHERE idPhoto = '$photoId';
        ");

        if($query->execute())
        if($query->execute())
            return $query->fetchAll(PDO::FETCH_ASSOC);
        return false; // Query error
    }


    /* ------ Administration ------ */

    /**
     * @param $user
     * @param $community
     * @param $privilege 0 = moderator, 1 = administrator
     * @return bool
     */
    public function insertAdmin($user, $community, $privilege) {
        $query = $this->connexion->prepare("
          INSERT INTO Utilisateur_Modere_Communaute (pseudoUtilisateur, nomCommunaute, niveauPrivilege)
		  VALUES ('$user', '$community', $privilege)
		");

        return $query->execute();
    }

    public function deleteAdmin($user, $community) {
        $query = $this->connexion->prepare("
          DELETE FROM Utilisateur_Modere_Communaute
          WHERE pseudoUtilisateur = '$user' AND nomCommunaute = '$community'
		");

        return $query->execute();
    }

} //db class

?>
