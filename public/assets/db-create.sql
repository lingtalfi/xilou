SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `zilu` ;
CREATE SCHEMA IF NOT EXISTS `zilu` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `zilu` ;

-- -----------------------------------------------------
-- Table `zilu`.`container`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `zilu`.`container` ;

CREATE  TABLE IF NOT EXISTS `zilu`.`container` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nom` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nom_UNIQUE` (`nom` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`commande`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `zilu`.`commande` ;

CREATE  TABLE IF NOT EXISTS `zilu`.`commande` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `reference` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`article`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `zilu`.`article` ;

CREATE  TABLE IF NOT EXISTS `zilu`.`article` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `reference_lf` VARCHAR(45) NOT NULL ,
  `reference_hldp` VARCHAR(45) NOT NULL ,
  `prix` DECIMAL(10,2) NOT NULL ,
  `poids` DECIMAL(10,2) NOT NULL ,
  `descr_fr` TEXT NOT NULL ,
  `descr_en` TEXT NOT NULL ,
  `commande_id` INT NULL ,
  `container_id` INT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_article_commande` (`commande_id` ASC) ,
  INDEX `fk_article_container1` (`container_id` ASC) ,
  CONSTRAINT `fk_article_commande`
    FOREIGN KEY (`commande_id` )
    REFERENCES `zilu`.`commande` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_article_container1`
    FOREIGN KEY (`container_id` )
    REFERENCES `zilu`.`container` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`fournisseur`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `zilu`.`fournisseur` ;

CREATE  TABLE IF NOT EXISTS `zilu`.`fournisseur` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nom` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`fournisseur_has_article`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `zilu`.`fournisseur_has_article` ;

CREATE  TABLE IF NOT EXISTS `zilu`.`fournisseur_has_article` (
  `fournisseur_id` INT NOT NULL ,
  `article_id` INT NOT NULL ,
  PRIMARY KEY (`fournisseur_id`, `article_id`) ,
  INDEX `fk_fournisseur_has_article_article1` (`article_id` ASC) ,
  INDEX `fk_fournisseur_has_article_fournisseur1` (`fournisseur_id` ASC) ,
  CONSTRAINT `fk_fournisseur_has_article_fournisseur1`
    FOREIGN KEY (`fournisseur_id` )
    REFERENCES `zilu`.`fournisseur` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_fournisseur_has_article_article1`
    FOREIGN KEY (`article_id` )
    REFERENCES `zilu`.`article` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
