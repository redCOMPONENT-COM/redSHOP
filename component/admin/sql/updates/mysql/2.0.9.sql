SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------
-- Table `#__redshop_template`
-- ------------------------------------------------------
CALL redSHOP_Index_Remove('#__redshop_template', 'idx_template_section');
CALL redSHOP_Index_Remove('#__redshop_template', 'idx_published');

CALL redSHOP_Column_Update('#__redshop_template', 'template_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_template', 'template_name', 'name', "VARCHAR(250) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_template', 'template_section', 'section', "VARCHAR(250) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_template', 'file_name', 'file_name', "VARCHAR(255) NOT NULL DEFAULT '' AFTER `section`");
CALL redSHOP_Column_Update('#__redshop_template', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_template', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_template', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_template', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_template', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_template', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `modified_by`");

CALL redSHOP_Column_Remove('#__redshop_template', 'template_desc');

CALL redSHOP_Index_Add('#__redshop_template', '#__rs_tmpl_section', '(`section` ASC)');
CALL redSHOP_Index_Add('#__redshop_template', '#__rs_tmpl_published', '(`published` ASC)');

SET FOREIGN_KEY_CHECKS = 1;