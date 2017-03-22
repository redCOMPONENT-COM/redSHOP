SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__redshop_configuration`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `#__redshop_configuration` (
  `config` VARCHAR(255) NOT NULL DEFAULT '',
  `value` VARCHAR(255) NULL DEFAULT '',
  PRIMARY KEY (`config`))
  ENGINE = InnoDB;

SET FOREIGN_KEY_CHECKS = 1;