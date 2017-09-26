SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__redshop_voucher`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_voucher`;

CREATE TABLE IF NOT EXISTS `#__redshop_voucher` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(255) NOT NULL DEFAULT '',
  `amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `type` VARCHAR(250) NOT NULL,
  `start_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `end_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `free_ship` TINYINT(4) NOT NULL,
  `voucher_left` INT(11) NOT NULL,
  `published` TINYINT(4) NOT NULL DEFAULT '0',
  `checked_out` INT(11) NULL DEFAULT NULL,
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INT(11) NULL DEFAULT NULL,
  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` INT(11) NULL DEFAULT NULL,
  `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `#__rs_voucher_code` (`code` ASC),
  INDEX `#__rs_voucher_common` (`code` ASC, `published` ASC, `start_date` ASC, `end_date` ASC),
  INDEX `#__rs_voucher_left` (`voucher_left` ASC))
ENGINE = InnoDB;

SET FOREIGN_KEY_CHECKS = 1;