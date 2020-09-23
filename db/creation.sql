-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
-- -----------------------------------------------------
-- Schema pflanzenbestimmung
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `pflanzenbestimmung`;
CREATE SCHEMA IF NOT EXISTS `pflanzenbestimmung` DEFAULT CHARACTER SET utf8 ;
USE `pflanzenbestimmung` ;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`admins`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `nutzername` VARCHAR(45) NOT NULL,
  `passwort` VARCHAR(64) NOT NULL,
  `berflag` INT(11) NOT NULL DEFAULT 2,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`ausbildungsart`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`ausbildungsart` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`quiz_art`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`quiz_art` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `groeße` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`fachrichtung`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`fachrichtung` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`azubis`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`azubis` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_ausbilder` INT UNSIGNED NOT NULL DEFAULT 1,
  `fk_ausbildungsart` INT UNSIGNED NOT NULL,
  `fk_fachrichtung` INT UNSIGNED NOT NULL,
  `fk_quiz_art` INT UNSIGNED NULL DEFAULT NULL,
  `nutzername` VARCHAR(45) NOT NULL,
  `passwort` VARCHAR(64) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `vorname` VARCHAR(45) NOT NULL,
  `pruefung` TINYINT UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `ausbilder`
    FOREIGN KEY (`fk_ausbilder`)
    REFERENCES `pflanzenbestimmung`.`admins` (`id`)
    ON UPDATE CASCADE,
  CONSTRAINT `user-ausbildungsart`
    FOREIGN KEY (`fk_ausbildungsart`)
    REFERENCES `pflanzenbestimmung`.`ausbildungsart` (`id`)
    ON UPDATE CASCADE,
  CONSTRAINT `user-quiz_art`
    FOREIGN KEY (`fk_quiz_art`)
    REFERENCES `pflanzenbestimmung`.`quiz_art` (`id`)
    ON UPDATE CASCADE,
  CONSTRAINT `user-richtung`
    FOREIGN KEY (`fk_fachrichtung`)
    REFERENCES `pflanzenbestimmung`.`fachrichtung` (`id`)
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`pflanze`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`pflanze` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `zierbau` TINYINT UNSIGNED NOT NULL,
  `galabau` TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`p_kategorien`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`p_kategorien` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `kat_name` VARCHAR(45) NOT NULL,
  `abfrage` TINYINT(4) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`p_antworten`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`p_antworten` (
  `fk_pflanze` INT UNSIGNED NOT NULL,
  `fk_kategorie` INT UNSIGNED NOT NULL,
  `antwort` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`fk_kategorie`, `fk_pflanze`),
  CONSTRAINT `antw_pflanze`
    FOREIGN KEY (`fk_pflanze`)
    REFERENCES `pflanzenbestimmung`.`pflanze` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `kat_antw`
    FOREIGN KEY (`fk_kategorie`)
    REFERENCES `pflanzenbestimmung`.`p_kategorien` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`p_bilder`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`p_bilder` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `fk_pflanze` INT UNSIGNED NOT NULL,
  `bild` BLOB NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `pflanze-bild`
    FOREIGN KEY (`fk_pflanze`)
    REFERENCES `pflanzenbestimmung`.`pflanze` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`statistik`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`statistik` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `log` DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  `fk_azubi` INT UNSIGNED NOT NULL,
  `fehlerquote` INT(11) NOT NULL,
  `quizzeit` TIME NOT NULL DEFAULT '00:00:00',
  `fk_beste_pflanze` INT UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `azubi-stat`
    FOREIGN KEY (`fk_azubi`)
    REFERENCES `pflanzenbestimmung`.`azubis` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `stat-pflanze`
    FOREIGN KEY (`fk_beste_pflanze`)
    REFERENCES `pflanzenbestimmung`.`pflanze` (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`stat_einzel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`stat_einzel` (
  `fk_statistik` INT UNSIGNED NOT NULL,
  `fk_pflanze` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`fk_statistik`, `fk_pflanze`),
  CONSTRAINT `pflanze-stat_details`
    FOREIGN KEY (`fk_pflanze`)
    REFERENCES `pflanzenbestimmung`.`pflanze` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `stat-stat_details`
    FOREIGN KEY (`fk_statistik`)
    REFERENCES `pflanzenbestimmung`.`statistik` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`stat_einzel_detail`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`stat_einzel_detail` (
  `fk_kategorie` INT UNSIGNED NOT NULL,
  `fk_statistik` INT UNSIGNED NOT NULL,
  `fk_pflanze` INT UNSIGNED NOT NULL,
  `eingabe` VARCHAR(45) NOT NULL,
  `korrekt` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`fk_kategorie`, `fk_statistik`, `fk_pflanze`),
  CONSTRAINT `stat_einzelstat_s_details`
    FOREIGN KEY (`fk_statistik`, `fk_pflanze`)
    REFERENCES `pflanzenbestimmung`.`stat_einzel` (`fk_statistik`, `fk_pflanze`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`abgefragt`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`abgefragt` (
  `fk_azubi` INT UNSIGNED NOT NULL,
  `fk_pflanze` INT UNSIGNED NOT NULL,
  `counter_korrekt` INT UNSIGNED NOT NULL,
  `gelernt` TINYINT UNSIGNED NOT NULL,
  CONSTRAINT `azubi-gelernt`
    FOREIGN KEY (`fk_azubi`)
    REFERENCES `pflanzenbestimmung`.`azubis` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `pflanze-gelernt`
    FOREIGN KEY (`fk_pflanze`)
    REFERENCES `pflanzenbestimmung`.`pflanze` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`quiz_p_zuweisung`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`quiz_p_zuweisung` (
  `fk_azubi` INT UNSIGNED NOT NULL,
  `fk_pflanze` INT UNSIGNED NOT NULL,
  CONSTRAINT `azubi_quiz_frage`
    FOREIGN KEY (`fk_azubi`)
    REFERENCES `pflanzenbestimmung`.`azubis` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `pflanze_quiz_frage`
    FOREIGN KEY (`fk_pflanze`)
    REFERENCES `pflanzenbestimmung`.`pflanze` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


-- -----------------------------------------------------
-- INSERTS
-- -----------------------------------------------------
-- REQUIRED
INSERT INTO admins (nutzername, passwort, berflag) VALUES ("SysAdmin", "41344d1c296c38c21a90268b3a88a20e25be38d6e3a0680d0eca3df9e6c69651", "1"); --  First Admin
INSERT INTO ausbildungsart (name) VALUES ("Vollzeit");
INSERT INTO ausbildungsart (name) VALUES ("Werker");
INSERT INTO fachrichtung (name) VALUES ("Gartenlandschaftsbau");
INSERT INTO fachrichtung (name) VALUES ("Zierpflanzensbau");

-- OPTIONAL
-- azubis ------------------------
INSERT into azubis(nutzername, passwort, vorname,  name, fk_ausbilder, fk_ausbildungsart, fk_fachrichtung) 
values ("SysUserA", "f6b05dbfd63c14a30efb92329c246f85b33f4ad04c040798b0512e2a39c1471b", "Gala", "Voll", 1, 
(SELECT id from ausbildungsart WHERE name = "Vollzeit"), 
(SELECT id from fachrichtung WHERE name = "Gartenlandschaftsbau"));

INSERT into azubis(nutzername, passwort, vorname, name, fk_ausbilder, fk_ausbildungsart, fk_fachrichtung) 
values ("SysUserB", "19e84d1ff217d960f6d9e7b485f149fb7a5a8b6da81c9a4a93bc51e2cff9264d","Zier", "Voll", 1, 
(SELECT id from ausbildungsart WHERE name = "Vollzeit"), 
(SELECT id from fachrichtung WHERE name = "Zierpflanzensbau"));

INSERT into azubis(nutzername, passwort, vorname, name, fk_ausbilder, fk_ausbildungsart, fk_fachrichtung) 
values ("SysUserC", "7c7a6bd332f8f9aa4055305e12ef7f35b76f0f8d26ae27ddacbc58b53496a8c0", "Gala", "Werk", 1, 
(SELECT id from ausbildungsart WHERE name = "Werker"), 
(SELECT id from fachrichtung WHERE name = "Gartenlandschaftsbau"));

INSERT into azubis(nutzername, passwort, vorname, name, fk_ausbilder, fk_ausbildungsart, fk_fachrichtung) 
values ("SysUserD", "3893c0be02d8ecefa5f3c31c625be111ef927759a9f0c277d22bb3cf2b48eb8f", "Zier", "Werk", 1, 
(SELECT id from ausbildungsart WHERE name = "Werker"), 
(SELECT id from fachrichtung WHERE name = "Zierpflanzensbau"));

-- pflanzen kategorien ------------------------
INSERT INTO p_kategorien (kat_name)
VALUES ("Gattungsname"), ("Artname"), ("Dename"), ("Famname"), ("Herkunft"), ("Bluete"), ("Bluetezeit"), ("Blatt");
-- ,("wuchs"), ("besonderheiten"), ("lebensdauer"), ("wuchsformen"), ("standort"), ("gverwendung")

-- pflanzen ------------------------
INSERT INTO pflanze (zierbau, galabau) VALUES (1, 1); -- Ziergartenbau?, Gartenlandschaftsbau?
INSERT INTO pflanze (zierbau, galabau) VALUES (0, 1);
INSERT INTO pflanze (zierbau, galabau) VALUES (1, 0);

INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort)
    VALUES (1,                                                                -- ID der Pflanze
            (SELECT id from p_kategorien WHERE kat_name = "gattungsname"),    -- ID der Kategorie
            "Gattungsname A");                                                -- Entsprechende Antwort
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort)
    VALUES (1,                                                              
            (SELECT id from p_kategorien WHERE kat_name = "artname"),   
            "Artname A");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort)
    VALUES (1,                                                              
            (SELECT id from p_kategorien WHERE kat_name = "dename"),   
            "DeName A");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort)
    VALUES  (1,                                                              
            (SELECT id from p_kategorien WHERE kat_name = "famname"),   
            "Famname A");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort)
    VALUES (1,                                                              
            (SELECT id from p_kategorien WHERE kat_name = "herkunft"),   
            "Herkunft A");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort)
    VALUES (1,                                                              
            (SELECT id from p_kategorien WHERE kat_name = "bluete"),   
            "Bluete A");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort)
    VALUES (1,                                                              
            (SELECT id from p_kategorien WHERE kat_name = "bluetezeit"),   
            "Bluetezeit A");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort)
    VALUES (1,                                                              
            (SELECT id from p_kategorien WHERE kat_name = "blatt"),   
            "Blatt A");
-- INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (1, (SELECT id from p_kategorien WHERE kat_name = "wuchs"),  "Wuchs A");
-- INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (1, (SELECT id from p_kategorien WHERE kat_name = "besonderheiten"), "Besonderheiten A");
-- INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (1, (SELECT id from p_kategorien WHERE kat_name = "lebensdauer"), "Lebensdauer A");
-- INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (1, (SELECT id from p_kategorien WHERE kat_name = "wuchsformen"), "");
-- INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (1, (SELECT id from p_kategorien WHERE kat_name = "standort"), "");
-- INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES  (1, (SELECT id from p_kategorien WHERE kat_name = "gverwendung"), "");           
            
            
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort)
    VALUES (2,                                                                -- ID der Pflanze
            (SELECT id from p_kategorien WHERE kat_name = "gattungsname"),    -- ID der Kategorie
            "Gattungsname B");                                                -- Entsprechende Antwort
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (2, (SELECT id from p_kategorien WHERE kat_name = "artname"), "Artname B");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (2, (SELECT id from p_kategorien WHERE kat_name = "dename"), "DeName B");             
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES  (2, (SELECT id from p_kategorien WHERE kat_name = "famname"), "Famname B");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (2, (SELECT id from p_kategorien WHERE kat_name = "herkunft"),   "Herkunft B");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (2,  (SELECT id from p_kategorien WHERE kat_name = "bluete"),    "Bluete B");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (2, (SELECT id from p_kategorien WHERE kat_name = "bluetezeit"), "Bluetezeit B");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort)  VALUES (2, (SELECT id from p_kategorien WHERE kat_name = "blatt"),  "Blatt B");                         
-- NEUE PFLANZE ---------------------            
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort)
    VALUES (3,                                                                -- ID der Pflanze
            (SELECT id from p_kategorien WHERE kat_name = "gattungsname"),    -- ID der Kategorie
            "Gattungsname C");                                                -- Entsprechende Antwort
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (3, (SELECT id from p_kategorien WHERE kat_name = "artname"), "Artname C");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (3, (SELECT id from p_kategorien WHERE kat_name = "dename"),  "DeName C");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES  (3, (SELECT id from p_kategorien WHERE kat_name = "famname"), "Famname C");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (3,   (SELECT id from p_kategorien WHERE kat_name = "herkunft"), "Herkunft C");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (3,  (SELECT id from p_kategorien WHERE kat_name = "bluete"),  "Bluete C");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (3, (SELECT id from p_kategorien WHERE kat_name = "bluetezeit"), "Bluetezeit C");
INSERT INTO p_antworten (fk_pflanze, fk_kategorie, antwort) VALUES (3, (SELECT id from p_kategorien WHERE kat_name = "blatt"), "Blatt C");             
                 
   
-- statistik ------------------------
INSERT INTO statistik (fk_azubi, fehlerquote, quizzeit, fk_beste_pflanze) 
VALUES (1, 0, '0:2:30', 1);

