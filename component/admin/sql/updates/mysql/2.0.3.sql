SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Index_Add('#__redshop_order_item', 'idx_product_quantity', "USING BTREE (`product_id` ASC, `product_quantity` ASC)");
CALL redSHOP_Index_Add('#__redshop_product', '#__rs_prod_publish_parent', "(`product_parent_id` ASC, `published` ASC)");
CALL redSHOP_Index_Add('#__redshop_product', '#__rs_prod_publish_parent_special', "(`product_parent_id` ASC, `published` ASC, `product_special` ASC)");
CALL redSHOP_Index_Add('#__redshop_product_subattribute_color', '#__rs_sub_prop_common', "(`subattribute_id` ASC, `subattribute_published` ASC, `ordering` ASC)");

CALL redSHOP_Column_Update('#__redshop_tax_group', 'tax_group_id', 'id', "INT(11) NOT NULL AUTO_INCREMENT");
CALL redSHOP_Column_Update('#__redshop_tax_group', 'tax_group_name', 'name', "VARCHAR(255) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_tax_group', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_tax_group', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_tax_group', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_tax_group', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_tax_group', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_tax_group', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");

SET FOREIGN_KEY_CHECKS = 1;