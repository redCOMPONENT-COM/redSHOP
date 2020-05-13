SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------
-- Table `#__redshop_tax_rate`
-- ------------------------------------------------------

CALL redSHOP_Column_Update('#__redshop_tax_rate', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
CALL redSHOP_Column_Update('#__redshop_tax_rate', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP");
CALL redSHOP_Column_Update('#__redshop_tax_rate', 'shopper_group_id', 'shopper_group_id', "INT(11) NOT NULL");

-- -----------------------------------------------------
-- Table `#__redshop_fields`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_fields', 'desc', 'description', 'VARCHAR(20) NOT NULL');

SET FOREIGN_KEY_CHECKS = 1;