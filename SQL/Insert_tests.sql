--
-- Titre du mini-projet : PICTURA
-- Auteurs :              Stéphane Bottin, 
--   		              Robin Demarta &
--                        Simon Mattei
-- Date du rendu :        13.12.2019
-- ----------------------------------------------------- 

USE PICTURA; 

-- Ajout d'utilisateurs


INSERT INTO utilisateur VALUES ("Steph", "stephane@heig-vd.ch", "76t824z9uoigtjknf", "Bottin", "Stephane");
INSERT INTO utilisateur VALUES ("Robin", "robin@heig-vd.ch", "4tb9eu0r2jio4", "Demarta", "Robin");
INSERT INTO utilisateur VALUES ("Simon", "simon@heig-vd.ch", "dfkjhfor3t5t54", "Mattei", "Simon");

INSERT INTO Communaute VALUES ("Chats","cest mignon",NULL);
INSERT INTO Communaute VALUES ("Chiens","cest mignon",NULL);
INSERT INTO Communaute VALUES ("Oiseaux","meilleure communaute",NULL);

INSERT INTO Balise VALUES ("funny");
INSERT INTO Balise VALUES ("cool");
INSERT INTO Balise VALUES ("sick");

INSERT INTO Photo VALUES ("1","chat",NULL,"2009-10-10 15:30:20","0","dot.com","Steph","Chats");
INSERT INTO Photo VALUES ("2","chat2",NULL,"2001-10-10 15:30:20","0","dot.com","Simon","Chats");
INSERT INTO Photo VALUES ("3","chien1",NULL,"2001-10-10 15:30:20","0","dot.com","Robin","Chiens");
-- INSERT INTO Photo VALUES ("4","Oiseaux1",NULL,"2021-10-10 15:30:20","0","dot.com","Simon","Oiseaux"); TRIGGER photo_bonne_date
INSERT INTO Photo VALUES ("4","Oiseaux1",NULL,"2018-10-10 15:30:20","0","dot.com","Simon","Oiseaux");

INSERT INTO Commentaire VALUES ("2010-10-10 15:30:30","1","Simon","undeuxtrois");
INSERT INTO Commentaire VALUES ("2010-10-10 15:30:30","2","Simon","undeuxtrois");


INSERT INTO Commentaire VALUES ("2010-10-10 14:30:30","2","Steph","c'est nul");
INSERT INTO Commentaire VALUES ("2011-10-10 14:30:30","1","Steph","c'est nul");

-- INSERT INTO Reponse_Commentaire VALUES ("2010-10-10 14:30:30","2","2010-10-10 15:30:30","2"); TRIGGER reponse_bonne_date
INSERT INTO Reponse_Commentaire VALUES ("2011-10-10 14:30:30","1","Steph","2010-10-10 15:30:30","1","Simon");
-- INSERT INTO Reponse_Commentaire VALUES ("2011-10-10 14:30:30","1","2010-10-10 15:30:30","2"); TRIGGER reponse_meme_photo

-- INSERT INTO Commentaire VALUES ("2009-09-10 14:30:30","1","Robin","j'adore tester la BD"); TRIGGER commentaire_bonne_date

INSERT INTO Utilisateur_Modere_Communaute VALUES ("Steph","Chats","1");
INSERT INTO Utilisateur_Modere_Communaute VALUES ("Simon","Chats","1");
INSERT INTO Utilisateur_Modere_Communaute VALUES ("Robin","Chats","0");
INSERT INTO Utilisateur_Modere_Communaute VALUES ("Steph","Chiens","1");
INSERT INTO Utilisateur_Modere_Communaute VALUES ("Simon","Chiens","1");
INSERT INTO Utilisateur_Modere_Communaute VALUES ("Robin","Chiens","0");

UPDATE Utilisateur_Modere_Communaute
SET niveauPrivilege = 0
WHERE pseudoUtilisateur = "Simon" AND nomCommunaute = "Chats";

/*
UPDATE Utilisateur_Modere_Communaute
SET niveauPrivilege = 0
WHERE pseudoUtilisateur = "Steph" AND nomCommunaute = "Chats";
*/
-- TRIGGER admin_update

/*
DELETE FROM Utilisateur_Modere_Communaute
WHERE pseudoUtilisateur = "Steph";
 -- TRIGGER admin_delete
*/

INSERT INTO Photo_Balise VALUES("1","funny");
INSERT INTO Photo_Balise VALUES("2","cool");

INSERT INTO Utilisateur_Like_Photo VALUES("Simon","4");
INSERT INTO Utilisateur_Like_Photo VALUES("Robin","1");

INSERT INTO Utilisateur_Suit_Communaute VALUES("Steph","Chats");
INSERT INTO Utilisateur_Suit_Communaute VALUES("Steph","Chiens");
INSERT INTO Utilisateur_Suit_Communaute VALUES("Simon","Oiseaux");
