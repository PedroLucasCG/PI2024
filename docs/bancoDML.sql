-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema 1CORRE_MANAGER
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema 1CORRE_MANAGER
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `1CORRE_MANAGER` DEFAULT CHARACTER SET utf8 ;
USE `1CORRE_MANAGER` ;

-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Endereco`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Endereco` (
  `idEndereco` INT NOT NULL AUTO_INCREMENT,
  `cep` CHAR(8) NULL,
  `estado` VARCHAR(45) NOT NULL,
  `cidade` VARCHAR(45) NOT NULL,
  `bairro` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idEndereco`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Pessoa`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Pessoa` (
  `idPessoa` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(120) NOT NULL,
  `data_nasc` DATE NOT NULL,
  `cpf` VARCHAR(45) NOT NULL,
  `rg` VARCHAR(45) NOT NULL,
  `senha` VARCHAR(8) NOT NULL,
  `usuario` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `Endereco` INT NOT NULL,
  `data_registro` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idPessoa`),
  INDEX `fk_Pessoa_Endereco_idx` (`Endereco` ASC),
  CONSTRAINT `fk_Pessoa_Endereco`
    FOREIGN KEY (`Endereco`)
    REFERENCES `1CORRE_MANAGER`.`Endereco` (`idEndereco`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Freelancer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Freelancer` (
  `idFreelancer` INT NOT NULL AUTO_INCREMENT,
  `Pessoa` INT NOT NULL,
  PRIMARY KEY (`idFreelancer`),
  INDEX `fk_Freelancer_Pessoa1_idx` (`Pessoa` ASC),
  CONSTRAINT `fk_Freelancer_Pessoa1`
    FOREIGN KEY (`Pessoa`)
    REFERENCES `1CORRE_MANAGER`.`Pessoa` (`idPessoa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Area`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Area` (
  `idArea` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idArea`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Servico`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Servico` (
  `idServico` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `Area` INT NOT NULL,
  PRIMARY KEY (`idServico`),
  INDEX `fk_Servico_Area1_idx` (`Area` ASC),
  CONSTRAINT `fk_Servico_Area1`
    FOREIGN KEY (`Area`)
    REFERENCES `1CORRE_MANAGER`.`Area` (`idArea`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Oferta`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Oferta` (
  `idOferta` INT NOT NULL,
  `Freelancer` INT NOT NULL,
  `Servico` INT NOT NULL,
  `descricao` VARCHAR(200) NOT NULL,
  `preco` DOUBLE NULL,
  PRIMARY KEY (`idOferta`, `Freelancer`, `Servico`),
  INDEX `fk_Freelancer_has_Servico_Servico1_idx` (`Servico` ASC),
  INDEX `fk_Freelancer_has_Servico_Freelancer1_idx` (`Freelancer` ASC),
  CONSTRAINT `fk_Freelancer_has_Servico_Freelancer1`
    FOREIGN KEY (`Freelancer`)
    REFERENCES `1CORRE_MANAGER`.`Freelancer` (`idFreelancer`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Freelancer_has_Servico_Servico1`
    FOREIGN KEY (`Servico`)
    REFERENCES `1CORRE_MANAGER`.`Servico` (`idServico`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Amostra`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Amostra` (
  `idAmostra` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL,
  `descricao` VARCHAR(800) NULL,
  `Oferta` INT NOT NULL,
  `url` VARCHAR(120) NULL,
  `imagem` VARCHAR(130) NULL,
  PRIMARY KEY (`idAmostra`),
  INDEX `fk_Amostra_Oferta1_idx` (`Oferta` ASC),
  CONSTRAINT `fk_Amostra_Oferta1`
    FOREIGN KEY (`Oferta`)
    REFERENCES `1CORRE_MANAGER`.`Oferta` (`idOferta`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Cliente`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Cliente` (
  `idCliente` INT NOT NULL AUTO_INCREMENT,
  `Pessoa` INT NOT NULL,
  PRIMARY KEY (`idCliente`),
  INDEX `fk_Cliente_Pessoa1_idx` (`Pessoa` ASC),
  CONSTRAINT `fk_Cliente_Pessoa1`
    FOREIGN KEY (`Pessoa`)
    REFERENCES `1CORRE_MANAGER`.`Pessoa` (`idPessoa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Telefone`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Telefone` (
  `idTelefone` INT NOT NULL AUTO_INCREMENT,
  `telefone` VARCHAR(45) NOT NULL,
  `Pessoa` INT NOT NULL,
  PRIMARY KEY (`idTelefone`),
  INDEX `fk_E-mail_Pessoa1_idx` (`Pessoa` ASC),
  CONSTRAINT `fk_E-mail_Pessoa1`
    FOREIGN KEY (`Pessoa`)
    REFERENCES `1CORRE_MANAGER`.`Pessoa` (`idPessoa`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Estado`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Estado` (
  `idEstado` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL COMMENT 'Aberto = acordo ativo\nFechado = acordo finalizado\nOscioso = acordo pausado\nCancelado = acordo cancelado\nQuebrado = algumas partes não fez sua responsabilidade',
  PRIMARY KEY (`idEstado`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Acordo`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Acordo` (
  `idAcordo` INT NOT NULL AUTO_INCREMENT,
  `Cliente` INT NOT NULL,
  `Oferta` INT NOT NULL,
  `valor` DOUBLE NOT NULL,
  `descricao` VARCHAR(300) NULL,
  `Estado` INT NOT NULL,
  `modalidade` ENUM("horista", "total") NOT NULL,
  PRIMARY KEY (`idAcordo`, `Cliente`, `Oferta`, `Estado`),
  INDEX `fk_Cliente_has_Oferta_Oferta1_idx` (`Oferta` ASC),
  INDEX `fk_Cliente_has_Oferta_Cliente1_idx` (`Cliente` ASC),
  INDEX `fk_Acordo_Estado1_idx` (`Estado` ASC),
  CONSTRAINT `fk_Cliente_has_Oferta_Cliente1`
    FOREIGN KEY (`Cliente`)
    REFERENCES `1CORRE_MANAGER`.`Cliente` (`idCliente`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Cliente_has_Oferta_Oferta1`
    FOREIGN KEY (`Oferta`)
    REFERENCES `1CORRE_MANAGER`.`Oferta` (`idOferta`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Acordo_Estado1`
    FOREIGN KEY (`Estado`)
    REFERENCES `1CORRE_MANAGER`.`Estado` (`idEstado`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Quebra`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Quebra` (
  `idQuebra` INT NOT NULL,
  `parte` TINYINT(1) NOT NULL COMMENT 'parte que quebrou o acordo',
  `descricao` VARCHAR(400) NOT NULL,
  `Acordo` INT NOT NULL,
  PRIMARY KEY (`idQuebra`),
  INDEX `fk_Quebra_Acordo1_idx` (`Acordo` ASC),
  CONSTRAINT `fk_Quebra_Acordo1`
    FOREIGN KEY (`Acordo`)
    REFERENCES `1CORRE_MANAGER`.`Acordo` (`idAcordo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Grau`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Grau` (
  `idGrau` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(45) NOT NULL COMMENT 'péssimo\nruim\nbom\nótimo',
  PRIMARY KEY (`idGrau`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `1CORRE_MANAGER`.`Avaliacao`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `1CORRE_MANAGER`.`Avaliacao` (
  `idAvaliacao` INT NOT NULL AUTO_INCREMENT,
  `comentario` VARCHAR(200) NULL,
  `Acordo` INT NOT NULL,
  `Grau` INT NOT NULL,
  PRIMARY KEY (`idAvaliacao`),
  INDEX `fk_Avaliacao_Acordo1_idx` (`Acordo` ASC),
  INDEX `fk_Avaliacao_Grau1_idx` (`Grau` ASC),
  CONSTRAINT `fk_Avaliacao_Acordo1`
    FOREIGN KEY (`Acordo`)
    REFERENCES `1CORRE_MANAGER`.`Acordo` (`idAcordo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Avaliacao_Grau1`
    FOREIGN KEY (`Grau`)
    REFERENCES `1CORRE_MANAGER`.`Grau` (`idGrau`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
