SET FOREIGN_KEY_CHECKS = 0;

 -- -----------------------------------------------------
-- Table `#__redshop_catalog_sample`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'sample_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'sample_name', 'name', 'VARCHAR(100) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`");

 -- -----------------------------------------------------
-- Table `#__redshop_product_rating`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_product_rating', 'rating_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_product_rating', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_product_rating', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_product_rating', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_product_rating', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_product_rating', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_product_rating', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`");

 -- -----------------------------------------------------
-- Table `#__redshop_newsletter`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_newsletter', 'newsletter_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_newsletter', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_newsletter', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_newsletter', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_newsletter', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_newsletter', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_newsletter', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`");

-- -----------------------------------------------------
-- Table `#__redshop_stockroom`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_stockroom', 'stockroom_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_stockroom', 'stockroom_name', 'name', 'VARCHAR(250) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_stockroom', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_stockroom', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_stockroom', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_stockroom', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_stockroom', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_stockroom', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`");

SET FOREIGN_KEY_CHECKS = 1;