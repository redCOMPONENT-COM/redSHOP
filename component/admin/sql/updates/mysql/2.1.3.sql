SET FOREIGN_KEY_CHECKS = 0;

 -- -----------------------------------------------------
-- Table `#__redshop_fields`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_fields', 'is_searchable', 'is_searchable', "TINYINT(4) NOT NULL DEFAULT '0' AFTER `display_in_product`");

SET FOREIGN_KEY_CHECKS = 1;