SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------
-- Table `#__redshop_currency`
-- ------------------------------------------------------
CALL redSHOP_Index_Remove('#__redshop_currency', 'idx_currency_code');

CALL redSHOP_Column_Update('#__redshop_currency', 'currency_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_currency', 'currency_name', 'name', "VARCHAR(64) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_currency', 'currency_code', 'code', "CHAR(3) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_currency', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_currency', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_currency', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_currency', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_currency', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_currency', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");

CALL redSHOP_Index_Unique_Add('#__redshop_currency', '#__rs_cur_code', '(`code` ASC)');

SET FOREIGN_KEY_CHECKS = 1;