SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `#__redshop_attribute_set`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_attribute_set` ;

CREATE TABLE IF NOT EXISTS `#__redshop_attribute_set` (
  `attribute_set_id` INT(11) NOT NULL AUTO_INCREMENT,
  `attribute_set_name` VARCHAR(255) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`attribute_set_id`),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Attribute set detail';


-- -----------------------------------------------------
-- Table `#__redshop_cart`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_cart` ;

CREATE TABLE IF NOT EXISTS `#__redshop_cart` (
  `session_id` VARCHAR(255) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `section` VARCHAR(250) NOT NULL,
  `qty` INT(11) NOT NULL,
  `time` DOUBLE NOT NULL,
  INDEX `idx_session_id` (`session_id` ASC),
  INDEX `idx_product_id` (`product_id` ASC),
  INDEX `idx_section` (`section` ASC),
  INDEX `idx_time` (`time` ASC),
  PRIMARY KEY (`session_id`, `product_id`, `section`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Cart';


-- -----------------------------------------------------
-- Table `#__redshop_catalog`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_catalog` ;

CREATE TABLE IF NOT EXISTS `#__redshop_catalog` (
  `catalog_id` INT(11) NOT NULL AUTO_INCREMENT,
  `catalog_name` VARCHAR(250) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`catalog_id`),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Catalog';


