DELETE FROM `#__redshop_template` WHERE `template_section` = 'shippment_invoice_template';

CREATE TABLE IF NOT EXISTS `#__redshop_accessmanager` (
	`id`           INT(11)      NOT NULL AUTO_INCREMENT,
	`section_name` VARCHAR(256) NOT NULL,
	`gid`          INT(11)      NOT NULL,
	`view`         ENUM('1', '0') DEFAULT NULL,
	`add`          ENUM('1', '0') DEFAULT NULL,
	`edit`         ENUM('1', '0') DEFAULT NULL,
	`delete`       ENUM('1', '0') DEFAULT NULL,
	PRIMARY KEY (`id`),
	KEY `idx_section_name` (`section_name`(255)),
	KEY `idx_gid` (`gid`),
	KEY `idx_view` (`view`),
	KEY `idx_add` (`add`),
	KEY `idx_edit` (`edit`),
	KEY `idx_delete` (`delete`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Access Manager';

CREATE TABLE IF NOT EXISTS `#__redshop_product_discount_calc_extra` (
	`pdcextra_id` INT(11)      NOT NULL AUTO_INCREMENT,
	`option_name` VARCHAR(255) NOT NULL,
	`oprand`      CHAR(1)      NOT NULL,
	`price`       FLOAT(10, 2) NOT NULL,
	`product_id`  INT(11)      NOT NULL,
	PRIMARY KEY (`pdcextra_id`),
	KEY `idx_product_id` (`product_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Product Discount Calculator Extra Value';

CREATE TABLE IF NOT EXISTS `#__redshop_wishlist_userfielddata` (
	`fieldid`       INT(11) NOT NULL AUTO_INCREMENT,
	`wishlist_id`   INT(11) NOT NULL,
	`product_id`    INT(11) NOT NULL,
	`userfielddata` TEXT    NOT NULL,
	PRIMARY KEY (`fieldid`),
	KEY `idx_common` (`wishlist_id`, `product_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Wishlist Product userfielddata';

CREATE TABLE IF NOT EXISTS `#__redshop_usercart` (
	`cart_id` INT(11) NOT NULL AUTO_INCREMENT,
	`user_id` INT(11) NOT NULL,
	`cdate`   INT(11) NOT NULL,
	`mdate`   INT(11) NOT NULL,
	PRIMARY KEY (`cart_id`),
	KEY `idx_user_id` (`user_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP User Cart Item';

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_accessory_item` (
	`cart_acc_item_id`   INT(11) NOT NULL AUTO_INCREMENT,
	`cart_item_id`       INT(11) NOT NULL,
	`accessory_id`       INT(11) NOT NULL,
	`accessory_quantity` INT(11) NOT NULL,
	PRIMARY KEY (`cart_acc_item_id`),
	KEY `idx_cart_item_id` (`cart_item_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP User Cart Accessory Item';

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_attribute_item` (
	`cart_att_item_id`  INT(11)     NOT NULL AUTO_INCREMENT,
	`cart_item_id`      INT(11)     NOT NULL,
	`section_id`        INT(11)     NOT NULL,
	`section`           VARCHAR(25) NOT NULL,
	`parent_section_id` INT(11)     NOT NULL,
	`is_accessory_att`  TINYINT(4)  NOT NULL,
	PRIMARY KEY (`cart_att_item_id`),
	KEY `idx_common` (`is_accessory_att`, `section`, `parent_section_id`, `cart_item_id`),
	KEY `idx_cart_item_id` (`cart_item_id`),
	KEY `idx_parent_section_id` (`parent_section_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP User cart Attribute Item';

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_item` (
	`cart_item_id` int(11) NOT NULL AUTO_INCREMENT,
	`cart_idx` int(11) NOT NULL,
	`cart_id` int(11) NOT NULL,
	`product_id` int(11) NOT NULL,
	`product_quantity` int(11) NOT NULL,
	`product_wrapper_id` int(11) NOT NULL,
	`product_subscription_id` int(11) NOT NULL,
	`giftcard_id` int(11) NOT NULL,
	`attribs` varchar(5020) NOT NULL COMMENT 'Specified user attributes related with current item',
	PRIMARY KEY (`cart_item_id`),
	KEY `idx_cart_id` (`cart_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='redSHOP User Cart Item';

CREATE TABLE IF NOT EXISTS `#__redshop_orderbarcode_log` (
	`log_id`      INT(11)      NOT NULL AUTO_INCREMENT,
	`order_id`    INT(11)      NOT NULL,
	`barcode`     VARCHAR(255) NOT NULL,
	`user_id`     INT(11)      NOT NULL,
	`search_date` DATETIME     NOT NULL,
	PRIMARY KEY (`log_id`),
	KEY `idx_order_id` (`order_id`)
)
	ENGINE =MyISAM
	DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__redshop_zipcode` (
	`zipcode_id`   INT(11)     NOT NULL AUTO_INCREMENT,
	`country_code` VARCHAR(10) NOT NULL DEFAULT '',
	`state_code`   VARCHAR(10) NOT NULL DEFAULT '',
	`city_name`    VARCHAR(64)          DEFAULT NULL,
	`zipcode`      VARCHAR(255)         DEFAULT NULL,
	`zipcodeto`    VARCHAR(255)         DEFAULT NULL,
	PRIMARY KEY (`zipcode_id`),
	KEY `zipcode` (`zipcode`),
	KEY `idx_country_code` (`country_code`),
	KEY `idx_state_code` (`state_code`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__redshop_ordernumber_track` (
	`trackdatetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='redSHOP Order number track';

CREATE TABLE IF NOT EXISTS `#__redshop_notifystock_users` (
	`id`                  INT(11) NOT NULL AUTO_INCREMENT,
	`product_id`          INT(11) NOT NULL,
	`property_id`         INT(11) NOT NULL,
	`subproperty_id`      INT(11) NOT NULL,
	`user_id`             INT(11) NOT NULL,
	`notification_status` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	KEY `idx_common` (`product_id`, `property_id`, `subproperty_id`, `notification_status`, `user_id`),
	KEY `idx_user_id` (`user_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8;

ALTER TABLE `#__redshop_product_price` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `#__redshop_product_subattribute_color` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

ALTER TABLE `#__redshop_product_voucher` CHANGE `voucher_type` `voucher_type` VARCHAR(250) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `#__redshop_stockroom` CHANGE `stockroom_desc` `stockroom_desc` LONGTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

DROP TABLE IF EXISTS `#__redshop_shipping_method`;