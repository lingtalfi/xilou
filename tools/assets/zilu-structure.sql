-- MySQL Script generated by MySQL Workbench
-- Wed Feb 15 06:53:16 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema zilu
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `zilu` ;

-- -----------------------------------------------------
-- Schema zilu
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `zilu` DEFAULT CHARACTER SET latin1 ;
USE `zilu` ;

-- -----------------------------------------------------
-- Table `zilu`.`type_container`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zilu`.`type_container` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `label` VARCHAR(64) NOT NULL,
  `poids_max` VARCHAR(64) NOT NULL,
  `volume_max` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`container`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zilu`.`container` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(45) NOT NULL,
  `type_container_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `nom_UNIQUE` (`nom` ASC),
  INDEX `fk_container_type_container1_idx` (`type_container_id` ASC),
  CONSTRAINT `fk_container_type_container1`
    FOREIGN KEY (`type_container_id`)
    REFERENCES `zilu`.`type_container` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zilu`.`article` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `reference_lf` VARCHAR(45) NOT NULL,
  `reference_hldp` VARCHAR(45) NOT NULL,
  `poids` DECIMAL(10,2) NOT NULL,
  `descr_fr` TEXT NOT NULL,
  `descr_en` TEXT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`commande`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zilu`.`commande` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `reference` VARCHAR(45) NOT NULL,
  `estimated_date` DATE NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`fournisseur`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zilu`.`fournisseur` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(45) NOT NULL,
  `email` VARCHAR(128) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`fournisseur_has_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zilu`.`fournisseur_has_article` (
  `fournisseur_id` INT NOT NULL,
  `article_id` INT NOT NULL,
  `reference` VARCHAR(64) NOT NULL,
  `prix` DECIMAL(10,2) NOT NULL,
  `volume` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`fournisseur_id`, `article_id`),
  INDEX `fk_fournisseur_has_article_article1_idx` (`article_id` ASC),
  INDEX `fk_fournisseur_has_article_fournisseur1_idx` (`fournisseur_id` ASC),
  CONSTRAINT `fk_fournisseur_has_article_fournisseur1`
    FOREIGN KEY (`fournisseur_id`)
    REFERENCES `zilu`.`fournisseur` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_fournisseur_has_article_article1`
    FOREIGN KEY (`article_id`)
    REFERENCES `zilu`.`article` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`sav`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zilu`.`sav` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `fournisseur` VARCHAR(64) NOT NULL,
  `reference_lf` VARCHAR(64) NOT NULL,
  `produit` VARCHAR(64) NOT NULL,
  `livre_le` DATE NULL,
  `quantite` INT NULL,
  `prix` DECIMAL(10,2) NULL,
  `nb_produits_defec` INT NULL,
  `date_notif` DATE NULL,
  `demande_remboursement` DECIMAL(10,2) NULL,
  `montant_rembourse` DECIMAL(10,2) NULL,
  `pourcentage_rembourse` TINYINT NULL,
  `date_remboursement` DATE NULL,
  `forme` VARCHAR(128) NOT NULL,
  `statut` TEXT NOT NULL,
  `photo` VARCHAR(256) NOT NULL,
  `avancement` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`commande_ligne_statut`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zilu`.`commande_ligne_statut` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `nom` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`commande_has_article`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zilu`.`commande_has_article` (
  `commande_id` INT NOT NULL,
  `article_id` INT NOT NULL,
  `container_id` INT NULL,
  `fournisseur_id` INT NOT NULL,
  `sav_id` INT NULL,
  `commande_ligne_statut_id` INT NOT NULL,
  `prix_override` DECIMAL(10,2) NULL,
  `date_estimee` DATE NULL,
  `quantite` INT NOT NULL,
  PRIMARY KEY (`commande_id`, `article_id`),
  INDEX `fk_commande_has_article_article1_idx` (`article_id` ASC),
  INDEX `fk_commande_has_article_commande1_idx` (`commande_id` ASC),
  INDEX `fk_commande_has_article_container1_idx` (`container_id` ASC),
  INDEX `fk_commande_has_article_fournisseur1_idx` (`fournisseur_id` ASC),
  INDEX `fk_commande_has_article_sav1_idx` (`sav_id` ASC),
  INDEX `fk_commande_has_article_commande_ligne_statut1_idx` (`commande_ligne_statut_id` ASC),
  CONSTRAINT `fk_commande_has_article_commande1`
    FOREIGN KEY (`commande_id`)
    REFERENCES `zilu`.`commande` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commande_has_article_article1`
    FOREIGN KEY (`article_id`)
    REFERENCES `zilu`.`article` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commande_has_article_container1`
    FOREIGN KEY (`container_id`)
    REFERENCES `zilu`.`container` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commande_has_article_fournisseur1`
    FOREIGN KEY (`fournisseur_id`)
    REFERENCES `zilu`.`fournisseur` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commande_has_article_sav1`
    FOREIGN KEY (`sav_id`)
    REFERENCES `zilu`.`sav` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commande_has_article_commande_ligne_statut1`
    FOREIGN KEY (`commande_ligne_statut_id`)
    REFERENCES `zilu`.`commande_ligne_statut` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`csv_prix_materiel`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `zilu`.`csv_prix_materiel` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `reference` VARCHAR(64) NULL,
  `reference_fournisseur` VARCHAR(64) NULL,
  `fournisseur` VARCHAR(64) NULL,
  `produits` VARCHAR(128) NULL,
  `libelle_origine` VARCHAR(128) NULL,
  `unit` VARCHAR(64) NULL,
  `pmp_achat_dollar` DECIMAL(10,2) NULL,
  `pmp_achat_euro` DECIMAL(10,2) NULL,
  `port` DECIMAL(10,2) NULL,
  `paht_frais` DECIMAL(10,2) NULL,
  `pv_public_ht` DECIMAL(10,2) NULL,
  `marge_prix_public` DECIMAL(10,2) NULL,
  `pv_public_ttc` DECIMAL(10,2) NULL,
  `prix_pro` DECIMAL(10,2) NULL,
  `remise_club` DECIMAL(10,2) NULL,
  `marge_prix_club` DECIMAL(10,2) NULL,
  `prix_franchise` DECIMAL(10,2) NULL,
  `remise_franchise` DECIMAL(10,2) NULL,
  `marge_franchise` DECIMAL(10,2) NULL,
  `poids_net` DECIMAL(10,3) NULL,
  `poids` DECIMAL(10,3) NULL,
  `famille_produit` VARCHAR(64) NULL,
  `dimensions` VARCHAR(128) NULL,
  `code_compta` VARCHAR(64) NULL,
  `description` TEXT NULL,
  `photos` VARCHAR(128) NULL,
  `tva` DECIMAL(10,2) NULL,
  `code_ean` VARCHAR(64) NULL,
  `date_arrivee` VARCHAR(64) NULL,
  `m3` VARCHAR(64) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
