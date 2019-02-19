SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__redshop_zipcode`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_zipcode', 'zipcode_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');

SET FOREIGN_KEY_CHECKS = 1;