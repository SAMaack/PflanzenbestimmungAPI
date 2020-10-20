drop database if exists pflanzenbestimmung;

-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
-- -----------------------------------------------------
-- Schemapflanzenbestimmung
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `pflanzenbestimmung`;
CREATE SCHEMA IF NOT EXISTS `pflanzenbestimmung` DEFAULT CHARACTER SET utf8 ;
USE `pflanzenbestimmung` ;
-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`admins`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`admins` (
  `id` INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
  `nutzername` VARCHAR(45) UNIQUE NOT NULL,
  `vorname` VARCHAR(45) NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `passwort` VARCHAR(64) NOT NULL,
  `berflag` INT NOT NULL DEFAULT 2,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`ausbildungsart`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`ausbildungsart` (
  `id` INT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`quiz_art`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`quiz_art` (
  `id` INT UNSIGNED UNIQUE NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) UNIQUE NOT NULL,
  `groeße` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`fachrichtung`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`fachrichtung` (
  `id` INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL UNIQUE,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`azubis`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`azubis` (
  `id` INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
  `fk_ausbilder` INT UNSIGNED NOT NULL DEFAULT 1,
  `fk_ausbildungsart` INT UNSIGNED NOT NULL,
  `fk_fachrichtung` INT UNSIGNED NOT NULL,
  `fk_quiz_art` INT UNSIGNED NOT NULL,
  `nutzername` VARCHAR(45) UNIQUE NOT NULL,
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
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`pflanze`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`pflanze` (
  `id` INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
  `zierbau` TINYINT UNSIGNED NOT NULL,
  `galabau` TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`p_kategorien`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`p_kategorien` (
  `id` INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
  `kat_name` VARCHAR(45) UNIQUE NOT NULL,
  `anzeige_gala` TINYINT NOT NULL DEFAULT 1,
  `anzeige_zier` TINYINT NOT NULL DEFAULT 1,
  `werker_gewertet` TINYINT NOT NULL,
  `im_quiz` TINYINT NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
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
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`p_bilder`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`p_bilder` (
  `id` INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
  `fk_pflanze` INT UNSIGNED NOT NULL,
  `bild` LONGBLOB NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `pflanze-bild`
    FOREIGN KEY (`fk_pflanze`)
    REFERENCES `pflanzenbestimmung`.`pflanze` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = MyISAM;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`statistik`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`statistik` (
  `id` INT UNSIGNED NOT NULL UNIQUE AUTO_INCREMENT,
  `log` DATETIME NULL DEFAULT CURRENT_TIMESTAMP(),
  `fk_azubi` INT UNSIGNED NOT NULL,
  `fehlerquote` VARCHAR(10) NOT NULL,
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
ENGINE = MyISAM
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
ENGINE = MyISAM
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
  CONSTRAINT `stat_einzel-stat_e_details`
    FOREIGN KEY (`fk_statistik`, `fk_pflanze`)
    REFERENCES `pflanzenbestimmung`.`stat_einzel` (`fk_statistik`, `fk_pflanze`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`abgefragt`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`abgefragt` (
  `fk_azubi` INT UNSIGNED NOT NULL,
  `fk_pflanze` INT UNSIGNED NOT NULL,
  `counter_korrekt` INT UNSIGNED NOT NULL,
  `gelernt` TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (`fk_azubi`, `fk_pflanze` ),
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
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `pflanzenbestimmung`.`quiz_p_zuweisung`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `pflanzenbestimmung`.`quiz_p_zuweisung` (
  `fk_azubi` INT UNSIGNED NOT NULL,
  `fk_pflanze` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`fk_azubi`, `fk_pflanze` ),
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
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- INSERTS
-- -----------------------------------------------------

-- REQUIRED
INSERT INTO admins (nutzername, vorname, name, passwort, berflag) VALUES ("SysAdmin", "Sys", "Admin", "41344d1c296c38c21a90268b3a88a20e25be38d6e3a0680d0eca3df9e6c69651", "1"); --  First Admin
INSERT INTO ausbildungsart (name) VALUES ("Vollzeit");
INSERT INTO ausbildungsart (name) VALUES ("Werker");
INSERT INTO fachrichtung (name) VALUES ("Gartenlandschaftsbau");
INSERT INTO fachrichtung (name) VALUES ("Zierpflanzensbau");

INSERT INTO quiz_art (name, groeße) VALUES ("Maxi-Quiz", 100);
INSERT INTO quiz_art (name, groeße) VALUES ("Mikro-Quiz", 10);


