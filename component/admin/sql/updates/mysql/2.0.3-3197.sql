SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `mydb`.`#__redshop_tax_group` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `published` TINYINT(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Tax Group'

SET FOREIGN_KEY_CHECKS = 1;