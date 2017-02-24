SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `zilu` ;
CREATE SCHEMA IF NOT EXISTS `zilu` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `zilu` ;

-- -----------------------------------------------------
-- Table `zilu`.`type_container`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`type_container` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `label` VARCHAR(64) NOT NULL ,
  `poids_max` VARCHAR(64) NOT NULL ,
  `volume_max` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`container`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`container` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nom` VARCHAR(45) NOT NULL ,
  `type_container_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `nom_UNIQUE` (`nom` ASC) ,
  INDEX `fk_container_type_container1_idx` (`type_container_id` ASC) ,
  CONSTRAINT `fk_container_type_container1`
    FOREIGN KEY (`type_container_id` )
    REFERENCES `zilu`.`type_container` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`article`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`article` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `reference_lf` VARCHAR(45) NOT NULL ,
  `reference_hldp` VARCHAR(45) NOT NULL ,
  `descr_fr` TEXT NOT NULL ,
  `descr_en` TEXT NOT NULL ,
  `ean` VARCHAR(45) NOT NULL ,
  `photo` VARCHAR(128) NULL ,
  `logo` VARCHAR(128) NULL ,
  `long_desc_en` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`commande`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`commande` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `reference` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `reference_UNIQUE` (`reference` ASC) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`fournisseur`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`fournisseur` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nom` VARCHAR(45) NOT NULL ,
  `email` VARCHAR(128) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`fournisseur_has_article`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`fournisseur_has_article` (
  `fournisseur_id` INT NOT NULL ,
  `article_id` INT NOT NULL ,
  `reference` VARCHAR(64) NULL ,
  `prix` DECIMAL(10,2) NULL ,
  `volume` DECIMAL(10,2) NULL ,
  `poids` DECIMAL(10,2) NULL ,
  PRIMARY KEY (`fournisseur_id`, `article_id`) ,
  INDEX `fk_fournisseur_has_article_article1_idx` (`article_id` ASC) ,
  INDEX `fk_fournisseur_has_article_fournisseur1_idx` (`fournisseur_id` ASC) ,
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


-- -----------------------------------------------------
-- Table `zilu`.`sav`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`sav` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fournisseur` VARCHAR(64) NOT NULL ,
  `reference_lf` VARCHAR(64) NOT NULL ,
  `produit` VARCHAR(64) NOT NULL ,
  `livre_le` DATE NULL ,
  `quantite` INT NULL ,
  `prix` DECIMAL(10,2) NULL ,
  `nb_produits_defec` INT NULL ,
  `date_notif` DATE NULL ,
  `demande_remboursement` DECIMAL(10,2) NULL ,
  `montant_rembourse` DECIMAL(10,2) NULL ,
  `pourcentage_rembourse` TINYINT NULL ,
  `date_remboursement` DATE NULL ,
  `forme` VARCHAR(128) NOT NULL ,
  `statut` TEXT NOT NULL ,
  `photo` VARCHAR(256) NOT NULL ,
  `avancement` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`commande_ligne_statut`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`commande_ligne_statut` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `nom` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`commande_has_article`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`commande_has_article` (
  `commande_id` INT NOT NULL ,
  `article_id` INT NOT NULL ,
  `container_id` INT NULL ,
  `fournisseur_id` INT NOT NULL ,
  `sav_id` INT NULL ,
  `commande_ligne_statut_id` INT NOT NULL ,
  `prix_override` DECIMAL(10,2) NULL ,
  `date_estimee` DATE NULL ,
  `quantite` INT NOT NULL ,
  `unit` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`commande_id`, `article_id`) ,
  INDEX `fk_commande_has_article_article1_idx` (`article_id` ASC) ,
  INDEX `fk_commande_has_article_commande1_idx` (`commande_id` ASC) ,
  INDEX `fk_commande_has_article_container1_idx` (`container_id` ASC) ,
  INDEX `fk_commande_has_article_fournisseur1_idx` (`fournisseur_id` ASC) ,
  INDEX `fk_commande_has_article_sav1_idx` (`sav_id` ASC) ,
  INDEX `fk_commande_has_article_commande_ligne_statut1_idx` (`commande_ligne_statut_id` ASC) ,
  CONSTRAINT `fk_commande_has_article_commande1`
    FOREIGN KEY (`commande_id` )
    REFERENCES `zilu`.`commande` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commande_has_article_article1`
    FOREIGN KEY (`article_id` )
    REFERENCES `zilu`.`article` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commande_has_article_container1`
    FOREIGN KEY (`container_id` )
    REFERENCES `zilu`.`container` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commande_has_article_fournisseur1`
    FOREIGN KEY (`fournisseur_id` )
    REFERENCES `zilu`.`fournisseur` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commande_has_article_sav1`
    FOREIGN KEY (`sav_id` )
    REFERENCES `zilu`.`sav` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_commande_has_article_commande_ligne_statut1`
    FOREIGN KEY (`commande_ligne_statut_id` )
    REFERENCES `zilu`.`commande_ligne_statut` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`csv_prix_materiel`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`csv_prix_materiel` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `reference` VARCHAR(64) NULL ,
  `reference_fournisseur` VARCHAR(64) NULL ,
  `fournisseur` VARCHAR(64) NULL ,
  `produits` VARCHAR(128) NULL ,
  `libelle_origine` VARCHAR(128) NULL ,
  `unit` VARCHAR(64) NULL ,
  `pmp_achat_dollar` DECIMAL(10,2) NULL ,
  `pmp_achat_euro` DECIMAL(10,2) NULL ,
  `port` DECIMAL(10,2) NULL ,
  `paht_frais` DECIMAL(10,2) NULL ,
  `pv_public_ht` DECIMAL(10,2) NULL ,
  `marge_prix_public` DECIMAL(10,2) NULL ,
  `pv_public_ttc` DECIMAL(10,2) NULL ,
  `prix_pro` DECIMAL(10,2) NULL ,
  `remise_club` DECIMAL(10,2) NULL ,
  `marge_prix_club` DECIMAL(10,2) NULL ,
  `prix_franchise` DECIMAL(10,2) NULL ,
  `remise_franchise` DECIMAL(10,2) NULL ,
  `marge_franchise` DECIMAL(10,2) NULL ,
  `poids_net` DECIMAL(10,3) NULL ,
  `poids` DECIMAL(10,3) NULL ,
  `famille_produit` VARCHAR(64) NULL ,
  `dimensions` VARCHAR(128) NULL ,
  `code_compta` VARCHAR(64) NULL ,
  `description` TEXT NULL ,
  `photos` VARCHAR(128) NULL ,
  `tva` DECIMAL(10,2) NULL ,
  `code_ean` VARCHAR(64) NULL ,
  `date_arrivee` VARCHAR(64) NULL ,
  `m3` VARCHAR(64) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`csv_product_details`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`csv_product_details` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `ref` VARCHAR(45) NOT NULL ,
  `product_fr` VARCHAR(128) NOT NULL ,
  `product` VARCHAR(128) NOT NULL ,
  `photo` VARCHAR(64) NOT NULL ,
  `features` TEXT NOT NULL ,
  `logo` VARCHAR(45) NOT NULL ,
  `packing` TEXT NOT NULL ,
  `ean` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`csv_product_list`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`csv_product_list` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `ref_hldp` VARCHAR(45) NOT NULL ,
  `ref_lf` VARCHAR(45) NOT NULL ,
  `produits` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`csv_fournisseurs_sav`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`csv_fournisseurs_sav` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fournisseur` VARCHAR(45) NOT NULL ,
  `reference_lf` VARCHAR(45) NOT NULL ,
  `produit` VARCHAR(128) NOT NULL ,
  `livre_le` VARCHAR(45) NOT NULL ,
  `quantite` VARCHAR(45) NOT NULL ,
  `prix` VARCHAR(45) NOT NULL ,
  `nb_produits_defec` VARCHAR(45) NOT NULL ,
  `date_notif` VARCHAR(45) NOT NULL ,
  `demande_remboursement` VARCHAR(45) NOT NULL ,
  `montant_rembourse` VARCHAR(45) NOT NULL ,
  `remboursement` VARCHAR(128) NOT NULL ,
  `forme` VARCHAR(128) NOT NULL ,
  `statut` VARCHAR(45) NOT NULL ,
  `avoir_lf` VARCHAR(45) NOT NULL ,
  `date_remboursement` TEXT NOT NULL ,
  `problemes` TEXT NOT NULL ,
  `avancement` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`csv_fournisseurs_fournisseurs`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`csv_fournisseurs_fournisseurs` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `fournisseur` VARCHAR(45) NOT NULL ,
  `ref_hldp` VARCHAR(45) NOT NULL ,
  `ref` VARCHAR(45) NOT NULL ,
  `produits_fr` TEXT NOT NULL ,
  `produits_en` TEXT NOT NULL ,
  `moq` VARCHAR(45) NOT NULL ,
  `details` TEXT NOT NULL ,
  `client` VARCHAR(45) NOT NULL ,
  `quantity` VARCHAR(45) NOT NULL ,
  `unit` VARCHAR(45) NOT NULL ,
  `unit_price` VARCHAR(45) NOT NULL ,
  `total_amount` VARCHAR(45) NOT NULL ,
  `packing_details` TEXT NOT NULL ,
  `m3` VARCHAR(45) NOT NULL ,
  `poids` VARCHAR(45) NOT NULL ,
  `m3_unit` VARCHAR(45) NOT NULL ,
  `poids_unit` VARCHAR(45) NOT NULL ,
  `units_20` VARCHAR(45) NOT NULL ,
  `units_40` VARCHAR(45) NOT NULL ,
  `units_40hq` VARCHAR(45) NOT NULL ,
  `lf` VARCHAR(45) NOT NULL ,
  `reference` VARCHAR(45) NOT NULL ,
  `champ1` TEXT NOT NULL ,
  `champ2` TEXT NOT NULL ,
  `champ3` TEXT NOT NULL ,
  `champ4` TEXT NOT NULL ,
  `fournisseur_nom1` VARCHAR(45) NOT NULL ,
  `fournisseur_nom2` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`csv_fournisseurs_containers`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`csv_fournisseurs_containers` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `date_commande` VARCHAR(128) NOT NULL ,
  `container` VARCHAR(128) NOT NULL ,
  `produit_fr` TEXT NOT NULL ,
  `reference` VARCHAR(128) NOT NULL ,
  `produits_fr` TEXT NOT NULL ,
  `produits_en` TEXT NOT NULL ,
  `details` TEXT NOT NULL ,
  `quantity` VARCHAR(45) NOT NULL ,
  `unit` VARCHAR(45) NOT NULL ,
  `unit_price` VARCHAR(45) NOT NULL ,
  `total_price` VARCHAR(128) NOT NULL ,
  `m3` VARCHAR(45) NOT NULL ,
  `poids` VARCHAR(45) NOT NULL ,
  `client` VARCHAR(45) NOT NULL ,
  `ref_hldp` VARCHAR(45) NOT NULL ,
  `ref_lf` VARCHAR(45) NOT NULL ,
  `numero_commande` VARCHAR(45) NOT NULL ,
  `m3_u` VARCHAR(45) NOT NULL ,
  `kgs_u` VARCHAR(45) NOT NULL ,
  `facture_lf` VARCHAR(45) NOT NULL ,
  `commande_en_cours` VARCHAR(128) NOT NULL ,
  `note` TEXT NOT NULL ,
  `livraison` TEXT NOT NULL ,
  `simulation_date` VARCHAR(45) NOT NULL ,
  `simulation_date_2` VARCHAR(45) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`csv_fournisseurs_comparatif`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`csv_fournisseurs_comparatif` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `ref_hldp` VARCHAR(64) NOT NULL ,
  `ref_lf` VARCHAR(64) NOT NULL ,
  `produit` TEXT NOT NULL ,
  `m3` VARCHAR(64) NOT NULL ,
  `gw` VARCHAR(64) NOT NULL ,
  `nw` VARCHAR(64) NOT NULL ,
  `vendu_par` VARCHAR(64) NOT NULL ,
  `ean` VARCHAR(64) NOT NULL ,
  `nom_hldp` TEXT NOT NULL ,
  `nom_leaderfit` TEXT NOT NULL ,
  `poids` VARCHAR(64) NOT NULL ,
  `materiaux` VARCHAR(64) NOT NULL ,
  `etat_import` VARCHAR(64) NOT NULL ,
  `largeur` VARCHAR(64) NOT NULL ,
  `hauteur` VARCHAR(64) NOT NULL ,
  `longueur` VARCHAR(64) NOT NULL ,
  `resistance` VARCHAR(64) NOT NULL ,
  `autres` VARCHAR(64) NOT NULL ,
  `MOQ` VARCHAR(64) NOT NULL ,
  `packaging` VARCHAR(64) NOT NULL ,
  `categorie` VARCHAR(64) NOT NULL ,
  `descriptif` TEXT NOT NULL ,
  `url` VARCHAR(128) NOT NULL ,
  `en_products` VARCHAR(64) NOT NULL ,
  `en_sold_by` VARCHAR(64) NOT NULL ,
  `en_packaging` VARCHAR(64) NOT NULL ,
  `en_material` VARCHAR(64) NOT NULL ,
  `en_description` TEXT NOT NULL ,
  `en_category` VARCHAR(64) NOT NULL ,
  `es_products` TEXT NOT NULL ,
  `es_sold_by` VARCHAR(64) NOT NULL ,
  `es_packaging` VARCHAR(64) NOT NULL ,
  `es_material` VARCHAR(64) NOT NULL ,
  `es_category` VARCHAR(64) NOT NULL ,
  `moyenne` VARCHAR(64) NOT NULL ,
  `wohlstand` VARCHAR(64) NOT NULL ,
  `rising` VARCHAR(64) NOT NULL ,
  `top_asia` VARCHAR(64) NOT NULL ,
  `azuni` VARCHAR(64) NOT NULL ,
  `kylin` VARCHAR(64) NOT NULL ,
  `modern_sports` VARCHAR(64) NOT NULL ,
  `gyco` VARCHAR(64) NOT NULL ,
  `lion` VARCHAR(64) NOT NULL ,
  `live_up` VARCHAR(64) NOT NULL ,
  `ironmaster` VARCHAR(64) NOT NULL ,
  `record` VARCHAR(64) NOT NULL ,
  `tengtai` VARCHAR(64) NOT NULL ,
  `dekai` VARCHAR(64) NOT NULL ,
  `alex` VARCHAR(64) NOT NULL ,
  `regal` VARCHAR(64) NOT NULL ,
  `helisports` VARCHAR(64) NOT NULL ,
  `amaya` VARCHAR(64) NOT NULL ,
  `msd` VARCHAR(64) NOT NULL ,
  `fournisseur` VARCHAR(64) NOT NULL ,
  `unit` VARCHAR(64) NOT NULL ,
  `pa_dollar` VARCHAR(64) NOT NULL ,
  `pa_fdp_inclus` VARCHAR(64) NOT NULL ,
  `ob_marge_hldp` VARCHAR(64) NOT NULL ,
  `ob_pv_fob_dollar` VARCHAR(64) NOT NULL ,
  `ob_pv_fob` VARCHAR(64) NOT NULL ,
  `ob_pv_hldp_dollar` VARCHAR(64) NOT NULL ,
  `ob_pv_hldp` VARCHAR(64) NOT NULL ,
  `pv_lf_orange` VARCHAR(64) NOT NULL ,
  `reduction` VARCHAR(64) NOT NULL ,
  `produit_specifique` VARCHAR(64) NOT NULL ,
  `rev_marge_hldp` VARCHAR(64) NOT NULL ,
  `rev_pv_fob_dollar` VARCHAR(64) NOT NULL ,
  `rev_pv_fob` VARCHAR(64) NOT NULL ,
  `rev_pv_hldp_dollar` VARCHAR(64) NOT NULL ,
  `rev_pv_hldp` VARCHAR(64) NOT NULL ,
  `gev_marge_hldp` VARCHAR(64) NOT NULL ,
  `gev_pv_fob_dollar` VARCHAR(64) NOT NULL ,
  `gev_pv_fob` VARCHAR(64) NOT NULL ,
  `gev_pv_hldp_dollar` VARCHAR(64) NOT NULL ,
  `gev_pv_hldp` VARCHAR(64) NOT NULL ,
  `gev_pv_hldp2` VARCHAR(64) NOT NULL ,
  `gev_pv_hldp3` VARCHAR(64) NOT NULL ,
  `cha_marge_hldp` VARCHAR(64) NOT NULL ,
  `cha_pv_fob_dollar` VARCHAR(64) NOT NULL ,
  `cha_pv_fob` VARCHAR(64) NOT NULL ,
  `cha_pv_hldp_dollar` VARCHAR(64) NOT NULL ,
  `cha_pv_hldp` VARCHAR(64) NOT NULL ,
  `cha_pv_hldp2` VARCHAR(64) NOT NULL ,
  `kin_marge_hldp` VARCHAR(64) NOT NULL ,
  `kin_pv_fob_dollar` VARCHAR(64) NOT NULL ,
  `kin_pv_fob` VARCHAR(64) NOT NULL ,
  `kin_pv_hldp_dollar` VARCHAR(64) NOT NULL ,
  `kin_pv_hldp` VARCHAR(64) NOT NULL ,
  `kin_pv_hldp2` VARCHAR(64) NOT NULL ,
  `fit_marge_hldp` VARCHAR(64) NOT NULL ,
  `fit_pv_fob_dollar` VARCHAR(64) NOT NULL ,
  `fit_pv_fob` VARCHAR(64) NOT NULL ,
  `fit_pv_hldp_dollar` VARCHAR(64) NOT NULL ,
  `fit_pv_hldp` VARCHAR(64) NOT NULL ,
  `fit_pv_hldp2` VARCHAR(64) NOT NULL ,
  `lf_pv_public` VARCHAR(64) NOT NULL ,
  `lf_pv_public_dollar` VARCHAR(64) NOT NULL ,
  `lf_reduction` VARCHAR(64) NOT NULL ,
  `lf_pv_revendeur` VARCHAR(64) NOT NULL ,
  `lf_pv_revendeur_dollar` VARCHAR(64) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`devis`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`devis` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `reference` VARCHAR(45) NOT NULL ,
  `date_reception` DATE NOT NULL ,
  `fournisseur_id` INT NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_devis_fournisseur1` (`fournisseur_id` ASC) ,
  CONSTRAINT `fk_devis_fournisseur1`
    FOREIGN KEY (`fournisseur_id` )
    REFERENCES `zilu`.`fournisseur` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `zilu`.`devis_has_commande_has_article`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `zilu`.`devis_has_commande_has_article` (
  `devis_id` INT NOT NULL ,
  `commande_has_article_commande_id` INT NOT NULL ,
  `commande_has_article_article_id` INT NOT NULL ,
  PRIMARY KEY (`devis_id`, `commande_has_article_commande_id`, `commande_has_article_article_id`) ,
  INDEX `fk_devis_has_commande_has_article_commande_has_article1` (`commande_has_article_commande_id` ASC, `commande_has_article_article_id` ASC) ,
  INDEX `fk_devis_has_commande_has_article_devis1` (`devis_id` ASC) ,
  CONSTRAINT `fk_devis_has_commande_has_article_devis1`
    FOREIGN KEY (`devis_id` )
    REFERENCES `zilu`.`devis` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_devis_has_commande_has_article_commande_has_article1`
    FOREIGN KEY (`commande_has_article_commande_id` , `commande_has_article_article_id` )
    REFERENCES `zilu`.`commande_has_article` (`commande_id` , `article_id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