INSERT INTO stat_einzel (fk_statistik, fk_pflanze) VALUES(1, 1);

INSERT INTO stat_einzel_detail (fk_kategorie, fk_statistik, fk_pflanze, eingabe, korrekt)
VALUES (1, 1, 1, "Gattung A", (SELECT antwort FROM p_antworten WHERE fk_kategorie = 1 AND fk_pflanze = 1));
INSERT INTO stat_einzel_detail (fk_kategorie, fk_statistik, fk_pflanze, eingabe, korrekt)
VALUES (2, 1, 1, "Artname A", (SELECT antwort FROM p_antworten WHERE fk_kategorie = 2 AND fk_pflanze = 1));

INSERT INTO stat_einzel (fk_statistik, fk_pflanze) VALUES(1, 2);

INSERT INTO stat_einzel_detail (fk_kategorie, fk_statistik, fk_pflanze, eingabe, korrekt)
VALUES (1, 1, 2, "Gattung B", (SELECT antwort FROM p_antworten WHERE fk_kategorie = 2 AND fk_pflanze = 1));
INSERT INTO stat_einzel_detail (fk_kategorie, fk_statistik, fk_pflanze, eingabe, korrekt)
VALUES (2, 1, 2, "MIAU B", (SELECT antwort FROM p_antworten WHERE fk_kategorie = 2 AND fk_pflanze = 2));

