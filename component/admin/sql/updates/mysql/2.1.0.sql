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
CALL redSHOP_Index_Add('#__redshop_manufacturer', '#__manufacturer_common_idx', '(`manufacturer_id` ASC, `manufacturer_name` ASC, `published` ASC)');
CALL redSHOP_Index_Add('#__redshop_product_category_xref', '#__prod_cat_idx1', '(`category_id` ASC, `product_id` ASC)');
CALL redSHOP_Index_Add('#__redshop_fields', '#__rs_idx_field_common', '(`id` ASC, `name` ASC, `published` ASC, `section` ASC)');
CALL redSHOP_Index_Remove('#__redshop_fields_data', 'itemid');

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `#__redshop_fields_group` (
  `id` int(11) NOT NULL,
  `name` varchar(125) NOT NULL,
  `description` mediumtext NOT NULL,
  `section` varchar(125) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_by_alias` varchar(125) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` int(11) NOT NULL,
  `ordering` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `params` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `#__redshop_fields_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

ALTER TABLE `#__redshop_fields_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__redshop_fields` ADD `groupId` INT NOT NULL AFTER `section`;