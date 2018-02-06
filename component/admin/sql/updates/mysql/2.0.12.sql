SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------
-- Table `#__redshop_textlibrary`
-- ------------------------------------------------------
CALL redSHOP_Index_Remove('#__redshop_textlibrary', 'idx_section');
CALL redSHOP_Index_Remove('#__redshop_textlibrary', 'idx_published');

CALL redSHOP_Column_Update('#__redshop_textlibrary', 'textlibrary_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'text_name', 'name', "VARCHAR(255) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'text_desc', 'desc', "VARCHAR(255) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'text_field', 'content', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'published', 'published', "TINYINT(4) NOT NULL DEFAULT 1");
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_textlibrary', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");

CALL redSHOP_Index_Add('#__redshop_textlibrary', '#__rs_text_tag_section', '(`section` ASC)');
CALL redSHOP_Index_Add('#__redshop_textlibrary', '#__rs_text_tag_published', '(`published` ASC)');

SET FOREIGN_KEY_CHECKS = 1;