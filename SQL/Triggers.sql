-- -----------------------------------------------------
-- Mini-projet 		: PICTURA
-- 
-- Auteurs 			: Stéphane Bottin, 
--   		      	  Robin Demarta &
--                	  Simon Mattei
-- 
-- Date du rendu 	: 13.12.2019
-- 
-- Details 			: Triggers de la base de données
-- -----------------------------------------------------   

-- 

USE PICTURA; 

-- delete tag if no photo uses it anymore EVENT EVERY DAY
DELIMITER $$
CREATE EVENT balise_check
	ON SCHEDULE EVERY 1 DAY
DO
	BEGIN
		START TRANSACTION;
			DELETE FROM Balise
            WHERE (SELECT COUNT(*)
				   FROM Photo_Balise
                   WHERE Balise.label = Photo_Balise.labelBalise) = 0;
		COMMIT;
	END
$$


-- comment only responds to comments on same photo
DELIMITER $$
CREATE TRIGGER reponse_meme_photo BEFORE INSERT ON Reponse_Commentaire
FOR EACH ROW
BEGIN
	IF 
		NEW.idPhoto_Parent <> NEW.idPhoto_Reponse
    THEN
		DELETE FROM Commentaire
        WHERE (NEW.dateHeureAjout_Reponse,NEW.idPhoto_Reponse,NEW.pseudoUtilisateur_Reponse) = (dateHeureAjout,idPhoto,pseudoUtilisateur);
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = "Comment IdPhoto != Responde IdPhoto";
	END IF;
END
$$

-- response's datetime > parent comment
DELIMITER $$
CREATE TRIGGER reponse_bonne_date BEFORE INSERT ON Reponse_Commentaire
FOR EACH ROW
BEGIN
	IF 
		NEW.dateHeureAjout_Parent >= NEW.dateHeureAjout_Reponse
    THEN
		DELETE FROM Commentaire
        WHERE (NEW.dateHeureAjout_Reponse,NEW.idPhoto_Reponse,NEW.pseudoUtilisateur_Reponse) = (dateHeureAjout,idPhoto,pseudoUtilisateur);
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = "Comment date > Response date";
	END IF;
END
$$

-- comment datetime > photo datetime
DELIMITER $$
CREATE TRIGGER commentaire_bonne_date BEFORE INSERT ON Commentaire
FOR EACH ROW
BEGIN
	IF 
		NEW.dateHeureAjout <= (SELECT dateHeureAjout
							  FROM Photo
							  WHERE NEW.idPhoto = Photo.id)
    THEN
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = "Comment date < Photo date";
	END IF;
END
$$

-- Photo date < today
DELIMITER $$
CREATE TRIGGER photo_bonne_date BEFORE INSERT ON Photo
FOR EACH ROW
BEGIN
	IF 
		NEW.dateHeureAjout > NOW()
    THEN
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = "Photo date > Today";
	END IF;
END
$$

-- there must always be one admin per community (2 triggers)
DELIMITER $$
CREATE TRIGGER admin_update BEFORE UPDATE ON Utilisateur_Modere_Communaute
FOR EACH ROW
BEGIN
	IF 
		NEW.niveauPrivilege = 0
			AND
		OLD.niveauPrivilege = 1
			AND
		(SELECT COUNT(*) 
         FROM Utilisateur_Modere_Communaute
		 WHERE niveauPrivilege = 1 AND nomCommunaute = OLD.nomCommunaute) = 1
    THEN
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = "can't update last admin from community into mod";
	END IF;
END
$$

DELIMITER $$
CREATE TRIGGER admin_delete BEFORE DELETE ON Utilisateur_Modere_Communaute
FOR EACH ROW
BEGIN
	IF 
		OLD.niveauPrivilege = 1
			AND
		(SELECT COUNT(*) 
         FROM Utilisateur_Modere_Communaute
		 WHERE niveauPrivilege = 1 AND nomCommunaute = OLD.nomCommunaute) = 1
    THEN
		SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = "can't delete last admin from community";
	END IF;
END
$$





