SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------
-- Table `#__redshop_manufacturer`
-- ------------------------------------------------------
CALL redSHOP_Index_Remove('#__redshop_manufacturer', 'idx_published');

CALL redSHOP_Column_Update('#__redshop_manufacturer', 'manufacturer_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'manufacturer_name', 'name', "VARCHAR(250) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'manufacturer_desc', 'description', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'manufacturer_email', 'email', "VARCHAR(250) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'product_per_page', 'product_per_page', "INT(11) NOT NULL DEFAULT 0");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'metakey', 'metakey', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'metadesc', 'metadesc', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'metalanguage_setting', 'metalanguage_setting', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'metarobot_info', 'metarobot_info', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'pagetitle', 'pagetitle', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'pageheading', 'pageheading', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'sef_url', 'sef_url', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'ordering', 'ordering', "INT(11) NOT NULL DEFAULT 0");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'manufacturer_url', 'manufacturer_url', "VARCHAR(255) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'excluding_category_list', 'excluding_category_list', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'published', 'published', "TINYINT(4) NOT NULL DEFAULT 1");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_manufacturer', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");

CALL redSHOP_Index_Add('#__redshop_manufacturer', '#__manufacturer_published', '(`published` ASC)');

SET FOREIGN_KEY_CHECKS = 1;