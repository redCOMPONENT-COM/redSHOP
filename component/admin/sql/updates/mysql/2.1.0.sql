SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Column_Update('#__redshop_product', 'use_individual_payment_method', 'use_individual_payment_method', 'INT(4) NOT NULL DEFAULT 0');

-- -----------------------------------------------------
-- Table `#__redshop_product_payment_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_payment_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_payment_xref` (
  `payment_id` VARCHAR(255) NOT NULL DEFAULT '',
  `product_id` TINYINT(11) NOT NULL,
  PRIMARY KEY (`product_id`, `payment_id`),
  INDEX `#__rs_pro_pay_ref_fk1` (`product_id` ASC))
ENGINE = InnoDB
COMMENT = 'redSHOP Product Individual payment reference.';

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

SET FOREIGN_KEY_CHECKS = 1;