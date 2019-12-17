SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__redshop_category`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_category', 'product_filter_params', 'product_filter_params', "MEDIUMTEXT NOT NULL DEFAULT '' AFTER `publish_down`");

SET FOREIGN_KEY_CHECKS = 1;