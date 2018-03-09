SET FOREIGN_KEY_CHECKS = 0;

-- ------------------------------------------------------
-- Table `#__redshop_coupons`
-- ------------------------------------------------------
CALL redSHOP_Index_Remove('#__redshop_coupons', 'idx_coupon_code');
CALL redSHOP_Index_Remove('#__redshop_coupons', 'idx_percent_or_total');
CALL redSHOP_Index_Remove('#__redshop_coupons', 'idx_start_date');
CALL redSHOP_Index_Remove('#__redshop_coupons', 'idx_end_date');
CALL redSHOP_Index_Remove('#__redshop_coupons', 'idx_coupon_type');
CALL redSHOP_Index_Remove('#__redshop_coupons', 'idx_userid');
CALL redSHOP_Index_Remove('#__redshop_coupons', 'idx_coupon_left');
CALL redSHOP_Index_Remove('#__redshop_coupons', 'idx_published');
CALL redSHOP_Index_Remove('#__redshop_coupons', 'idx_subtotal');
CALL redSHOP_Index_Remove('#__redshop_coupons', 'idx_order_id');

CALL redSHOP_Column_Update('#__redshop_coupons', 'coupon_id', 'id', "INT(11) NOT NULL AUTO_INCREMENT");
CALL redSHOP_Column_Update('#__redshop_coupons', 'coupon_code', 'code', "VARCHAR(32) NOT NULL DEFAULT ''");
CALL redSHOP_Column_Update('#__redshop_coupons', 'percent_or_total', 'type', "TINYINT(4) NOT NULL DEFAULT 0");
CALL redSHOP_Column_Update('#__redshop_coupons', 'coupon_value', 'value', "DECIMAL(12,2) NOT NULL DEFAULT '0.00'");
CALL redSHOP_Column_Update('#__redshop_coupons', 'coupon_type', 'effect', "TINYINT(4) NOT NULL DEFAULT 0 COMMENT '0 - Global, 1 - User Specific'");
CALL redSHOP_Column_Update('#__redshop_coupons', 'coupon_left', 'amount_left', "INT(11) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_coupons', 'free_shipping', 'free_shipping', "TINYINT(4) NOT NULL DEFAULT 0");
CALL redSHOP_Column_Update('#__redshop_coupons', 'start_date', 'start_date_old', "DOUBLE NOT NULL DEFAULT 0");
CALL redSHOP_Column_Update('#__redshop_coupons', 'end_date', 'end_date_old', "DOUBLE NOT NULL DEFAULT 0");
CALL redSHOP_Column_Update('#__redshop_coupons', 'start_date', 'start_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_coupons', 'end_date', 'end_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'");
CALL redSHOP_Column_Update('#__redshop_coupons', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_coupons', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_coupons', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_coupons', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_coupons', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_coupons', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `modified_by`");

CALL redSHOP_Index_Add('#__redshop_coupons', '#__rs_coupon_code', "(`code` ASC)");
CALL redSHOP_Index_Add('#__redshop_coupons', '#__rs_coupon_type', "(`type` ASC)");
CALL redSHOP_Index_Add('#__redshop_coupons', '#__rs_coupon_start_date', "(`start_date` ASC)");
CALL redSHOP_Index_Add('#__redshop_coupons', '#__rs_coupon_end_date', "(`end_date` ASC)");
CALL redSHOP_Index_Add('#__redshop_coupons', '#__rs_coupon_effect', "(`effect` ASC)");
CALL redSHOP_Index_Add('#__redshop_coupons', '#__rs_coupon_user_id', "(`userid` ASC)");
CALL redSHOP_Index_Add('#__redshop_coupons', '#__rs_coupon_left', "(`amount_left` ASC)");
CALL redSHOP_Index_Add('#__redshop_coupons', '#__rs_coupon_published', "(`published` ASC)");
CALL redSHOP_Index_Add('#__redshop_coupons', '#__rs_coupon_subtotal', "(`subtotal` ASC)");
CALL redSHOP_Index_Add('#__redshop_coupons', '#__rs_coupon_order_id', "(`order_id` ASC)");

CALL redSHOP_Index_Add('#__redshop_category', '#__rs_idx_category_parent', '(`parent_id` ASC)');
CALL redSHOP_Index_Add('#__redshop_product', '#__prod_pub_exp_parent', '(`product_parent_id` ASC, `published` ASC, `expired` ASC)');
CALL redSHOP_Index_Add('#__redshop_fields_data', '#__field_data_common', '(`itemid` ASC, `section` ASC)');
CALL redSHOP_Index_Add('#__redshop_manufacturer', '#__manufacturer_common_idx', '(`id` ASC, `name` ASC, `published` ASC)');
CALL redSHOP_Index_Add('#__redshop_product_category_xref', '#__prod_cat_idx1', '(`category_id` ASC, `product_id` ASC)');
CALL redSHOP_Index_Add('#__redshop_fields', '#__rs_idx_field_common', '(`id` ASC, `name` ASC, `published` ASC, `section` ASC)');
CALL redSHOP_Index_Remove('#__redshop_fields_data', 'itemid');

-- -----------------------------------------------------
-- Table `#__redshop_fields_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_fields_group` ;

CREATE TABLE IF NOT EXISTS `#__redshop_fields_group` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(125) NOT NULL,
  `description` VARCHAR(1024) NOT NULL DEFAULT '',
  `section` VARCHAR(20) NOT NULL,
  `ordering` INT(11) NOT NULL DEFAULT 0,
  `published` TINYINT(4) NOT NULL DEFAULT 0,
  `created_by` INT(11) NULL DEFAULT NULL,
  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `checked_out` INT(11) NULL DEFAULT NULL,
  `checked_out_time` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` INT(11) NULL DEFAULT NULL,
  `modified_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX `#__rs_feld_group_idx1` (`section` ASC),
  INDEX `#__rs_feld_group_idx2` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Custom fields groups';

CALL redSHOP_Column_Update('#__redshop_fields', 'groupId', 'groupId', "INT(11) NULL DEFAULT NULL AFTER `section`");
CALL redSHOP_Constraint_Remove('#__redshop_fields', '#__rs_field_fk1');
CALL redSHOP_Index_Add('#__redshop_fields', '#__rs_field_fk1', "(`groupId` ASC)");
CALL redSHOP_Constraint_Update('#__redshop_fields', '#__rs_field_fk1', 'groupId', '#__redshop_fields_group', 'id', 'CASCADE', 'SET NULL');

SET FOREIGN_KEY_CHECKS = 1;