SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `#__redshop_wishlist_product_item` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `ref_id` INT(11) NOT NULL COMMENT 'Wishlist Reference ID',
  `attribute_id` INT(11) NULL DEFAULT NULL COMMENT 'Product Attribute ID',
  `property_id` INT(11) NULL DEFAULT NULL COMMENT 'Product Attribute Property ID',
  `subattribute_id` INT(11) NULL DEFAULT NULL COMMENT 'Product Sub-Attribute ID',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `#__idx_wishlist_prod_item_unique` (`ref_id` ASC, `attribute_id` ASC, `property_id` ASC, `subattribute_id` ASC),
  INDEX `#__wishlist_prod_item_fk2` (`attribute_id` ASC),
  INDEX `#__wishlist_prod_item_fk3` (`property_id` ASC),
  INDEX `#__wishlist_prod_item_fk4` (`subattribute_id` ASC),
  INDEX `#__wishlist_prod_item_fk1` (`ref_id` ASC),
  CONSTRAINT `#__wishlist_prod_item_fk1`
  FOREIGN KEY (`ref_id`)
  REFERENCES `#__redshop_wishlist_product` (`wishlist_product_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `#__wishlist_prod_item_fk2`
  FOREIGN KEY (`attribute_id`)
  REFERENCES `#__redshop_product_attribute` (`attribute_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `#__wishlist_prod_item_fk3`
  FOREIGN KEY (`property_id`)
  REFERENCES `#__redshop_product_attribute_property` (`property_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `#__wishlist_prod_item_fk4`
  FOREIGN KEY (`subattribute_id`)
  REFERENCES `#__redshop_product_subattribute_color` (`subattribute_color_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
  ROW_FORMAT=DYNAMIC
  ENGINE = InnoDB
  DEFAULT CHARACTER SET = utf8
  COMMENT = 'Wishlist product item';

CALL redSHOP_Column_Update('#__redshop_supplier', 'supplier_id', 'id', "INT(11) NOT NULL AUTO_INCREMENT");
CALL redSHOP_Column_Update('#__redshop_supplier', 'supplier_name', 'name', "VARCHAR(255) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_supplier', 'supplier_desc', 'description', "TEXT NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_supplier', 'supplier_email', 'email', "VARCHAR(255) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_supplier', 'published', 'published', "TINYINT(4) NOT NULL DEFAULT 0");
CALL redSHOP_Column_Update('#__redshop_supplier', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL AFTER `published`");
CALL redSHOP_Column_Update('#__redshop_supplier', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_supplier', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_supplier', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_supplier', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_supplier', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `modified_by`");

CALL redSHOP_Index_Add('#__redshop_supplier', '#__rs_idx_supplier_published', "(`published` ASC)");

CALL redSHOP_Index_Add('#__redshop_product', '#__rs_product_supplier_fk1', "(`supplier_id` ASC)");

CALL redSHOP_Column_Update('#__redshop_tax_rate', 'tax_rate_id', 'id', "INT(11) NOT NULL AUTO_INCREMENT");
CALL redSHOP_Column_Update('#__redshop_tax_rate', 'name', 'name', "VARCHAR(255) NOT NULL DEFAULT '' AFTER `id`");
CALL redSHOP_Column_Update('#__redshop_tax_rate', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_tax_rate', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_tax_rate', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_tax_rate', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");

CALL redSHOP_Index_Remove('#__redshop_order_status', 'order_status_code');
CALL redSHOP_Index_Remove('#__redshop_order_status', 'idx_published');

CALL redSHOP_Column_Update('#__redshop_order_status', 'published', 'published', "TINYINT(4) NOT NULL DEFAULT 0");

CALL redSHOP_Index_Add('#__redshop_order_status', '#__rs_idx_order_status_published', "(`published` ASC)");

CALL redSHOP_Index_Unique_Add('#__redshop_order_status', '#__rs_idx_order_status_code', "(`order_status_code` ASC)");

CALL redSHOP_Column_Update('#__redshop_order_status', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_order_status', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_order_status', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_order_status', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_order_status', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_order_status', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");

CALL redSHOP_Index_Add('#__redshop_media', '#__rs_idx_media_common', "(`section_id` ASC, `media_section` ASC, `media_type` ASC, `published` ASC, `ordering` ASC)
  USING BTREE");

CALL redSHOP_Column_Update('#__redshop_mass_discount', 'mass_discount_id', 'id', "INT(11) NOT NULL AUTO_INCREMENT");
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'discount_type', 'type', "TINYINT(4) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'discount_amount', 'amount', "DOUBLE(10,2) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'discount_startdate', 'start_date', "INT(11) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'discount_enddate', 'end_date', "INT(11) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'discount_name', 'name', "VARCHAR(255) NOT NULL");

CALL redSHOP_Column_Update('#__redshop_mass_discount', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_mass_discount', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");

SET FOREIGN_KEY_CHECKS = 1;