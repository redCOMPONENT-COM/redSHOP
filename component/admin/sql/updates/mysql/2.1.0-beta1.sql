SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Column_Update('#__redshop_template', 'twig_support', 'twig_support', "TINYINT(1) NOT NULL DEFAULT 0 AFTER `shipping_methods`");
CALL redSHOP_Column_Update('#__redshop_template', 'twig_enable', 'twig_enable', "TINYINT(1) NOT NULL DEFAULT 0 AFTER `twig_support`");

CALL redSHOP_Index_Add('#__redshop_template', '#__rs_tmpl_twig_support', '(`twig_support` ASC)');
CALL redSHOP_Index_Add('#__redshop_template', '#__rs_tmpl_twig_enable', '(`twig_enable` ASC)');

SET FOREIGN_KEY_CHECKS = 1;