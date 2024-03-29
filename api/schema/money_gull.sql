-- MySQL Script generated by MySQL Workbench
-- Wed 18 Jan 2017 05:07:19 PM MST
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema money_gull
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema money_gull
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `money_gull` DEFAULT CHARACTER SET utf8 ;
USE `money_gull` ;

-- -----------------------------------------------------
-- Table `money_gull`.`Users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `money_gull`.`Users` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `user_name` VARCHAR(45) NOT NULL,
  `salt` VARCHAR(255) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `userName_UNIQUE` (`user_name` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `money_gull`.`Accounts`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `money_gull`.`Accounts` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Users_id` INT NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `amount` DECIMAL(65,2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_Accounts_Users1_idx` (`Users_id` ASC),
  CONSTRAINT `fk_Accounts_Users1`
    FOREIGN KEY (`Users_id`)
    REFERENCES `money_gull`.`Users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `money_gull`.`Transactions`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `money_gull`.`Transactions` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Users_id` INT NOT NULL,
  `Accounts_from` INT NULL DEFAULT NULL,
  `Accounts_to` INT NULL DEFAULT NULL,
  `Goals_to` INT NULL DEFAULT NULL,
  `amount` DECIMAL(65,2) NOT NULL,
  `type` ENUM('expense', 'income', 'transfer') NOT NULL,
  `date_time` DATETIME NOT NULL,
  `summary` VARCHAR(140) NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `money_gull`.`Goals`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `money_gull`.`Goals` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `Users_id` INT NOT NULL,
  `name` VARCHAR(120) NOT NULL,
  `amount_needed` DECIMAL(65,2) NOT NULL,
  `amount_saved` DECIMAL(65,2) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Goals_Users2_idx` (`Users_id` ASC),
  CONSTRAINT `fk_Goals_Users2`
    FOREIGN KEY (`Users_id`)
    REFERENCES `money_gull`.`Users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `money_gull`.`Settings`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `money_gull`.`Settings` (
  `what_ever_settings` INT NOT NULL,
  `Users_id` INT NOT NULL,
  UNIQUE INDEX `Users_id_UNIQUE` (),
  INDEX `fk_Settings_Users2_idx` (`Users_id` ASC),
  UNIQUE INDEX `Users_id1_UNIQUE` (`Users_id` ASC),
  CONSTRAINT `fk_Settings_Users2`
    FOREIGN KEY (`Users_id`)
    REFERENCES `money_gull`.`Users` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
