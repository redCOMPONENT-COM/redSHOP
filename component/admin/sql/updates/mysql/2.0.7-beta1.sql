SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__redshop_voucher`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_voucher` ;

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

INSERT IGNORE INTO `#__content_types`
(`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`)
VALUES
('redSHOP', 'com_redshop.product', '{"special":{"dbtable":"#__redshop_product","key":"product_id"}}', '', '{"common":{"core_content_item_id":"product_id","core_title":"product_name","core_state":"published","core_catid":"cat_in_sefurl"}}', 'RedshopHelperRoute::getProductRoute', '');

SET FOREIGN_KEY_CHECKS = 1;