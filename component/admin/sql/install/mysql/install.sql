SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;

-- -----------------------------------------------------
-- Table `#__redshop_accessmanager`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_accessmanager` ;

CREATE TABLE IF NOT EXISTS `#__redshop_accessmanager` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `section_name` VARCHAR(256) NOT NULL COMMENT '',
  `gid` INT(11) NOT NULL COMMENT '',
  `view` ENUM('1', '0') NULL DEFAULT NULL COMMENT '',
  `add` ENUM('1', '0') NULL DEFAULT NULL COMMENT '',
  `edit` ENUM('1', '0') NULL DEFAULT NULL COMMENT '',
  `delete` ENUM('1', '0') NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `idx_section_name` (`section_name`(255) ASC)  COMMENT '',
  INDEX `idx_gid` (`gid` ASC)  COMMENT '',
  INDEX `idx_view` (`view` ASC)  COMMENT '',
  INDEX `idx_add` (`add` ASC)  COMMENT '',
  INDEX `idx_edit` (`edit` ASC)  COMMENT '',
  INDEX `idx_delete` (`delete` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Access Manager';


-- -----------------------------------------------------
-- Table `#__redshop_attribute_set`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_attribute_set` ;

CREATE TABLE IF NOT EXISTS `#__redshop_attribute_set` (
  `attribute_set_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `attribute_set_name` VARCHAR(255) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`attribute_set_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Attribute set detail';


-- -----------------------------------------------------
-- Table `#__redshop_cart`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_cart` ;

CREATE TABLE IF NOT EXISTS `#__redshop_cart` (
  `session_id` VARCHAR(255) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `section` VARCHAR(250) NOT NULL COMMENT '',
  `qty` INT(11) NOT NULL COMMENT '',
  `time` DOUBLE NOT NULL COMMENT '',
  INDEX `idx_session_id` (`session_id` ASC)  COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '',
  INDEX `idx_section` (`section` ASC)  COMMENT '',
  INDEX `idx_time` (`time` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Cart';


-- -----------------------------------------------------
-- Table `#__redshop_catalog`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_catalog` ;

CREATE TABLE IF NOT EXISTS `#__redshop_catalog` (
  `catalog_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `catalog_name` VARCHAR(250) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`catalog_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Catalog';


-- -----------------------------------------------------
-- Table `#__redshop_catalog_colour`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_catalog_colour` ;

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_colour` (
  `colour_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `sample_id` INT(11) NOT NULL COMMENT '',
  `code_image` VARCHAR(250) NOT NULL COMMENT '',
  `is_image` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`colour_id`)  COMMENT '',
  INDEX `idx_sample_id` (`sample_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Catalog Colour';


-- -----------------------------------------------------
-- Table `#__redshop_catalog_request`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_catalog_request` ;

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_request` (
  `catalog_user_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `catalog_id` INT(11) NOT NULL COMMENT '',
  `name` VARCHAR(250) NOT NULL COMMENT '',
  `email` VARCHAR(250) NOT NULL COMMENT '',
  `registerDate` INT(11) NOT NULL COMMENT '',
  `block` TINYINT(4) NOT NULL COMMENT '',
  `reminder_1` TINYINT(4) NOT NULL COMMENT '',
  `reminder_2` TINYINT(4) NOT NULL COMMENT '',
  `reminder_3` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`catalog_user_id`)  COMMENT '',
  INDEX `idx_block` (`block` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Catalog Request';


-- -----------------------------------------------------
-- Table `#__redshop_catalog_sample`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_catalog_sample` ;

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_sample` (
  `sample_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `sample_name` VARCHAR(100) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`sample_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Catalog Sample';


-- -----------------------------------------------------
-- Table `#__redshop_category`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_category` ;

CREATE TABLE IF NOT EXISTS `#__redshop_category` (
  `category_id` INT(11) NOT NULL COMMENT '',
  `category_name` VARCHAR(250) NOT NULL COMMENT '',
  `category_short_description` LONGTEXT NOT NULL COMMENT '',
  `category_description` LONGTEXT NOT NULL COMMENT '',
  `category_template` INT(11) NOT NULL COMMENT '',
  `category_more_template` VARCHAR(255) NOT NULL COMMENT '',
  `products_per_page` INT(11) NOT NULL COMMENT '',
  `category_thumb_image` VARCHAR(250) NOT NULL COMMENT '',
  `category_full_image` VARCHAR(250) NOT NULL COMMENT '',
  `metakey` VARCHAR(250) NOT NULL COMMENT '',
  `metadesc` LONGTEXT NOT NULL COMMENT '',
  `metalanguage_setting` TEXT NOT NULL COMMENT '',
  `metarobot_info` TEXT NOT NULL COMMENT '',
  `pagetitle` TEXT NOT NULL COMMENT '',
  `pageheading` LONGTEXT NOT NULL COMMENT '',
  `sef_url` TEXT NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `category_pdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '',
  `ordering` INT(11) NOT NULL COMMENT '',
  `canonical_url` TEXT NOT NULL COMMENT '',
  `category_back_full_image` VARCHAR(250) NOT NULL COMMENT '',
  `compare_template_id` VARCHAR(255) NOT NULL COMMENT '',
  `append_to_global_seo` ENUM('append', 'prepend', 'replace') NOT NULL DEFAULT 'append' COMMENT '',
  `asset_id` INT(10) NOT NULL COMMENT 'FK to the #__assets table.',
  PRIMARY KEY (`category_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Category';


-- -----------------------------------------------------
-- Table `#__redshop_category_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_category_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_category_xref` (
  `category_parent_id` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `category_child_id` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  INDEX `category_xref_category_parent_id` (`category_parent_id` ASC)  COMMENT '',
  INDEX `category_xref_category_child_id` (`category_child_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Category relation';


-- -----------------------------------------------------
-- Table `#__redshop_country`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_country` ;

CREATE TABLE IF NOT EXISTS `#__redshop_country` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`country_name` varchar(64) DEFAULT NULL,
	`country_3_code` char(3) DEFAULT NULL,
	`country_2_code` char(2) DEFAULT NULL,
	`country_jtext` varchar(255) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `idx_country_3_code` (`country_3_code`),
	KEY `idx_country_2_code` (`country_2_code`),
	KEY `id` (`id`)
) 
	ENGINE=InnoDB
	DEFAULT CHARSET=utf8 
	COMMENT='Country records';

-- -----------------------------------------------------
-- Table `#__redshop_coupons`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_coupons` ;

CREATE TABLE IF NOT EXISTS `#__redshop_coupons` (
  `coupon_id` INT(16) NOT NULL AUTO_INCREMENT COMMENT '',
  `coupon_code` VARCHAR(32) NOT NULL DEFAULT '' COMMENT '',
  `percent_or_total` TINYINT(4) NOT NULL COMMENT '',
  `coupon_value` DECIMAL(12,2) NOT NULL DEFAULT '0.00' COMMENT '',
  `start_date` DOUBLE NOT NULL COMMENT '',
  `end_date` DOUBLE NOT NULL COMMENT '',
  `coupon_type` TINYINT(4) NOT NULL COMMENT '0 - Global, 1 - User Specific',
  `userid` INT(11) NOT NULL COMMENT '',
  `coupon_left` INT(11) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `subtotal` INT(11) NOT NULL COMMENT '',
  `order_id` INT(11) NOT NULL COMMENT '',
  `free_shipping` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`coupon_id`)  COMMENT '',
  INDEX `idx_coupon_code` (`coupon_code` ASC)  COMMENT '',
  INDEX `idx_percent_or_total` (`percent_or_total` ASC)  COMMENT '',
  INDEX `idx_start_date` (`start_date` ASC)  COMMENT '',
  INDEX `idx_end_date` (`end_date` ASC)  COMMENT '',
  INDEX `idx_coupon_type` (`coupon_type` ASC)  COMMENT '',
  INDEX `idx_userid` (`userid` ASC)  COMMENT '',
  INDEX `idx_coupon_left` (`coupon_left` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '',
  INDEX `idx_subtotal` (`subtotal` ASC)  COMMENT '',
  INDEX `idx_order_id` (`order_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Coupons';


-- -----------------------------------------------------
-- Table `#__redshop_coupons_transaction`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_coupons_transaction` ;

CREATE TABLE IF NOT EXISTS `#__redshop_coupons_transaction` (
  `transaction_coupon_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `coupon_id` INT(11) NOT NULL COMMENT '',
  `coupon_code` VARCHAR(255) NOT NULL COMMENT '',
  `coupon_value` DECIMAL(10,3) NOT NULL COMMENT '',
  `userid` INT(11) NOT NULL COMMENT '',
  `trancation_date` INT(11) NOT NULL COMMENT '',
  `published` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`transaction_coupon_id`)  COMMENT '',
  INDEX `idx_coupon_id` (`coupon_id` ASC)  COMMENT '',
  INDEX `idx_coupon_code` (`coupon_code` ASC)  COMMENT '',
  INDEX `idx_coupon_value` (`coupon_value` ASC)  COMMENT '',
  INDEX `idx_userid` (`userid` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Coupons Transaction';


-- -----------------------------------------------------
-- Table `#__redshop_cron`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_cron` ;

CREATE TABLE IF NOT EXISTS `#__redshop_cron` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `date` DATE NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `idx_date` (`date` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Cron Job';


-- -----------------------------------------------------
-- Table `#__redshop_currency`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_currency` ;

CREATE TABLE IF NOT EXISTS `#__redshop_currency` (
  `currency_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `currency_name` VARCHAR(64) NULL DEFAULT NULL COMMENT '',
  `currency_code` CHAR(3) NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`currency_id`)  COMMENT '',
  INDEX `idx_currency_code` (`currency_code` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Currency Detail';


-- -----------------------------------------------------
-- Table `#__redshop_customer_question`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_customer_question` ;

CREATE TABLE IF NOT EXISTS `#__redshop_customer_question` (
  `question_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `parent_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `question` LONGTEXT NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `user_name` VARCHAR(255) NOT NULL COMMENT '',
  `user_email` VARCHAR(255) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `question_date` INT(11) NOT NULL COMMENT '',
  `ordering` INT(11) NOT NULL COMMENT '',
  `telephone` VARCHAR(50) NOT NULL COMMENT '',
  `address` VARCHAR(250) NOT NULL COMMENT '',
  PRIMARY KEY (`question_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '',
  INDEX `idx_parent_id` (`parent_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Customer Question';


-- -----------------------------------------------------
-- Table `#__redshop_discount`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_discount` ;

CREATE TABLE IF NOT EXISTS `#__redshop_discount` (
  `discount_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(250) NOT NULL COMMENT '',
  `amount` INT(11) NOT NULL COMMENT '',
  `condition` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '',
  `discount_amount` DECIMAL(10,4) NOT NULL COMMENT '',
  `discount_type` TINYINT(4) NOT NULL COMMENT '',
  `start_date` DOUBLE NOT NULL COMMENT '',
  `end_date` DOUBLE NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`discount_id`)  COMMENT '',
  INDEX `idx_start_date` (`start_date` ASC)  COMMENT '',
  INDEX `idx_end_date` (`end_date` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '',
  INDEX `idx_discount_name` (`name` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Discount';


-- -----------------------------------------------------
-- Table `#__redshop_discount_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_discount_product` ;

CREATE TABLE IF NOT EXISTS `#__redshop_discount_product` (
  `discount_product_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `amount` INT(11) NOT NULL COMMENT '',
  `condition` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '',
  `discount_amount` DECIMAL(10,2) NOT NULL COMMENT '',
  `discount_type` TINYINT(4) NOT NULL COMMENT '',
  `start_date` DOUBLE NOT NULL COMMENT '',
  `end_date` DOUBLE NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `category_ids` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`discount_product_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '',
  INDEX `idx_start_date` (`start_date` ASC)  COMMENT '',
  INDEX `idx_end_date` (`end_date` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_discount_product_shoppers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_discount_product_shoppers` ;

CREATE TABLE IF NOT EXISTS `#__redshop_discount_product_shoppers` (
  `discount_product_id` INT(11) NOT NULL COMMENT '',
  `shopper_group_id` INT(11) NOT NULL COMMENT '',
  INDEX `idx_discount_product_id` (`discount_product_id` ASC)  COMMENT '',
  INDEX `idx_shopper_group_id` (`shopper_group_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_discount_shoppers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_discount_shoppers` ;

CREATE TABLE IF NOT EXISTS `#__redshop_discount_shoppers` (
  `discount_id` INT(11) NOT NULL COMMENT '',
  `shopper_group_id` INT(11) NOT NULL COMMENT '',
  INDEX `idx_discount_id` (`discount_id` ASC)  COMMENT '',
  INDEX `idx_shopper_group_id` (`shopper_group_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_economic_accountgroup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_economic_accountgroup` ;

CREATE TABLE IF NOT EXISTS `#__redshop_economic_accountgroup` (
  `accountgroup_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `accountgroup_name` VARCHAR(255) NOT NULL COMMENT '',
  `economic_vat_account` VARCHAR(255) NOT NULL COMMENT '',
  `economic_nonvat_account` VARCHAR(255) NOT NULL COMMENT '',
  `economic_discount_nonvat_account` VARCHAR(255) NOT NULL COMMENT '',
  `economic_shipping_vat_account` VARCHAR(255) NOT NULL COMMENT '',
  `economic_shipping_nonvat_account` VARCHAR(255) NOT NULL COMMENT '',
  `economic_discount_product_number` VARCHAR(255) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `economic_service_nonvat_account` VARCHAR(255) NOT NULL COMMENT '',
  `economic_discount_vat_account` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`accountgroup_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Economic Account Group';


-- -----------------------------------------------------
-- Table `#__redshop_fields`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_fields` ;

CREATE TABLE IF NOT EXISTS `#__redshop_fields` (
  `field_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `field_title` VARCHAR(250) NOT NULL COMMENT '',
  `field_name` VARCHAR(20) NOT NULL COMMENT '',
  `field_type` VARCHAR(20) NOT NULL COMMENT '',
  `field_desc` LONGTEXT NOT NULL COMMENT '',
  `field_class` VARCHAR(20) NOT NULL COMMENT '',
  `field_section` VARCHAR(20) NOT NULL COMMENT '',
  `field_maxlength` INT(11) NOT NULL COMMENT '',
  `field_cols` INT(11) NOT NULL COMMENT '',
  `field_rows` INT(11) NOT NULL COMMENT '',
  `field_size` TINYINT(4) NOT NULL COMMENT '',
  `field_show_in_front` TINYINT(4) NOT NULL COMMENT '',
  `required` TINYINT(4) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `display_in_product` TINYINT(4) NOT NULL COMMENT '',
  `ordering` INT(11) NOT NULL COMMENT '',
  `display_in_checkout` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`field_id`)  COMMENT '',
  UNIQUE INDEX `field_name` (`field_name` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '',
  INDEX `idx_field_section` (`field_section` ASC)  COMMENT '',
  INDEX `idx_field_type` (`field_type` ASC)  COMMENT '',
  INDEX `idx_required` (`required` ASC)  COMMENT '',
  INDEX `idx_field_name` (`field_name` ASC)  COMMENT '',
  INDEX `idx_field_show_in_front` (`field_show_in_front` ASC)  COMMENT '',
  INDEX `idx_display_in_product` (`display_in_product` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Fields';


-- -----------------------------------------------------
-- Table `#__redshop_fields_data`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_fields_data` ;

CREATE TABLE IF NOT EXISTS `#__redshop_fields_data` (
  `data_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `fieldid` INT(11) NULL DEFAULT NULL COMMENT '',
  `data_txt` LONGTEXT NULL DEFAULT NULL COMMENT '',
  `itemid` INT(11) NULL DEFAULT NULL COMMENT '',
  `section` VARCHAR(20) NULL DEFAULT NULL COMMENT '',
  `alt_text` VARCHAR(255) NOT NULL COMMENT '',
  `image_link` VARCHAR(255) NOT NULL COMMENT '',
  `user_email` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`data_id`)  COMMENT '',
  INDEX `itemid` (`itemid` ASC)  COMMENT '',
  INDEX `idx_fieldid` (`fieldid` ASC)  COMMENT '',
  INDEX `idx_itemid` (`itemid` ASC)  COMMENT '',
  INDEX `idx_section` (`section` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Fields Data';


-- -----------------------------------------------------
-- Table `#__redshop_fields_value`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_fields_value` ;

CREATE TABLE IF NOT EXISTS `#__redshop_fields_value` (
  `value_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `field_id` INT(11) NOT NULL COMMENT '',
  `field_value` VARCHAR(250) NOT NULL COMMENT '',
  `field_name` VARCHAR(250) NOT NULL COMMENT '',
  `alt_text` VARCHAR(255) NOT NULL COMMENT '',
  `image_link` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`value_id`)  COMMENT '',
  INDEX `idx_field_id` (`field_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Fields Value';


-- -----------------------------------------------------
-- Table `#__redshop_giftcard`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_giftcard` ;

CREATE TABLE IF NOT EXISTS `#__redshop_giftcard` (
  `giftcard_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `giftcard_name` VARCHAR(255) NOT NULL COMMENT '',
  `giftcard_price` DECIMAL(10,3) NOT NULL COMMENT '',
  `giftcard_value` DECIMAL(10,3) NOT NULL COMMENT '',
  `giftcard_validity` INT(11) NOT NULL COMMENT '',
  `giftcard_date` INT(11) NOT NULL COMMENT '',
  `giftcard_bgimage` VARCHAR(255) NOT NULL COMMENT '',
  `giftcard_image` VARCHAR(255) NOT NULL COMMENT '',
  `published` INT(11) NOT NULL COMMENT '',
  `giftcard_desc` LONGTEXT NOT NULL COMMENT '',
  `customer_amount` INT(11) NOT NULL COMMENT '',
  `accountgroup_id` INT(11) NOT NULL COMMENT '',
  `free_shipping` TINYINT NOT NULL COMMENT '',
  PRIMARY KEY (`giftcard_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Giftcard';


-- -----------------------------------------------------
-- Table `#__redshop_mail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_mail` ;

CREATE TABLE IF NOT EXISTS `#__redshop_mail` (
  `mail_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `mail_name` VARCHAR(255) NOT NULL COMMENT '',
  `mail_subject` VARCHAR(255) NOT NULL COMMENT '',
  `mail_section` VARCHAR(255) NOT NULL COMMENT '',
  `mail_order_status` VARCHAR(11) NOT NULL COMMENT '',
  `mail_body` LONGTEXT NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `mail_bcc` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`mail_id`)  COMMENT '',
  INDEX `idx_mail_section` (`mail_section` ASC)  COMMENT '',
  INDEX `idx_mail_order_status` (`mail_order_status` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Mail Center';


-- -----------------------------------------------------
-- Table `#__redshop_manufacturer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_manufacturer` ;

CREATE TABLE IF NOT EXISTS `#__redshop_manufacturer` (
  `manufacturer_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `manufacturer_name` VARCHAR(250) NOT NULL COMMENT '',
  `manufacturer_desc` LONGTEXT NOT NULL COMMENT '',
  `manufacturer_email` VARCHAR(250) NOT NULL COMMENT '',
  `product_per_page` INT(11) NOT NULL COMMENT '',
  `template_id` INT(11) NOT NULL COMMENT '',
  `metakey` TEXT NOT NULL COMMENT '',
  `metadesc` TEXT NOT NULL COMMENT '',
  `metalanguage_setting` TEXT NOT NULL COMMENT '',
  `metarobot_info` TEXT NOT NULL COMMENT '',
  `pagetitle` TEXT NOT NULL COMMENT '',
  `pageheading` TEXT NOT NULL COMMENT '',
  `sef_url` TEXT NOT NULL COMMENT '',
  `published` INT(11) NOT NULL COMMENT '',
  `ordering` INT(11) NOT NULL COMMENT '',
  `manufacturer_url` VARCHAR(255) NOT NULL COMMENT '',
  `excluding_category_list` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`manufacturer_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Manufacturer';


-- -----------------------------------------------------
-- Table `#__redshop_mass_discount`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_mass_discount` ;

CREATE TABLE IF NOT EXISTS `#__redshop_mass_discount` (
  `mass_discount_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `discount_product` LONGTEXT NOT NULL COMMENT '',
  `category_id` LONGTEXT NOT NULL COMMENT '',
  `manufacturer_id` LONGTEXT NOT NULL COMMENT '',
  `discount_type` TINYINT(4) NOT NULL COMMENT '',
  `discount_amount` DOUBLE(10,2) NOT NULL COMMENT '',
  `discount_startdate` INT(11) NOT NULL COMMENT '',
  `discount_enddate` INT(11) NOT NULL COMMENT '',
  `discount_name` LONGTEXT NOT NULL COMMENT '',
  PRIMARY KEY (`mass_discount_id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Page Viewer';


-- -----------------------------------------------------
-- Table `#__redshop_media`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_media` ;

CREATE TABLE IF NOT EXISTS `#__redshop_media` (
  `media_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `media_name` VARCHAR(250) NOT NULL COMMENT '',
  `media_alternate_text` VARCHAR(255) NOT NULL COMMENT '',
  `media_section` VARCHAR(20) NOT NULL COMMENT '',
  `section_id` INT(11) NOT NULL COMMENT '',
  `media_type` VARCHAR(250) NOT NULL COMMENT '',
  `media_mimetype` VARCHAR(20) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `ordering` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`media_id`)  COMMENT '',
  INDEX `idx_section_id` (`section_id` ASC)  COMMENT '',
  INDEX `idx_media_section` (`media_section` ASC)  COMMENT '',
  INDEX `idx_media_type` (`media_type` ASC)  COMMENT '',
  INDEX `idx_media_name` (`media_name` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Media';


-- -----------------------------------------------------
-- Table `#__redshop_media_download`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_media_download` ;

CREATE TABLE IF NOT EXISTS `#__redshop_media_download` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `media_id` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `idx_media_id` (`media_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Media Additional Downloadable Files';


-- -----------------------------------------------------
-- Table `#__redshop_newsletter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_newsletter` ;

CREATE TABLE IF NOT EXISTS `#__redshop_newsletter` (
  `newsletter_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `subject` VARCHAR(255) NOT NULL COMMENT '',
  `body` LONGTEXT NOT NULL COMMENT '',
  `template_id` INT(11) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`newsletter_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Newsletter';


-- -----------------------------------------------------
-- Table `#__redshop_newsletter_subscription`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_newsletter_subscription` ;

CREATE TABLE IF NOT EXISTS `#__redshop_newsletter_subscription` (
  `subscription_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `date` INT(11) NOT NULL COMMENT '',
  `newsletter_id` INT(11) NOT NULL COMMENT '',
  `name` VARCHAR(255) NOT NULL COMMENT '',
  `email` VARCHAR(255) NOT NULL COMMENT '',
  `checkout` TINYINT(4) NOT NULL COMMENT '',
  `published` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`subscription_id`)  COMMENT '',
  INDEX `idx_user_id` (`user_id` ASC)  COMMENT '',
  INDEX `idx_newsletter_id` (`newsletter_id` ASC)  COMMENT '',
  INDEX `idx_email` (`email` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Newsletter subscribers';


-- -----------------------------------------------------
-- Table `#__redshop_newsletter_tracker`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_newsletter_tracker` ;

CREATE TABLE IF NOT EXISTS `#__redshop_newsletter_tracker` (
  `tracker_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `newsletter_id` INT(11) NOT NULL COMMENT '',
  `subscription_id` INT(11) NOT NULL COMMENT '',
  `subscriber_name` VARCHAR(255) NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `read` TINYINT(4) NOT NULL COMMENT '',
  `date` DOUBLE NOT NULL COMMENT '',
  PRIMARY KEY (`tracker_id`)  COMMENT '',
  INDEX `idx_newsletter_id` (`newsletter_id` ASC)  COMMENT '',
  INDEX `idx_read` (`read` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Newsletter Tracker';


-- -----------------------------------------------------
-- Table `#__redshop_notifystock_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_notifystock_users` ;

CREATE TABLE IF NOT EXISTS `#__redshop_notifystock_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `property_id` INT(11) NOT NULL COMMENT '',
  `subproperty_id` INT(11) NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `notification_status` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `idx_common` (`product_id` ASC, `property_id` ASC, `subproperty_id` ASC, `notification_status` ASC, `user_id` ASC)  COMMENT '',
  INDEX `idx_user_id` (`user_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_orderbarcode_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_orderbarcode_log` ;

CREATE TABLE IF NOT EXISTS `#__redshop_orderbarcode_log` (
  `log_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `order_id` INT(11) NOT NULL COMMENT '',
  `barcode` VARCHAR(255) NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `search_date` DATETIME NOT NULL COMMENT '',
  PRIMARY KEY (`log_id`)  COMMENT '',
  INDEX `idx_order_id` (`order_id` ASC)  COMMENT '')
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_ordernumber_track`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_ordernumber_track` ;

CREATE TABLE IF NOT EXISTS `#__redshop_ordernumber_track` (
  `trackdatetime` DATETIME NOT NULL COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order number track';


-- -----------------------------------------------------
-- Table `#__redshop_orders`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_orders` ;

CREATE TABLE IF NOT EXISTS `#__redshop_orders` (
  `order_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `order_number` VARCHAR(32) NULL DEFAULT NULL COMMENT '',
  `invoice_number_chrono` INT(11) NOT NULL COMMENT 'Order invoice number in chronological order',
  `invoice_number` VARCHAR(255) NOT NULL COMMENT 'Formatted Order Invoice for final use',
  `barcode` VARCHAR(13) NOT NULL COMMENT '',
  `user_info_id` VARCHAR(32) NULL DEFAULT NULL COMMENT '',
  `order_total` DECIMAL(15,2) NOT NULL DEFAULT '0.00' COMMENT '',
  `order_subtotal` DECIMAL(15,5) NULL DEFAULT NULL COMMENT '',
  `order_tax` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '',
  `order_tax_details` TEXT NOT NULL COMMENT '',
  `order_shipping` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '',
  `order_shipping_tax` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '',
  `coupon_discount` DECIMAL(12,2) NOT NULL DEFAULT '0.00' COMMENT '',
  `order_discount` DECIMAL(12,2) NOT NULL DEFAULT '0.00' COMMENT '',
  `special_discount_amount` DECIMAL(12,2) NOT NULL COMMENT '',
  `payment_dicount` DECIMAL(12,2) NOT NULL COMMENT '',
  `order_status` VARCHAR(5) NULL DEFAULT NULL COMMENT '',
  `order_payment_status` VARCHAR(25) NOT NULL COMMENT '',
  `cdate` INT(11) NULL DEFAULT NULL COMMENT '',
  `mdate` INT(11) NULL DEFAULT NULL COMMENT '',
  `ship_method_id` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `customer_note` TEXT NOT NULL COMMENT '',
  `ip_address` VARCHAR(15) NOT NULL DEFAULT '' COMMENT '',
  `encr_key` VARCHAR(255) NOT NULL COMMENT '',
  `invoice_no` VARCHAR(255) NOT NULL COMMENT '',
  `mail1_status` TINYINT(1) NOT NULL COMMENT '',
  `mail2_status` TINYINT(1) NOT NULL COMMENT '',
  `mail3_status` TINYINT(1) NOT NULL COMMENT '',
  `special_discount` DECIMAL(10,2) NOT NULL COMMENT '',
  `payment_discount` DECIMAL(10,2) NOT NULL COMMENT '',
  `is_booked` TINYINT(1) NOT NULL COMMENT '',
  `order_label_create` TINYINT(1) NOT NULL COMMENT '',
  `vm_order_number` VARCHAR(32) NOT NULL COMMENT '',
  `requisition_number` VARCHAR(255) NOT NULL COMMENT '',
  `bookinvoice_number` INT(11) NOT NULL COMMENT '',
  `bookinvoice_date` INT(11) NOT NULL COMMENT '',
  `referral_code` VARCHAR(50) NOT NULL COMMENT '',
  `customer_message` VARCHAR(255) NOT NULL COMMENT '',
  `shop_id` VARCHAR(255) NOT NULL COMMENT '',
  `order_discount_vat` DECIMAL(10,3) NOT NULL COMMENT '',
  `track_no` VARCHAR(250) NOT NULL COMMENT '',
  `payment_oprand` VARCHAR(50) NOT NULL COMMENT '',
  `discount_type` VARCHAR(255) NOT NULL COMMENT '',
  `analytics_status` INT(1) NOT NULL COMMENT '',
  `tax_after_discount` DECIMAL(10,3) NOT NULL COMMENT '',
  `recuuring_subcription_id` VARCHAR(500) NOT NULL COMMENT '',
  PRIMARY KEY (`order_id`)  COMMENT '',
  INDEX `idx_orders_user_id` (`user_id` ASC)  COMMENT '',
  INDEX `idx_orders_order_number` (`order_number` ASC)  COMMENT '',
  INDEX `idx_orders_user_info_id` (`user_info_id` ASC)  COMMENT '',
  INDEX `idx_orders_ship_method_id` (`ship_method_id` ASC)  COMMENT '',
  INDEX `idx_barcode` (`barcode` ASC)  COMMENT '',
  INDEX `idx_order_payment_status` (`order_payment_status` ASC)  COMMENT '',
  INDEX `idx_order_status` (`order_status` ASC)  COMMENT '',
  INDEX `vm_order_number` (`vm_order_number` ASC)  COMMENT '',
  INDEX `idx_orders_invoice_number` (`invoice_number` ASC)  COMMENT '',
  INDEX `idx_orders_invoice_number_chrono` (`invoice_number_chrono` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order Detail';


-- -----------------------------------------------------
-- Table `#__redshop_order_acc_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_acc_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_acc_item` (
  `order_item_acc_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `order_item_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `order_acc_item_sku` VARCHAR(255) NOT NULL COMMENT '',
  `order_acc_item_name` VARCHAR(255) NOT NULL COMMENT '',
  `order_acc_price` DECIMAL(15,4) NOT NULL COMMENT '',
  `order_acc_vat` DECIMAL(15,4) NOT NULL COMMENT '',
  `product_quantity` INT(11) NOT NULL COMMENT '',
  `product_acc_item_price` DECIMAL(15,4) NOT NULL COMMENT '',
  `product_acc_final_price` DECIMAL(15,4) NOT NULL COMMENT '',
  `product_attribute` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`order_item_acc_id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order Accessory Item Detail';


-- -----------------------------------------------------
-- Table `#__redshop_order_attribute_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_attribute_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_attribute_item` (
  `order_att_item_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `order_item_id` INT(11) NOT NULL COMMENT '',
  `section_id` INT(11) NOT NULL COMMENT '',
  `section` VARCHAR(250) NOT NULL COMMENT '',
  `parent_section_id` INT(11) NOT NULL COMMENT '',
  `section_name` VARCHAR(250) NOT NULL COMMENT '',
  `section_price` DECIMAL(15,4) NOT NULL COMMENT '',
  `section_vat` DECIMAL(15,4) NOT NULL COMMENT '',
  `section_oprand` CHAR(1) NOT NULL COMMENT '',
  `is_accessory_att` TINYINT(4) NOT NULL COMMENT '',
  `stockroom_id` VARCHAR(255) NOT NULL COMMENT '',
  `stockroom_quantity` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`order_att_item_id`)  COMMENT '',
  INDEX `idx_order_item_id` (`order_item_id` ASC)  COMMENT '',
  INDEX `idx_section` (`section` ASC)  COMMENT '',
  INDEX `idx_parent_section_id` (`parent_section_id` ASC)  COMMENT '',
  INDEX `idx_is_accessory_att` (`is_accessory_att` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP order Attribute item';


-- -----------------------------------------------------
-- Table `#__redshop_order_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_item` (
  `order_item_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `order_id` INT(11) NULL DEFAULT NULL COMMENT '',
  `user_info_id` VARCHAR(32) NULL DEFAULT NULL COMMENT '',
  `supplier_id` INT(11) NULL DEFAULT NULL COMMENT '',
  `product_id` INT(11) NULL DEFAULT NULL COMMENT '',
  `order_item_sku` VARCHAR(64) NOT NULL DEFAULT '' COMMENT '',
  `order_item_name` VARCHAR(255) NOT NULL COMMENT '',
  `product_quantity` INT(11) NULL DEFAULT NULL COMMENT '',
  `product_item_price` DECIMAL(15,4) NULL DEFAULT NULL COMMENT '',
  `product_item_price_excl_vat` DECIMAL(15,4) NULL DEFAULT NULL COMMENT '',
  `product_final_price` DECIMAL(12,4) NOT NULL DEFAULT '0.0000' COMMENT '',
  `order_item_currency` VARCHAR(16) NULL DEFAULT NULL COMMENT '',
  `order_status` VARCHAR(250) NULL DEFAULT NULL COMMENT '',
  `customer_note` TEXT NOT NULL COMMENT '',
  `cdate` INT(11) NULL DEFAULT NULL COMMENT '',
  `mdate` INT(11) NULL DEFAULT NULL COMMENT '',
  `product_attribute` TEXT NULL DEFAULT NULL COMMENT '',
  `product_accessory` TEXT NOT NULL COMMENT '',
  `delivery_time` INT(11) NOT NULL COMMENT '',
  `stockroom_id` VARCHAR(255) NOT NULL COMMENT '',
  `stockroom_quantity` VARCHAR(255) NOT NULL COMMENT '',
  `is_split` TINYINT(1) NOT NULL COMMENT '',
  `attribute_image` TEXT NOT NULL COMMENT '',
  `is_giftcard` TINYINT(4) NOT NULL COMMENT '',
  `wrapper_id` INT(11) NOT NULL COMMENT '',
  `wrapper_price` DECIMAL(10,2) NOT NULL COMMENT '',
  `giftcard_user_name` VARCHAR(255) NOT NULL COMMENT '',
  `giftcard_user_email` VARCHAR(255) NOT NULL COMMENT '',
  `product_item_old_price` DECIMAL(10,4) NOT NULL COMMENT '',
  `product_purchase_price` DECIMAL(10,4) NOT NULL COMMENT '',
  `discount_calc_data` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`order_item_id`)  COMMENT '',
  INDEX `idx_order_id` (`order_id` ASC)  COMMENT '',
  INDEX `idx_user_info_id` (`user_info_id` ASC)  COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '',
  INDEX `idx_order_status` (`order_status` ASC)  COMMENT '',
  INDEX `idx_cdate` (`cdate` ASC)  COMMENT '',
  INDEX `idx_is_giftcard` (`is_giftcard` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order Item Detail';


-- -----------------------------------------------------
-- Table `#__redshop_order_payment`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_payment` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_payment` (
  `payment_order_id` BIGINT(20) NOT NULL AUTO_INCREMENT COMMENT '',
  `order_id` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `payment_method_id` INT(11) NULL DEFAULT NULL COMMENT '',
  `order_payment_code` VARCHAR(30) NOT NULL DEFAULT '' COMMENT '',
  `order_payment_cardname` BLOB NOT NULL COMMENT '',
  `order_payment_number` BLOB NULL DEFAULT NULL COMMENT '',
  `order_payment_ccv` BLOB NOT NULL COMMENT '',
  `order_payment_amount` DOUBLE(10,2) NOT NULL COMMENT '',
  `order_payment_expire` INT(11) NULL DEFAULT NULL COMMENT '',
  `order_payment_name` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `payment_method_class` VARCHAR(256) NULL DEFAULT NULL COMMENT '',
  `order_payment_trans_id` TEXT NOT NULL COMMENT '',
  `authorize_status` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `order_transfee` DOUBLE(10,2) NOT NULL COMMENT '',
  PRIMARY KEY (`payment_order_id`)  COMMENT '',
  INDEX `idx_order_id` (`order_id` ASC)  COMMENT '',
  INDEX `idx_payment_method_id` (`payment_method_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order Payment Detail';


-- -----------------------------------------------------
-- Table `#__redshop_order_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_status` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_status` (
  `order_status_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `order_status_code` VARCHAR(64) NOT NULL COMMENT '',
  `order_status_name` VARCHAR(64) NULL DEFAULT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`order_status_id`)  COMMENT '',
  UNIQUE INDEX `order_status_code` (`order_status_code` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Orders Status';


-- -----------------------------------------------------
-- Table `#__redshop_order_status_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_status_log` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_status_log` (
  `order_status_log_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `order_id` INT(11) NOT NULL COMMENT '',
  `order_status` VARCHAR(5) NOT NULL COMMENT '',
  `order_payment_status` VARCHAR(25) NOT NULL COMMENT '',
  `date_changed` INT(11) NOT NULL COMMENT '',
  `customer_note` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`order_status_log_id`)  COMMENT '',
  INDEX `idx_order_id` (`order_id` ASC)  COMMENT '',
  INDEX `idx_order_status` (`order_status` ASC)  COMMENT '')
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Orders Status history';


-- -----------------------------------------------------
-- Table `#__redshop_order_users_info`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_order_users_info` ;

CREATE TABLE IF NOT EXISTS `#__redshop_order_users_info` (
  `order_info_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `users_info_id` INT(11) NOT NULL COMMENT '',
  `order_id` INT(11) NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `firstname` VARCHAR(250) NOT NULL COMMENT '',
  `lastname` VARCHAR(250) NOT NULL COMMENT '',
  `address_type` VARCHAR(255) NOT NULL COMMENT '',
  `vat_number` VARCHAR(250) NOT NULL COMMENT '',
  `tax_exempt` TINYINT(4) NOT NULL COMMENT '',
  `shopper_group_id` INT(11) NOT NULL COMMENT '',
  `address` VARCHAR(255) NOT NULL COMMENT '',
  `city` VARCHAR(255) NOT NULL COMMENT '',
  `country_code` VARCHAR(11) NOT NULL COMMENT '',
  `state_code` VARCHAR(11) NOT NULL COMMENT '',
  `zipcode` VARCHAR(255) NOT NULL COMMENT '',
  `phone` VARCHAR(50) NOT NULL COMMENT '',
  `tax_exempt_approved` TINYINT(1) NOT NULL COMMENT '',
  `approved` TINYINT(1) NOT NULL COMMENT '',
  `is_company` TINYINT(4) NOT NULL COMMENT '',
  `user_email` VARCHAR(255) NOT NULL COMMENT '',
  `company_name` VARCHAR(255) NOT NULL COMMENT '',
  `ean_number` VARCHAR(250) NOT NULL COMMENT '',
  `requesting_tax_exempt` TINYINT(4) NOT NULL COMMENT '',
  `thirdparty_email` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`order_info_id`)  COMMENT '',
  INDEX `idx_order_id` (`order_id` ASC)  COMMENT '',
  INDEX `idx_address_type` (`address_type` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Order User Information';


-- -----------------------------------------------------
-- Table `#__redshop_pageviewer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_pageviewer` ;

CREATE TABLE IF NOT EXISTS `#__redshop_pageviewer` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `session_id` VARCHAR(250) NOT NULL COMMENT '',
  `section` VARCHAR(250) NOT NULL COMMENT '',
  `section_id` INT(11) NOT NULL COMMENT '',
  `hit` INT(11) NOT NULL COMMENT '',
  `created_date` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `idx_session_id` (`session_id` ASC)  COMMENT '',
  INDEX `idx_section` (`section` ASC)  COMMENT '',
  INDEX `idx_section_id` (`section_id` ASC)  COMMENT '',
  INDEX `idx_created_date` (`created_date` ASC)  COMMENT '')
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Page Viewer';


-- -----------------------------------------------------
-- Table `#__redshop_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product` (
  `product_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `product_parent_id` INT(11) NOT NULL COMMENT '',
  `manufacturer_id` INT(11) NOT NULL COMMENT '',
  `supplier_id` INT(11) NOT NULL COMMENT '',
  `product_on_sale` TINYINT(4) NOT NULL COMMENT '',
  `product_special` TINYINT(4) NOT NULL COMMENT '',
  `product_download` TINYINT(4) NOT NULL COMMENT '',
  `product_template` INT(11) NOT NULL COMMENT '',
  `product_name` VARCHAR(250) NOT NULL COMMENT '',
  `product_price` DOUBLE NOT NULL COMMENT '',
  `discount_price` DOUBLE NOT NULL COMMENT '',
  `discount_stratdate` INT(11) NOT NULL COMMENT '',
  `discount_enddate` INT(11) NOT NULL COMMENT '',
  `product_number` VARCHAR(250) NOT NULL COMMENT '',
  `product_type` VARCHAR(20) NOT NULL COMMENT '',
  `product_s_desc` LONGTEXT NOT NULL COMMENT '',
  `product_desc` LONGTEXT NOT NULL COMMENT '',
  `product_volume` DOUBLE NOT NULL COMMENT '',
  `product_tax_id` INT(11) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `product_thumb_image` VARCHAR(250) NOT NULL COMMENT '',
  `product_full_image` VARCHAR(250) NOT NULL COMMENT '',
  `publish_date` DATETIME NOT NULL COMMENT '',
  `update_date` TIMESTAMP NOT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT '',
  `visited` INT(11) NOT NULL COMMENT '',
  `metakey` TEXT NOT NULL COMMENT '',
  `metadesc` TEXT NOT NULL COMMENT '',
  `metalanguage_setting` TEXT NOT NULL COMMENT '',
  `metarobot_info` TEXT NOT NULL COMMENT '',
  `pagetitle` TEXT NOT NULL COMMENT '',
  `pageheading` TEXT NOT NULL COMMENT '',
  `sef_url` TEXT NOT NULL COMMENT '',
  `cat_in_sefurl` INT(11) NOT NULL COMMENT '',
  `weight` FLOAT(10,3) NOT NULL COMMENT '',
  `expired` TINYINT(4) NOT NULL COMMENT '',
  `not_for_sale` TINYINT(4) NOT NULL COMMENT '',
  `use_discount_calc` TINYINT(4) NOT NULL COMMENT '',
  `discount_calc_method` VARCHAR(255) NOT NULL COMMENT '',
  `min_order_product_quantity` INT(11) NOT NULL COMMENT '',
  `attribute_set_id` INT(11) NOT NULL COMMENT '',
  `product_length` DECIMAL(10,2) NOT NULL COMMENT '',
  `product_height` DECIMAL(10,2) NOT NULL COMMENT '',
  `product_width` DECIMAL(10,2) NOT NULL COMMENT '',
  `product_diameter` DECIMAL(10,2) NOT NULL COMMENT '',
  `product_availability_date` INT(11) NOT NULL COMMENT '',
  `use_range` TINYINT(4) NOT NULL COMMENT '',
  `product_tax_group_id` INT(11) NOT NULL COMMENT '',
  `product_download_days` INT(11) NOT NULL COMMENT '',
  `product_download_limit` INT(11) NOT NULL COMMENT '',
  `product_download_clock` INT(11) NOT NULL COMMENT '',
  `product_download_clock_min` INT(11) NOT NULL COMMENT '',
  `accountgroup_id` INT(11) NOT NULL COMMENT '',
  `canonical_url` TEXT NOT NULL COMMENT '',
  `minimum_per_product_total` INT(11) NOT NULL COMMENT '',
  `allow_decimal_piece` INT(4) NOT NULL COMMENT '',
  `quantity_selectbox_value` VARCHAR(255) NOT NULL COMMENT '',
  `checked_out` INT(11) NOT NULL COMMENT '',
  `checked_out_time` DATETIME NOT NULL COMMENT '',
  `max_order_product_quantity` INT(11) NOT NULL COMMENT '',
  `product_download_infinite` TINYINT(4) NOT NULL COMMENT '',
  `product_back_full_image` VARCHAR(250) NOT NULL COMMENT '',
  `product_back_thumb_image` VARCHAR(250) NOT NULL COMMENT '',
  `product_preview_image` VARCHAR(250) NOT NULL COMMENT '',
  `product_preview_back_image` VARCHAR(250) NOT NULL COMMENT '',
  `preorder` VARCHAR(255) NOT NULL COMMENT '',
  `append_to_global_seo` ENUM('append', 'prepend', 'replace') NOT NULL DEFAULT 'append' COMMENT '',
  PRIMARY KEY (`product_id`)  COMMENT '',
  UNIQUE INDEX `idx_product_number` (`product_number` ASC)  COMMENT '',
  INDEX `idx_manufacturer_id` (`manufacturer_id` ASC)  COMMENT '',
  INDEX `idx_product_on_sale` (`product_on_sale` ASC)  COMMENT '',
  INDEX `idx_product_special` (`product_special` ASC)  COMMENT '',
  INDEX `idx_product_parent_id` (`product_parent_id` ASC)  COMMENT '',
  INDEX `idx_common` (`published` ASC, `expired` ASC, `product_parent_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products';


-- -----------------------------------------------------
-- Table `#__redshop_product_accessory`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_accessory` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_accessory` (
  `accessory_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `child_product_id` INT(11) NOT NULL COMMENT '',
  `accessory_price` DOUBLE NOT NULL COMMENT '',
  `oprand` CHAR(1) NOT NULL COMMENT '',
  `setdefault_selected` TINYINT(4) NOT NULL COMMENT '',
  `ordering` INT(11) NOT NULL COMMENT '',
  `category_id` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`accessory_id`)  COMMENT '',
  INDEX `idx_common` (`product_id` ASC, `child_product_id` ASC)  COMMENT '',
  INDEX `idx_child_product_id` (`child_product_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products Accessory';


-- -----------------------------------------------------
-- Table `#__redshop_product_attribute`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_attribute` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute` (
  `attribute_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `attribute_name` VARCHAR(250) NOT NULL COMMENT '',
  `attribute_required` TINYINT(4) NOT NULL COMMENT '',
  `allow_multiple_selection` TINYINT(1) NOT NULL COMMENT '',
  `hide_attribute_price` TINYINT(1) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `ordering` INT(11) NOT NULL COMMENT '',
  `attribute_set_id` INT(11) NOT NULL COMMENT '',
  `display_type` VARCHAR(255) NOT NULL COMMENT '',
  `attribute_published` INT(11) NOT NULL DEFAULT '1' COMMENT '',
  PRIMARY KEY (`attribute_id`)  COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '',
  INDEX `idx_attribute_name` (`attribute_name` ASC)  COMMENT '',
  INDEX `idx_attribute_set_id` (`attribute_set_id` ASC)  COMMENT '',
  INDEX `idx_attribute_published` (`attribute_published` ASC)  COMMENT '',
  INDEX `idx_attribute_required` (`attribute_required` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products Attribute';


-- -----------------------------------------------------
-- Table `#__redshop_product_attribute_price`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_attribute_price` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_price` (
  `price_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `section_id` INT(11) NOT NULL COMMENT '',
  `section` VARCHAR(255) NOT NULL COMMENT '',
  `product_price` DOUBLE NOT NULL COMMENT '',
  `product_currency` VARCHAR(10) NOT NULL COMMENT '',
  `cdate` INT(11) NOT NULL COMMENT '',
  `shopper_group_id` INT(11) NOT NULL COMMENT '',
  `price_quantity_start` INT(11) NOT NULL COMMENT '',
  `price_quantity_end` BIGINT(20) NOT NULL COMMENT '',
  `discount_price` DOUBLE NOT NULL COMMENT '',
  `discount_start_date` INT(11) NOT NULL COMMENT '',
  `discount_end_date` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`price_id`)  COMMENT '',
  INDEX `idx_shopper_group_id` (`shopper_group_id` ASC)  COMMENT '',
  INDEX `idx_common` (`section_id` ASC, `section` ASC, `price_quantity_start` ASC, `price_quantity_end` ASC, `shopper_group_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Attribute Price';


-- -----------------------------------------------------
-- Table `#__redshop_product_attribute_property`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_attribute_property` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_property` (
  `property_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `attribute_id` INT(11) NOT NULL COMMENT '',
  `property_name` VARCHAR(255) NOT NULL COMMENT '',
  `property_price` DOUBLE NOT NULL COMMENT '',
  `oprand` CHAR(1) NOT NULL DEFAULT '+' COMMENT '',
  `property_image` VARCHAR(255) NOT NULL COMMENT '',
  `property_main_image` VARCHAR(255) NOT NULL COMMENT '',
  `ordering` INT(11) NOT NULL COMMENT '',
  `setdefault_selected` TINYINT(4) NOT NULL COMMENT '',
  `setrequire_selected` TINYINT(3) NOT NULL COMMENT '',
  `setmulti_selected` TINYINT(4) NOT NULL COMMENT '',
  `setdisplay_type` VARCHAR(255) NOT NULL COMMENT '',
  `extra_field` VARCHAR(250) NOT NULL COMMENT '',
  `property_published` INT(11) NOT NULL DEFAULT '1' COMMENT '',
  `property_number` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`property_id`)  COMMENT '',
  INDEX `idx_attribute_id` (`attribute_id` ASC)  COMMENT '',
  INDEX `idx_setrequire_selected` (`setrequire_selected` ASC)  COMMENT '',
  INDEX `idx_property_published` (`property_published` ASC)  COMMENT '',
  INDEX `idx_property_number` (`property_number` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products Attribute Property';


-- -----------------------------------------------------
-- Table `#__redshop_product_attribute_stockroom_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_attribute_stockroom_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_stockroom_xref` (
  `section_id` INT(11) NOT NULL COMMENT '',
  `section` VARCHAR(255) NOT NULL COMMENT '',
  `stockroom_id` INT(11) NOT NULL COMMENT '',
  `quantity` INT(11) NOT NULL COMMENT '',
  `preorder_stock` INT(11) NOT NULL COMMENT '',
  `ordered_preorder` INT(11) NOT NULL COMMENT '',
  INDEX `idx_stockroom_id` (`stockroom_id` ASC)  COMMENT '',
  INDEX `idx_common` (`section_id` ASC, `section` ASC, `stockroom_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Attribute Stockroom relation';


-- -----------------------------------------------------
-- Table `#__redshop_product_category_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_category_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_category_xref` (
  `category_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `ordering` INT(11) NOT NULL COMMENT '',
  INDEX `ref_category` (`product_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Category Relation';


-- -----------------------------------------------------
-- Table `#__redshop_product_compare`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_compare` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_compare` (
  `compare_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`compare_id`)  COMMENT '',
  INDEX `idx_common` (`user_id` ASC, `product_id` ASC)  COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Comparision';


-- -----------------------------------------------------
-- Table `#__redshop_product_discount_calc`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_discount_calc` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_discount_calc` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `area_start` FLOAT(10,2) NOT NULL COMMENT '',
  `area_end` FLOAT(10,2) NOT NULL COMMENT '',
  `area_price` DOUBLE NOT NULL COMMENT '',
  `discount_calc_unit` VARCHAR(255) NOT NULL COMMENT '',
  `area_start_converted` FLOAT(20,8) NOT NULL COMMENT '',
  `area_end_converted` FLOAT(20,8) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Discount Calculator';


-- -----------------------------------------------------
-- Table `#__redshop_product_discount_calc_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_discount_calc_extra` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_discount_calc_extra` (
  `pdcextra_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `option_name` VARCHAR(255) NOT NULL COMMENT '',
  `oprand` CHAR(1) NOT NULL COMMENT '',
  `price` FLOAT(10,2) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`pdcextra_id`)  COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Discount Calculator Extra Value';


-- -----------------------------------------------------
-- Table `#__redshop_product_download`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_download` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_download` (
  `product_id` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `user_id` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `order_id` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `end_date` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `download_max` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `download_id` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '',
  `file_name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '',
  `product_serial_number` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '',
  PRIMARY KEY (`download_id`)  COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '',
  INDEX `idx_user_id` (`user_id` ASC)  COMMENT '',
  INDEX `idx_order_id` (`order_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Downloadable Products';


-- -----------------------------------------------------
-- Table `#__redshop_product_download_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_download_log` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_download_log` (
  `user_id` INT(11) NOT NULL COMMENT '',
  `download_id` VARCHAR(32) NOT NULL COMMENT '',
  `download_time` INT(11) NOT NULL COMMENT '',
  `ip` VARCHAR(255) NOT NULL COMMENT '',
  INDEX `idx_download_id` (`download_id` ASC)  COMMENT '')
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Downloadable Products Logs';


-- -----------------------------------------------------
-- Table `#__redshop_product_price`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_price` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_price` (
  `price_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `product_price` DECIMAL(12,4) NOT NULL COMMENT '',
  `product_currency` VARCHAR(10) NOT NULL COMMENT '',
  `cdate` DATE NOT NULL COMMENT '',
  `shopper_group_id` INT(11) NOT NULL COMMENT '',
  `price_quantity_start` INT(11) NOT NULL COMMENT '',
  `price_quantity_end` BIGINT(20) NOT NULL COMMENT '',
  `discount_price` DECIMAL(12,4) NOT NULL COMMENT '',
  `discount_start_date` INT(11) NOT NULL COMMENT '',
  `discount_end_date` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`price_id`)  COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '',
  INDEX `idx_shopper_group_id` (`shopper_group_id` ASC)  COMMENT '',
  INDEX `idx_price_quantity_start` (`price_quantity_start` ASC)  COMMENT '',
  INDEX `idx_price_quantity_end` (`price_quantity_end` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Price';


-- -----------------------------------------------------
-- Table `#__redshop_product_rating`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_rating` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_rating` (
  `rating_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `product_id` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `title` VARCHAR(255) NOT NULL COMMENT '',
  `comment` TEXT NOT NULL COMMENT '',
  `userid` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `time` INT(11) NOT NULL DEFAULT '0' COMMENT '',
  `user_rating` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '',
  `favoured` TINYINT(4) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `email` VARCHAR(200) NOT NULL COMMENT '',
  `username` VARCHAR(255) NOT NULL COMMENT '',
  `company_name` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`rating_id`)  COMMENT '',
  UNIQUE INDEX `product_id` (`product_id` ASC, `userid` ASC, `email` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '',
  INDEX `idx_email` (`email` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_product_related`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_related` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_related` (
  `related_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `ordering` INT(11) NOT NULL COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Related Products';


-- -----------------------------------------------------
-- Table `#__redshop_product_serial_number`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_serial_number` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_serial_number` (
  `serial_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `serial_number` VARCHAR(255) NOT NULL COMMENT '',
  `is_used` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '',
  PRIMARY KEY (`serial_id`)  COMMENT '',
  INDEX `idx_common` (`product_id` ASC, `is_used` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP downloadable product serial numbers';


-- -----------------------------------------------------
-- Table `#__redshop_product_stockroom_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_stockroom_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_stockroom_xref` (
  `product_id` INT(11) NOT NULL COMMENT '',
  `stockroom_id` INT(11) NOT NULL COMMENT '',
  `quantity` INT(11) NOT NULL COMMENT '',
  `preorder_stock` INT(11) NOT NULL COMMENT '',
  `ordered_preorder` INT(11) NOT NULL COMMENT '',
  INDEX `idx_stockroom_id` (`stockroom_id` ASC)  COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '',
  INDEX `idx_quantity` (`quantity` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products Stockroom Relation';


-- -----------------------------------------------------
-- Table `#__redshop_product_subattribute_color`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_subattribute_color` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_subattribute_color` (
  `subattribute_color_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `subattribute_color_name` VARCHAR(255) NOT NULL COMMENT '',
  `subattribute_color_price` DOUBLE NOT NULL COMMENT '',
  `oprand` CHAR(1) NOT NULL COMMENT '',
  `subattribute_color_image` VARCHAR(255) NOT NULL COMMENT '',
  `subattribute_id` INT(11) NOT NULL COMMENT '',
  `ordering` INT(11) NOT NULL COMMENT '',
  `setdefault_selected` TINYINT(4) NOT NULL COMMENT '',
  `extra_field` VARCHAR(250) NOT NULL COMMENT '',
  `subattribute_published` INT(11) NOT NULL DEFAULT '1' COMMENT '',
  `subattribute_color_number` VARCHAR(255) NOT NULL COMMENT '',
  `subattribute_color_title` VARCHAR(255) NOT NULL COMMENT '',
  `subattribute_color_main_image` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`subattribute_color_id`)  COMMENT '',
  INDEX `idx_subattribute_id` (`subattribute_id` ASC)  COMMENT '',
  INDEX `idx_subattribute_published` (`subattribute_published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Product Subattribute Color';


-- -----------------------------------------------------
-- Table `#__redshop_product_subscribe_detail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_subscribe_detail` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_subscribe_detail` (
  `product_subscribe_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `order_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `subscription_id` INT(11) NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `start_date` INT(11) NOT NULL COMMENT '',
  `end_date` INT(11) NOT NULL COMMENT '',
  `order_item_id` INT(11) NOT NULL COMMENT '',
  `renewal_reminder` TINYINT(1) NOT NULL DEFAULT '1' COMMENT '',
  PRIMARY KEY (`product_subscribe_id`)  COMMENT '',
  INDEX `idx_common` (`product_id` ASC, `end_date` ASC)  COMMENT '',
  INDEX `idx_order_item_id` (`order_item_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP User product Subscribe detail';


-- -----------------------------------------------------
-- Table `#__redshop_product_subscription`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_subscription` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_subscription` (
  `subscription_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `subscription_period` INT(11) NOT NULL COMMENT '',
  `period_type` VARCHAR(10) NOT NULL COMMENT '',
  `subscription_price` DOUBLE NOT NULL COMMENT '',
  PRIMARY KEY (`subscription_id`)  COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Subscription';


-- -----------------------------------------------------
-- Table `#__redshop_product_tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_tags` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_tags` (
  `tags_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `tags_name` VARCHAR(255) NOT NULL COMMENT '',
  `tags_counter` INT(11) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`tags_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '',
  INDEX `idx_tags_name` (`tags_name` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Product Tags';


-- -----------------------------------------------------
-- Table `#__redshop_product_tags_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_tags_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_tags_xref` (
  `tags_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `users_id` INT(11) NOT NULL COMMENT '',
  INDEX `idx_product_id` (`product_id` ASC)  COMMENT '',
  INDEX `idx_users_id` (`users_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Product Tags Relation With product and user';


-- -----------------------------------------------------
-- Table `#__redshop_product_voucher`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_voucher` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher` (
  `voucher_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `voucher_code` VARCHAR(255) NOT NULL COMMENT '',
  `amount` DECIMAL(12,2) NOT NULL DEFAULT '0.00' COMMENT '',
  `voucher_type` VARCHAR(250) NOT NULL COMMENT '',
  `start_date` DOUBLE NOT NULL COMMENT '',
  `end_date` DOUBLE NOT NULL COMMENT '',
  `free_shipping` TINYINT(4) NOT NULL COMMENT '',
  `voucher_left` INT(11) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`voucher_id`)  COMMENT '',
  INDEX `idx_common` (`voucher_code` ASC, `published` ASC, `start_date` ASC, `end_date` ASC)  COMMENT '',
  INDEX `idx_voucher_left` (`voucher_left` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Voucher';


-- -----------------------------------------------------
-- Table `#__redshop_product_voucher_transaction`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_voucher_transaction` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher_transaction` (
  `transaction_voucher_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `voucher_id` INT(11) NOT NULL COMMENT '',
  `voucher_code` VARCHAR(255) NOT NULL COMMENT '',
  `amount` DECIMAL(10,3) NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `order_id` INT(11) NOT NULL COMMENT '',
  `trancation_date` INT(11) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `product_id` VARCHAR(50) NOT NULL COMMENT '',
  PRIMARY KEY (`transaction_voucher_id`)  COMMENT '',
  INDEX `idx_voucher_id` (`voucher_id` ASC)  COMMENT '',
  INDEX `idx_voucher_code` (`voucher_code` ASC)  COMMENT '',
  INDEX `idx_amount` (`amount` ASC)  COMMENT '',
  INDEX `idx_user_id` (`user_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Product Voucher Transaction';


-- -----------------------------------------------------
-- Table `#__redshop_product_voucher_xref`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_product_voucher_xref` ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher_xref` (
  `voucher_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  INDEX `idx_common` (`voucher_id` ASC, `product_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Products Voucher Relation';


-- -----------------------------------------------------
-- Table `#__redshop_quotation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_quotation` ;

CREATE TABLE IF NOT EXISTS `#__redshop_quotation` (
  `quotation_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `quotation_number` VARCHAR(50) NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `user_info_id` INT(11) NOT NULL COMMENT '',
  `order_id` INT(11) NOT NULL COMMENT '',
  `quotation_total` DECIMAL(15,2) NOT NULL COMMENT '',
  `quotation_subtotal` DECIMAL(15,2) NOT NULL COMMENT '',
  `quotation_tax` DECIMAL(15,2) NOT NULL COMMENT '',
  `quotation_discount` DECIMAL(15,4) NOT NULL COMMENT '',
  `quotation_status` INT(11) NOT NULL COMMENT '',
  `quotation_cdate` INT(11) NOT NULL COMMENT '',
  `quotation_mdate` INT(11) NOT NULL COMMENT '',
  `quotation_note` TEXT NOT NULL COMMENT '',
  `quotation_customer_note` TEXT NOT NULL COMMENT '',
  `quotation_ipaddress` VARCHAR(20) NOT NULL COMMENT '',
  `quotation_encrkey` VARCHAR(255) NOT NULL COMMENT '',
  `user_email` VARCHAR(255) NOT NULL COMMENT '',
  `quotation_special_discount` DECIMAL(15,4) NOT NULL COMMENT '',
  PRIMARY KEY (`quotation_id`)  COMMENT '',
  INDEX `idx_user_id` (`user_id` ASC)  COMMENT '',
  INDEX `idx_order_id` (`order_id` ASC)  COMMENT '',
  INDEX `idx_quotation_status` (`quotation_status` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Quotation';


-- -----------------------------------------------------
-- Table `#__redshop_quotation_accessory_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_quotation_accessory_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_accessory_item` (
  `quotation_item_acc_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `quotation_item_id` INT(11) NOT NULL COMMENT '',
  `accessory_id` INT(11) NOT NULL COMMENT '',
  `accessory_item_sku` VARCHAR(255) NOT NULL COMMENT '',
  `accessory_item_name` VARCHAR(255) NOT NULL COMMENT '',
  `accessory_price` DECIMAL(15,4) NOT NULL COMMENT '',
  `accessory_vat` DECIMAL(15,4) NOT NULL COMMENT '',
  `accessory_quantity` INT(11) NOT NULL COMMENT '',
  `accessory_item_price` DECIMAL(15,2) NOT NULL COMMENT '',
  `accessory_final_price` DECIMAL(15,2) NOT NULL COMMENT '',
  `accessory_attribute` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`quotation_item_acc_id`)  COMMENT '',
  INDEX `idx_quotation_item_id` (`quotation_item_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Quotation Accessory item';


-- -----------------------------------------------------
-- Table `#__redshop_quotation_attribute_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_quotation_attribute_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_attribute_item` (
  `quotation_att_item_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `quotation_item_id` INT(11) NOT NULL COMMENT '',
  `section_id` INT(11) NOT NULL COMMENT '',
  `section` VARCHAR(250) NOT NULL COMMENT '',
  `parent_section_id` INT(11) NOT NULL COMMENT '',
  `section_name` VARCHAR(250) NOT NULL COMMENT '',
  `section_price` DECIMAL(15,4) NOT NULL COMMENT '',
  `section_vat` DECIMAL(15,4) NOT NULL COMMENT '',
  `section_oprand` CHAR(1) NOT NULL COMMENT '',
  `is_accessory_att` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`quotation_att_item_id`)  COMMENT '',
  INDEX `idx_quotation_item_id` (`quotation_item_id` ASC)  COMMENT '',
  INDEX `idx_section` (`section` ASC)  COMMENT '',
  INDEX `idx_parent_section_id` (`parent_section_id` ASC)  COMMENT '',
  INDEX `idx_is_accessory_att` (`is_accessory_att` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Quotation Attribute item';


-- -----------------------------------------------------
-- Table `#__redshop_quotation_fields_data`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_quotation_fields_data` ;

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_fields_data` (
  `data_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `fieldid` INT(11) NULL DEFAULT NULL COMMENT '',
  `data_txt` LONGTEXT NULL DEFAULT NULL COMMENT '',
  `quotation_item_id` INT(11) NULL DEFAULT NULL COMMENT '',
  `section` VARCHAR(20) NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`data_id`)  COMMENT '',
  INDEX `quotation_item_id` (`quotation_item_id` ASC)  COMMENT '',
  INDEX `idx_fieldid` (`fieldid` ASC)  COMMENT '',
  INDEX `idx_quotation_item_id` (`quotation_item_id` ASC)  COMMENT '',
  INDEX `idx_section` (`section` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Quotation USer field';


-- -----------------------------------------------------
-- Table `#__redshop_quotation_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_quotation_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_item` (
  `quotation_item_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `quotation_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `product_name` VARCHAR(255) NOT NULL COMMENT '',
  `product_price` DECIMAL(15,4) NOT NULL COMMENT '',
  `product_excl_price` DECIMAL(15,4) NOT NULL COMMENT '',
  `product_final_price` DECIMAL(15,4) NOT NULL COMMENT '',
  `actualitem_price` DECIMAL(15,4) NOT NULL COMMENT '',
  `product_quantity` INT(11) NOT NULL COMMENT '',
  `product_attribute` TEXT NOT NULL COMMENT '',
  `product_accessory` TEXT NOT NULL COMMENT '',
  `mycart_accessory` TEXT NOT NULL COMMENT '',
  `product_wrapperid` INT(11) NOT NULL COMMENT '',
  `wrapper_price` DECIMAL(15,2) NOT NULL COMMENT '',
  `is_giftcard` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`quotation_item_id`)  COMMENT '',
  INDEX `quotation_id` (`quotation_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Quotation Item';


-- -----------------------------------------------------
-- Table `#__redshop_sample_request`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_sample_request` ;

CREATE TABLE IF NOT EXISTS `#__redshop_sample_request` (
  `request_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `name` VARCHAR(250) NOT NULL COMMENT '',
  `email` VARCHAR(250) NOT NULL COMMENT '',
  `colour_id` VARCHAR(250) NOT NULL COMMENT '',
  `block` TINYINT(4) NOT NULL COMMENT '',
  `reminder_1` TINYINT(1) NOT NULL COMMENT '',
  `reminder_2` TINYINT(1) NOT NULL COMMENT '',
  `reminder_3` TINYINT(1) NOT NULL COMMENT '',
  `reminder_coupon` TINYINT(1) NOT NULL COMMENT '',
  `registerdate` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`request_id`)  COMMENT '',
  INDEX `idx_block` (`block` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Sample Request';


-- -----------------------------------------------------
-- Table `#__redshop_shipping_boxes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_shipping_boxes` ;

CREATE TABLE IF NOT EXISTS `#__redshop_shipping_boxes` (
  `shipping_box_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `shipping_box_name` VARCHAR(255) NOT NULL COMMENT '',
  `shipping_box_length` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_box_width` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_box_height` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_box_priority` INT(11) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`shipping_box_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '',
  INDEX `idx_common` (`shipping_box_length` ASC, `shipping_box_width` ASC, `shipping_box_height` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Shipping Boxes';


-- -----------------------------------------------------
-- Table `#__redshop_shipping_rate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_shipping_rate` ;

CREATE TABLE IF NOT EXISTS `#__redshop_shipping_rate` (
  `shipping_rate_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `shipping_rate_name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '',
  `shipping_class` VARCHAR(255) NOT NULL DEFAULT '' COMMENT '',
  `shipping_rate_country` LONGTEXT NOT NULL COMMENT '',
  `shipping_rate_zip_start` VARCHAR(20) NOT NULL COMMENT '',
  `shipping_rate_zip_end` VARCHAR(20) NOT NULL COMMENT '',
  `shipping_rate_weight_start` DECIMAL(10,2) NOT NULL COMMENT '',
  `company_only` TINYINT(4) NOT NULL COMMENT '',
  `apply_vat` TINYINT(4) NOT NULL COMMENT '',
  `shipping_rate_weight_end` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_rate_volume_start` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_rate_volume_end` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_rate_ordertotal_start` DECIMAL(10,3) NOT NULL DEFAULT '0.000' COMMENT '',
  `shipping_rate_ordertotal_end` DECIMAL(10,3) NOT NULL COMMENT '',
  `shipping_rate_priority` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '',
  `shipping_rate_value` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '',
  `shipping_rate_package_fee` DECIMAL(10,2) NOT NULL DEFAULT '0.00' COMMENT '',
  `shipping_location_info` LONGTEXT NOT NULL COMMENT '',
  `shipping_rate_length_start` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_rate_length_end` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_rate_width_start` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_rate_width_end` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_rate_height_start` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_rate_height_end` DECIMAL(10,2) NOT NULL COMMENT '',
  `shipping_rate_on_shopper_group` LONGTEXT NOT NULL COMMENT '',
  `consignor_carrier_code` VARCHAR(255) NOT NULL COMMENT '',
  `shipping_tax_group_id` INT(11) NOT NULL COMMENT '',
  `deliver_type` INT(11) NOT NULL COMMENT '',
  `economic_displaynumber` VARCHAR(255) NOT NULL COMMENT '',
  `shipping_rate_on_product` LONGTEXT NOT NULL COMMENT '',
  `shipping_rate_on_category` LONGTEXT NOT NULL COMMENT '',
  `shipping_rate_state` LONGTEXT NOT NULL COMMENT '',
  PRIMARY KEY (`shipping_rate_id`)  COMMENT '',
  INDEX `shipping_rate_name` (`shipping_rate_name` ASC)  COMMENT '',
  INDEX `shipping_class` (`shipping_class` ASC)  COMMENT '',
  INDEX `shipping_rate_zip_start` (`shipping_rate_zip_start` ASC)  COMMENT '',
  INDEX `shipping_rate_zip_end` (`shipping_rate_zip_end` ASC)  COMMENT '',
  INDEX `company_only` (`company_only` ASC)  COMMENT '',
  INDEX `shipping_rate_value` (`shipping_rate_value` ASC)  COMMENT '',
  INDEX `shipping_tax_group_id` (`shipping_tax_group_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Shipping Rates';


-- -----------------------------------------------------
-- Table `#__redshop_shopper_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_shopper_group` ;

CREATE TABLE IF NOT EXISTS `#__redshop_shopper_group` (
  `shopper_group_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `shopper_group_name` VARCHAR(32) NULL DEFAULT NULL COMMENT '',
  `shopper_group_customer_type` TINYINT(4) NOT NULL COMMENT '',
  `shopper_group_portal` TINYINT(4) NOT NULL COMMENT '',
  `shopper_group_categories` LONGTEXT NOT NULL COMMENT '',
  `shopper_group_url` VARCHAR(255) NOT NULL COMMENT '',
  `shopper_group_logo` VARCHAR(255) NOT NULL COMMENT '',
  `shopper_group_introtext` LONGTEXT NOT NULL COMMENT '',
  `shopper_group_desc` TEXT NULL DEFAULT NULL COMMENT '',
  `parent_id` INT(11) NOT NULL COMMENT '',
  `default_shipping` TINYINT(4) NOT NULL COMMENT '',
  `default_shipping_rate` FLOAT(10,2) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `shopper_group_cart_checkout_itemid` INT(11) NOT NULL COMMENT '',
  `shopper_group_cart_itemid` INT(11) NOT NULL COMMENT '',
  `shopper_group_quotation_mode` TINYINT(4) NOT NULL COMMENT '',
  `show_price_without_vat` TINYINT(4) NOT NULL COMMENT '',
  `tax_group_id` INT(11) NOT NULL COMMENT '',
  `apply_product_price_vat` INT(11) NOT NULL COMMENT '',
  `show_price` VARCHAR(255) NOT NULL DEFAULT 'global' COMMENT '',
  `use_as_catalog` VARCHAR(255) NOT NULL DEFAULT 'global' COMMENT '',
  `is_logged_in` INT(11) NOT NULL DEFAULT '1' COMMENT '',
  `shopper_group_manufactures` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`shopper_group_id`)  COMMENT '',
  INDEX `idx_shopper_group_name` (`shopper_group_name` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '',
  INDEX `idx_parent_id` (`parent_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Shopper Groups that users can be assigned to';


-- -----------------------------------------------------
-- Table `#__redshop_siteviewer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_siteviewer` ;

CREATE TABLE IF NOT EXISTS `#__redshop_siteviewer` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `session_id` VARCHAR(250) NOT NULL COMMENT '',
  `created_date` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '',
  INDEX `idx_session_id` (`session_id` ASC)  COMMENT '',
  INDEX `idx_created_date` (`created_date` ASC)  COMMENT '')
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Site Viewer';


-- -----------------------------------------------------
-- Table `#__redshop_state`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_state` ;

CREATE TABLE IF NOT EXISTS `#__redshop_state` (
<<<<<<< HEAD
  `state_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `country_id` INT(11) NOT NULL DEFAULT '1' COMMENT '',
  `state_name` VARCHAR(64) NULL DEFAULT NULL COMMENT '',
  `state_3_code` CHAR(3) NULL DEFAULT NULL COMMENT '',
  `state_2_code` CHAR(2) NULL DEFAULT NULL COMMENT '',
  `checked_out` INT(11) NOT NULL COMMENT '',
  `checked_out_time` DATETIME NOT NULL COMMENT '',
  `show_state` INT(11) NOT NULL DEFAULT '2' COMMENT '',
  PRIMARY KEY (`state_id`)  COMMENT '',
  UNIQUE INDEX `state_3_code` (`country_id` ASC, `state_3_code` ASC)  COMMENT '',
  UNIQUE INDEX `state_2_code` (`country_id` ASC, `state_2_code` ASC)  COMMENT '',
  INDEX `idx_country_id` (`country_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'States that are assigned to a country';

=======
	`state_id` int(11) NOT NULL AUTO_INCREMENT,
	`country_id` int(11) DEFAULT NULL,
	`state_name` varchar(64) DEFAULT NULL,
	`state_3_code` char(3) DEFAULT NULL,
	`state_2_code` char(2) DEFAULT NULL,
	`checked_out` int(11) NOT NULL,
	`checked_out_time` datetime NOT NULL,
	`show_state` int(11) NOT NULL DEFAULT '2',
	PRIMARY KEY (`state_id`),
	UNIQUE KEY `state_3_code` (`country_id`,`state_3_code`),
	UNIQUE KEY `state_2_code` (`country_id`,`state_2_code`),
	INDEX `#__rs_state_country_fk1` (`country_id` ASC),
	CONSTRAINT `#__rs_state_country_fk1` 
		FOREIGN KEY (`country_id`) 
		REFERENCES `#__redshop_country` (`id`) 
		ON DELETE SET NULL 
		ON UPDATE CASCADE
)
	ENGINE=InnoDB 
	DEFAULT CHARSET=utf8 
	COMMENT='States that are assigned to a country';
>>>>>>> 02bad066235a887b57060118088bc5acb7b7e9ee

-- -----------------------------------------------------
-- Table `#__redshop_stockroom`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_stockroom` ;

CREATE TABLE IF NOT EXISTS `#__redshop_stockroom` (
  `stockroom_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `stockroom_name` VARCHAR(250) NOT NULL COMMENT '',
  `min_stock_amount` INT(11) NOT NULL COMMENT '',
  `stockroom_desc` LONGTEXT NOT NULL COMMENT '',
  `creation_date` DOUBLE NOT NULL COMMENT '',
  `min_del_time` INT(11) NOT NULL COMMENT '',
  `max_del_time` INT(11) NOT NULL COMMENT '',
  `show_in_front` TINYINT(1) NOT NULL COMMENT '',
  `delivery_time` VARCHAR(255) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`stockroom_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '',
  INDEX `idx_min_del_time` (`min_del_time` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Stockroom';


-- -----------------------------------------------------
-- Table `#__redshop_stockroom_amount_image`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_stockroom_amount_image` ;

CREATE TABLE IF NOT EXISTS `#__redshop_stockroom_amount_image` (
  `stock_amount_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `stockroom_id` INT(11) NOT NULL COMMENT '',
  `stock_option` TINYINT(4) NOT NULL COMMENT '',
  `stock_quantity` INT(11) NOT NULL COMMENT '',
  `stock_amount_image` VARCHAR(255) NOT NULL COMMENT '',
  `stock_amount_image_tooltip` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`stock_amount_id`)  COMMENT '',
  INDEX `idx_stockroom_id` (`stockroom_id` ASC)  COMMENT '',
  INDEX `idx_stock_option` (`stock_option` ASC)  COMMENT '',
  INDEX `idx_stock_quantity` (`stock_quantity` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP stockroom amount image';


-- -----------------------------------------------------
-- Table `#__redshop_subscription_renewal`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_subscription_renewal` ;

CREATE TABLE IF NOT EXISTS `#__redshop_subscription_renewal` (
  `renewal_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `before_no_days` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`renewal_id`)  COMMENT '',
  INDEX `idx_common` (`product_id` ASC, `before_no_days` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Subscription Renewal';


-- -----------------------------------------------------
-- Table `#__redshop_supplier`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_supplier` ;

CREATE TABLE IF NOT EXISTS `#__redshop_supplier` (
  `supplier_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `supplier_name` VARCHAR(250) NOT NULL COMMENT '',
  `supplier_desc` LONGTEXT NOT NULL COMMENT '',
  `supplier_email` VARCHAR(255) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`supplier_id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Supplier';


-- -----------------------------------------------------
-- Table `#__redshop_tax_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_tax_group` ;

CREATE TABLE IF NOT EXISTS `#__redshop_tax_group` (
  `tax_group_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `tax_group_name` VARCHAR(255) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`tax_group_id`)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Tax Group';


-- -----------------------------------------------------
-- Table `#__redshop_tax_rate`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_tax_rate` ;

CREATE TABLE IF NOT EXISTS `#__redshop_tax_rate` (
  `tax_rate_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `tax_state` VARCHAR(64) NULL DEFAULT NULL COMMENT '',
  `tax_country` VARCHAR(64) NULL DEFAULT NULL COMMENT '',
  `mdate` INT(11) NULL DEFAULT NULL COMMENT '',
  `tax_rate` DECIMAL(10,4) NULL DEFAULT NULL COMMENT '',
  `tax_group_id` INT(11) NOT NULL COMMENT '',
  `is_eu_country` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`tax_rate_id`)  COMMENT '',
  INDEX `idx_tax_group_id` (`tax_group_id` ASC)  COMMENT '',
  INDEX `idx_tax_country` (`tax_country` ASC)  COMMENT '',
  INDEX `idx_tax_state` (`tax_state` ASC)  COMMENT '',
  INDEX `idx_is_eu_country` (`is_eu_country` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Tax Rates';


-- -----------------------------------------------------
-- Table `#__redshop_template`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_template` ;

CREATE TABLE IF NOT EXISTS `#__redshop_template` (
  `template_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `template_name` VARCHAR(250) NOT NULL COMMENT '',
  `template_section` VARCHAR(250) NOT NULL COMMENT '',
  `template_desc` LONGTEXT NOT NULL COMMENT '',
  `order_status` VARCHAR(250) NOT NULL COMMENT '',
  `payment_methods` VARCHAR(250) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `shipping_methods` VARCHAR(255) NOT NULL COMMENT '',
  `checked_out` INT(11) NOT NULL COMMENT '',
  `checked_out_time` DATETIME NOT NULL COMMENT '',
  PRIMARY KEY (`template_id`)  COMMENT '',
  INDEX `idx_template_section` (`template_section` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Templates Detail';


-- -----------------------------------------------------
-- Table `#__redshop_textlibrary`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_textlibrary` ;

CREATE TABLE IF NOT EXISTS `#__redshop_textlibrary` (
  `textlibrary_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `text_name` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `text_desc` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `text_field` TEXT NULL DEFAULT NULL COMMENT '',
  `section` VARCHAR(255) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`textlibrary_id`)  COMMENT '',
  INDEX `idx_section` (`section` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP TextLibrary';


-- -----------------------------------------------------
-- Table `#__redshop_usercart`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_usercart` ;

CREATE TABLE IF NOT EXISTS `#__redshop_usercart` (
  `cart_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `cdate` INT(11) NOT NULL COMMENT '',
  `mdate` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`cart_id`)  COMMENT '',
  INDEX `idx_user_id` (`user_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP User Cart Item';


-- -----------------------------------------------------
-- Table `#__redshop_usercart_accessory_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_usercart_accessory_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_accessory_item` (
  `cart_acc_item_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `cart_item_id` INT(11) NOT NULL COMMENT '',
  `accessory_id` INT(11) NOT NULL COMMENT '',
  `accessory_quantity` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`cart_acc_item_id`)  COMMENT '',
  INDEX `idx_cart_item_id` (`cart_item_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP User Cart Accessory Item';


-- -----------------------------------------------------
-- Table `#__redshop_usercart_attribute_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_usercart_attribute_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_attribute_item` (
  `cart_att_item_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `cart_item_id` INT(11) NOT NULL COMMENT '',
  `section_id` INT(11) NOT NULL COMMENT '',
  `section` VARCHAR(25) NOT NULL COMMENT '',
  `parent_section_id` INT(11) NOT NULL COMMENT '',
  `is_accessory_att` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`cart_att_item_id`)  COMMENT '',
  INDEX `idx_common` (`is_accessory_att` ASC, `section` ASC, `parent_section_id` ASC, `cart_item_id` ASC)  COMMENT '',
  INDEX `idx_cart_item_id` (`cart_item_id` ASC)  COMMENT '',
  INDEX `idx_parent_section_id` (`parent_section_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP User cart Attribute Item';


-- -----------------------------------------------------
-- Table `#__redshop_usercart_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_usercart_item` ;

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_item` (
  `cart_item_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `cart_idx` INT(11) NOT NULL COMMENT '',
  `cart_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `product_quantity` INT(11) NOT NULL COMMENT '',
  `product_wrapper_id` INT(11) NOT NULL COMMENT '',
  `product_subscription_id` INT(11) NOT NULL COMMENT '',
  `giftcard_id` INT(11) NOT NULL COMMENT '',
  `attribs` VARCHAR(5120) NOT NULL COMMENT 'Specified user attributes related with current item',
  PRIMARY KEY (`cart_item_id`)  COMMENT '',
  INDEX `idx_cart_id` (`cart_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP User Cart Item';


-- -----------------------------------------------------
-- Table `#__redshop_users_info`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_users_info` ;

CREATE TABLE IF NOT EXISTS `#__redshop_users_info` (
  `users_info_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `user_email` VARCHAR(255) NOT NULL COMMENT '',
  `address_type` VARCHAR(11) NOT NULL COMMENT '',
  `firstname` VARCHAR(250) NOT NULL COMMENT '',
  `lastname` VARCHAR(250) NOT NULL COMMENT '',
  `vat_number` VARCHAR(250) NOT NULL COMMENT '',
  `tax_exempt` TINYINT(4) NOT NULL COMMENT '',
  `shopper_group_id` INT(11) NOT NULL COMMENT '',
  `country_code` VARCHAR(11) NOT NULL COMMENT '',
  `address` VARCHAR(255) NOT NULL COMMENT '',
  `city` VARCHAR(50) NOT NULL COMMENT '',
  `state_code` VARCHAR(11) NOT NULL COMMENT '',
  `zipcode` VARCHAR(255) NOT NULL COMMENT '',
  `phone` VARCHAR(50) NOT NULL COMMENT '',
  `tax_exempt_approved` TINYINT(1) NOT NULL COMMENT '',
  `approved` TINYINT(1) NOT NULL COMMENT '',
  `is_company` TINYINT(4) NOT NULL COMMENT '',
  `ean_number` VARCHAR(250) NOT NULL COMMENT '',
  `braintree_vault_number` VARCHAR(255) NOT NULL COMMENT '',
  `veis_vat_number` VARCHAR(255) NOT NULL COMMENT '',
  `veis_status` VARCHAR(255) NOT NULL COMMENT '',
  `company_name` VARCHAR(255) NOT NULL COMMENT '',
  `requesting_tax_exempt` TINYINT(4) NOT NULL COMMENT '',
  `accept_terms_conditions` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`users_info_id`)  COMMENT '',
  INDEX `idx_common` (`address_type` ASC, `user_id` ASC)  COMMENT '',
  INDEX `user_id` (`user_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Users Information';


-- -----------------------------------------------------
-- Table `#__redshop_wishlist`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_wishlist` ;

CREATE TABLE IF NOT EXISTS `#__redshop_wishlist` (
  `wishlist_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `wishlist_name` VARCHAR(100) NOT NULL COMMENT '',
  `user_id` INT(11) NOT NULL COMMENT '',
  `comment` MEDIUMTEXT NOT NULL COMMENT '',
  `cdate` DOUBLE NOT NULL COMMENT '',
  PRIMARY KEY (`wishlist_id`)  COMMENT '',
  INDEX `idx_user_id` (`user_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP wishlist';


-- -----------------------------------------------------
-- Table `#__redshop_wishlist_product`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_wishlist_product` ;

CREATE TABLE IF NOT EXISTS `#__redshop_wishlist_product` (
  `wishlist_product_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `wishlist_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `cdate` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`wishlist_product_id`)  COMMENT '',
  INDEX `idx_wishlist_id` (`wishlist_id` ASC)  COMMENT '',
  INDEX `idx_common` (`product_id` ASC, `wishlist_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Wishlist Product';


-- -----------------------------------------------------
-- Table `#__redshop_wishlist_userfielddata`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_wishlist_userfielddata` ;

CREATE TABLE IF NOT EXISTS `#__redshop_wishlist_userfielddata` (
  `fieldid` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `wishlist_id` INT(11) NOT NULL COMMENT '',
  `product_id` INT(11) NOT NULL COMMENT '',
  `userfielddata` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`fieldid`)  COMMENT '',
  INDEX `idx_common` (`wishlist_id` ASC, `product_id` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Wishlist Product userfielddata';


-- -----------------------------------------------------
-- Table `#__redshop_wrapper`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_wrapper` ;

CREATE TABLE IF NOT EXISTS `#__redshop_wrapper` (
  `wrapper_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `product_id` VARCHAR(255) NOT NULL COMMENT '',
  `category_id` VARCHAR(250) NOT NULL COMMENT '',
  `wrapper_name` VARCHAR(255) NOT NULL COMMENT '',
  `wrapper_price` DOUBLE NOT NULL COMMENT '',
  `wrapper_image` VARCHAR(255) NOT NULL COMMENT '',
  `createdate` INT(11) NOT NULL COMMENT '',
  `wrapper_use_to_all` TINYINT(4) NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`wrapper_id`)  COMMENT '',
  INDEX `idx_wrapper_use_to_all` (`wrapper_use_to_all` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Wrapper';


-- -----------------------------------------------------
-- Table `#__redshop_xml_export`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_xml_export` ;

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export` (
  `xmlexport_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `filename` VARCHAR(255) NOT NULL COMMENT '',
  `display_filename` VARCHAR(255) NOT NULL COMMENT '',
  `parent_name` VARCHAR(255) NOT NULL COMMENT '',
  `section_type` VARCHAR(255) NOT NULL COMMENT '',
  `auto_sync` TINYINT(4) NOT NULL COMMENT '',
  `sync_on_request` TINYINT(4) NOT NULL COMMENT '',
  `auto_sync_interval` INT(11) NOT NULL COMMENT '',
  `xmlexport_date` INT(11) NOT NULL COMMENT '',
  `xmlexport_filetag` TEXT NOT NULL COMMENT '',
  `element_name` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `use_to_all_users` TINYINT(4) NOT NULL COMMENT '',
  `xmlexport_billingtag` TEXT NOT NULL COMMENT '',
  `billing_element_name` VARCHAR(255) NOT NULL COMMENT '',
  `xmlexport_shippingtag` TEXT NOT NULL COMMENT '',
  `shipping_element_name` VARCHAR(255) NOT NULL COMMENT '',
  `xmlexport_orderitemtag` TEXT NOT NULL COMMENT '',
  `orderitem_element_name` VARCHAR(255) NOT NULL COMMENT '',
  `xmlexport_stocktag` TEXT NOT NULL COMMENT '',
  `stock_element_name` VARCHAR(255) NOT NULL COMMENT '',
  `xmlexport_prdextrafieldtag` TEXT NOT NULL COMMENT '',
  `prdextrafield_element_name` VARCHAR(255) NOT NULL COMMENT '',
  `xmlexport_on_category` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`xmlexport_id`)  COMMENT '',
  INDEX `idx_filename` (`filename` ASC)  COMMENT '',
  INDEX `idx_auto_sync` (`auto_sync` ASC)  COMMENT '',
  INDEX `idx_sync_on_request` (`sync_on_request` ASC)  COMMENT '',
  INDEX `idx_auto_sync_interval` (`auto_sync_interval` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP XML Export';


-- -----------------------------------------------------
-- Table `#__redshop_xml_export_ipaddress`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_xml_export_ipaddress` ;

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export_ipaddress` (
  `xmlexport_ip_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `xmlexport_id` INT(11) NOT NULL COMMENT '',
  `access_ipaddress` VARCHAR(255) NOT NULL COMMENT '',
  PRIMARY KEY (`xmlexport_ip_id`)  COMMENT '',
  INDEX `idx_xmlexport_id` (`xmlexport_id` ASC)  COMMENT '',
  INDEX `idx_access_ipaddress` (`access_ipaddress` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP XML Export Ip Address';


-- -----------------------------------------------------
-- Table `#__redshop_xml_export_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_xml_export_log` ;

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export_log` (
  `xmlexport_log_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `xmlexport_id` INT(11) NOT NULL COMMENT '',
  `xmlexport_filename` VARCHAR(255) NOT NULL COMMENT '',
  `xmlexport_date` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`xmlexport_log_id`)  COMMENT '',
  INDEX `idx_xmlexport_id` (`xmlexport_id` ASC)  COMMENT '',
  INDEX `idx_xmlexport_filename` (`xmlexport_filename` ASC)  COMMENT '')
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP XML Export log';


-- -----------------------------------------------------
-- Table `#__redshop_xml_import`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_xml_import` ;

CREATE TABLE IF NOT EXISTS `#__redshop_xml_import` (
  `xmlimport_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `filename` VARCHAR(255) NOT NULL COMMENT '',
  `display_filename` VARCHAR(255) NOT NULL COMMENT '',
  `xmlimport_url` VARCHAR(255) NOT NULL COMMENT '',
  `section_type` VARCHAR(255) NOT NULL COMMENT '',
  `auto_sync` TINYINT(4) NOT NULL COMMENT '',
  `sync_on_request` TINYINT(4) NOT NULL COMMENT '',
  `auto_sync_interval` INT(11) NOT NULL COMMENT '',
  `override_existing` TINYINT(4) NOT NULL COMMENT '',
  `add_prefix_for_existing` VARCHAR(50) NOT NULL COMMENT '',
  `xmlimport_date` INT(11) NOT NULL COMMENT '',
  `xmlimport_filetag` TEXT NOT NULL COMMENT '',
  `xmlimport_billingtag` TEXT NOT NULL COMMENT '',
  `xmlimport_shippingtag` TEXT NOT NULL COMMENT '',
  `xmlimport_orderitemtag` TEXT NOT NULL COMMENT '',
  `xmlimport_stocktag` TEXT NOT NULL COMMENT '',
  `xmlimport_prdextrafieldtag` TEXT NOT NULL COMMENT '',
  `published` TINYINT(4) NOT NULL COMMENT '',
  `element_name` VARCHAR(255) NOT NULL COMMENT '',
  `billing_element_name` VARCHAR(255) NOT NULL COMMENT '',
  `shipping_element_name` VARCHAR(255) NOT NULL COMMENT '',
  `orderitem_element_name` VARCHAR(255) NOT NULL COMMENT '',
  `stock_element_name` VARCHAR(255) NOT NULL COMMENT '',
  `prdextrafield_element_name` VARCHAR(255) NOT NULL COMMENT '',
  `xmlexport_billingtag` TEXT NOT NULL COMMENT '',
  `xmlexport_shippingtag` TEXT NOT NULL COMMENT '',
  `xmlexport_orderitemtag` TEXT NOT NULL COMMENT '',
  PRIMARY KEY (`xmlimport_id`)  COMMENT '',
  INDEX `idx_auto_sync` (`auto_sync` ASC)  COMMENT '',
  INDEX `idx_sync_on_request` (`sync_on_request` ASC)  COMMENT '',
  INDEX `idx_auto_sync_interval` (`auto_sync_interval` ASC)  COMMENT '',
  INDEX `idx_published` (`published` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP XML Import';


-- -----------------------------------------------------
-- Table `#__redshop_xml_import_log`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_xml_import_log` ;

CREATE TABLE IF NOT EXISTS `#__redshop_xml_import_log` (
  `xmlimport_log_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `xmlimport_id` INT(11) NOT NULL COMMENT '',
  `xmlimport_filename` VARCHAR(255) NOT NULL COMMENT '',
  `xmlimport_date` INT(11) NOT NULL COMMENT '',
  PRIMARY KEY (`xmlimport_log_id`)  COMMENT '',
  INDEX `idx_xmlimport_id` (`xmlimport_id` ASC)  COMMENT '')
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP XML Import log';


-- -----------------------------------------------------
-- Table `#__redshop_zipcode`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_zipcode` ;

CREATE TABLE IF NOT EXISTS `#__redshop_zipcode` (
  `zipcode_id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `country_code` VARCHAR(10) NOT NULL DEFAULT '' COMMENT '',
  `state_code` VARCHAR(10) NOT NULL DEFAULT '' COMMENT '',
  `city_name` VARCHAR(64) NULL DEFAULT NULL COMMENT '',
  `zipcode` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  `zipcodeto` VARCHAR(255) NULL DEFAULT NULL COMMENT '',
  PRIMARY KEY (`zipcode_id`)  COMMENT '',
  INDEX `zipcode` (`zipcode` ASC)  COMMENT '',
  INDEX `idx_country_code` (`country_code` ASC)  COMMENT '',
  INDEX `idx_state_code` (`state_code` ASC)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `#__redshop_alerts`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_alerts` ;

CREATE TABLE IF NOT EXISTS `#__redshop_alerts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT '',
  `message` VARCHAR(255) NOT NULL COMMENT '',
  `sent_date` DATETIME NOT NULL COMMENT '',
  `read` TINYINT(4) NOT NULL COMMENT '',
  PRIMARY KEY (`id`)  COMMENT '')
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'redSHOP Notification Alert';

SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
