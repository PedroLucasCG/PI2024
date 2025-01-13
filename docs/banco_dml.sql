-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema 1corre_manager
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema 1corre_manager
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `1corre_manager` DEFAULT CHARACTER SET utf8 ;
USE `1corre_manager` ;

-- -----------------------------------------------------
-- Table `1corre_manager`.`endereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1corre_manager`.`endereco` (
  `idEndereco` INT(11) NOT NULL AUTO_INCREMENT,
  `cep` CHAR(8) NULL DEFAULT NULL,
  `estado` VARCHAR(45) NOT NULL,
  `cidade` VARCHAR(45) NOT NULL,
  `bairro` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idEndereco`))
ENGINE = InnoDB
AUTO_INCREMENT = 6
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `1corre_manager`.`pessoa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1corre_manager`.`pessoa` (
  `idPessoa` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(120) NOT NULL,
  `data_nasc` DATE NOT NULL,
  `cpf` VARCHAR(45) NOT NULL,
  `senha` VARCHAR(8) NOT NULL,
  `usuario` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `Endereco` INT(11) NOT NULL,
  `data_registro` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idPessoa`),
  INDEX `fk_Pessoa_Endereco_idx` (`Endereco` ASC),
  CONSTRAINT `fk_Pessoa_Endereco`
    FOREIGN KEY (`Endereco`)
    REFERENCES `1corre_manager`.`endereco` (`idEndereco`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 9
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `1corre_manager`.`area`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1corre_manager`.`area` (
  `idArea` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idArea`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `1corre_manager`.`oferta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1corre_manager`.`oferta` (
  `idOferta` INT(11) NOT NULL AUTO_INCREMENT,
  `descricao` VARCHAR(200) NOT NULL,
  `preco` DOUBLE NULL DEFAULT NULL,
  `Freelancer` INT(11) NOT NULL,
  `Area` INT(11) NOT NULL,
  `titulo` VARCHAR(120) NOT NULL,
  `foto` VARCHAR(45) NULL,
  PRIMARY KEY (`idOferta`),
  INDEX `fk_oferta_pessoa1_idx` (`Freelancer` ASC),
  INDEX `fk_oferta_area1_idx` (`Area` ASC),
  CONSTRAINT `fk_oferta_pessoa1`
    FOREIGN KEY (`Freelancer`)
    REFERENCES `1corre_manager`.`pessoa` (`idPessoa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_oferta_area1`
    FOREIGN KEY (`Area`)
    REFERENCES `1corre_manager`.`area` (`idArea`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `1corre_manager`.`acordo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1corre_manager`.`acordo` (
  `idAcordo` INT(11) NOT NULL AUTO_INCREMENT,
  `Oferta` INT(11) NOT NULL,
  `valor` DOUBLE NOT NULL,
  `descricao` VARCHAR(300) NULL DEFAULT NULL,
  `estado` ENUM('ativo', 'pausado', 'finalizado', 'proposto', 'quebrado') NOT NULL,
  `modalidade` ENUM('horista', 'total') NOT NULL,
  `Contratante` INT(11) NOT NULL,
  PRIMARY KEY (`idAcordo`),
  INDEX `fk_Cliente_has_Oferta_Oferta1_idx` (`Oferta` ASC),
  INDEX `fk_acordo_pessoa1_idx` (`Contratante` ASC),
  CONSTRAINT `fk_Cliente_has_Oferta_Oferta1`
    FOREIGN KEY (`Oferta`)
    REFERENCES `1corre_manager`.`oferta` (`idOferta`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_acordo_pessoa1`
    FOREIGN KEY (`Contratante`)
    REFERENCES `1corre_manager`.`pessoa` (`idPessoa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `1corre_manager`.`avaliacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1corre_manager`.`avaliacao` (
  `idAvaliacao` INT(11) NOT NULL AUTO_INCREMENT,
  `comentario` VARCHAR(200) NULL DEFAULT NULL,
  `Acordo` INT(11) NOT NULL,
  `grau` INT(11) NOT NULL,
  PRIMARY KEY (`idAvaliacao`),
  INDEX `fk_Avaliacao_Acordo1_idx` (`Acordo` ASC),
  CONSTRAINT `fk_Avaliacao_Acordo1`
    FOREIGN KEY (`Acordo`)
    REFERENCES `1corre_manager`.`acordo` (`idAcordo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `1corre_manager`.`quebra`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1corre_manager`.`quebra` (
  `idQuebra` INT(11) NOT NULL AUTO_INCREMENT,
  `parte` ENUM('contratante', 'freelancer') NOT NULL COMMENT 'parte que quebrou o acordo',
  `descricao` VARCHAR(400) NOT NULL,
  `Acordo` INT(11) NOT NULL,
  PRIMARY KEY (`idQuebra`),
  INDEX `fk_Quebra_Acordo1_idx` (`Acordo` ASC),
  CONSTRAINT `fk_Quebra_Acordo1`
    FOREIGN KEY (`Acordo`)
    REFERENCES `1corre_manager`.`acordo` (`idAcordo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `1corre_manager`.`telefone`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1corre_manager`.`telefone` (
  `idTelefone` INT(11) NOT NULL AUTO_INCREMENT,
  `telefone` VARCHAR(45) NOT NULL,
  `Pessoa` INT(11) NOT NULL,
  PRIMARY KEY (`idTelefone`),
  UNIQUE INDEX `telefone_UNIQUE` (`telefone` ASC),
  INDEX `fk_E-mail_Pessoa1_idx` (`Pessoa` ASC),
  CONSTRAINT `fk_E-mail_Pessoa1`
    FOREIGN KEY (`Pessoa`)
    REFERENCES `1corre_manager`.`pessoa` (`idPessoa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 5
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `1corre_manager`.`periodo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1corre_manager`.`periodo` (
  `idperiodo` INT NOT NULL AUTO_INCREMENT,
  `Oferta` INT(11) NOT NULL,
  `dia` VARCHAR(45) NOT NULL,
  `hora_inicio` TIME NOT NULL,
  `hora_final` TIME NOT NULL,
  PRIMARY KEY (`idperiodo`),
  INDEX `fk_periodo_oferta1_idx` (`Oferta` ASC),
  CONSTRAINT `fk_periodo_oferta1`
    FOREIGN KEY (`Oferta`)
    REFERENCES `1corre_manager`.`oferta` (`idOferta`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