-- -----------------------------------------------------
-- Table `#__redshop_catalog_colour`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_catalog_colour` ;

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_colour` (
  `colour_id` INT(11) NOT NULL AUTO_INCREMENT,
  `sample_id` INT(11) NOT NULL,
  `code_image` VARCHAR(250) NOT NULL,
  `is_image` TINYINT(4) NOT NULL,
  PRIMARY KEY (`colour_id`),
  INDEX `idx_sample_id` (`sample_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Catalog Colour';


-- -----------------------------------------------------
-- Table `#__redshop_catalog_request`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_catalog_request` ;

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_request` (
  `catalog_user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `catalog_id` INT(11) NOT NULL,
  `name` VARCHAR(250) NOT NULL,
  `email` VARCHAR(250) NOT NULL,
  `registerDate` INT(11) NOT NULL,
  `block` TINYINT(4) NOT NULL,
  `reminder_1` TINYINT(4) NOT NULL,
  `reminder_2` TINYINT(4) NOT NULL,
  `reminder_3` TINYINT(4) NOT NULL,
  PRIMARY KEY (`catalog_user_id`),
  INDEX `idx_block` (`block` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Catalog Request';


-- -----------------------------------------------------
-- Table `#__redshop_catalog_sample`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_catalog_sample` ;

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_sample` (
  `sample_id` INT(11) NOT NULL AUTO_INCREMENT,
  `sample_name` VARCHAR(100) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`sample_id`),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Catalog Sample';


-- -----------------------------------------------------
-- Table `#__redshop_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_category` ;

CREATE TABLE IF NOT EXISTS `#__redshop_category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(250) NOT NULL,
  `short_description` LONGTEXT NOT NULL,
  `description` LONGTEXT NOT NULL,
  `template` INT(11) NOT NULL,
  `more_template` VARCHAR(255) NOT NULL,
  `products_per_page` INT(11) NOT NULL,
  `category_thumb_image` VARCHAR(250) NOT NULL,
  `category_full_image` VARCHAR(250) NOT NULL,
  `metakey` VARCHAR(250) NOT NULL,
  `metadesc` LONGTEXT NOT NULL,
  `metalanguage_setting` TEXT NOT NULL,
  `metarobot_info` TEXT NOT NULL,
  `pagetitle` TEXT NOT NULL,
  `pageheading` LONGTEXT NOT NULL,
  `sef_url` TEXT NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `category_pdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ordering` INT(11) NOT NULL,
  `canonical_url` TEXT NOT NULL,
  `category_back_full_image` VARCHAR(250) NOT NULL,
  `compare_template_id` VARCHAR(255) NOT NULL,
  `append_to_global_seo` ENUM('append', 'prepend', 'replace') NOT NULL DEFAULT 'append',
  `alias` VARCHAR(400) NOT NULL,
  `path` VARCHAR(255) NOT NULL,
  `asset_id` INT(11) UNSIGNED NULL COMMENT 'FK to the #__assets table.',
  `parent_id` INT(11) NULL DEFAULT 0,
  `level` INT(11) UNSIGNED NOT NULL DEFAULT 0,
  `lft` INT(11) NOT NULL DEFAULT 0,
  `rgt` INT(11) NOT NULL DEFAULT 0,
  `checked_out` INT(11) NULL,
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INT(11) NULL,
  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` INT(11) NULL,
  `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX `#__rs_idx_category_published` (`published` ASC),
  INDEX `#__rs_idx_left_right` (`lft` ASC, `rgt` ASC),
  INDEX `#__rs_idx_alias` (`alias` ASC),
  INDEX `#__rs_idx_path` (`path` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Category';


-- -----------------------------------------------------
-- Table `#__redshop_country`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_country` ;

CREATE TABLE IF NOT EXISTS `#__redshop_country` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `country_name` VARCHAR(64) NOT NULL DEFAULT '',
  `country_3_code` CHAR(3) NOT NULL,
  `country_2_code` CHAR(2) NOT NULL,
  `country_jtext` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `#__rs_idx_country_3_code` (`country_3_code` ASC),
  UNIQUE INDEX `#__rs_idx_country_2_code` (`country_2_code` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Country records';


-- -----------------------------------------------------
-- Table `#__redshop_coupons`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_coupons` ;

CREATE TABLE IF NOT EXISTS `#__redshop_coupons` (
  `coupon_id` INT(16) NOT NULL AUTO_INCREMENT,
  `coupon_code` VARCHAR(32) NOT NULL DEFAULT '',
  `percent_or_total` TINYINT(4) NOT NULL,
  `coupon_value` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `start_date` DOUBLE NOT NULL,
  `end_date` DOUBLE NOT NULL,
  `coupon_type` TINYINT(4) NOT NULL COMMENT '0 - Global, 1 - User Specific',
  `userid` INT(11) NOT NULL,
  `coupon_left` INT(11) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `subtotal` INT(11) NOT NULL,
  `order_id` INT(11) NOT NULL,
  `free_shipping` TINYINT(4) NOT NULL,
  PRIMARY KEY (`coupon_id`),
  INDEX `idx_coupon_code` (`coupon_code` ASC),
  INDEX `idx_percent_or_total` (`percent_or_total` ASC),
  INDEX `idx_start_date` (`start_date` ASC),
  INDEX `idx_end_date` (`end_date` ASC),
  INDEX `idx_coupon_type` (`coupon_type` ASC),
  INDEX `idx_userid` (`userid` ASC),
  INDEX `idx_coupon_left` (`coupon_left` ASC),
  INDEX `idx_published` (`published` ASC),
  INDEX `idx_subtotal` (`subtotal` ASC),
  INDEX `idx_order_id` (`order_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Coupons';


-- -----------------------------------------------------
-- Table `#__redshop_coupons_transaction`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_coupons_transaction` ;

CREATE TABLE IF NOT EXISTS `#__redshop_coupons_transaction` (
  `transaction_coupon_id` INT(11) NOT NULL AUTO_INCREMENT,
  `coupon_id` INT(11) NOT NULL,
  `coupon_code` VARCHAR(255) NOT NULL,
  `coupon_value` DECIMAL(10,3) NOT NULL,
  `userid` INT(11) NOT NULL,
  `trancation_date` INT(11) NOT NULL,
  `published` INT(11) NOT NULL,
  PRIMARY KEY (`transaction_coupon_id`),
  INDEX `idx_coupon_id` (`coupon_id` ASC),
  INDEX `idx_coupon_code` (`coupon_code` ASC),
  INDEX `idx_coupon_value` (`coupon_value` ASC),
  INDEX `idx_userid` (`userid` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Coupons Transaction';


-- -----------------------------------------------------
-- Table `#__redshop_cron`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_cron` ;

CREATE TABLE IF NOT EXISTS `#__redshop_cron` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_date` (`date` ASC),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Cron Job';


-- -----------------------------------------------------
-- Table `#__redshop_currency`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_currency` ;

CREATE TABLE IF NOT EXISTS `#__redshop_currency` (
  `currency_id` INT(11) NOT NULL AUTO_INCREMENT,
  `currency_name` VARCHAR(64) NULL DEFAULT NULL,
  `currency_code` CHAR(3) NULL DEFAULT NULL,
  PRIMARY KEY (`currency_id`),
  INDEX `idx_currency_code` (`currency_code` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Currency Detail';


-- -----------------------------------------------------
-- Table `#__redshop_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product` (
  `product_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_parent_id` INT(11) NOT NULL,
  `manufacturer_id` INT(11) NOT NULL,
  `supplier_id` INT(11) NOT NULL,
  `product_on_sale` TINYINT(4) NOT NULL,
  `product_special` TINYINT(4) NOT NULL,
  `product_download` TINYINT(4) NOT NULL,
  `product_template` INT(11) NOT NULL,
  `product_name` VARCHAR(250) NOT NULL,
  `product_price` DOUBLE NOT NULL,
  `discount_price` DOUBLE NOT NULL,
  `discount_stratdate` INT(11) NOT NULL,
  `discount_enddate` INT(11) NOT NULL,
  `product_number` VARCHAR(250) NOT NULL,
  `product_type` VARCHAR(20) NOT NULL,
  `product_s_desc` LONGTEXT NOT NULL,
  `product_desc` LONGTEXT NOT NULL,
  `product_volume` DOUBLE NOT NULL,
  `product_tax_id` INT(11) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `product_thumb_image` VARCHAR(250) NOT NULL,
  `product_full_image` VARCHAR(250) NOT NULL,
  `publish_date` DATETIME NOT NULL,
  `update_date` TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP,
  `visited` INT(11) NOT NULL,
  `metakey` TEXT NOT NULL,
  `metadesc` TEXT NOT NULL,
  `metalanguage_setting` TEXT NOT NULL,
  `metarobot_info` TEXT NOT NULL,
  `pagetitle` TEXT NOT NULL,
  `pageheading` TEXT NOT NULL,
  `sef_url` TEXT NOT NULL,
  `cat_in_sefurl` INT(11) NOT NULL,
  `weight` FLOAT(10,3) NOT NULL,
  `expired` TINYINT(4) NOT NULL,
  `not_for_sale` TINYINT(4) NOT NULL,
  `use_discount_calc` TINYINT(4) NOT NULL,
  `discount_calc_method` VARCHAR(255) NOT NULL,
  `min_order_product_quantity` INT(11) NOT NULL,
  `attribute_set_id` INT(11) NOT NULL,
  `product_length` DECIMAL(10,2) NOT NULL,
  `product_height` DECIMAL(10,2) NOT NULL,
  `product_width` DECIMAL(10,2) NOT NULL,
  `product_diameter` DECIMAL(10,2) NOT NULL,
  `product_availability_date` INT(11) NOT NULL,
  `use_range` TINYINT(4) NOT NULL,
  `product_tax_group_id` INT(11) NOT NULL,
  `product_download_days` INT(11) NOT NULL,
  `product_download_limit` INT(11) NOT NULL,
  `product_download_clock` INT(11) NOT NULL,
  `product_download_clock_min` INT(11) NOT NULL,
  `accountgroup_id` INT(11) NOT NULL,
  `canonical_url` TEXT NOT NULL,
  `minimum_per_product_total` INT(11) NOT NULL,
  `allow_decimal_piece` INT(4) NOT NULL,
  `quantity_selectbox_value` VARCHAR(255) NOT NULL,
  `checked_out` INT(11) NOT NULL,
  `checked_out_time` DATETIME NOT NULL,
  `max_order_product_quantity` INT(11) NOT NULL,
  `product_download_infinite` TINYINT(4) NOT NULL,
  `product_back_full_image` VARCHAR(250) NOT NULL,
  `product_back_thumb_image` VARCHAR(250) NOT NULL,
  `product_preview_image` VARCHAR(250) NOT NULL,
  `product_preview_back_image` VARCHAR(250) NOT NULL,
  `preorder` VARCHAR(255) NOT NULL,
  `append_to_global_seo` ENUM('append', 'prepend', 'replace') NOT NULL DEFAULT 'append',
  PRIMARY KEY (`product_id`),
  UNIQUE INDEX `idx_product_number` (`product_number` ASC),
  INDEX `idx_manufacturer_id` (`manufacturer_id` ASC),
  INDEX `idx_product_on_sale` (`product_on_sale` ASC),
  INDEX `idx_product_special` (`product_special` ASC),
  INDEX `idx_product_parent_id` (`product_parent_id` ASC),
  INDEX `idx_common` (`published` ASC, `expired` ASC, `product_parent_id` ASC),
  INDEX `#__rs_product_supplier_fk1` (`supplier_id` ASC),
  INDEX `#__rs_prod_publish_parent` (`product_parent_id` ASC, `published` ASC),
  INDEX `#__rs_prod_publish_parent_special` (`product_parent_id` ASC, `published` ASC, `product_special` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products';


-- -----------------------------------------------------
-- Table `#__redshop_customer_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_customer_question` ;

CREATE TABLE IF NOT EXISTS `#__redshop_customer_question` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `parent_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `question` LONGTEXT NOT NULL,
  `user_id` INT(11) NOT NULL,
  `user_name` VARCHAR(255) NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `question_date` INT(11) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `telephone` VARCHAR(50) NOT NULL,
  `address` VARCHAR(250) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `#__rs_idx_published` (`published` ASC),
  INDEX `#__rs_idx_product_id` (`product_id` ASC),
  INDEX `#__rs_idx_parent_id` (`parent_id` ASC),
  CONSTRAINT `#__rs_customer_question_fk1`
    FOREIGN KEY (`product_id`)
    REFERENCES `#__redshop_product` (`product_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Customer Question';


-- -----------------------------------------------------
-- Table `#__redshop_discount`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_discount` ;

CREATE TABLE IF NOT EXISTS `#__redshop_discount` (
  `discount_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(250) NOT NULL,
  `amount` INT(11) NOT NULL,
  `condition` TINYINT(1) NOT NULL DEFAULT '1',
  `discount_amount` DECIMAL(10,4) NOT NULL,
  `discount_type` TINYINT(4) NOT NULL,
  `start_date` DOUBLE NOT NULL,
  `end_date` DOUBLE NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`discount_id`),
  INDEX `idx_start_date` (`start_date` ASC),
  INDEX `idx_end_date` (`end_date` ASC),
  INDEX `idx_published` (`published` ASC),
  INDEX `idx_discount_name` (`name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Discount';


-- -----------------------------------------------------
-- Table `#__redshop_discount_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_discount_product` ;

CREATE TABLE IF NOT EXISTS `#__redshop_discount_product` (
  `discount_product_id` INT(11) NOT NULL AUTO_INCREMENT,
  `amount` INT(11) NOT NULL,
  `condition` TINYINT(1) NOT NULL DEFAULT '1',
  `discount_amount` DECIMAL(10,2) NOT NULL,
  `discount_type` TINYINT(4) NOT NULL,
  `start_date` DOUBLE NOT NULL,
  `end_date` DOUBLE NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `category_ids` TEXT NOT NULL,
  PRIMARY KEY (`discount_product_id`),
  INDEX `idx_published` (`published` ASC),
  INDEX `idx_start_date` (`start_date` ASC),
  INDEX `idx_end_date` (`end_date` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_discount_product_shoppers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_discount_product_shoppers` ;

CREATE TABLE IF NOT EXISTS `#__redshop_discount_product_shoppers` (
  `discount_product_id` INT(11) NOT NULL,
  `shopper_group_id` INT(11) NOT NULL,
  INDEX `idx_discount_product_id` (`discount_product_id` ASC),
  INDEX `idx_shopper_group_id` (`shopper_group_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_discount_shoppers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_discount_shoppers` ;

CREATE TABLE IF NOT EXISTS `#__redshop_discount_shoppers` (
  `discount_id` INT(11) NOT NULL,
  `shopper_group_id` INT(11) NOT NULL,
  INDEX `idx_discount_id` (`discount_id` ASC),
  INDEX `idx_shopper_group_id` (`shopper_group_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_economic_accountgroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_economic_accountgroup` ;

CREATE TABLE IF NOT EXISTS `#__redshop_economic_accountgroup` (
  `accountgroup_id` INT(11) NOT NULL AUTO_INCREMENT,
  `accountgroup_name` VARCHAR(255) NOT NULL,
  `economic_vat_account` VARCHAR(255) NOT NULL,
  `economic_nonvat_account` VARCHAR(255) NOT NULL,
  `economic_discount_nonvat_account` VARCHAR(255) NOT NULL,
  `economic_shipping_vat_account` VARCHAR(255) NOT NULL,
  `economic_shipping_nonvat_account` VARCHAR(255) NOT NULL,
  `economic_discount_product_number` VARCHAR(255) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `economic_service_nonvat_account` VARCHAR(255) NOT NULL,
  `economic_discount_vat_account` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`accountgroup_id`),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Economic Account Group';


-- -----------------------------------------------------
-- Table `#__redshop_fields`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_fields` ;

CREATE TABLE IF NOT EXISTS `#__redshop_fields` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(250) NOT NULL,
  `name` VARCHAR(250) NOT NULL,
  `type` VARCHAR(20) NOT NULL,
  `desc` LONGTEXT NOT NULL,
  `class` VARCHAR(20) NOT NULL,
  `section` VARCHAR(20) NOT NULL,
  `maxlength` INT(11) NOT NULL,
  `cols` INT(11) NOT NULL,
  `rows` INT(11) NOT NULL,
  `size` TINYINT(4) NOT NULL,
  `show_in_front` TINYINT(4) NOT NULL,
  `required` TINYINT(4) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `publish_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publish_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `display_in_product` TINYINT(4) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `display_in_checkout` TINYINT(4) NOT NULL,
  `checked_out` INT(11) NULL DEFAULT NULL,
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INT(11) NULL DEFAULT NULL,
  `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` INT(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `name` (`name` ASC),
  INDEX `#__rs_idx_field_published` (`published` ASC),
  INDEX `#__rs_idx_field_section` (`section` ASC),
  INDEX `#__rs_idx_field_type` (`type` ASC),
  INDEX `#__rs_idx_field_required` (`required` ASC),
  INDEX `#__rs_idx_field_name` (`name` ASC),
  INDEX `#__rs_idx_field_show_in_front` (`show_in_front` ASC),
  INDEX `#__rs_idx_field_display_in_product` (`display_in_product` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Fields';


-- -----------------------------------------------------
-- Table `#__redshop_fields_data`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_fields_data` ;

CREATE TABLE IF NOT EXISTS `#__redshop_fields_data` (
  `data_id` INT(11) NOT NULL AUTO_INCREMENT,
  `fieldid` INT(11) NULL DEFAULT NULL,
  `data_txt` LONGTEXT NULL DEFAULT NULL,
  `itemid` INT(11) NULL DEFAULT NULL,
  `section` VARCHAR(20) NULL DEFAULT NULL,
  `alt_text` VARCHAR(255) NOT NULL,
  `image_link` VARCHAR(255) NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`data_id`),
  INDEX `itemid` (`itemid` ASC),
  INDEX `idx_fieldid` (`fieldid` ASC),
  INDEX `idx_itemid` (`itemid` ASC),
  INDEX `idx_section` (`section` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Fields Data';


-- -----------------------------------------------------
-- Table `#__redshop_fields_value`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_fields_value` ;

CREATE TABLE IF NOT EXISTS `#__redshop_fields_value` (
  `value_id` INT(11) NOT NULL AUTO_INCREMENT,
  `field_id` INT(11) NOT NULL,
  `field_value` VARCHAR(250) NOT NULL,
  `field_name` VARCHAR(250) NOT NULL,
  `alt_text` VARCHAR(255) NOT NULL,
  `image_link` TEXT NOT NULL,
  PRIMARY KEY (`value_id`),
  INDEX `idx_field_id` (`field_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Fields Value';


-- -----------------------------------------------------
-- Table `#__redshop_giftcard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_giftcard` ;

CREATE TABLE IF NOT EXISTS `#__redshop_giftcard` (
  `giftcard_id` INT(11) NOT NULL AUTO_INCREMENT,
  `giftcard_name` VARCHAR(255) NOT NULL,
  `giftcard_price` DECIMAL(10,3) NOT NULL,
  `giftcard_value` DECIMAL(10,3) NOT NULL,
  `giftcard_validity` INT(11) NOT NULL,
  `giftcard_date` INT(11) NOT NULL,
  `giftcard_bgimage` VARCHAR(255) NOT NULL,
  `giftcard_image` VARCHAR(255) NOT NULL,
  `published` INT(11) NOT NULL,
  `giftcard_desc` LONGTEXT NOT NULL,
  `customer_amount` INT(11) NOT NULL,
  `accountgroup_id` INT(11) NOT NULL,
  `free_shipping` TINYINT NOT NULL,
  PRIMARY KEY (`giftcard_id`),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Giftcard';


-- -----------------------------------------------------
-- Table `#__redshop_mail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_mail` ;

CREATE TABLE IF NOT EXISTS `#__redshop_mail` (
  `mail_id` INT(11) NOT NULL AUTO_INCREMENT,
  `mail_name` VARCHAR(255) NOT NULL,
  `mail_subject` VARCHAR(255) NOT NULL,
  `mail_section` VARCHAR(255) NOT NULL,
  `mail_order_status` VARCHAR(11) NOT NULL,
  `mail_body` LONGTEXT NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `mail_bcc` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`mail_id`),
  INDEX `idx_mail_section` (`mail_section` ASC),
  INDEX `idx_mail_order_status` (`mail_order_status` ASC),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Mail Center';


-- -----------------------------------------------------
-- Table `#__redshop_manufacturer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_manufacturer` ;

CREATE TABLE IF NOT EXISTS `#__redshop_manufacturer` (
  `manufacturer_id` INT(11) NOT NULL AUTO_INCREMENT,
  `manufacturer_name` VARCHAR(250) NOT NULL,
  `manufacturer_desc` LONGTEXT NOT NULL,
  `manufacturer_email` VARCHAR(250) NOT NULL,
  `product_per_page` INT(11) NOT NULL,
  `template_id` INT(11) NOT NULL,
  `metakey` TEXT NOT NULL,
  `metadesc` TEXT NOT NULL,
  `metalanguage_setting` TEXT NOT NULL,
  `metarobot_info` TEXT NOT NULL,
  `pagetitle` TEXT NOT NULL,
  `pageheading` TEXT NOT NULL,
  `sef_url` TEXT NOT NULL,
  `published` INT(11) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `manufacturer_url` VARCHAR(255) NOT NULL,
  `excluding_category_list` TEXT NOT NULL,
  PRIMARY KEY (`manufacturer_id`),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Manufacturer';


-- -----------------------------------------------------
-- Table `#__redshop_mass_discount`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_mass_discount` ;

CREATE TABLE IF NOT EXISTS `#__redshop_mass_discount` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `discount_product` LONGTEXT NOT NULL DEFAULT '',
  `category_id` LONGTEXT NOT NULL DEFAULT '',
  `manufacturer_id` LONGTEXT NOT NULL DEFAULT '',
  `type` TINYINT(4) NOT NULL,
  `amount` DOUBLE(10,2) NOT NULL,
  `start_date` INT(11) NOT NULL,
  `end_date` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `checked_out` INT(11) NULL,
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INT(11) NULL,
  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` INT(11) NULL,
  `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Mass Discount.';


-- -----------------------------------------------------
-- Table `#__redshop_media`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_media` ;

CREATE TABLE IF NOT EXISTS `#__redshop_media` (
  `media_id` INT(11) NOT NULL AUTO_INCREMENT,
  `media_name` VARCHAR(250) NOT NULL,
  `media_alternate_text` VARCHAR(255) NOT NULL,
  `media_section` VARCHAR(20) NOT NULL,
  `section_id` INT(11) NOT NULL,
  `media_type` VARCHAR(250) NOT NULL,
  `media_mimetype` VARCHAR(20) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `ordering` INT(11) NOT NULL,
  PRIMARY KEY (`media_id`),
  INDEX `idx_section_id` (`section_id` ASC),
  INDEX `idx_media_section` (`media_section` ASC),
  INDEX `idx_media_type` (`media_type` ASC),
  INDEX `idx_media_name` (`media_name` ASC),
  INDEX `idx_published` (`published` ASC),
  INDEX `#__rs_idx_media_common` USING BTREE (`section_id` ASC, `media_section` ASC, `media_type` ASC, `published` ASC, `ordering` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Media';


-- -----------------------------------------------------
-- Table `#__redshop_media_download`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_media_download` ;

CREATE TABLE IF NOT EXISTS `#__redshop_media_download` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `media_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_media_id` (`media_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Media Additional Downloadable Files';


-- -----------------------------------------------------
-- Table `#__redshop_newsletter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_newsletter` ;

CREATE TABLE IF NOT EXISTS `#__redshop_newsletter` (
  `newsletter_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `body` LONGTEXT NOT NULL,
  `template_id` INT(11) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`newsletter_id`),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Newsletter';


-- -----------------------------------------------------
-- Table `#__redshop_newsletter_subscription`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_newsletter_subscription` ;

CREATE TABLE IF NOT EXISTS `#__redshop_newsletter_subscription` (
  `subscription_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `date` INT(11) NOT NULL,
  `newsletter_id` INT(11) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `checkout` TINYINT(4) NOT NULL,
  `published` INT(11) NOT NULL,
  PRIMARY KEY (`subscription_id`),
  INDEX `idx_user_id` (`user_id` ASC),
  INDEX `idx_newsletter_id` (`newsletter_id` ASC),
  INDEX `idx_email` (`email` ASC),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Newsletter subscribers';


-- -----------------------------------------------------
-- Table `#__redshop_newsletter_tracker`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_newsletter_tracker` ;

CREATE TABLE IF NOT EXISTS `#__redshop_newsletter_tracker` (
  `tracker_id` INT(11) NOT NULL AUTO_INCREMENT,
  `newsletter_id` INT(11) NOT NULL,
  `subscription_id` INT(11) NOT NULL,
  `subscriber_name` VARCHAR(255) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `read` TINYINT(4) NOT NULL,
  `date` DOUBLE NOT NULL,
  PRIMARY KEY (`tracker_id`),
  INDEX `idx_newsletter_id` (`newsletter_id` ASC),
  INDEX `idx_read` (`read` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Newsletter Tracker';


-- -----------------------------------------------------
-- Table `#__redshop_notifystock_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_notifystock_users` ;

CREATE TABLE IF NOT EXISTS `#__redshop_notifystock_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `property_id` INT(11) NOT NULL,
  `subproperty_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `notification_status` INT(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  INDEX `idx_common` (`product_id` ASC, `property_id` ASC, `subproperty_id` ASC, `notification_status` ASC, `user_id` ASC),
  INDEX `idx_user_id` (`user_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_orderbarcode_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_orderbarcode_log` ;

CREATE TABLE IF NOT EXISTS `#__redshop_orderbarcode_log` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `barcode` VARCHAR(255) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `search_date` DATETIME NOT NULL,
  PRIMARY KEY (`log_id`),
  INDEX `idx_order_id` (`order_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_ordernumber_track`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_ordernumber_track` ;

CREATE TABLE IF NOT EXISTS `#__redshop_ordernumber_track` (
  `trackdatetime` DATETIME NOT NULL)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order number track';


-- -----------------------------------------------------
-- Table `#__redshop_orders`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_orders` ;

CREATE TABLE IF NOT EXISTS `#__redshop_orders` (
  `order_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL DEFAULT '0',
  `order_number` VARCHAR(32) NULL DEFAULT NULL,
  `invoice_number_chrono` INT(11) NOT NULL COMMENT 'Order invoice number in chronological order',
  `invoice_number` VARCHAR(255) NOT NULL COMMENT 'Formatted Order Invoice for final use',
  `barcode` VARCHAR(13) NOT NULL,
  `user_info_id` VARCHAR(32) NULL DEFAULT NULL,
  `order_total` DECIMAL(15,2) NOT NULL DEFAULT '0.00',
  `order_subtotal` DECIMAL(15,5) NULL DEFAULT NULL,
  `order_tax` DECIMAL(10,2) NULL DEFAULT NULL,
  `order_tax_details` TEXT NOT NULL,
  `order_shipping` DECIMAL(10,2) NULL DEFAULT NULL,
  `order_shipping_tax` DECIMAL(10,2) NULL DEFAULT NULL,
  `coupon_discount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `order_discount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `special_discount_amount` DECIMAL(12,2) NOT NULL,
  `payment_dicount` DECIMAL(12,2) NOT NULL,
  `order_status` VARCHAR(5) NULL DEFAULT NULL,
  `order_payment_status` VARCHAR(25) NOT NULL,
  `cdate` INT(11) NULL DEFAULT NULL,
  `mdate` INT(11) NULL DEFAULT NULL,
  `ship_method_id` VARCHAR(255) NULL DEFAULT NULL,
  `customer_note` TEXT NOT NULL,
  `ip_address` VARCHAR(15) NOT NULL DEFAULT '',
  `encr_key` VARCHAR(255) NOT NULL,
  `invoice_no` VARCHAR(255) NOT NULL,
  `mail1_status` TINYINT(1) NOT NULL,
  `mail2_status` TINYINT(1) NOT NULL,
  `mail3_status` TINYINT(1) NOT NULL,
  `special_discount` DECIMAL(10,2) NOT NULL,
  `payment_discount` DECIMAL(10,2) NOT NULL,
  `is_booked` TINYINT(1) NOT NULL,
  `order_label_create` TINYINT(1) NOT NULL,
  `vm_order_number` VARCHAR(32) NOT NULL,
  `requisition_number` VARCHAR(255) NOT NULL,
  `bookinvoice_number` INT(11) NOT NULL,
  `bookinvoice_date` INT(11) NOT NULL,
  `referral_code` VARCHAR(50) NOT NULL,
  `customer_message` VARCHAR(255) NOT NULL,
  `shop_id` VARCHAR(255) NOT NULL,
  `order_discount_vat` DECIMAL(10,3) NOT NULL,
  `track_no` VARCHAR(250) NOT NULL,
  `payment_oprand` VARCHAR(50) NOT NULL,
  `discount_type` VARCHAR(255) NOT NULL,
  `analytics_status` INT(1) NOT NULL,
  `tax_after_discount` DECIMAL(10,3) NOT NULL,
  `recuuring_subcription_id` VARCHAR(500) NOT NULL,
  PRIMARY KEY (`order_id`),
  INDEX `idx_orders_user_id` (`user_id` ASC),
  INDEX `idx_orders_order_number` (`order_number` ASC),
  INDEX `idx_orders_user_info_id` (`user_info_id` ASC),
  INDEX `idx_orders_ship_method_id` (`ship_method_id` ASC),
  INDEX `idx_barcode` (`barcode` ASC),
  INDEX `idx_order_payment_status` (`order_payment_status` ASC),
  INDEX `idx_order_status` (`order_status` ASC),
  INDEX `vm_order_number` (`vm_order_number` ASC),
  INDEX `idx_orders_invoice_number` (`invoice_number` ASC),
  INDEX `idx_orders_invoice_number_chrono` (`invoice_number_chrono` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order Detail';


-- -----------------------------------------------------
-- Table `#__redshop_order_acc_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_acc_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_acc_item` (
  `order_item_acc_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_item_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `order_acc_item_sku` VARCHAR(255) NOT NULL,
  `order_acc_item_name` VARCHAR(255) NOT NULL,
  `order_acc_price` DECIMAL(15,4) NOT NULL,
  `order_acc_vat` DECIMAL(15,4) NOT NULL,
  `product_quantity` INT(11) NOT NULL,
  `product_acc_item_price` DECIMAL(15,4) NOT NULL,
  `product_acc_final_price` DECIMAL(15,4) NOT NULL,
  `product_attribute` TEXT NOT NULL,
  PRIMARY KEY (`order_item_acc_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order Accessory Item Detail';


-- -----------------------------------------------------
-- Table `#__redshop_order_attribute_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_attribute_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_attribute_item` (
  `order_att_item_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_item_id` INT(11) NOT NULL,
  `section_id` INT(11) NOT NULL,
  `section` VARCHAR(250) NOT NULL,
  `parent_section_id` INT(11) NOT NULL,
  `section_name` VARCHAR(250) NOT NULL,
  `section_price` DECIMAL(15,4) NOT NULL,
  `section_vat` DECIMAL(15,4) NOT NULL,
  `section_oprand` CHAR(1) NOT NULL,
  `is_accessory_att` TINYINT(4) NOT NULL,
  `stockroom_id` VARCHAR(255) NOT NULL,
  `stockroom_quantity` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`order_att_item_id`),
  INDEX `idx_order_item_id` (`order_item_id` ASC),
  INDEX `idx_section` (`section` ASC),
  INDEX `idx_parent_section_id` (`parent_section_id` ASC),
  INDEX `idx_is_accessory_att` (`is_accessory_att` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP order Attribute item';


-- -----------------------------------------------------
-- Table `#__redshop_order_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_item` (
  `order_item_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NULL DEFAULT NULL,
  `user_info_id` VARCHAR(32) NULL DEFAULT NULL,
  `supplier_id` INT(11) NULL DEFAULT NULL,
  `product_id` INT(11) NULL DEFAULT NULL,
  `order_item_sku` VARCHAR(64) NOT NULL DEFAULT '',
  `order_item_name` VARCHAR(255) NOT NULL,
  `product_quantity` INT(11) NULL DEFAULT NULL,
  `product_item_price` DECIMAL(15,4) NULL DEFAULT NULL,
  `product_item_price_excl_vat` DECIMAL(15,4) NULL DEFAULT NULL,
  `product_final_price` DECIMAL(12,4) NOT NULL DEFAULT '0.0000',
  `order_item_currency` VARCHAR(16) NULL DEFAULT NULL,
  `order_status` VARCHAR(250) NULL DEFAULT NULL,
  `customer_note` TEXT NOT NULL,
  `cdate` INT(11) NULL DEFAULT NULL,
  `mdate` INT(11) NULL DEFAULT NULL,
  `product_attribute` TEXT NULL DEFAULT NULL,
  `product_accessory` TEXT NOT NULL,
  `delivery_time` INT(11) NOT NULL,
  `stockroom_id` VARCHAR(255) NOT NULL,
  `stockroom_quantity` VARCHAR(255) NOT NULL,
  `is_split` TINYINT(1) NOT NULL,
  `attribute_image` TEXT NOT NULL,
  `is_giftcard` TINYINT(4) NOT NULL,
  `wrapper_id` INT(11) NOT NULL,
  `wrapper_price` DECIMAL(10,2) NOT NULL,
  `giftcard_user_name` VARCHAR(255) NOT NULL,
  `giftcard_user_email` VARCHAR(255) NOT NULL,
  `product_item_old_price` DECIMAL(10,4) NOT NULL,
  `product_purchase_price` DECIMAL(10,4) NOT NULL,
  `discount_calc_data` TEXT NOT NULL,
  PRIMARY KEY (`order_item_id`),
  INDEX `idx_order_id` (`order_id` ASC),
  INDEX `idx_user_info_id` (`user_info_id` ASC),
  INDEX `idx_product_id` (`product_id` ASC),
  INDEX `idx_order_status` (`order_status` ASC),
  INDEX `idx_cdate` (`cdate` ASC),
  INDEX `idx_is_giftcard` (`is_giftcard` ASC),
  INDEX `idx_product_quantity` USING BTREE (`product_id` ASC, `product_quantity` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order Item Detail';


-- -----------------------------------------------------
-- Table `#__redshop_order_payment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_payment` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_payment` (
  `payment_order_id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL DEFAULT '0',
  `payment_method_id` INT(11) NULL DEFAULT NULL,
  `order_payment_code` VARCHAR(30) NOT NULL DEFAULT '',
  `order_payment_cardname` BLOB NOT NULL,
  `order_payment_number` BLOB NULL DEFAULT NULL,
  `order_payment_ccv` BLOB NOT NULL,
  `order_payment_amount` DOUBLE(10,2) NOT NULL,
  `order_payment_expire` INT(11) NULL DEFAULT NULL,
  `order_payment_name` VARCHAR(255) NULL DEFAULT NULL,
  `payment_method_class` VARCHAR(256) NULL DEFAULT NULL,
  `order_payment_trans_id` TEXT NOT NULL,
  `authorize_status` VARCHAR(255) NULL DEFAULT NULL,
  `order_transfee` DOUBLE(10,2) NOT NULL,
  PRIMARY KEY (`payment_order_id`),
  INDEX `idx_order_id` (`order_id` ASC),
  INDEX `idx_payment_method_id` (`payment_method_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order Payment Detail';


-- -----------------------------------------------------
-- Table `#__redshop_order_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_status` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_status` (
  `order_status_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_status_code` VARCHAR(64) NOT NULL,
  `order_status_name` VARCHAR(64) NULL DEFAULT NULL,
  `published` TINYINT(4) NOT NULL DEFAULT 0,
  `checked_out` INT(11) NULL DEFAULT NULL,
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INT(11) NULL DEFAULT NULL,
  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` INT(11) NULL DEFAULT NULL,
  `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`order_status_id`),
  UNIQUE INDEX `#__rs_idx_order_status_code` (`order_status_code` ASC),
  INDEX `#__rs_idx_order_status_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Orders Status';


-- -----------------------------------------------------
-- Table `#__redshop_order_status_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_status_log` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_status_log` (
  `order_status_log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `order_status` VARCHAR(5) NOT NULL,
  `order_payment_status` VARCHAR(25) NOT NULL,
  `date_changed` INT(11) NOT NULL,
  `customer_note` TEXT NOT NULL,
  PRIMARY KEY (`order_status_log_id`),
  INDEX `idx_order_id` (`order_id` ASC),
  INDEX `idx_order_status` (`order_status` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Orders Status history';


-- -----------------------------------------------------
-- Table `#__redshop_order_users_info`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_users_info` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_users_info` (
  `order_info_id` INT(11) NOT NULL AUTO_INCREMENT,
  `users_info_id` INT(11) NOT NULL,
  `order_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `firstname` VARCHAR(250) NOT NULL,
  `lastname` VARCHAR(250) NOT NULL,
  `address_type` VARCHAR(255) NOT NULL,
  `vat_number` VARCHAR(250) NOT NULL,
  `tax_exempt` TINYINT(4) NOT NULL,
  `shopper_group_id` INT(11) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `city` VARCHAR(255) NOT NULL,
  `country_code` VARCHAR(11) NOT NULL,
  `state_code` VARCHAR(11) NOT NULL,
  `zipcode` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `tax_exempt_approved` TINYINT(1) NOT NULL,
  `approved` TINYINT(1) NOT NULL,
  `is_company` TINYINT(4) NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  `company_name` VARCHAR(255) NOT NULL,
  `ean_number` VARCHAR(250) NOT NULL,
  `requesting_tax_exempt` TINYINT(4) NOT NULL,
  `thirdparty_email` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`order_info_id`),
  INDEX `idx_order_id` (`order_id` ASC),
  INDEX `idx_address_type` (`address_type` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order User Information';


-- -----------------------------------------------------
-- Table `#__redshop_pageviewer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_pageviewer` ;

CREATE TABLE IF NOT EXISTS `#__redshop_pageviewer` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `session_id` VARCHAR(250) NOT NULL,
  `section` VARCHAR(250) NOT NULL,
  `section_id` INT(11) NOT NULL,
  `hit` INT(11) NOT NULL,
  `created_date` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_session_id` (`session_id` ASC),
  INDEX `idx_section` (`section` ASC),
  INDEX `idx_section_id` (`section_id` ASC),
  INDEX `idx_created_date` (`created_date` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Page Viewer';


-- -----------------------------------------------------
-- Table `#__redshop_product_accessory`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_accessory` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_accessory` (
  `accessory_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `child_product_id` INT(11) NOT NULL,
  `accessory_price` DOUBLE NOT NULL,
  `oprand` CHAR(1) NOT NULL,
  `setdefault_selected` TINYINT(4) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `category_id` INT(11) NOT NULL,
  PRIMARY KEY (`accessory_id`),
  INDEX `idx_common` (`product_id` ASC, `child_product_id` ASC),
  INDEX `idx_child_product_id` (`child_product_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products Accessory';


-- -----------------------------------------------------
-- Table `#__redshop_product_attribute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_attribute` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute` (
  `attribute_id` INT(11) NOT NULL AUTO_INCREMENT,
  `attribute_name` VARCHAR(250) NOT NULL,
  `attribute_required` TINYINT(4) NOT NULL,
  `allow_multiple_selection` TINYINT(1) NOT NULL,
  `hide_attribute_price` TINYINT(1) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `attribute_set_id` INT(11) NOT NULL,
  `display_type` VARCHAR(255) NOT NULL,
  `attribute_published` INT(11) NOT NULL DEFAULT '1',
  `attribute_description` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`attribute_id`),
  INDEX `idx_product_id` (`product_id` ASC),
  INDEX `idx_attribute_name` (`attribute_name` ASC),
  INDEX `idx_attribute_set_id` (`attribute_set_id` ASC),
  INDEX `idx_attribute_published` (`attribute_published` ASC),
  INDEX `idx_attribute_required` (`attribute_required` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products Attribute';


-- -----------------------------------------------------
-- Table `#__redshop_product_attribute_price`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_attribute_price` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_price` (
  `price_id` INT(11) NOT NULL AUTO_INCREMENT,
  `section_id` INT(11) NOT NULL,
  `section` VARCHAR(255) NOT NULL,
  `product_price` DOUBLE NOT NULL,
  `product_currency` VARCHAR(10) NOT NULL,
  `cdate` INT(11) NOT NULL,
  `shopper_group_id` INT(11) NOT NULL,
  `price_quantity_start` INT(11) NOT NULL,
  `price_quantity_end` BIGINT(20) NOT NULL,
  `discount_price` DOUBLE NOT NULL,
  `discount_start_date` INT(11) NOT NULL,
  `discount_end_date` INT(11) NOT NULL,
  PRIMARY KEY (`price_id`),
  INDEX `idx_shopper_group_id` (`shopper_group_id` ASC),
  INDEX `idx_common` (`section_id` ASC, `section` ASC, `price_quantity_start` ASC, `price_quantity_end` ASC, `shopper_group_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Attribute Price';


-- -----------------------------------------------------
-- Table `#__redshop_product_attribute_property`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_attribute_property` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_property` (
  `property_id` INT(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` INT(11) NOT NULL,
  `property_name` VARCHAR(255) NOT NULL,
  `property_price` DOUBLE NOT NULL,
  `oprand` CHAR(1) NOT NULL DEFAULT '+',
  `property_image` VARCHAR(255) NOT NULL,
  `property_main_image` VARCHAR(255) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `setdefault_selected` TINYINT(4) NOT NULL,
  `setrequire_selected` TINYINT(3) NOT NULL,
  `setmulti_selected` TINYINT(4) NOT NULL,
  `setdisplay_type` VARCHAR(255) NOT NULL,
  `extra_field` VARCHAR(250) NOT NULL,
  `property_published` INT(11) NOT NULL DEFAULT '1',
  `property_number` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`property_id`),
  INDEX `idx_attribute_id` (`attribute_id` ASC),
  INDEX `idx_setrequire_selected` (`setrequire_selected` ASC),
  INDEX `idx_property_published` (`property_published` ASC),
  INDEX `idx_property_number` (`property_number` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products Attribute Property';


-- -----------------------------------------------------
-- Table `#__redshop_product_attribute_stockroom_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_attribute_stockroom_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_stockroom_xref` (
  `section_id` INT(11) NOT NULL,
  `section` VARCHAR(255) NOT NULL,
  `stockroom_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `preorder_stock` INT(11) NOT NULL,
  `ordered_preorder` INT(11) NOT NULL,
  INDEX `idx_stockroom_id` (`stockroom_id` ASC),
  INDEX `idx_common` (`section_id` ASC, `section` ASC, `stockroom_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Attribute Stockroom relation';


-- -----------------------------------------------------
-- Table `#__redshop_product_category_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_category_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_category_xref` (
  `category_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `ordering` INT(11) NOT NULL,
  INDEX `ref_category` (`product_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Category Relation';


-- -----------------------------------------------------
-- Table `#__redshop_product_compare`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_compare` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_compare` (
  `compare_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  PRIMARY KEY (`compare_id`),
  INDEX `idx_common` (`user_id` ASC, `product_id` ASC),
  INDEX `idx_product_id` (`product_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Comparision';


-- -----------------------------------------------------
-- Table `#__redshop_product_discount_calc`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_discount_calc` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_discount_calc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `area_start` FLOAT(10,2) NOT NULL,
  `area_end` FLOAT(10,2) NOT NULL,
  `area_price` DOUBLE NOT NULL,
  `discount_calc_unit` VARCHAR(255) NOT NULL,
  `area_start_converted` FLOAT(20,8) NOT NULL,
  `area_end_converted` FLOAT(20,8) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_product_id` (`product_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Discount Calculator';


-- -----------------------------------------------------
-- Table `#__redshop_product_discount_calc_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_discount_calc_extra` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_discount_calc_extra` (
  `pdcextra_id` INT(11) NOT NULL AUTO_INCREMENT,
  `option_name` VARCHAR(255) NOT NULL,
  `oprand` CHAR(1) NOT NULL,
  `price` FLOAT(10,2) NOT NULL,
  `product_id` INT(11) NOT NULL,
  PRIMARY KEY (`pdcextra_id`),
  INDEX `idx_product_id` (`product_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Discount Calculator Extra Value';


-- -----------------------------------------------------
-- Table `#__redshop_product_download`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_download` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_download` (
  `product_id` INT(11) NOT NULL DEFAULT '0',
  `user_id` INT(11) NOT NULL DEFAULT '0',
  `order_id` INT(11) NOT NULL DEFAULT '0',
  `end_date` INT(11) NOT NULL DEFAULT '0',
  `download_max` INT(11) NOT NULL DEFAULT '0',
  `download_id` VARCHAR(255) NOT NULL DEFAULT '',
  `file_name` VARCHAR(255) NOT NULL DEFAULT '',
  `product_serial_number` VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`download_id`),
  INDEX `idx_product_id` (`product_id` ASC),
  INDEX `idx_user_id` (`user_id` ASC),
  INDEX `idx_order_id` (`order_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Downloadable Products';


-- -----------------------------------------------------
-- Table `#__redshop_product_download_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_download_log` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_download_log` (
  `user_id` INT(11) NOT NULL,
  `download_id` VARCHAR(32) NOT NULL,
  `download_time` INT(11) NOT NULL,
  `ip` VARCHAR(255) NOT NULL,
  INDEX `idx_download_id` (`download_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Downloadable Products Logs';


-- -----------------------------------------------------
-- Table `#__redshop_product_price`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_price` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_price` (
  `price_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `product_price` DECIMAL(12,4) NOT NULL,
  `product_currency` VARCHAR(10) NOT NULL,
  `cdate` DATE NOT NULL,
  `shopper_group_id` INT(11) NOT NULL,
  `price_quantity_start` INT(11) NOT NULL,
  `price_quantity_end` BIGINT(20) NOT NULL,
  `discount_price` DECIMAL(12,4) NOT NULL,
  `discount_start_date` INT(11) NOT NULL,
  `discount_end_date` INT(11) NOT NULL,
  PRIMARY KEY (`price_id`),
  INDEX `idx_product_id` (`product_id` ASC),
  INDEX `idx_shopper_group_id` (`shopper_group_id` ASC),
  INDEX `idx_price_quantity_start` (`price_quantity_start` ASC),
  INDEX `idx_price_quantity_end` (`price_quantity_end` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Price';


-- -----------------------------------------------------
-- Table `#__redshop_product_rating`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_rating` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_rating` (
  `rating_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL DEFAULT '0',
  `title` VARCHAR(255) NOT NULL,
  `comment` TEXT NOT NULL,
  `userid` INT(11) NOT NULL DEFAULT '0',
  `time` INT(11) NOT NULL DEFAULT '0',
  `user_rating` TINYINT(1) NOT NULL DEFAULT '0',
  `favoured` TINYINT(4) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `email` VARCHAR(200) NOT NULL,
  `username` VARCHAR(255) NOT NULL,
  `company_name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`rating_id`),
  UNIQUE INDEX `product_id` (`product_id` ASC, `userid` ASC, `email` ASC),
  INDEX `idx_published` (`published` ASC),
  INDEX `idx_email` (`email` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_product_related`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_related` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_related` (
  `related_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `ordering` INT(11) NOT NULL,
  INDEX `idx_product_id` (`product_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Related Products';


-- -----------------------------------------------------
-- Table `#__redshop_product_serial_number`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_serial_number` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_serial_number` (
  `serial_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `serial_number` VARCHAR(255) NOT NULL,
  `is_used` TINYINT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`serial_id`),
  INDEX `idx_common` (`product_id` ASC, `is_used` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP downloadable product serial numbers';


-- -----------------------------------------------------
-- Table `#__redshop_product_stockroom_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_stockroom_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_stockroom_xref` (
  `product_id` INT(11) NOT NULL,
  `stockroom_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `preorder_stock` INT(11) NOT NULL,
  `ordered_preorder` INT(11) NOT NULL,
  INDEX `idx_stockroom_id` (`stockroom_id` ASC),
  INDEX `idx_product_id` (`product_id` ASC),
  INDEX `idx_quantity` (`quantity` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products Stockroom Relation';


-- -----------------------------------------------------
-- Table `#__redshop_product_subattribute_color`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_subattribute_color` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_subattribute_color` (
  `subattribute_color_id` INT(11) NOT NULL AUTO_INCREMENT,
  `subattribute_color_name` VARCHAR(255) NOT NULL,
  `subattribute_color_price` DOUBLE NOT NULL,
  `oprand` CHAR(1) NOT NULL,
  `subattribute_color_image` VARCHAR(255) NOT NULL,
  `subattribute_id` INT(11) NOT NULL,
  `ordering` INT(11) NOT NULL,
  `setdefault_selected` TINYINT(4) NOT NULL,
  `extra_field` VARCHAR(250) NOT NULL,
  `subattribute_published` INT(11) NOT NULL DEFAULT '1',
  `subattribute_color_number` VARCHAR(255) NOT NULL,
  `subattribute_color_title` VARCHAR(255) NOT NULL,
  `subattribute_color_main_image` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`subattribute_color_id`),
  INDEX `idx_subattribute_id` (`subattribute_id` ASC),
  INDEX `idx_subattribute_published` (`subattribute_published` ASC),
  INDEX `#__rs_sub_prop_common` (`subattribute_id` ASC, `subattribute_published` ASC, `ordering` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Product Subattribute Color';


-- -----------------------------------------------------
-- Table `#__redshop_product_subscribe_detail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_subscribe_detail` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_subscribe_detail` (
  `product_subscribe_id` INT(11) NOT NULL AUTO_INCREMENT,
  `order_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `subscription_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `start_date` INT(11) NOT NULL,
  `end_date` INT(11) NOT NULL,
  `order_item_id` INT(11) NOT NULL,
  `renewal_reminder` TINYINT(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`product_subscribe_id`),
  INDEX `idx_common` (`product_id` ASC, `end_date` ASC),
  INDEX `idx_order_item_id` (`order_item_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP User product Subscribe detail';


-- -----------------------------------------------------
-- Table `#__redshop_product_subscription`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_subscription` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_subscription` (
  `subscription_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `subscription_period` INT(11) NOT NULL,
  `period_type` VARCHAR(10) NOT NULL,
  `subscription_price` DOUBLE NOT NULL,
  PRIMARY KEY (`subscription_id`),
  INDEX `idx_product_id` (`product_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Subscription';


-- -----------------------------------------------------
-- Table `#__redshop_product_tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_tags` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_tags` (
  `tags_id` INT(11) NOT NULL AUTO_INCREMENT,
  `tags_name` VARCHAR(255) NOT NULL,
  `tags_counter` INT(11) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`tags_id`),
  INDEX `idx_published` (`published` ASC),
  INDEX `idx_tags_name` (`tags_name` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Product Tags';


-- -----------------------------------------------------
-- Table `#__redshop_product_tags_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_tags_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_tags_xref` (
  `tags_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `users_id` INT(11) NOT NULL,
  INDEX `idx_product_id` (`product_id` ASC),
  INDEX `idx_users_id` (`users_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Product Tags Relation With product and user';


-- -----------------------------------------------------
-- Table `#__redshop_product_voucher`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_voucher` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher` (
  `voucher_id` INT(11) NOT NULL AUTO_INCREMENT,
  `voucher_code` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00',
  `voucher_type` VARCHAR(250) NOT NULL,
  `start_date` DOUBLE NOT NULL,
  `end_date` DOUBLE NOT NULL,
  `free_shipping` TINYINT(4) NOT NULL,
  `voucher_left` INT(11) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`voucher_id`),
  INDEX `idx_common` (`voucher_code` ASC, `published` ASC, `start_date` ASC, `end_date` ASC),
  INDEX `idx_voucher_left` (`voucher_left` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Voucher';


-- -----------------------------------------------------
-- Table `#__redshop_product_voucher_transaction`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_voucher_transaction` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher_transaction` (
  `transaction_voucher_id` INT(11) NOT NULL AUTO_INCREMENT,
  `voucher_id` INT(11) NOT NULL,
  `voucher_code` VARCHAR(255) NOT NULL,
  `amount` DECIMAL(10,3) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `order_id` INT(11) NOT NULL,
  `trancation_date` INT(11) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `product_id` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`transaction_voucher_id`),
  INDEX `idx_voucher_id` (`voucher_id` ASC),
  INDEX `idx_voucher_code` (`voucher_code` ASC),
  INDEX `idx_amount` (`amount` ASC),
  INDEX `idx_user_id` (`user_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Voucher Transaction';


-- -----------------------------------------------------
-- Table `#__redshop_product_voucher_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_voucher_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher_xref` (
  `voucher_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  INDEX `idx_common` (`voucher_id` ASC, `product_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products Voucher Relation';


-- -----------------------------------------------------
-- Table `#__redshop_quotation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_quotation` ;

CREATE TABLE IF NOT EXISTS `#__redshop_quotation` (
  `quotation_id` INT(11) NOT NULL AUTO_INCREMENT,
  `quotation_number` VARCHAR(50) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `user_info_id` INT(11) NOT NULL,
  `order_id` INT(11) NOT NULL,
  `quotation_total` DECIMAL(15,2) NOT NULL,
  `quotation_subtotal` DECIMAL(15,2) NOT NULL,
  `quotation_tax` DECIMAL(15,2) NOT NULL,
  `quotation_discount` DECIMAL(15,4) NOT NULL,
  `quotation_status` INT(11) NOT NULL,
  `quotation_cdate` INT(11) NOT NULL,
  `quotation_mdate` INT(11) NOT NULL,
  `quotation_note` TEXT NOT NULL,
  `quotation_customer_note` TEXT NOT NULL,
  `quotation_ipaddress` VARCHAR(20) NOT NULL,
  `quotation_encrkey` VARCHAR(255) NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  `quotation_special_discount` DECIMAL(15,4) NOT NULL,
  PRIMARY KEY (`quotation_id`),
  INDEX `idx_user_id` (`user_id` ASC),
  INDEX `idx_order_id` (`order_id` ASC),
  INDEX `idx_quotation_status` (`quotation_status` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Quotation';


-- -----------------------------------------------------
-- Table `#__redshop_quotation_accessory_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_quotation_accessory_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_accessory_item` (
  `quotation_item_acc_id` INT(11) NOT NULL AUTO_INCREMENT,
  `quotation_item_id` INT(11) NOT NULL,
  `accessory_id` INT(11) NOT NULL,
  `accessory_item_sku` VARCHAR(255) NOT NULL,
  `accessory_item_name` VARCHAR(255) NOT NULL,
  `accessory_price` DECIMAL(15,4) NOT NULL,
  `accessory_vat` DECIMAL(15,4) NOT NULL,
  `accessory_quantity` INT(11) NOT NULL,
  `accessory_item_price` DECIMAL(15,2) NOT NULL,
  `accessory_final_price` DECIMAL(15,2) NOT NULL,
  `accessory_attribute` TEXT NOT NULL,
  PRIMARY KEY (`quotation_item_acc_id`),
  INDEX `idx_quotation_item_id` (`quotation_item_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Quotation Accessory item';


-- -----------------------------------------------------
-- Table `#__redshop_quotation_attribute_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_quotation_attribute_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_attribute_item` (
  `quotation_att_item_id` INT(11) NOT NULL AUTO_INCREMENT,
  `quotation_item_id` INT(11) NOT NULL,
  `section_id` INT(11) NOT NULL,
  `section` VARCHAR(250) NOT NULL,
  `parent_section_id` INT(11) NOT NULL,
  `section_name` VARCHAR(250) NOT NULL,
  `section_price` DECIMAL(15,4) NOT NULL,
  `section_vat` DECIMAL(15,4) NOT NULL,
  `section_oprand` CHAR(1) NOT NULL,
  `is_accessory_att` TINYINT(4) NOT NULL,
  PRIMARY KEY (`quotation_att_item_id`),
  INDEX `idx_quotation_item_id` (`quotation_item_id` ASC),
  INDEX `idx_section` (`section` ASC),
  INDEX `idx_parent_section_id` (`parent_section_id` ASC),
  INDEX `idx_is_accessory_att` (`is_accessory_att` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Quotation Attribute item';


-- -----------------------------------------------------
-- Table `#__redshop_quotation_fields_data`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_quotation_fields_data` ;

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_fields_data` (
  `data_id` INT(11) NOT NULL AUTO_INCREMENT,
  `fieldid` INT(11) NULL DEFAULT NULL,
  `data_txt` LONGTEXT NULL DEFAULT NULL,
  `quotation_item_id` INT(11) NULL DEFAULT NULL,
  `section` VARCHAR(20) NULL DEFAULT NULL,
  PRIMARY KEY (`data_id`),
  INDEX `quotation_item_id` (`quotation_item_id` ASC),
  INDEX `idx_fieldid` (`fieldid` ASC),
  INDEX `idx_quotation_item_id` (`quotation_item_id` ASC),
  INDEX `idx_section` (`section` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Quotation USer field';


-- -----------------------------------------------------
-- Table `#__redshop_quotation_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_quotation_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_item` (
  `quotation_item_id` INT(11) NOT NULL AUTO_INCREMENT,
  `quotation_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `product_price` DECIMAL(15,4) NOT NULL,
  `product_excl_price` DECIMAL(15,4) NOT NULL,
  `product_final_price` DECIMAL(15,4) NOT NULL,
  `actualitem_price` DECIMAL(15,4) NOT NULL,
  `product_quantity` INT(11) NOT NULL,
  `product_attribute` TEXT NOT NULL,
  `product_accessory` TEXT NOT NULL,
  `mycart_accessory` TEXT NOT NULL,
  `product_wrapperid` INT(11) NOT NULL,
  `wrapper_price` DECIMAL(15,2) NOT NULL,
  `is_giftcard` TINYINT(4) NOT NULL,
  PRIMARY KEY (`quotation_item_id`),
  INDEX `quotation_id` (`quotation_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Quotation Item';


-- -----------------------------------------------------
-- Table `#__redshop_sample_request`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_sample_request` ;

CREATE TABLE IF NOT EXISTS `#__redshop_sample_request` (
  `request_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(250) NOT NULL,
  `email` VARCHAR(250) NOT NULL,
  `colour_id` VARCHAR(250) NOT NULL,
  `block` TINYINT(4) NOT NULL,
  `reminder_1` TINYINT(1) NOT NULL,
  `reminder_2` TINYINT(1) NOT NULL,
  `reminder_3` TINYINT(1) NOT NULL,
  `reminder_coupon` TINYINT(1) NOT NULL,
  `registerdate` INT(11) NOT NULL,
  PRIMARY KEY (`request_id`),
  INDEX `idx_block` (`block` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Sample Request';


-- -----------------------------------------------------
-- Table `#__redshop_shipping_boxes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_shipping_boxes` ;

CREATE TABLE IF NOT EXISTS `#__redshop_shipping_boxes` (
  `shipping_box_id` INT(11) NOT NULL AUTO_INCREMENT,
  `shipping_box_name` VARCHAR(255) NOT NULL,
  `shipping_box_length` DECIMAL(10,2) NOT NULL,
  `shipping_box_width` DECIMAL(10,2) NOT NULL,
  `shipping_box_height` DECIMAL(10,2) NOT NULL,
  `shipping_box_priority` INT(11) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`shipping_box_id`),
  INDEX `idx_published` (`published` ASC),
  INDEX `idx_common` (`shipping_box_length` ASC, `shipping_box_width` ASC, `shipping_box_height` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Shipping Boxes';


-- -----------------------------------------------------
-- Table `#__redshop_shipping_rate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_shipping_rate` ;

CREATE TABLE IF NOT EXISTS `#__redshop_shipping_rate` (
  `shipping_rate_id` INT(11) NOT NULL AUTO_INCREMENT,
  `shipping_rate_name` VARCHAR(255) NOT NULL DEFAULT '',
  `shipping_class` VARCHAR(255) NOT NULL DEFAULT '',
  `shipping_rate_country` LONGTEXT NOT NULL,
  `shipping_rate_zip_start` VARCHAR(20) NOT NULL,
  `shipping_rate_zip_end` VARCHAR(20) NOT NULL,
  `shipping_rate_weight_start` DECIMAL(10,2) NOT NULL,
  `company_only` TINYINT(4) NOT NULL,
  `apply_vat` TINYINT(4) NOT NULL,
  `shipping_rate_weight_end` DECIMAL(10,2) NOT NULL,
  `shipping_rate_volume_start` DECIMAL(10,2) NOT NULL,
  `shipping_rate_volume_end` DECIMAL(10,2) NOT NULL,
  `shipping_rate_ordertotal_start` DECIMAL(10,3) NOT NULL DEFAULT '0.000',
  `shipping_rate_ordertotal_end` DECIMAL(10,3) NOT NULL,
  `shipping_rate_priority` TINYINT(4) NOT NULL DEFAULT '0',
  `shipping_rate_value` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `shipping_rate_package_fee` DECIMAL(10,2) NOT NULL DEFAULT '0.00',
  `shipping_location_info` LONGTEXT NOT NULL,
  `shipping_rate_length_start` DECIMAL(10,2) NOT NULL,
  `shipping_rate_length_end` DECIMAL(10,2) NOT NULL,
  `shipping_rate_width_start` DECIMAL(10,2) NOT NULL,
  `shipping_rate_width_end` DECIMAL(10,2) NOT NULL,
  `shipping_rate_height_start` DECIMAL(10,2) NOT NULL,
  `shipping_rate_height_end` DECIMAL(10,2) NOT NULL,
  `shipping_rate_on_shopper_group` LONGTEXT NOT NULL,
  `consignor_carrier_code` VARCHAR(255) NOT NULL,
  `shipping_tax_group_id` INT(11) NOT NULL,
  `deliver_type` INT(11) NOT NULL,
  `economic_displaynumber` VARCHAR(255) NOT NULL,
  `shipping_rate_on_product` LONGTEXT NOT NULL,
  `shipping_rate_on_category` LONGTEXT NOT NULL,
  `shipping_rate_state` LONGTEXT NOT NULL,
  PRIMARY KEY (`shipping_rate_id`),
  INDEX `shipping_rate_name` (`shipping_rate_name` ASC),
  INDEX `shipping_class` (`shipping_class` ASC),
  INDEX `shipping_rate_zip_start` (`shipping_rate_zip_start` ASC),
  INDEX `shipping_rate_zip_end` (`shipping_rate_zip_end` ASC),
  INDEX `company_only` (`company_only` ASC),
  INDEX `shipping_rate_value` (`shipping_rate_value` ASC),
  INDEX `shipping_tax_group_id` (`shipping_tax_group_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Shipping Rates';


-- -----------------------------------------------------
-- Table `#__redshop_shopper_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_shopper_group` ;

CREATE TABLE IF NOT EXISTS `#__redshop_shopper_group` (
  `shopper_group_id` INT(11) NOT NULL AUTO_INCREMENT,
  `shopper_group_name` VARCHAR(32) NULL DEFAULT NULL,
  `shopper_group_customer_type` TINYINT(4) NOT NULL,
  `shopper_group_portal` TINYINT(4) NOT NULL,
  `shopper_group_categories` LONGTEXT NOT NULL,
  `shopper_group_url` VARCHAR(255) NOT NULL,
  `shopper_group_logo` VARCHAR(255) NOT NULL,
  `shopper_group_introtext` LONGTEXT NOT NULL,
  `shopper_group_desc` TEXT NULL DEFAULT NULL,
  `parent_id` INT(11) NOT NULL,
  `default_shipping` TINYINT(4) NOT NULL,
  `default_shipping_rate` FLOAT(10,2) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `shopper_group_cart_checkout_itemid` INT(11) NOT NULL,
  `shopper_group_cart_itemid` INT(11) NOT NULL,
  `shopper_group_quotation_mode` TINYINT(4) NOT NULL,
  `show_price_without_vat` TINYINT(4) NOT NULL,
  `tax_group_id` INT(11) NOT NULL,
  `apply_product_price_vat` INT(11) NOT NULL,
  `show_price` VARCHAR(255) NOT NULL DEFAULT 'global',
  `use_as_catalog` VARCHAR(255) NOT NULL DEFAULT 'global',
  `is_logged_in` INT(11) NOT NULL DEFAULT '1',
  `shopper_group_manufactures` TEXT NOT NULL,
  PRIMARY KEY (`shopper_group_id`),
  INDEX `idx_shopper_group_name` (`shopper_group_name` ASC),
  INDEX `idx_published` (`published` ASC),
  INDEX `idx_parent_id` (`parent_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Shopper Groups that users can be assigned to';


-- -----------------------------------------------------
-- Table `#__redshop_siteviewer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_siteviewer` ;

CREATE TABLE IF NOT EXISTS `#__redshop_siteviewer` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `session_id` VARCHAR(250) NOT NULL,
  `created_date` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `idx_session_id` (`session_id` ASC),
  INDEX `idx_created_date` (`created_date` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Site Viewer';


-- -----------------------------------------------------
-- Table `#__redshop_state`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_state` ;

CREATE TABLE IF NOT EXISTS `#__redshop_state` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `country_id` INT(11) NULL DEFAULT NULL,
  `state_name` VARCHAR(64) NULL DEFAULT NULL,
  `state_3_code` CHAR(3) NULL DEFAULT NULL,
  `state_2_code` CHAR(2) NULL DEFAULT NULL,
  `checked_out` INT(11) NOT NULL,
  `checked_out_time` DATETIME NOT NULL,
  `show_state` INT(11) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `#__rs_idx_state_3_code` (`country_id` ASC, `state_3_code` ASC),
  UNIQUE INDEX `#__rs_idx_state_2_code` (`country_id` ASC, `state_2_code` ASC),
  INDEX `#__rs_state_country_fk1` (`country_id` ASC),
  CONSTRAINT `#__rs_state_country_fk1`
    FOREIGN KEY (`country_id`)
    REFERENCES `#__redshop_country` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'States that are assigned to a country';


-- -----------------------------------------------------
-- Table `#__redshop_stockroom`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_stockroom` ;

CREATE TABLE IF NOT EXISTS `#__redshop_stockroom` (
  `stockroom_id` INT(11) NOT NULL AUTO_INCREMENT,
  `stockroom_name` VARCHAR(250) NOT NULL,
  `min_stock_amount` INT(11) NOT NULL,
  `stockroom_desc` LONGTEXT NOT NULL,
  `creation_date` DOUBLE NOT NULL,
  `min_del_time` INT(11) NOT NULL,
  `max_del_time` INT(11) NOT NULL,
  `show_in_front` TINYINT(1) NOT NULL,
  `delivery_time` VARCHAR(255) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`stockroom_id`),
  INDEX `idx_published` (`published` ASC),
  INDEX `idx_min_del_time` (`min_del_time` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Stockroom';


-- -----------------------------------------------------
-- Table `#__redshop_stockroom_amount_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_stockroom_amount_image` ;

CREATE TABLE IF NOT EXISTS `#__redshop_stockroom_amount_image` (
  `stock_amount_id` INT(11) NOT NULL AUTO_INCREMENT,
  `stockroom_id` INT(11) NOT NULL,
  `stock_option` TINYINT(4) NOT NULL,
  `stock_quantity` INT(11) NOT NULL,
  `stock_amount_image` VARCHAR(255) NOT NULL,
  `stock_amount_image_tooltip` TEXT NOT NULL,
  PRIMARY KEY (`stock_amount_id`),
  INDEX `idx_stockroom_id` (`stockroom_id` ASC),
  INDEX `idx_stock_option` (`stock_option` ASC),
  INDEX `idx_stock_quantity` (`stock_quantity` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP stockroom amount image';


-- -----------------------------------------------------
-- Table `#__redshop_subscription_renewal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_subscription_renewal` ;

CREATE TABLE IF NOT EXISTS `#__redshop_subscription_renewal` (
  `renewal_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` INT(11) NOT NULL,
  `before_no_days` INT(11) NOT NULL,
  PRIMARY KEY (`renewal_id`),
  INDEX `idx_common` (`product_id` ASC, `before_no_days` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Subscription Renewal';


-- -----------------------------------------------------
-- Table `#__redshop_supplier`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_supplier` ;

CREATE TABLE IF NOT EXISTS `#__redshop_supplier` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL DEFAULT '',
  `description` TEXT NOT NULL DEFAULT '',
  `email` VARCHAR(255) NOT NULL DEFAULT '',
  `published` TINYINT(4) NOT NULL DEFAULT 0,
  `checked_out` INT(11) NULL DEFAULT NULL,
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INT(11) NULL DEFAULT NULL,
  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` INT(11) NULL DEFAULT NULL,
  `modified_date` VARCHAR(45) NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX `#__rs_idx_supplier_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Supplier';


-- -----------------------------------------------------
-- Table `#__redshop_tax_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_tax_group` ;

CREATE TABLE IF NOT EXISTS `#__redshop_tax_group` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `checked_out` INT(11) NULL DEFAULT NULL,
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INT(11) NULL DEFAULT NULL,
  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` INT(11) NULL DEFAULT NULL,
  `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Tax Group';


-- -----------------------------------------------------
-- Table `#__redshop_tax_rate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_tax_rate` ;

CREATE TABLE IF NOT EXISTS `#__redshop_tax_rate` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL DEFAULT '',
  `tax_state` VARCHAR(64) NULL DEFAULT NULL,
  `tax_country` VARCHAR(64) NULL DEFAULT NULL,
  `mdate` INT(11) NULL DEFAULT NULL,
  `tax_rate` DECIMAL(10,4) NULL DEFAULT NULL,
  `tax_group_id` INT(11) NOT NULL,
  `is_eu_country` TINYINT(4) NOT NULL,
  `checked_out` INT(11) NULL DEFAULT NULL,
  `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` INT(11) NULL DEFAULT NULL,
  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX `idx_tax_group_id` (`tax_group_id` ASC),
  INDEX `idx_tax_country` (`tax_country` ASC),
  INDEX `idx_tax_state` (`tax_state` ASC),
  INDEX `idx_is_eu_country` (`is_eu_country` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Tax Rates';


-- -----------------------------------------------------
-- Table `#__redshop_template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_template` ;

CREATE TABLE IF NOT EXISTS `#__redshop_template` (
  `template_id` INT(11) NOT NULL AUTO_INCREMENT,
  `template_name` VARCHAR(250) NOT NULL,
  `template_section` VARCHAR(250) NOT NULL,
  `template_desc` LONGTEXT NOT NULL,
  `order_status` VARCHAR(250) NOT NULL,
  `payment_methods` VARCHAR(250) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `shipping_methods` VARCHAR(255) NOT NULL,
  `checked_out` INT(11) NOT NULL,
  `checked_out_time` DATETIME NOT NULL,
  PRIMARY KEY (`template_id`),
  INDEX `idx_template_section` (`template_section` ASC),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Templates Detail';


-- -----------------------------------------------------
-- Table `#__redshop_textlibrary`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_textlibrary` ;

CREATE TABLE IF NOT EXISTS `#__redshop_textlibrary` (
  `textlibrary_id` INT(11) NOT NULL AUTO_INCREMENT,
  `text_name` VARCHAR(255) NULL DEFAULT NULL,
  `text_desc` VARCHAR(255) NULL DEFAULT NULL,
  `text_field` TEXT NULL DEFAULT NULL,
  `section` VARCHAR(255) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`textlibrary_id`),
  INDEX `idx_section` (`section` ASC),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP TextLibrary';


-- -----------------------------------------------------
-- Table `#__redshop_usercart`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_usercart` ;

CREATE TABLE IF NOT EXISTS `#__redshop_usercart` (
  `cart_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `cdate` INT(11) NOT NULL,
  `mdate` INT(11) NOT NULL,
  PRIMARY KEY (`cart_id`),
  INDEX `idx_user_id` (`user_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP User Cart Item';


-- -----------------------------------------------------
-- Table `#__redshop_usercart_accessory_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_usercart_accessory_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_accessory_item` (
  `cart_acc_item_id` INT(11) NOT NULL AUTO_INCREMENT,
  `cart_item_id` INT(11) NOT NULL,
  `accessory_id` INT(11) NOT NULL,
  `accessory_quantity` INT(11) NOT NULL,
  PRIMARY KEY (`cart_acc_item_id`),
  INDEX `idx_cart_item_id` (`cart_item_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP User Cart Accessory Item';


-- -----------------------------------------------------
-- Table `#__redshop_usercart_attribute_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_usercart_attribute_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_attribute_item` (
  `cart_att_item_id` INT(11) NOT NULL AUTO_INCREMENT,
  `cart_item_id` INT(11) NOT NULL,
  `section_id` INT(11) NOT NULL,
  `section` VARCHAR(25) NOT NULL,
  `parent_section_id` INT(11) NOT NULL,
  `is_accessory_att` TINYINT(4) NOT NULL,
  PRIMARY KEY (`cart_att_item_id`),
  INDEX `idx_common` (`is_accessory_att` ASC, `section` ASC, `parent_section_id` ASC, `cart_item_id` ASC),
  INDEX `idx_cart_item_id` (`cart_item_id` ASC),
  INDEX `idx_parent_section_id` (`parent_section_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP User cart Attribute Item';


-- -----------------------------------------------------
-- Table `#__redshop_usercart_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_usercart_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_item` (
  `cart_item_id` INT(11) NOT NULL AUTO_INCREMENT,
  `cart_idx` INT(11) NOT NULL,
  `cart_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `product_quantity` INT(11) NOT NULL,
  `product_wrapper_id` INT(11) NOT NULL,
  `product_subscription_id` INT(11) NOT NULL,
  `giftcard_id` INT(11) NOT NULL,
  `attribs` VARCHAR(5120) NOT NULL COMMENT 'Specified user attributes related with current item',
  PRIMARY KEY (`cart_item_id`),
  INDEX `idx_cart_id` (`cart_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP User Cart Item';


-- -----------------------------------------------------
-- Table `#__redshop_users_info`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_users_info` ;

CREATE TABLE IF NOT EXISTS `#__redshop_users_info` (
  `users_info_id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `user_email` VARCHAR(255) NOT NULL,
  `address_type` VARCHAR(11) NOT NULL,
  `firstname` VARCHAR(250) NOT NULL,
  `lastname` VARCHAR(250) NOT NULL,
  `vat_number` VARCHAR(250) NOT NULL,
  `tax_exempt` TINYINT(4) NOT NULL,
  `shopper_group_id` INT(11) NOT NULL,
  `country_code` VARCHAR(11) NOT NULL,
  `address` VARCHAR(255) NOT NULL,
  `city` VARCHAR(50) NOT NULL,
  `state_code` VARCHAR(11) NOT NULL,
  `zipcode` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(50) NOT NULL,
  `tax_exempt_approved` TINYINT(1) NOT NULL,
  `approved` TINYINT(1) NOT NULL,
  `is_company` TINYINT(4) NOT NULL,
  `ean_number` VARCHAR(250) NOT NULL,
  `braintree_vault_number` VARCHAR(255) NOT NULL,
  `veis_vat_number` VARCHAR(255) NOT NULL,
  `veis_status` VARCHAR(255) NOT NULL,
  `company_name` VARCHAR(255) NOT NULL,
  `requesting_tax_exempt` TINYINT(4) NOT NULL,
  `accept_terms_conditions` TINYINT(4) NOT NULL,
  PRIMARY KEY (`users_info_id`),
  INDEX `idx_common` (`address_type` ASC, `user_id` ASC),
  INDEX `user_id` (`user_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Users Information';


-- -----------------------------------------------------
-- Table `#__redshop_wishlist`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_wishlist` ;

CREATE TABLE IF NOT EXISTS `#__redshop_wishlist` (
  `wishlist_id` INT(11) NOT NULL AUTO_INCREMENT,
  `wishlist_name` VARCHAR(100) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `comment` MEDIUMTEXT NOT NULL,
  `cdate` DOUBLE NOT NULL,
  PRIMARY KEY (`wishlist_id`),
  INDEX `idx_user_id` (`user_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP wishlist';


-- -----------------------------------------------------
-- Table `#__redshop_wishlist_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_wishlist_product` ;

CREATE TABLE IF NOT EXISTS `#__redshop_wishlist_product` (
  `wishlist_product_id` INT(11) NOT NULL AUTO_INCREMENT,
  `wishlist_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `cdate` INT(11) NOT NULL,
  PRIMARY KEY (`wishlist_product_id`),
  INDEX `idx_wishlist_id` (`wishlist_id` ASC),
  INDEX `idx_common` (`product_id` ASC, `wishlist_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Wishlist Product';


-- -----------------------------------------------------
-- Table `#__redshop_wishlist_userfielddata`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_wishlist_userfielddata` ;

CREATE TABLE IF NOT EXISTS `#__redshop_wishlist_userfielddata` (
  `fieldid` INT(11) NOT NULL AUTO_INCREMENT,
  `wishlist_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `userfielddata` TEXT NOT NULL,
  PRIMARY KEY (`fieldid`),
  INDEX `idx_common` (`wishlist_id` ASC, `product_id` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Wishlist Product userfielddata';


-- -----------------------------------------------------
-- Table `#__redshop_wrapper`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_wrapper` ;

CREATE TABLE IF NOT EXISTS `#__redshop_wrapper` (
  `wrapper_id` INT(11) NOT NULL AUTO_INCREMENT,
  `product_id` VARCHAR(255) NOT NULL,
  `category_id` VARCHAR(250) NOT NULL,
  `wrapper_name` VARCHAR(255) NOT NULL,
  `wrapper_price` DOUBLE NOT NULL,
  `wrapper_image` VARCHAR(255) NOT NULL,
  `createdate` INT(11) NOT NULL,
  `wrapper_use_to_all` TINYINT(4) NOT NULL,
  `published` TINYINT(4) NOT NULL,
  PRIMARY KEY (`wrapper_id`),
  INDEX `idx_wrapper_use_to_all` (`wrapper_use_to_all` ASC),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Wrapper';


-- -----------------------------------------------------
-- Table `#__redshop_xml_export`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_xml_export` ;

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export` (
  `xmlexport_id` INT(11) NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(255) NOT NULL,
  `display_filename` VARCHAR(255) NOT NULL,
  `parent_name` VARCHAR(255) NOT NULL,
  `section_type` VARCHAR(255) NOT NULL,
  `auto_sync` TINYINT(4) NOT NULL,
  `sync_on_request` TINYINT(4) NOT NULL,
  `auto_sync_interval` INT(11) NOT NULL,
  `xmlexport_date` INT(11) NOT NULL,
  `xmlexport_filetag` TEXT NOT NULL,
  `element_name` VARCHAR(255) NULL DEFAULT NULL,
  `published` TINYINT(4) NOT NULL,
  `use_to_all_users` TINYINT(4) NOT NULL,
  `xmlexport_billingtag` TEXT NOT NULL,
  `billing_element_name` VARCHAR(255) NOT NULL,
  `xmlexport_shippingtag` TEXT NOT NULL,
  `shipping_element_name` VARCHAR(255) NOT NULL,
  `xmlexport_orderitemtag` TEXT NOT NULL,
  `orderitem_element_name` VARCHAR(255) NOT NULL,
  `xmlexport_stocktag` TEXT NOT NULL,
  `stock_element_name` VARCHAR(255) NOT NULL,
  `xmlexport_prdextrafieldtag` TEXT NOT NULL,
  `prdextrafield_element_name` VARCHAR(255) NOT NULL,
  `xmlexport_on_category` TEXT NOT NULL,
  PRIMARY KEY (`xmlexport_id`),
  INDEX `idx_filename` (`filename` ASC),
  INDEX `idx_auto_sync` (`auto_sync` ASC),
  INDEX `idx_sync_on_request` (`sync_on_request` ASC),
  INDEX `idx_auto_sync_interval` (`auto_sync_interval` ASC),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP XML Export';


-- -----------------------------------------------------
-- Table `#__redshop_xml_export_ipaddress`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_xml_export_ipaddress` ;

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export_ipaddress` (
  `xmlexport_ip_id` INT(11) NOT NULL AUTO_INCREMENT,
  `xmlexport_id` INT(11) NOT NULL,
  `access_ipaddress` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`xmlexport_ip_id`),
  INDEX `idx_xmlexport_id` (`xmlexport_id` ASC),
  INDEX `idx_access_ipaddress` (`access_ipaddress` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP XML Export Ip Address';


-- -----------------------------------------------------
-- Table `#__redshop_xml_export_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_xml_export_log` ;

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export_log` (
  `xmlexport_log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `xmlexport_id` INT(11) NOT NULL,
  `xmlexport_filename` VARCHAR(255) NOT NULL,
  `xmlexport_date` INT(11) NOT NULL,
  PRIMARY KEY (`xmlexport_log_id`),
  INDEX `idx_xmlexport_id` (`xmlexport_id` ASC),
  INDEX `idx_xmlexport_filename` (`xmlexport_filename` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP XML Export log';


-- -----------------------------------------------------
-- Table `#__redshop_xml_import`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_xml_import` ;

CREATE TABLE IF NOT EXISTS `#__redshop_xml_import` (
  `xmlimport_id` INT(11) NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(255) NOT NULL,
  `display_filename` VARCHAR(255) NOT NULL,
  `xmlimport_url` VARCHAR(255) NOT NULL,
  `section_type` VARCHAR(255) NOT NULL,
  `auto_sync` TINYINT(4) NOT NULL,
  `sync_on_request` TINYINT(4) NOT NULL,
  `auto_sync_interval` INT(11) NOT NULL,
  `override_existing` TINYINT(4) NOT NULL,
  `add_prefix_for_existing` VARCHAR(50) NOT NULL,
  `xmlimport_date` INT(11) NOT NULL,
  `xmlimport_filetag` TEXT NOT NULL,
  `xmlimport_billingtag` TEXT NOT NULL,
  `xmlimport_shippingtag` TEXT NOT NULL,
  `xmlimport_orderitemtag` TEXT NOT NULL,
  `xmlimport_stocktag` TEXT NOT NULL,
  `xmlimport_prdextrafieldtag` TEXT NOT NULL,
  `published` TINYINT(4) NOT NULL,
  `element_name` VARCHAR(255) NOT NULL,
  `billing_element_name` VARCHAR(255) NOT NULL,
  `shipping_element_name` VARCHAR(255) NOT NULL,
  `orderitem_element_name` VARCHAR(255) NOT NULL,
  `stock_element_name` VARCHAR(255) NOT NULL,
  `prdextrafield_element_name` VARCHAR(255) NOT NULL,
  `xmlexport_billingtag` TEXT NOT NULL,
  `xmlexport_shippingtag` TEXT NOT NULL,
  `xmlexport_orderitemtag` TEXT NOT NULL,
  PRIMARY KEY (`xmlimport_id`),
  INDEX `idx_auto_sync` (`auto_sync` ASC),
  INDEX `idx_sync_on_request` (`sync_on_request` ASC),
  INDEX `idx_auto_sync_interval` (`auto_sync_interval` ASC),
  INDEX `idx_published` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP XML Import';


-- -----------------------------------------------------
-- Table `#__redshop_xml_import_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_xml_import_log` ;

CREATE TABLE IF NOT EXISTS `#__redshop_xml_import_log` (
  `xmlimport_log_id` INT(11) NOT NULL AUTO_INCREMENT,
  `xmlimport_id` INT(11) NOT NULL,
  `xmlimport_filename` VARCHAR(255) NOT NULL,
  `xmlimport_date` INT(11) NOT NULL,
  PRIMARY KEY (`xmlimport_log_id`),
  INDEX `idx_xmlimport_id` (`xmlimport_id` ASC))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP XML Import log';


-- -----------------------------------------------------
-- Table `#__redshop_zipcode`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_zipcode` ;

CREATE TABLE IF NOT EXISTS `#__redshop_zipcode` (
  `zipcode_id` INT(11) NOT NULL AUTO_INCREMENT,
  `country_code` VARCHAR(10) NOT NULL DEFAULT '',
  `state_code` VARCHAR(10) NOT NULL DEFAULT '',
  `city_name` VARCHAR(64) NULL DEFAULT NULL,
  `zipcode` VARCHAR(255) NULL DEFAULT NULL,
  `zipcodeto` VARCHAR(255) NULL DEFAULT NULL,
  PRIMARY KEY (`zipcode_id`),
  INDEX `zipcode` (`zipcode` ASC),
  INDEX `idx_country_code` (`country_code` ASC),
  INDEX `idx_state_code` (`state_code` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_alerts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_alerts` ;

CREATE TABLE IF NOT EXISTS `#__redshop_alerts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `message` VARCHAR(255) NOT NULL,
  `sent_date` DATETIME NOT NULL,
  `read` TINYINT(4) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Notification Alert';


-- -----------------------------------------------------
-- Table `#__redshop_wishlist_product_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_wishlist_product_item` ;

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
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Wishlist product item';

SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
