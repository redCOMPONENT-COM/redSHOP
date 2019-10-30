SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `#__redshop_accessmanager`;

CALL redSHOP_Column_Update('#__redshop_category', 'category_id', 'id', "INT(11) NOT NULL AUTO_INCREMENT");
CALL redSHOP_Column_Update('#__redshop_category', 'category_name', 'name', "VARCHAR(255) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_category', 'category_short_description', 'short_description', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_category', 'category_description', 'description', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_category', 'published', 'published', "TINYINT(4) NOT NULL DEFAULT 0");
CALL redSHOP_Column_Update('#__redshop_category', 'category_template', 'template', "INT(11) NOT NULL DEFAULT 0");
CALL redSHOP_Column_Update('#__redshop_category', 'category_more_template', 'more_template', "VARCHAR(255) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_category', 'category_pdate', 'category_pdate', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_category', 'alias', 'alias', "VARCHAR(255) NOT NULL DEFAULT '' AFTER `append_to_global_seo`");
CALL redSHOP_Column_Update('#__redshop_category', 'path', 'path', "VARCHAR(255) NOT NULL DEFAULT '' AFTER `alias`");
CALL redSHOP_Column_Update('#__redshop_category', 'asset_id', 'asset_id', "INT(11) NULL DEFAULT '0' COMMENT 'FK to the #__assets table.'  AFTER `path`");
CALL redSHOP_Column_Update('#__redshop_category', 'parent_id', 'parent_id', "INT(11) NULL DEFAULT '0' AFTER `asset_id`");
CALL redSHOP_Column_Update('#__redshop_category', 'level', 'level', "INT(11) NOT NULL DEFAULT '0' AFTER `parent_id`");
CALL redSHOP_Column_Update('#__redshop_category', 'lft', 'lft', "INT(11) NOT NULL DEFAULT '0' AFTER `level`");
CALL redSHOP_Column_Update('#__redshop_category', 'rgt', 'rgt', "INT(11) NOT NULL DEFAULT '0' AFTER `lft`");
CALL redSHOP_Column_Update('#__redshop_category', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL AFTER `rgt`");
CALL redSHOP_Column_Update('#__redshop_category', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_category', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_category', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_category', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_category', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `modified_by`");
CALL redSHOP_Column_Update('#__redshop_category', 'publish_up', 'publish_up', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `modified_date`");
CALL redSHOP_Column_Update('#__redshop_category', 'publish_down', 'publish_down', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `publish_up`");

CALL redSHOP_Index_Add('#__redshop_category', '#__rs_idx_category_published', "(`published` ASC)");
CALL redSHOP_Index_Add('#__redshop_category', '#__rs_idx_left_right', "(`lft` ASC, `rgt` ASC)");
CALL redSHOP_Index_Add('#__redshop_category', '#__rs_idx_alias', "(`alias` ASC)");
CALL redSHOP_Index_Add('#__redshop_category', '#__rs_idx_path', "(`path` ASC)");

CALL redSHOP_Column_Update('#__redshop_fields', 'field_id', 'id', "INT(11) NOT NULL AUTO_INCREMENT");
CALL redSHOP_Column_Update('#__redshop_fields', 'field_title', 'title', "VARCHAR(250) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'field_name', 'name', "VARCHAR(250) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'field_type', 'type', "VARCHAR(20) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'field_desc', 'desc', "LONGTEXT NOT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'field_class', 'class', "VARCHAR(20) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'field_section', 'section', "VARCHAR(20) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'field_maxlength', 'maxlength', "INT(11) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'field_cols', 'cols', "INT(11) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'field_rows', 'rows', "INT(11) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'field_size', 'size', "TINYINT(4) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'field_show_in_front', 'show_in_front', "TINYINT(4) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'publish_up', 'publish_up', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `published`");
CALL redSHOP_Column_Update('#__redshop_fields', 'publish_down', 'publish_down', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `publish_up`");
CALL redSHOP_Column_Update('#__redshop_fields', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_fields', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_fields', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_fields', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_fields', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL");

CALL redSHOP_Index_Remove('#__redshop_fields', 'idx_published');
CALL redSHOP_Index_Remove('#__redshop_fields', 'idx_field_section');
CALL redSHOP_Index_Remove('#__redshop_fields', 'idx_field_type');
CALL redSHOP_Index_Remove('#__redshop_fields', 'idx_required');
CALL redSHOP_Index_Remove('#__redshop_fields', 'idx_field_name');
CALL redSHOP_Index_Remove('#__redshop_fields', 'idx_field_show_in_front');
CALL redSHOP_Index_Remove('#__redshop_fields', 'idx_display_in_product');

CALL redSHOP_Index_Add('#__redshop_fields', '#__rs_idx_field_published', "(`published` ASC)");
CALL redSHOP_Index_Add('#__redshop_fields', '#__rs_idx_field_section', "(`section` ASC)");
CALL redSHOP_Index_Add('#__redshop_fields', '#__rs_idx_field_type', "(`type` ASC)");
CALL redSHOP_Index_Add('#__redshop_fields', '#__rs_idx_field_required', "(`required` ASC)");
CALL redSHOP_Index_Add('#__redshop_fields', '#__rs_idx_field_name', "(`name` ASC)");
CALL redSHOP_Index_Add('#__redshop_fields', '#__rs_idx_field_show_in_front', "(`show_in_front` ASC)");
CALL redSHOP_Index_Add('#__redshop_fields', '#__rs_idx_field_display_in_product', "(`display_in_product` ASC)");

CALL redSHOP_Column_Update('#__redshop_product_attribute', 'attribute_description', 'attribute_description', "VARCHAR(255) NOT NULL DEFAULT '' AFTER `attribute_name`");

SET FOREIGN_KEY_CHECKS = 1;