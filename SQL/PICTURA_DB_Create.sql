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
-- -----------------------------------------------------   

DROP SCHEMA IF EXISTS PICTURA;
CREATE SCHEMA IF NOT EXISTS PICTURA DEFAULT CHARACTER SET utf8mb4;
USE PICTURA;

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
  
  -- FOREIGN KEYS
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
  CONSTRAINT PK_Commentaire PRIMARY KEY (dateHeureAjout, idPhoto)
) 
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table Reponse_Commentaire
-- -----------------------------------------------------
CREATE TABLE Reponse_Commentaire (  
  dateHeureAjout_Reponse DATETIME,
  idPhoto_Reponse INT UNSIGNED,
  dateHeureAjout_Parent DATETIME NOT NULL,
  idPhoto_Parent INT UNSIGNED NOT NULL,
  CONSTRAINT PK_Reponse_Commentaire PRIMARY KEY (dateHeureAjout_Reponse, idPhoto_Reponse)
) 
ENGINE = InnoDB;



-- -----------------------------------------------------
-- -----------------------------------------------------
-- -----------------------------------------------------
-- FOREIGN KEYS
-- -----------------------------------------------------
-- -----------------------------------------------------
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
    FOREIGN KEY (dateHeureAjout_Parent , idPhoto_Parent) REFERENCES Commentaire (dateHeureAjout , idPhoto)
    ON DELETE CASCADE
    ON UPDATE CASCADE;
    
ALTER TABLE Reponse_Commentaire ADD CONSTRAINT FK_Commentaire_Reponse
    FOREIGN KEY (dateHeureAjout_Parent , idPhoto_Parent) REFERENCES Commentaire (dateHeureAjout , idPhoto)
    ON DELETE CASCADE
    ON UPDATE CASCADE;