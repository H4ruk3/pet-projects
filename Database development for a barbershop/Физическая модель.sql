SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `Barbershop` DEFAULT CHARACTER SET utf8 ;
USE `Barbershop` ;

CREATE TABLE IF NOT EXISTS `position` (
  `id_position` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_position`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `employee` (
  `id_employee` INT NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(200) NOT NULL,
  `phone_number` VARCHAR(45) NULL DEFAULT NULL,
  `id_position` INT NOT NULL,
  PRIMARY KEY (`id_employee`),
  INDEX `fk_Employee_Position1_idx` (`id_position` ASC) VISIBLE,
  CONSTRAINT `fk_Employee_Position1`
    FOREIGN KEY (`id_position`)
    REFERENCES `position` (`id_position`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `schedule` (
  `date` DATE NOT NULL,
  `id_employee` INT NOT NULL,
  `start_time` TIME NOT NULL,
  `end_time` TIME NOT NULL,
  PRIMARY KEY (`date`, `id_employee`),
  INDEX `fk_Schedule_Employee_idx` (`id_employee` ASC) VISIBLE,
  CONSTRAINT `fk_Schedule_Employee`
    FOREIGN KEY (`id_employee`)
    REFERENCES `employee` (`id_employee`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `service` (
  `id_service` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `description` VARCHAR(200) NULL DEFAULT NULL,
  `service_duration` INT NOT NULL,  -- длительность услуги в минутах
  PRIMARY KEY (`id_service`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `service_position` (
  `id_service` INT NOT NULL,
  `id_position` INT NOT NULL,
  `price` DECIMAL(10, 2) NOT NULL,
  PRIMARY KEY (`id_service`, `id_position`),
  INDEX `fk_Service_position_Service1_idx` (`id_service` ASC) VISIBLE,
  INDEX `fk_Service_position_Position1_idx` (`id_position` ASC) VISIBLE,
  CONSTRAINT `fk_Service_position_Service1`
    FOREIGN KEY (`id_service`)
    REFERENCES `service` (`id_service`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Service_position_Position1`
    FOREIGN KEY (`id_position`)
    REFERENCES `position` (`id_position`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `client` (
  `id_client` INT NOT NULL AUTO_INCREMENT,
  `full_name` VARCHAR(200) NOT NULL,
  `phone_number` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id_client`))
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `record` (
  `id_record` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `time` TIME NOT NULL,
  `service_rendered` TINYINT(1) NULL DEFAULT NULL,
  `id_client` INT NOT NULL,
  `id_employee` INT NOT NULL,
  PRIMARY KEY (`id_record`),
  INDEX `fk_Record_Client1_idx` (`id_client` ASC) VISIBLE,
  INDEX `fk_Record_Employee1_idx` (`id_employee` ASC) VISIBLE,
  INDEX `idx_Date` (`date` ASC) VISIBLE,
  CONSTRAINT `fk_Record_Client1`
    FOREIGN KEY (`id_client`)
    REFERENCES `client` (`id_client`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Record_Employee1`
    FOREIGN KEY (`id_employee`)
    REFERENCES `employee` (`id_employee`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE TABLE IF NOT EXISTS `record_service` (
  `id_record` INT NOT NULL,
  `id_service` INT NOT NULL,
  `record_duration` INT NOT NULL, -- длительность записи в минутах
  PRIMARY KEY (`id_record`, `id_service`),
  INDEX `fk_Record_Service1_idx` (`id_service` ASC) VISIBLE,
  CONSTRAINT `fk_Record_Service1`
    FOREIGN KEY (`id_service`)
    REFERENCES `service` (`id_service`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Record_Record1`
    FOREIGN KEY (`id_record`)
    REFERENCES `record` (`id_record`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECK;
SET UNIQUE_CHECKС=@OLD_UNIQUE_CHECK;
