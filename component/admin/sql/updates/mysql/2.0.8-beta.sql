ALTER TABLE `#__redshop_product` ADD `use_individual_payment_method` TINYINT(4) NOT NULL DEFAULT 0;

-- -----------------------------------------------------
-- Table `#__redshop_product_payment_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_payment_xref`;

CREATE TABLE IF NOT EXISTS `#__redshop_product_payment_xref` (
  `payment_id` VARCHAR(255) NOT NULL,
  `product_id` INT NOT NULL,
  PRIMARY KEY (`payment_id`,`product_id`),
  INDEX `ref_payment` (`product_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Payment Relation';