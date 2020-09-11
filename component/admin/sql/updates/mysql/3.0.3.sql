SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS  `#__redshop_promotion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `published` tinyint(1) NOT NULL DEFAULT '0',
  `data` text,
  `desc` varchar(255) DEFAULT NULL,
  `ordering` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Promotion';

 -- -----------------------------------------------------
-- Table `#__redshop_catalog_sample`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'sample_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'sample_name', 'name', 'VARCHAR(100) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_catalog_sample', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`");

 -- -----------------------------------------------------
-- Table `#__redshop_product_rating`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_product_rating', 'rating_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_product_rating', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_product_rating', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_product_rating', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_product_rating', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_product_rating', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_product_rating', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`");

-- -----------------------------------------------------
-- Table `#__redshop_wrapper`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_wrapper', 'wrapper_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_wrapper', 'wrapper_name', 'name', "VARCHAR(255) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_wrapper', 'wrapper_price', 'price', "DOUBLE NOT NULL");
CALL redSHOP_Column_Update('#__redshop_wrapper', 'wrapper_image', 'image', "VARCHAR(255) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_wrapper', 'wrapper_use_to_all', 'use_to_all', "TINYINT(4) NOT NULL");
CALL redSHOP_Column_Update('#__redshop_wrapper', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_wrapper', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_wrapper', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_wrapper', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_wrapper', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_wrapper', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`");
CALL redSHOP_Index_Remove('#__redshop_wrapper', 'idx_wrapper_use_to_all');
CALL redSHOP_Index_Add('#__redshop_wrapper', 'idx_use_to_all', "(`use_to_all` ASC)");

 -- -----------------------------------------------------
-- Table `#__redshop_newsletter`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_newsletter', 'newsletter_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_newsletter', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_newsletter', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_newsletter', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_newsletter', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_newsletter', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_newsletter', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`");

-- -----------------------------------------------------
-- Table `#__redshop_newsletter_subscription`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_newsletter_subscription', 'subscription_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_newsletter_subscription', 'checkout', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_newsletter_subscription', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_newsletter_subscription', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_newsletter_subscription', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_newsletter_subscription', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_newsletter_subscription', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`");

-- -----------------------------------------------------
-- Table `#__redshop_stockroom`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_stockroom', 'stockroom_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_stockroom', 'stockroom_name', 'name', 'VARCHAR(250) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_stockroom', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_stockroom', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_stockroom', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_stockroom', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_stockroom', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_stockroom', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`");

 -- -----------------------------------------------------
-- Table `#__redshop_shopper_group`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_id', 'id', 'INT(11) NOT NULL AUTO_INCREMENT');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_name', 'name', 'VARCHAR(32) NULL DEFAULT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_customer_type', 'customer_type', 'TINYINT(4) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_portal', 'portal', 'TINYINT(4) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_categories', 'categories', 'LONGTEXT NOT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_url', 'url', 'VARCHAR(255) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_logo', 'logo', 'VARCHAR(255) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_introtext', 'introtext', 'LONGTEXT NOT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_desc', 'desc', 'TEXT NULL DEFAULT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_cart_checkout_itemid', 'cart_checkout_itemid', 'INT(11) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_cart_itemid', 'cart_itemid', 'INT(11) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_quotation_mode', 'quotation_mode', 'TINYINT(4) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'shopper_group_manufactures', 'manufactures', 'TEXT NOT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'ordering', 'ordering', 'INT(11) NOT NULL');
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'checked_out', 'checked_out', "INT(11) NULL DEFAULT NULL");
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'checked_out_time', 'checked_out_time', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out`");
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'created_date', 'created_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `checked_out_time`");
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'created_by', 'created_by', "INT(11) NULL DEFAULT NULL AFTER `created_date`");
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'modified_by', 'modified_by', "INT(11) NULL DEFAULT NULL AFTER `created_by`");
CALL redSHOP_Column_Update('#__redshop_shopper_group', 'modified_date', 'modified_date', "DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`");

SET FOREIGN_KEY_CHECKS = 1;