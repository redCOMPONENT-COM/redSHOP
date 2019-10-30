SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Index_Remove('#__redshop_country', 'idx_country_2_code');
CALL redSHOP_Index_Remove('#__redshop_country', 'idx_country_3_code');
CALL redSHOP_Column_Update('#__redshop_country', 'country_id', 'id', "INT(11) NOT NULL AUTO_INCREMENT");
CALL redSHOP_Column_Update('#__redshop_country', 'country_name', 'country_name', "VARCHAR(64) CHARACTER SET utf8 NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_country', 'country_3_code', 'country_3_code', "CHAR(3) CHARACTER SET utf8 NOT NULL");
CALL redSHOP_Column_Update('#__redshop_country', 'country_2_code', 'country_2_code', "CHAR(2) CHARACTER SET utf8 NOT NULL");
CALL redSHOP_Index_Unique_Add('#__redshop_country', '#__rs_idx_country_3_code', "(`country_3_code` ASC)");
CALL redSHOP_Index_Unique_Add('#__redshop_country', '#__rs_idx_country_2_code', "(`country_2_code` ASC)");

CALL redSHOP_Column_Update('#__redshop_state', 'country_id', 'country_id', "INT(11) NOT NULL");
CALL redSHOP_Index_Remove('#__redshop_state', 'state_3_code');
CALL redSHOP_Index_Remove('#__redshop_state', 'state_2_code');
CALL redSHOP_Index_Unique_Add('#__redshop_state', '#__rs_idx_state_3_code', "(`country_id` ASC, `state_3_code` ASC)");
CALL redSHOP_Index_Unique_Add('#__redshop_state', '#__rs_idx_state_2_code', "(`country_id` ASC, `state_2_code` ASC)");
CALL redSHOP_Index_Add('#__redshop_state', 'country_id', "(`country_id` ASC)");

ALTER TABLE
  `#__redshop_state` ADD CONSTRAINT `#__rs_state_country_fk1` FOREIGN KEY(`country_id`) REFERENCES `#__redshop_country`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;
