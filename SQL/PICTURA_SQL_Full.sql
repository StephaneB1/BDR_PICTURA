-- -----------------------------------------------------
-- Mini-projet 		: PICTURA
-- 
-- Auteurs 			: Stéphane Bottin, 
--   		      	  Robin Demarta &
--                	  Simon Mattei
-- 
-- Date du rendu 	: 13.12.2019
-- 
-- Details 			: Création de la base de données de 
-- 					  PICTURA avec l'ensemble des tables
-- 					  et données de test.
-- -----------------------------------------------------   

DROP SCHEMA IF EXISTS PICTURA;
CREATE SCHEMA IF NOT EXISTS PICTURA DEFAULT CHARACTER SET utf8mb4;
USE PICTURA;

-- -----------------------------------------------------
-- -----------------------------------------------------
-- STRUCTURE DE LA BASE DE DONNÉES
-- -----------------------------------------------------
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table Utilisateur
-- -----------------------------------------------------
CREATE TABLE Utilisateur (
  pseudo VARCHAR(20),
  email VARCHAR(50) UNIQUE NOT NULL,
  motDePasse VARCHAR(100) NOT NULL,
  nom VARCHAR(50),
  prenom VARCHAR(50),
  CONSTRAINT PK_Utilisateur PRIMARY KEY (pseudo)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table Communaute
-- -----------------------------------------------------
CREATE TABLE Communaute (
  nom VARCHAR(20),
  detail VARCHAR(500) NOT NULL,
  imageDeProfil VARCHAR(100),
  CONSTRAINT PK_Communaute PRIMARY KEY (nom)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table Photo
-- -----------------------------------------------------
CREATE TABLE Photo (
  id INT UNSIGNED AUTO_INCREMENT,
  titre VARCHAR(50) NOT NULL,
  detail VARCHAR(500),
  dateHeureAjout DATETIME NOT NULL,
  masquee TINYINT NOT NULL,
  urlPhoto VARCHAR(100) NOT NULL,
  CONSTRAINT PK_Photo PRIMARY KEY (id),
  pseudoUtilisateur VARCHAR(20) NOT NULL,
  nomCommunaute VARCHAR(20) NOT NULL
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table Utilisateur_Suit_Communaute
-- -----------------------------------------------------
CREATE TABLE Utilisateur_Suit_Communaute (
  pseudoUtilisateur VARCHAR(20),
  nomCommunaute VARCHAR(20),
  CONSTRAINT PK_Utilisateur_Suit_Communaute PRIMARY KEY (pseudoUtilisateur, nomCommunaute)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table Utilisateur_Modere_Communaute
-- -----------------------------------------------------
CREATE TABLE Utilisateur_Modere_Communaute (
  pseudoUtilisateur VARCHAR(20),
  nomCommunaute VARCHAR(20),
  niveauPrivilege INT UNSIGNED NOT NULL,
  CONSTRAINT PK_Utilisateur_Modere_Communaute PRIMARY KEY (pseudoUtilisateur, nomCommunaute)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table Utilisateur_Like_Photo
-- -----------------------------------------------------
CREATE TABLE Utilisateur_Like_Photo (
  pseudoUtilisateur VARCHAR(20),
  idPhoto INT UNSIGNED,
  CONSTRAINT PK_Utilisateur_Like_Photo PRIMARY KEY (pseudoUtilisateur, idPhoto)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table Balise
-- -----------------------------------------------------
CREATE TABLE Balise (
  label VARCHAR(50),
  CONSTRAINT PK_Balise PRIMARY KEY (label)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table Photo_Balise
-- -----------------------------------------------------
CREATE TABLE Photo_Balise (
  idPhoto INT UNSIGNED,
  labelBalise VARCHAR(50),
  CONSTRAINT PK_Photo_Balise PRIMARY KEY (idPhoto, labelBalise)
)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table Commentaire
-- -----------------------------------------------------
CREATE TABLE Commentaire (
  dateHeureAjout DATETIME,
  idPhoto INT UNSIGNED,
  pseudoUtilisateur VARCHAR(20) NOT NULL,
  commentaire VARCHAR(500) NOT NULL,
  CONSTRAINT PK_Commentaire PRIMARY KEY (dateHeureAjout, idPhoto, pseudoUtilisateur)
) 
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table Reponse_Commentaire
-- -----------------------------------------------------
CREATE TABLE Reponse_Commentaire (  
  dateHeureAjout_Reponse DATETIME,
  idPhoto_Reponse INT UNSIGNED,
  pseudoUtilisateur_Reponse VARCHAR(20),
  dateHeureAjout_Parent DATETIME NOT NULL,
  idPhoto_Parent INT UNSIGNED NOT NULL,
  pseudoUtilisateur_Parent VARCHAR(20) NOT NULL,
  CONSTRAINT PK_Reponse_Commentaire PRIMARY KEY (dateHeureAjout_Reponse, idPhoto_Reponse, pseudoUtilisateur_Reponse)
) 
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Foreign keys constraints
-- -----------------------------------------------------

ALTER TABLE Photo ADD CONSTRAINT FK_Photo_pseudoUtilisateur
	FOREIGN KEY (pseudoUtilisateur) REFERENCES Utilisateur (pseudo)
		ON UPDATE CASCADE
        ON DELETE CASCADE;
        
ALTER TABLE Photo ADD CONSTRAINT FK_Photo_nomCommunaute
	FOREIGN KEY (nomCommunaute) REFERENCES Communaute (nom)
		ON UPDATE RESTRICT -- car une photo ne peut pas changer de communauté
        ON DELETE CASCADE;
        
ALTER TABLE Utilisateur_Suit_Communaute ADD CONSTRAINT FK_Utilisateur_Suit_Communaute_pseudoUtilisateur
    FOREIGN KEY (pseudoUtilisateur) REFERENCES Utilisateur (pseudo)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
    
ALTER TABLE Utilisateur_Suit_Communaute ADD CONSTRAINT FK_Utilisateur_Suit_Communaute_nomCommunaute
    FOREIGN KEY (nomCommunaute) REFERENCES Communaute (nom)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
    
ALTER TABLE Utilisateur_Modere_Communaute ADD CONSTRAINT FK_Utilisateur_Modere_Communaute_pseudoUtilisateur
    FOREIGN KEY (pseudoUtilisateur) REFERENCES Utilisateur (pseudo)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
    
ALTER TABLE Utilisateur_Modere_Communaute ADD CONSTRAINT FK_Utilisateur_Modere_Communaute_nomCommunaute
    FOREIGN KEY (nomCommunaute) REFERENCES Communaute (nom)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
    
ALTER TABLE Utilisateur_Like_Photo ADD CONSTRAINT FK_Utilisateur_Like_Photo_pseudoUtilisateur
    FOREIGN KEY (pseudoUtilisateur) REFERENCES Utilisateur (pseudo)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE Utilisateur_Like_Photo ADD CONSTRAINT FK_Utilisateur_Like_Photo_idPhoto
    FOREIGN KEY (idPhoto) REFERENCES Photo (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
    
ALTER TABLE Photo_Balise ADD CONSTRAINT FK_Photo_Balise_idPhoto
    FOREIGN KEY (idPhoto) REFERENCES Photo (id)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
    
ALTER TABLE Photo_Balise ADD CONSTRAINT FK_Photo_Balise_label
    FOREIGN KEY (labelBalise) REFERENCES Balise (label)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
    
ALTER TABLE Commentaire ADD CONSTRAINT FK_Commentaire_pseudoUtilisateur
    FOREIGN KEY (pseudoUtilisateur) REFERENCES Utilisateur (pseudo)
    ON UPDATE CASCADE
    ON DELETE CASCADE;
    
 ALTER TABLE Commentaire ADD CONSTRAINT FK_Commentaire_idPhoto
    FOREIGN KEY (idPhoto) REFERENCES Photo (id)
    ON UPDATE CASCADE
    ON DELETE CASCADE;
    
 ALTER TABLE Reponse_Commentaire ADD CONSTRAINT FK_Commentaire_Parent
    FOREIGN KEY (dateHeureAjout_Parent , idPhoto_Parent, pseudoUtilisateur_Parent) REFERENCES Commentaire (dateHeureAjout , idPhoto, pseudoUtilisateur)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
    
ALTER TABLE Reponse_Commentaire ADD CONSTRAINT FK_Commentaire_Reponse
    FOREIGN KEY (dateHeureAjout_Reponse , idPhoto_Reponse, pseudoUtilisateur_Reponse) REFERENCES Commentaire (dateHeureAjout , idPhoto, pseudoUtilisateur)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
	
-- -----------------------------------------------------
-- -----------------------------------------------------
-- TRIGGERS / EVENTS
-- -----------------------------------------------------
-- -----------------------------------------------------

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

-- -----------------------------------------------------
-- -----------------------------------------------------
-- DONNÉES TEST
-- -----------------------------------------------------
-- -----------------------------------------------------

INSERT INTO `utilisateur` VALUES
	('robin','robin@mail.ch','$2y$10$RJIDUagYWmu1zLCckyFeWOpRaCGGYOs0JI/hlAwBw57DoCeeF/9nW','Demarta','Robin'),
	('simon','simon@mail.ch','$2y$10$1W9XPE2rgg6pwF71yGhlz.zbZ7BRttZHUEp0EK5U2J8FuGM1DCCza','Mattei','Simon'),
	('stephane','stephane@mail.ch','$2y$10$tSwBZFihHsQ0CEUIIAb4r.BcRJIp2vocBYN0V56WLoZaaHDJ3MLjO','Bottin','Stéphane');

INSERT INTO `communaute` VALUES
	('Birds','Nice photos of birds from around the world.','hqdefault_5e2490792ce41.jpg'),
	('Cats','Photos of cute cats.','tigrisse1_5e248f7484277.jpg'),
	('Nature','Photos of nature, animals, landscapes, etc.','unsplash9_5e24b6c9a891b.jpg'),
	('Space','Beautiful photos of our universe.','stscihp182_5e24b42ddd67e.png');

INSERT INTO `utilisateur_modere_communaute` VALUES
	('robin','Cats',1), ('robin','Nature',1), ('robin','space',0), ('simon','Birds',1),
	('simon','cats',0),('simon','space',0),('stephane','birds',0),('stephane','Nature',1),
	('stephane','Space',1);

INSERT INTO `utilisateur_suit_communaute` VALUES
	('stephane','Birds'), ('robin','Cats'), ('simon','Cats'), ('stephane','Cats'),
	('robin','Nature'),('simon','Nature'),('robin','Space'),('stephane','Space');

INSERT INTO `photo` VALUES
	(1,'Here\'s my cat','Her name is Tigrisse','2020-01-19 18:19:26',0,'tigrisse2_5e248f9ed1b1b.jpg','robin','Cats'),
	(2,'Crimson Sunbird',NULL,'2020-01-19 18:23:28',0,'mg98983a_5e2490903a038.jpg','simon','Birds'),
	(3,'Cute little kitten',NULL,'2020-01-19 18:25:37',0,'cute_5e249111e10c2.jpg','simon','Cats'),
	(6,'Random nebula',NULL,'2020-01-19 20:56:01',0,'stscihp171_5e24b45111154.png','stephane','Space'),
	(7,'La terre depuis la lune (Apollo 11)',NULL,'2020-01-19 21:04:01',0,'as11446552_5e24b631ce825.jpg','robin','Space'),
	(8,'La lune lors d\'un coucher de soleil',NULL,'2020-01-19 21:04:53',0,'eta7i9bvrx_5e24b665d378d.jpg','robin','Space'),
	(9,'Forest wallpaper',NULL,'2020-01-19 21:06:53',0,'dmci0cmyjy_5e24b6dd9ab41.jpg','robin','Nature'),
	(10,'Wild ibex','Beautiful creature','2020-01-19 21:07:29',0,'shuttersto_5e24b701cd107.jpg','robin','Nature'),
	(11,'That\'s a little robin',NULL,'2020-01-19 21:09:33',0,'image11_5e24b77d9f1ca.jpg','robin','Birds');
	
INSERT INTO `utilisateur_like_photo` VALUES
	('robin',1),('simon',1),('stephane',1),('robin',2),
	('robin',6),('simon',6),('robin',7),('simon',7),
	('robin',8),('simon',8),('stephane',8),('robin',9),
	('robin',10),('simon',10),('simon',11),('stephane',11);

INSERT INTO `balise` VALUES
	('alps'), ('animal'), ('apollo'), ('colors'),
	('cute'), ('earth'), ('forest'), ('funny'),
	('ibex'), ('kitten'), ('mars'), ('moon'),
	('nebula'),('robin');

INSERT INTO `photo_balise` VALUES
	(10,'alps'), (1,'animal'), (7,'apollo'), (6,'colors'),
	(1,'cute'), (3,'cute'), (11,'cute'), (7,'earth'),
	(9,'forest'), (11,'funny'), (10,'ibex'), (3,'kitten'),
	(7,'moon'), (8,'moon'), (6,'nebula'), (11,'robin');

INSERT INTO `commentaire` VALUES
	('2020-01-19 20:57:38',2,'stephane','il vient d\'où cet oiseau-là??'),
	('2020-01-19 20:58:05',1,'stephane','trop choue!'),
	('2020-01-19 20:58:40',1,'simon','c\'est une femelle?'),
	('2020-01-19 20:59:18',6,'simon','très joli'),
	('2020-01-19 21:01:59',1,'stephane','oui je crois'),
	('2020-01-19 21:02:23',1,'robin','oui :)'),
	('2020-01-19 21:05:23',2,'robin','d\'Asie je crois bien'),
	('2020-01-19 21:11:05',1,'simon','et oui elle est très choue'),
	('2020-01-19 21:12:18',8,'stephane','c\'est fou ces couleurs! magnifique'),
	('2020-01-19 21:13:12',1,'stephane','elle a quelle âge?'),
	('2020-01-19 21:13:40',1,'robin','elle a 15 ans actuellement'),
	('2020-01-19 21:15:00',7,'stephane','fabuleux!'),
	('2020-01-19 21:15:26',7,'simon','et c\'était en 1969'),
	('2020-01-19 21:16:40',7,'robin','oui il y a eu encore plus de progrès technologique depuis'),
	('2020-01-19 21:21:04',9,'stephane','en effet, très joli fond d\'écran ;)'),
	('2020-01-19 21:21:43',9,'simon','tout à fait oui');

INSERT INTO `reponse_commentaire` VALUES
	('2020-01-19 21:05:23',2,'robin','2020-01-19 20:57:38',2,'stephane'),
	('2020-01-19 20:58:40',1,'simon','2020-01-19 20:58:05',1,'stephane'),
	('2020-01-19 21:11:05',1,'simon','2020-01-19 20:58:05',1,'stephane'),
	('2020-01-19 21:01:59',1,'stephane','2020-01-19 20:58:40',1,'simon'),
	('2020-01-19 21:02:23',1,'robin','2020-01-19 20:58:40',1,'simon'),
	('2020-01-19 21:13:40',1,'robin','2020-01-19 21:13:12',1,'stephane'),
	('2020-01-19 21:16:40',7,'robin','2020-01-19 21:15:26',7,'simon'),
	('2020-01-19 21:21:43',9,'simon','2020-01-19 21:21:04',9,'stephane');