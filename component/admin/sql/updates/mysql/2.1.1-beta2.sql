SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Column_Update('#__redshop_template', 'twig_support', 'twig_support', "TINYINT(1) NOT NULL DEFAULT 0 AFTER `shipping_methods`");
CALL redSHOP_Column_Update('#__redshop_template', 'twig_enable', 'twig_enable', "TINYINT(1) NOT NULL DEFAULT 0 AFTER `twig_support`");
CALL redSHOP_Column_Update('#__redshop_template', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_template', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_template', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_template', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_template', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_template', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");

SET FOREIGN_KEY_CHECKS = 1;