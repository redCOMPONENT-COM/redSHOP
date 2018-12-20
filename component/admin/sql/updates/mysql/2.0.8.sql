SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Column_Update('#__redshop_product', 'use_individual_payment_method', 'use_individual_payment_method', 'INT(4) NOT NULL DEFAULT 0');

-- -----------------------------------------------------
-- Table `#__redshop_product_payment_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_payment_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_payment_xref` (
  `payment_id` VARCHAR(255) NOT NULL DEFAULT '',
  `product_id` TINYINT(11) NOT NULL,
  PRIMARY KEY (`product_id`, `payment_id`),
  INDEX `#__rs_pro_pay_ref_fk1` (`product_id` ASC))
ROW_FORMAT=DYNAMIC
ENGINE = InnoDB
COMMENT = 'redSHOP Product Individual payment reference.';

SET FOREIGN_KEY_CHECKS = 1;