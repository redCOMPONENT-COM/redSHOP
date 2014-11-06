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

CREATE TABLE IF NOT EXISTS `#__redshop_attribute_set` (
	`attribute_set_id`   INT(11)      NOT NULL AUTO_INCREMENT,
	`attribute_set_name` VARCHAR(255) NOT NULL,
	`published`          TINYINT(4)   NOT NULL,
	PRIMARY KEY (`attribute_set_id`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Attribute set detail';

CREATE TABLE IF NOT EXISTS `#__redshop_cart` (
	`session_id` VARCHAR(255) NOT NULL,
	`product_id` INT(11)      NOT NULL,
	`section`    VARCHAR(250) NOT NULL,
	`qty`        INT(11)      NOT NULL,
	`time`       DOUBLE       NOT NULL,
	KEY `idx_session_id` (`session_id`),
	KEY `idx_product_id` (`product_id`),
	KEY `idx_section` (`section`),
	KEY `idx_time` (`time`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Cart';

CREATE TABLE IF NOT EXISTS `#__redshop_catalog` (
	`catalog_id`   INT(11)      NOT NULL AUTO_INCREMENT,
	`catalog_name` VARCHAR(250) NOT NULL,
	`published`    TINYINT(4)   NOT NULL,
	PRIMARY KEY (`catalog_id`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Catalog';

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_colour` (
	`colour_id`  INT(11)      NOT NULL AUTO_INCREMENT,
	`sample_id`  INT(11)      NOT NULL,
	`code_image` VARCHAR(250) NOT NULL,
	`is_image`   TINYINT(4)   NOT NULL,
	PRIMARY KEY (`colour_id`),
	KEY `idx_sample_id` (`sample_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Catalog Colour';

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_request` (
	`catalog_user_id` INT(11)      NOT NULL AUTO_INCREMENT,
	`catalog_id`      INT(11)      NOT NULL,
	`name`            VARCHAR(250) NOT NULL,
	`email`           VARCHAR(250) NOT NULL,
	`registerDate`    INT(11)      NOT NULL,
	`block`           TINYINT(4)   NOT NULL,
	`reminder_1`      TINYINT(4)   NOT NULL,
	`reminder_2`      TINYINT(4)   NOT NULL,
	`reminder_3`      TINYINT(4)   NOT NULL,
	PRIMARY KEY (`catalog_user_id`),
	KEY `idx_block` (`block`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Catalog Request';

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_sample` (
	`sample_id`   INT(11)      NOT NULL AUTO_INCREMENT,
	`sample_name` VARCHAR(100) NOT NULL,
	`published`   TINYINT(4)   NOT NULL,
	PRIMARY KEY (`sample_id`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Catalog Sample';

CREATE TABLE IF NOT EXISTS `#__redshop_category` (
	`category_id`                INT(11)                              NOT NULL AUTO_INCREMENT,
	`category_name`              VARCHAR(250)                         NOT NULL,
	`category_short_description` LONGTEXT                             NOT NULL,
	`category_description`       LONGTEXT                             NOT NULL,
	`category_template`          INT(11)                              NOT NULL,
	`category_more_template`     VARCHAR(255)                         NOT NULL,
	`products_per_page`          INT(11)                              NOT NULL,
	`category_thumb_image`       VARCHAR(250)                         NOT NULL,
	`category_full_image`        VARCHAR(250)                         NOT NULL,
	`metakey`                    VARCHAR(250)                         NOT NULL,
	`metadesc`                   LONGTEXT                             NOT NULL,
	`metalanguage_setting`       TEXT                                 NOT NULL,
	`metarobot_info`             TEXT                                 NOT NULL,
	`pagetitle`                  TEXT                                 NOT NULL,
	`pageheading`                LONGTEXT                             NOT NULL,
	`sef_url`                    TEXT                                 NOT NULL,
	`published`                  TINYINT(4)                           NOT NULL,
	`category_pdate`             TIMESTAMP                            NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`ordering`                   INT(11)                              NOT NULL,
	`canonical_url`              TEXT                                 NOT NULL,
	`category_back_full_image`   VARCHAR(250)                         NOT NULL,
	`compare_template_id`        VARCHAR(255)                         NOT NULL,
	`append_to_global_seo`       ENUM('append', 'prepend', 'replace') NOT NULL DEFAULT 'append',
	PRIMARY KEY (`category_id`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Category';

CREATE TABLE IF NOT EXISTS `#__redshop_category_xref` (
	`category_parent_id` INT(11) NOT NULL DEFAULT '0',
	`category_child_id`  INT(11) NOT NULL DEFAULT '0',
	KEY `category_xref_category_parent_id` (`category_parent_id`),
	KEY `category_xref_category_child_id` (`category_child_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Category relation';

CREATE TABLE IF NOT EXISTS `#__redshop_country` (
	`country_id`     INT(11)      NOT NULL AUTO_INCREMENT,
	`country_name`   VARCHAR(64) DEFAULT NULL,
	`country_3_code` CHAR(3)     DEFAULT NULL,
	`country_2_code` CHAR(2)     DEFAULT NULL,
	`country_jtext`  VARCHAR(255) NOT NULL,
	PRIMARY KEY (`country_id`),
	KEY `idx_country_3_code` (`country_3_code`),
	KEY `idx_country_2_code` (`country_2_code`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='Country records';

CREATE TABLE IF NOT EXISTS `#__redshop_coupons` (
	`coupon_id`        INT(16)        NOT NULL AUTO_INCREMENT,
	`coupon_code`      VARCHAR(32)    NOT NULL DEFAULT '',
	`percent_or_total` TINYINT(4)     NOT NULL,
	`coupon_value`     DECIMAL(12, 2) NOT NULL DEFAULT '0.00',
	`start_date`       DOUBLE         NOT NULL,
	`end_date`         DOUBLE         NOT NULL,
	`coupon_type`      TINYINT(4)     NOT NULL
	COMMENT '0 - Global, 1 - User Specific',
	`userid`           INT(11)        NOT NULL,
	`coupon_left`      INT(11)        NOT NULL,
	`published`        TINYINT(4)     NOT NULL,
	`subtotal`         INT(11)        NOT NULL,
	`order_id`         INT(11)        NOT NULL,
	`free_shipping`    TINYINT(4)     NOT NULL,
	PRIMARY KEY (`coupon_id`),
	KEY `idx_coupon_code` (`coupon_code`),
	KEY `idx_percent_or_total` (`percent_or_total`),
	KEY `idx_start_date` (`start_date`),
	KEY `idx_end_date` (`end_date`),
	KEY `idx_coupon_type` (`coupon_type`),
	KEY `idx_userid` (`userid`),
	KEY `idx_coupon_left` (`coupon_left`),
	KEY `idx_published` (`published`),
	KEY `idx_subtotal` (`subtotal`),
	KEY `idx_order_id` (`order_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Coupons';

CREATE TABLE IF NOT EXISTS `#__redshop_coupons_transaction` (
	`transaction_coupon_id` INT(11)        NOT NULL AUTO_INCREMENT,
	`coupon_id`             INT(11)        NOT NULL,
	`coupon_code`           VARCHAR(255)   NOT NULL,
	`coupon_value`          DECIMAL(10, 3) NOT NULL,
	`userid`                INT(11)        NOT NULL,
	`trancation_date`       INT(11)        NOT NULL,
	`published`             INT(11)        NOT NULL,
	PRIMARY KEY (`transaction_coupon_id`),
	KEY `idx_coupon_id` (`coupon_id`),
	KEY `idx_coupon_code` (`coupon_code`),
	KEY `idx_coupon_value` (`coupon_value`),
	KEY `idx_userid` (`userid`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Coupons Transaction';

CREATE TABLE IF NOT EXISTS `#__redshop_cron` (
	`id`        INT(11)    NOT NULL AUTO_INCREMENT,
	`date`      DATE       NOT NULL,
	`published` TINYINT(4) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `idx_date` (`date`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Cron Job';

CREATE TABLE IF NOT EXISTS `#__redshop_currency` (
	`currency_id`   INT(11) NOT NULL AUTO_INCREMENT,
	`currency_name` VARCHAR(64) DEFAULT NULL,
	`currency_code` CHAR(3)     DEFAULT NULL,
	PRIMARY KEY (`currency_id`),
	KEY `idx_currency_code` (`currency_code`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Currency Detail';

CREATE TABLE IF NOT EXISTS `#__redshop_customer_question` (
	`question_id`   INT(11)      NOT NULL AUTO_INCREMENT,
	`parent_id`     INT(11)      NOT NULL,
	`product_id`    INT(11)      NOT NULL,
	`question`      LONGTEXT     NOT NULL,
	`user_id`       INT(11)      NOT NULL,
	`user_name`     VARCHAR(255) NOT NULL,
	`user_email`    VARCHAR(255) NOT NULL,
	`published`     TINYINT(4)   NOT NULL,
	`question_date` INT(11)      NOT NULL,
	`ordering`      INT(11)      NOT NULL,
	`telephone`     VARCHAR(50)  NOT NULL,
	`address`       VARCHAR(250) NOT NULL,
	PRIMARY KEY (`question_id`),
	KEY `idx_published` (`published`),
	KEY `idx_product_id` (`product_id`),
	KEY `idx_parent_id` (`parent_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Customer Question';

CREATE TABLE IF NOT EXISTS `#__redshop_discount` (
	`discount_id`     INT(11)        NOT NULL AUTO_INCREMENT,
	`amount`          INT(11)        NOT NULL,
	`condition`       TINYINT(1)     NOT NULL DEFAULT '1',
	`discount_amount` DECIMAL(10, 4) NOT NULL,
	`discount_type`   TINYINT(4)     NOT NULL,
	`start_date`      DOUBLE         NOT NULL,
	`end_date`        DOUBLE         NOT NULL,
	`published`       TINYINT(4)     NOT NULL,
	PRIMARY KEY (`discount_id`),
	KEY `idx_start_date` (`start_date`),
	KEY `idx_end_date` (`end_date`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Discount';

CREATE TABLE IF NOT EXISTS `#__redshop_discount_product` (
	`discount_product_id` INT(11)        NOT NULL AUTO_INCREMENT,
	`amount`              INT(11)        NOT NULL,
	`condition`           TINYINT(1)     NOT NULL DEFAULT '1',
	`discount_amount`     DECIMAL(10, 2) NOT NULL,
	`discount_type`       TINYINT(4)     NOT NULL,
	`start_date`          DOUBLE         NOT NULL,
	`end_date`            DOUBLE         NOT NULL,
	`published`           TINYINT(4)     NOT NULL,
	`category_ids`        TEXT           NOT NULL,
	PRIMARY KEY (`discount_product_id`),
	KEY `idx_published` (`published`),
	KEY `idx_start_date` (`start_date`),
	KEY `idx_end_date` (`end_date`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__redshop_discount_product_shoppers` (
	`discount_product_id` INT(11) NOT NULL,
	`shopper_group_id`    INT(11) NOT NULL,
	KEY `idx_discount_product_id` (`discount_product_id`),
	KEY `idx_shopper_group_id` (`shopper_group_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__redshop_discount_shoppers` (
	`discount_id`      INT(11) NOT NULL,
	`shopper_group_id` INT(11) NOT NULL,
	KEY `idx_discount_id` (`discount_id`),
	KEY `idx_shopper_group_id` (`shopper_group_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__redshop_economic_accountgroup` (
	`accountgroup_id`                  INT(11)      NOT NULL AUTO_INCREMENT,
	`accountgroup_name`                VARCHAR(255) NOT NULL,
	`economic_vat_account`             VARCHAR(255) NOT NULL,
	`economic_nonvat_account`          VARCHAR(255) NOT NULL,
	`economic_discount_nonvat_account` VARCHAR(255) NOT NULL,
	`economic_shipping_vat_account`    VARCHAR(255) NOT NULL,
	`economic_shipping_nonvat_account` VARCHAR(255) NOT NULL,
	`economic_discount_product_number` VARCHAR(255) NOT NULL,
	`published`                        TINYINT(4)   NOT NULL,
	`economic_service_nonvat_account`  VARCHAR(255) NOT NULL,
	`economic_discount_vat_account`    VARCHAR(255) NOT NULL,
	PRIMARY KEY (`accountgroup_id`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Economic Account Group';

CREATE TABLE IF NOT EXISTS `#__redshop_fields` (
	`field_id`            INT(11)      NOT NULL AUTO_INCREMENT,
	`field_title`         VARCHAR(250) NOT NULL,
	`field_name`          VARCHAR(20)  NOT NULL,
	`field_type`          VARCHAR(20)  NOT NULL,
	`field_desc`          LONGTEXT     NOT NULL,
	`field_class`         VARCHAR(20)  NOT NULL,
	`field_section`       VARCHAR(20)  NOT NULL,
	`field_maxlength`     INT(11)      NOT NULL,
	`field_cols`          INT(11)      NOT NULL,
	`field_rows`          INT(11)      NOT NULL,
	`field_size`          TINYINT(4)   NOT NULL,
	`field_show_in_front` TINYINT(4)   NOT NULL,
	`required`            TINYINT(4)   NOT NULL,
	`published`           TINYINT(4)   NOT NULL,
	`display_in_product`  TINYINT(4)   NOT NULL,
	`ordering`            INT(11)      NOT NULL,
	`display_in_checkout` TINYINT(4)   NOT NULL,
	PRIMARY KEY (`field_id`),
	KEY `idx_published` (`published`),
	KEY `idx_field_section` (`field_section`),
	KEY `idx_field_type` (`field_type`),
	KEY `idx_required` (`required`),
	KEY `idx_field_name` (`field_name`),
	KEY `idx_field_show_in_front` (`field_show_in_front`),
	KEY `idx_display_in_product` (`display_in_product`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Fields';

CREATE TABLE IF NOT EXISTS `#__redshop_fields_data` (
	`data_id`    INT(11)      NOT NULL AUTO_INCREMENT,
	`fieldid`    INT(11)     DEFAULT NULL,
	`data_txt`   LONGTEXT,
	`itemid`     INT(11)     DEFAULT NULL,
	`section`    VARCHAR(20) DEFAULT NULL,
	`alt_text`   VARCHAR(255) NOT NULL,
	`image_link` VARCHAR(255) NOT NULL,
	`user_email` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`data_id`),
	KEY `itemid` (`itemid`),
	KEY `idx_fieldid` (`fieldid`),
	KEY `idx_itemid` (`itemid`),
	KEY `idx_section` (`section`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Fields Data';

CREATE TABLE IF NOT EXISTS `#__redshop_fields_value` (
	`value_id`    INT(11)      NOT NULL AUTO_INCREMENT,
	`field_id`    INT(11)      NOT NULL,
	`field_value` VARCHAR(250) NOT NULL,
	`field_name`  VARCHAR(250) NOT NULL,
	`alt_text`    VARCHAR(255) NOT NULL,
	`image_link`  TEXT         NOT NULL,
	PRIMARY KEY (`value_id`),
	KEY `idx_field_id` (`field_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Fields Value';

CREATE TABLE IF NOT EXISTS `#__redshop_giftcard` (
	`giftcard_id`       INT(11)        NOT NULL AUTO_INCREMENT,
	`giftcard_name`     VARCHAR(255)   NOT NULL,
	`giftcard_price`    DECIMAL(10, 3) NOT NULL,
	`giftcard_value`    DECIMAL(10, 3) NOT NULL,
	`giftcard_validity` INT(11)        NOT NULL,
	`giftcard_date`     INT(11)        NOT NULL,
	`giftcard_bgimage`  VARCHAR(255)   NOT NULL,
	`giftcard_image`    VARCHAR(255)   NOT NULL,
	`published`         INT(11)        NOT NULL,
	`giftcard_desc`     LONGTEXT       NOT NULL,
	`customer_amount`   INT(11)        NOT NULL,
	`accountgroup_id`   INT(11)        NOT NULL,
	PRIMARY KEY (`giftcard_id`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Giftcard';

CREATE TABLE IF NOT EXISTS `#__redshop_mail` (
	`mail_id`           INT(11)      NOT NULL AUTO_INCREMENT,
	`mail_name`         VARCHAR(255) NOT NULL,
	`mail_subject`      VARCHAR(255) NOT NULL,
	`mail_section`      VARCHAR(255) NOT NULL,
	`mail_order_status` VARCHAR(11)  NOT NULL,
	`mail_body`         LONGTEXT     NOT NULL,
	`published`         TINYINT(4)   NOT NULL,
	`mail_bcc`          VARCHAR(255) NOT NULL,
	PRIMARY KEY (`mail_id`),
	KEY `idx_mail_section` (`mail_section`),
	KEY `idx_mail_order_status` (`mail_order_status`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Mail Center';

CREATE TABLE IF NOT EXISTS `#__redshop_manufacturer` (
	`manufacturer_id`         INT(11)      NOT NULL AUTO_INCREMENT,
	`manufacturer_name`       VARCHAR(250) NOT NULL,
	`manufacturer_desc`       LONGTEXT     NOT NULL,
	`manufacturer_email`      VARCHAR(250) NOT NULL,
	`product_per_page`        INT(11)      NOT NULL,
	`template_id`             INT(11)      NOT NULL,
	`metakey`                 TEXT         NOT NULL,
	`metadesc`                TEXT         NOT NULL,
	`metalanguage_setting`    TEXT         NOT NULL,
	`metarobot_info`          TEXT         NOT NULL,
	`pagetitle`               TEXT         NOT NULL,
	`pageheading`             TEXT         NOT NULL,
	`sef_url`                 TEXT         NOT NULL,
	`published`               INT(11)      NOT NULL,
	`ordering`                INT(11)      NOT NULL,
	`manufacturer_url`        VARCHAR(255) NOT NULL,
	`excluding_category_list` TEXT         NOT NULL,
	PRIMARY KEY (`manufacturer_id`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Manufacturer';

CREATE TABLE IF NOT EXISTS `#__redshop_mass_discount` (
	`mass_discount_id`   INT(11)       NOT NULL AUTO_INCREMENT,
	`discount_product`   LONGTEXT      NOT NULL,
	`category_id`        LONGTEXT      NOT NULL,
	`manufacturer_id`    LONGTEXT      NOT NULL,
	`discount_type`      TINYINT(4)    NOT NULL,
	`discount_amount`    DOUBLE(10, 2) NOT NULL,
	`discount_startdate` INT(11)       NOT NULL,
	`discount_enddate`   INT(11)       NOT NULL,
	`discount_name`      LONGTEXT      NOT NULL,
	PRIMARY KEY (`mass_discount_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Page Viewer';

CREATE TABLE IF NOT EXISTS `#__redshop_media` (
	`media_id`             INT(11)      NOT NULL AUTO_INCREMENT,
	`media_name`           VARCHAR(250) NOT NULL,
	`media_alternate_text` VARCHAR(255) NOT NULL,
	`media_section`        VARCHAR(20)  NOT NULL,
	`section_id`           INT(11)      NOT NULL,
	`media_type`           VARCHAR(250) NOT NULL,
	`media_mimetype`       VARCHAR(20)  NOT NULL,
	`published`            TINYINT(4)   NOT NULL,
	`ordering`             INT(11)      NOT NULL,
	PRIMARY KEY (`media_id`),
	KEY `idx_section_id` (`section_id`),
	KEY `idx_media_section` (`media_section`),
	KEY `idx_media_type` (`media_type`),
	KEY `idx_media_name` (`media_name`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Media';

CREATE TABLE IF NOT EXISTS `#__redshop_media_download` (
	`id`       INT(11)      NOT NULL AUTO_INCREMENT,
	`name`     VARCHAR(255) NOT NULL,
	`media_id` INT(11)      NOT NULL,
	PRIMARY KEY (`id`),
	KEY `idx_media_id` (`media_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Media Additional Downloadable Files';

CREATE TABLE IF NOT EXISTS `#__redshop_newsletter` (
	`newsletter_id` INT(11)      NOT NULL AUTO_INCREMENT,
	`name`          VARCHAR(255) NOT NULL,
	`subject`       VARCHAR(255) NOT NULL,
	`body`          LONGTEXT     NOT NULL,
	`template_id`   INT(11)      NOT NULL,
	`published`     TINYINT(4)   NOT NULL,
	PRIMARY KEY (`newsletter_id`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Newsletter';

CREATE TABLE IF NOT EXISTS `#__redshop_newsletter_subscription` (
	`subscription_id` INT(11)      NOT NULL AUTO_INCREMENT,
	`user_id`         INT(11)      NOT NULL,
	`date`            INT(11)      NOT NULL,
	`newsletter_id`   INT(11)      NOT NULL,
	`name`            VARCHAR(255) NOT NULL,
	`email`           VARCHAR(255) NOT NULL,
	`checkout`        TINYINT(4)   NOT NULL,
	`published`       INT(11)      NOT NULL,
	PRIMARY KEY (`subscription_id`),
	KEY `idx_user_id` (`user_id`),
	KEY `idx_newsletter_id` (`newsletter_id`),
	KEY `idx_email` (`email`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Newsletter subscribers';

CREATE TABLE IF NOT EXISTS `#__redshop_newsletter_tracker` (
	`tracker_id`      INT(11)      NOT NULL AUTO_INCREMENT,
	`newsletter_id`   INT(11)      NOT NULL,
	`subscription_id` INT(11)      NOT NULL,
	`subscriber_name` VARCHAR(255) NOT NULL,
	`user_id`         INT(11)      NOT NULL,
	`read`            TINYINT(4)   NOT NULL,
	`date`            DOUBLE       NOT NULL,
	PRIMARY KEY (`tracker_id`),
	KEY `idx_newsletter_id` (`newsletter_id`),
	KEY `idx_read` (`read`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Newsletter Tracker';

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

CREATE TABLE IF NOT EXISTS `#__redshop_ordernumber_track` (
	`trackdatetime` DATETIME NOT NULL
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Order number track';

CREATE TABLE IF NOT EXISTS `#__redshop_orders` (
	`order_id`                 INT(11)        NOT NULL AUTO_INCREMENT,
	`user_id`                  INT(11)        NOT NULL DEFAULT '0',
	`order_number`             VARCHAR(32)             DEFAULT NULL,
	`barcode`                  VARCHAR(13)    NOT NULL,
	`user_info_id`             VARCHAR(32)             DEFAULT NULL,
	`order_total`              DECIMAL(15, 2) NOT NULL DEFAULT '0.00',
	`order_subtotal`           DECIMAL(15, 5)          DEFAULT NULL,
	`order_tax`                DECIMAL(10, 2)          DEFAULT NULL,
	`order_tax_details`        TEXT           NOT NULL,
	`order_shipping`           DECIMAL(10, 2)          DEFAULT NULL,
	`order_shipping_tax`       DECIMAL(10, 2)          DEFAULT NULL,
	`coupon_discount`          DECIMAL(12, 2) NOT NULL DEFAULT '0.00',
	`order_discount`           DECIMAL(12, 2) NOT NULL DEFAULT '0.00',
	`special_discount_amount`  DECIMAL(12, 2) NOT NULL,
	`payment_dicount`          DECIMAL(12, 2) NOT NULL,
	`order_status`             VARCHAR(5)              DEFAULT NULL,
	`order_payment_status`     VARCHAR(25)    NOT NULL,
	`cdate`                    INT(11)                 DEFAULT NULL,
	`mdate`                    INT(11)                 DEFAULT NULL,
	`ship_method_id`           VARCHAR(255)            DEFAULT NULL,
	`customer_note`            TEXT           NOT NULL,
	`ip_address`               VARCHAR(15)    NOT NULL DEFAULT '',
	`encr_key`                 VARCHAR(255)   NOT NULL,
	`split_payment`            INT(11)        NOT NULL,
	`invoice_no`               VARCHAR(255)   NOT NULL,
	`mail1_status`             TINYINT(1)     NOT NULL,
	`mail2_status`             TINYINT(1)     NOT NULL,
	`mail3_status`             TINYINT(1)     NOT NULL,
	`special_discount`         DECIMAL(10, 2) NOT NULL,
	`payment_discount`         DECIMAL(10, 2) NOT NULL,
	`is_booked`                TINYINT(1)     NOT NULL,
	`order_label_create`       TINYINT(1)     NOT NULL,
	`vm_order_number`          VARCHAR(32)    NOT NULL,
	`requisition_number`       VARCHAR(255)   NOT NULL,
	`bookinvoice_number`       INT(11)        NOT NULL,
	`bookinvoice_date`         INT(11)        NOT NULL,
	`referral_code`            VARCHAR(50)    NOT NULL,
	`customer_message`         VARCHAR(255)   NOT NULL,
	`shop_id`                  VARCHAR(255)   NOT NULL,
	`order_discount_vat`       DECIMAL(10, 3) NOT NULL,
	`track_no`                 VARCHAR(250)   NOT NULL,
	`payment_oprand`           VARCHAR(50)    NOT NULL,
	`discount_type`            VARCHAR(255)   NOT NULL,
	`analytics_status`         INT(1)         NOT NULL,
	`tax_after_discount`       DECIMAL(10, 3) NOT NULL,
	`recuuring_subcription_id` VARCHAR(500)   NOT NULL,
	PRIMARY KEY (`order_id`),
	KEY `idx_orders_user_id` (`user_id`),
	KEY `idx_orders_order_number` (`order_number`),
	KEY `idx_orders_user_info_id` (`user_info_id`),
	KEY `idx_orders_ship_method_id` (`ship_method_id`),
	KEY `idx_barcode` (`barcode`),
	KEY `idx_order_payment_status` (`order_payment_status`),
	KEY `idx_order_status` (`order_status`),
	KEY `vm_order_number` (`vm_order_number`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Order Detail';

CREATE TABLE IF NOT EXISTS `#__redshop_order_acc_item` (
	`order_item_acc_id`       INT(11)        NOT NULL AUTO_INCREMENT,
	`order_item_id`           INT(11)        NOT NULL,
	`product_id`              INT(11)        NOT NULL,
	`order_acc_item_sku`      VARCHAR(255)   NOT NULL,
	`order_acc_item_name`     VARCHAR(255)   NOT NULL,
	`order_acc_price`         DECIMAL(15, 4) NOT NULL,
	`order_acc_vat`           DECIMAL(15, 4) NOT NULL,
	`product_quantity`        INT(11)        NOT NULL,
	`product_acc_item_price`  DECIMAL(15, 4) NOT NULL,
	`product_acc_final_price` DECIMAL(15, 4) NOT NULL,
	`product_attribute`       TEXT           NOT NULL,
	PRIMARY KEY (`order_item_acc_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Order Accessory Item Detail';

CREATE TABLE IF NOT EXISTS `#__redshop_order_attribute_item` (
	`order_att_item_id`  INT(11)        NOT NULL AUTO_INCREMENT,
	`order_item_id`      INT(11)        NOT NULL,
	`section_id`         INT(11)        NOT NULL,
	`section`            VARCHAR(250)   NOT NULL,
	`parent_section_id`  INT(11)        NOT NULL,
	`section_name`       VARCHAR(250)   NOT NULL,
	`section_price`      DECIMAL(15, 4) NOT NULL,
	`section_vat`        DECIMAL(15, 4) NOT NULL,
	`section_oprand`     CHAR(1)        NOT NULL,
	`is_accessory_att`   TINYINT(4)     NOT NULL,
	`stockroom_id`       VARCHAR(255)   NOT NULL,
	`stockroom_quantity` VARCHAR(255)   NOT NULL,
	PRIMARY KEY (`order_att_item_id`),
	KEY `idx_order_item_id` (`order_item_id`),
	KEY `idx_section` (`section`),
	KEY `idx_parent_section_id` (`parent_section_id`),
	KEY `idx_is_accessory_att` (`is_accessory_att`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP order Attribute item';

CREATE TABLE IF NOT EXISTS `#__redshop_order_item` (
	`order_item_id`               INT(11)        NOT NULL AUTO_INCREMENT,
	`order_id`                    INT(11)                 DEFAULT NULL,
	`user_info_id`                VARCHAR(32)             DEFAULT NULL,
	`supplier_id`                 INT(11)                 DEFAULT NULL,
	`product_id`                  INT(11)                 DEFAULT NULL,
	`order_item_sku`              VARCHAR(64)    NOT NULL DEFAULT '',
	`order_item_name`             VARCHAR(255)   NOT NULL,
	`product_quantity`            INT(11)                 DEFAULT NULL,
	`product_item_price`          DECIMAL(15, 4)          DEFAULT NULL,
	`product_item_price_excl_vat` DECIMAL(15, 4)          DEFAULT NULL,
	`product_final_price`         DECIMAL(12, 4) NOT NULL DEFAULT '0.0000',
	`order_item_currency`         VARCHAR(16)             DEFAULT NULL,
	`order_status`                VARCHAR(250)            DEFAULT NULL,
	`customer_note`               TEXT           NOT NULL,
	`cdate`                       INT(11)                 DEFAULT NULL,
	`mdate`                       INT(11)                 DEFAULT NULL,
	`product_attribute`           TEXT,
	`product_accessory`           TEXT           NOT NULL,
	`delivery_time`               INT(11)        NOT NULL,
	`stockroom_id`                VARCHAR(255)   NOT NULL,
	`stockroom_quantity`          VARCHAR(255)   NOT NULL,
	`is_split`                    TINYINT(1)     NOT NULL,
	`attribute_image`             TEXT           NOT NULL,
	`is_giftcard`                 TINYINT(4)     NOT NULL,
	`wrapper_id`                  INT(11)        NOT NULL,
	`wrapper_price`               DECIMAL(10, 2) NOT NULL,
	`giftcard_user_name`          VARCHAR(255)   NOT NULL,
	`giftcard_user_email`         VARCHAR(255)   NOT NULL,
	`product_item_old_price`      DECIMAL(10, 4) NOT NULL,
	`product_purchase_price`      DECIMAL(10, 4) NOT NULL,
	`discount_calc_data`          TEXT           NOT NULL,
	PRIMARY KEY (`order_item_id`),
	KEY `idx_order_id` (`order_id`),
	KEY `idx_user_info_id` (`user_info_id`),
	KEY `idx_product_id` (`product_id`),
	KEY `idx_order_status` (`order_status`),
	KEY `idx_cdate` (`cdate`),
	KEY `idx_is_giftcard` (`is_giftcard`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Order Item Detail';

CREATE TABLE IF NOT EXISTS `#__redshop_order_payment` (
	`payment_order_id`       BIGINT(20)    NOT NULL AUTO_INCREMENT,
	`order_id`               INT(11)       NOT NULL DEFAULT '0',
	`payment_method_id`      INT(11)                DEFAULT NULL,
	`order_payment_code`     VARCHAR(30)   NOT NULL DEFAULT '',
	`order_payment_cardname` BLOB          NOT NULL,
	`order_payment_number`   BLOB,
	`order_payment_ccv`      BLOB          NOT NULL,
	`order_payment_amount`   DOUBLE(10, 2) NOT NULL,
	`order_payment_expire`   INT(11)                DEFAULT NULL,
	`order_payment_name`     VARCHAR(255)           DEFAULT NULL,
	`payment_method_class`   VARCHAR(256)           DEFAULT NULL,
	`order_payment_trans_id` TEXT          NOT NULL,
	`authorize_status`       VARCHAR(255)           DEFAULT NULL,
	`order_transfee`         DOUBLE(10, 2) NOT NULL,
	PRIMARY KEY (`payment_order_id`),
	KEY `idx_order_id` (`order_id`),
	KEY `idx_payment_method_id` (`payment_method_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Order Payment Detail';

CREATE TABLE IF NOT EXISTS `#__redshop_order_status` (
	`order_status_id`   INT(11)     NOT NULL AUTO_INCREMENT,
	`order_status_code` VARCHAR(64) NOT NULL,
	`order_status_name` VARCHAR(64) DEFAULT NULL,
	`published`         TINYINT(4)  NOT NULL,
	PRIMARY KEY (`order_status_id`),
	UNIQUE KEY `order_status_code` (`order_status_code`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Orders Status';

CREATE TABLE IF NOT EXISTS `#__redshop_order_status_log` (
	`order_status_log_id`  INT(11)     NOT NULL AUTO_INCREMENT,
	`order_id`             INT(11)     NOT NULL,
	`order_status`         VARCHAR(5)  NOT NULL,
	`order_payment_status` VARCHAR(25) NOT NULL,
	`date_changed`         INT(11)     NOT NULL,
	`customer_note`        TEXT        NOT NULL,
	PRIMARY KEY (`order_status_log_id`),
	KEY `idx_order_id` (`order_id`),
	KEY `idx_order_status` (`order_status`)
)
	ENGINE =MyISAM
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Orders Status history';

CREATE TABLE IF NOT EXISTS `#__redshop_order_users_info` (
	`order_info_id`         INT(11)      NOT NULL AUTO_INCREMENT,
	`users_info_id`         INT(11)      NOT NULL,
	`order_id`              INT(11)      NOT NULL,
	`user_id`               INT(11)      NOT NULL,
	`firstname`             VARCHAR(250) NOT NULL,
	`lastname`              VARCHAR(250) NOT NULL,
	`address_type`          VARCHAR(255) NOT NULL,
	`vat_number`            VARCHAR(250) NOT NULL,
	`tax_exempt`            TINYINT(4)   NOT NULL,
	`shopper_group_id`      INT(11)      NOT NULL,
	`address`               VARCHAR(255) NOT NULL,
	`city`                  VARCHAR(255) NOT NULL,
	`country_code`          VARCHAR(11)  NOT NULL,
	`state_code`            VARCHAR(11)  NOT NULL,
	`zipcode`               VARCHAR(255) NOT NULL,
	`phone`                 VARCHAR(50)  NOT NULL,
	`tax_exempt_approved`   TINYINT(1)   NOT NULL,
	`approved`              TINYINT(1)   NOT NULL,
	`is_company`            TINYINT(4)   NOT NULL,
	`user_email`            VARCHAR(255) NOT NULL,
	`company_name`          VARCHAR(255) NOT NULL,
	`ean_number`            VARCHAR(250) NOT NULL,
	`requesting_tax_exempt` TINYINT(4)   NOT NULL,
	`thirdparty_email`      VARCHAR(255) NOT NULL,
	PRIMARY KEY (`order_info_id`),
	KEY `idx_order_id` (`order_id`),
	KEY `idx_address_type` (`address_type`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Order User Information';

CREATE TABLE IF NOT EXISTS `#__redshop_pageviewer` (
	`id`           INT(11)      NOT NULL AUTO_INCREMENT,
	`user_id`      INT(11)      NOT NULL,
	`session_id`   VARCHAR(250) NOT NULL,
	`section`      VARCHAR(250) NOT NULL,
	`section_id`   INT(11)      NOT NULL,
	`hit`          INT(11)      NOT NULL,
	`created_date` INT(11)      NOT NULL,
	PRIMARY KEY (`id`),
	KEY `idx_session_id` (`session_id`),
	KEY `idx_section` (`section`),
	KEY `idx_section_id` (`section_id`),
	KEY `idx_created_date` (`created_date`)
)
	ENGINE =MyISAM
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Page Viewer';

CREATE TABLE IF NOT EXISTS `#__redshop_product` (
	`product_id`                 INT(11)                              NOT NULL AUTO_INCREMENT,
	`product_parent_id`          INT(11)                              NOT NULL,
	`manufacturer_id`            INT(11)                              NOT NULL,
	`supplier_id`                INT(11)                              NOT NULL,
	`product_on_sale`            TINYINT(4)                           NOT NULL,
	`product_special`            TINYINT(4)                           NOT NULL,
	`product_download`           TINYINT(4)                           NOT NULL,
	`product_template`           INT(11)                              NOT NULL,
	`product_name`               VARCHAR(250)                         NOT NULL,
	`product_price`              DOUBLE                               NOT NULL,
	`discount_price`             DOUBLE                               NOT NULL,
	`discount_stratdate`         INT(11)                              NOT NULL,
	`discount_enddate`           INT(11)                              NOT NULL,
	`product_number`             VARCHAR(250)                         NOT NULL,
	`product_type`               VARCHAR(20)                          NOT NULL,
	`product_s_desc`             LONGTEXT                             NOT NULL,
	`product_desc`               LONGTEXT                             NOT NULL,
	`product_volume`             DOUBLE                               NOT NULL,
	`product_tax_id`             INT(11)                              NOT NULL,
	`published`                  TINYINT(4)                           NOT NULL,
	`product_thumb_image`        VARCHAR(250)                         NOT NULL,
	`product_full_image`         VARCHAR(250)                         NOT NULL,
	`publish_date`               DATETIME                             NOT NULL,
	`update_date`                TIMESTAMP                            NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
	`visited`                    INT(11)                              NOT NULL,
	`metakey`                    TEXT                                 NOT NULL,
	`metadesc`                   TEXT                                 NOT NULL,
	`metalanguage_setting`       TEXT                                 NOT NULL,
	`metarobot_info`             TEXT                                 NOT NULL,
	`pagetitle`                  TEXT                                 NOT NULL,
	`pageheading`                TEXT                                 NOT NULL,
	`sef_url`                    TEXT                                 NOT NULL,
	`cat_in_sefurl`              INT(11)                              NOT NULL,
	`weight`                     FLOAT(10, 3)                         NOT NULL,
	`expired`                    TINYINT(4)                           NOT NULL,
	`not_for_sale`               TINYINT(4)                           NOT NULL,
	`use_discount_calc`          TINYINT(4)                           NOT NULL,
	`discount_calc_method`       VARCHAR(255)                         NOT NULL,
	`min_order_product_quantity` INT(11)                              NOT NULL,
	`attribute_set_id`           INT(11)                              NOT NULL,
	`product_length`             DECIMAL(10, 2)                       NOT NULL,
	`product_height`             DECIMAL(10, 2)                       NOT NULL,
	`product_width`              DECIMAL(10, 2)                       NOT NULL,
	`product_diameter`           DECIMAL(10, 2)                       NOT NULL,
	`product_availability_date`  INT(11)                              NOT NULL,
	`use_range`                  TINYINT(4)                           NOT NULL,
	`product_tax_group_id`       INT(11)                              NOT NULL,
	`product_download_days`      INT(11)                              NOT NULL,
	`product_download_limit`     INT(11)                              NOT NULL,
	`product_download_clock`     INT(11)                              NOT NULL,
	`product_download_clock_min` INT(11)                              NOT NULL,
	`accountgroup_id`            INT(11)                              NOT NULL,
	`canonical_url`              TEXT                                 NOT NULL,
	`minimum_per_product_total`  INT(11)                              NOT NULL,
	`allow_decimal_piece`        INT(4)                               NOT NULL,
	`quantity_selectbox_value`   VARCHAR(255)                         NOT NULL,
	`checked_out`                INT(11)                              NOT NULL,
	`checked_out_time`           DATETIME                             NOT NULL,
	`max_order_product_quantity` INT(11)                              NOT NULL,
	`product_download_infinite`  TINYINT(4)                           NOT NULL,
	`product_back_full_image`    VARCHAR(250)                         NOT NULL,
	`product_back_thumb_image`   VARCHAR(250)                         NOT NULL,
	`product_preview_image`      VARCHAR(250)                         NOT NULL,
	`product_preview_back_image` VARCHAR(250)                         NOT NULL,
	`preorder`                   VARCHAR(255)                         NOT NULL,
	`append_to_global_seo`       ENUM('append', 'prepend', 'replace') NOT NULL DEFAULT 'append',
	PRIMARY KEY (`product_id`),
	UNIQUE KEY `idx_product_number` (`product_number`),
	KEY `idx_manufacturer_id` (`manufacturer_id`),
	KEY `idx_product_on_sale` (`product_on_sale`),
	KEY `idx_product_special` (`product_special`),
	KEY `idx_product_parent_id` (`product_parent_id`),
	KEY `idx_common` (`published`, `expired`, `product_parent_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Products';

CREATE TABLE IF NOT EXISTS `#__redshop_product_accessory` (
	`accessory_id`        INT(11)    NOT NULL AUTO_INCREMENT,
	`product_id`          INT(11)    NOT NULL,
	`child_product_id`    INT(11)    NOT NULL,
	`accessory_price`     DOUBLE     NOT NULL,
	`oprand`              CHAR(1)    NOT NULL,
	`setdefault_selected` TINYINT(4) NOT NULL,
	`ordering`            INT(11)    NOT NULL,
	`category_id`         INT(11)    NOT NULL,
	PRIMARY KEY (`accessory_id`),
	KEY `idx_common` (`product_id`, `child_product_id`),
	KEY `idx_child_product_id` (`child_product_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Products Accessory';

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute` (
	`attribute_id`             INT(11)      NOT NULL AUTO_INCREMENT,
	`attribute_name`           VARCHAR(250) NOT NULL,
	`attribute_required`       TINYINT(4)   NOT NULL,
	`allow_multiple_selection` TINYINT(1)   NOT NULL,
	`hide_attribute_price`     TINYINT(1)   NOT NULL,
	`product_id`               INT(11)      NOT NULL,
	`ordering`                 INT(11)      NOT NULL,
	`attribute_set_id`         INT(11)      NOT NULL,
	`display_type`             VARCHAR(255) NOT NULL,
	`attribute_published`      INT(11)      NOT NULL DEFAULT '1',
	PRIMARY KEY (`attribute_id`),
	KEY `idx_product_id` (`product_id`),
	KEY `idx_attribute_name` (`attribute_name`),
	KEY `idx_attribute_set_id` (`attribute_set_id`),
	KEY `idx_attribute_published` (`attribute_published`),
	KEY `idx_attribute_required` (`attribute_required`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Products Attribute';

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_price` (
	`price_id`             INT(11)      NOT NULL AUTO_INCREMENT,
	`section_id`           INT(11)      NOT NULL,
	`section`              VARCHAR(255) NOT NULL,
	`product_price`        DOUBLE       NOT NULL,
	`product_currency`     VARCHAR(10)  NOT NULL,
	`cdate`                INT(11)      NOT NULL,
	`shopper_group_id`     INT(11)      NOT NULL,
	`price_quantity_start` INT(11)      NOT NULL,
	`price_quantity_end`   BIGINT(20)   NOT NULL,
	`discount_price`       DOUBLE       NOT NULL,
	`discount_start_date`  INT(11)      NOT NULL,
	`discount_end_date`    INT(11)      NOT NULL,
	PRIMARY KEY (`price_id`),
	KEY `idx_shopper_group_id` (`shopper_group_id`),
	KEY `idx_common` (`section_id`, `section`, `price_quantity_start`, `price_quantity_end`, `shopper_group_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Product Attribute Price';

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_property` (
	`property_id`         INT(11)      NOT NULL AUTO_INCREMENT,
	`attribute_id`        INT(11)      NOT NULL,
	`property_name`       VARCHAR(255) NOT NULL,
	`property_price`      DOUBLE       NOT NULL,
	`oprand`              CHAR(1)      NOT NULL DEFAULT '+',
	`property_image`      VARCHAR(255) NOT NULL,
	`property_main_image` VARCHAR(255) NOT NULL,
	`ordering`            INT(11)      NOT NULL,
	`setdefault_selected` TINYINT(4)   NOT NULL,
	`setrequire_selected` TINYINT(3)   NOT NULL,
	`setmulti_selected`   TINYINT(4)   NOT NULL,
	`setdisplay_type`     VARCHAR(255) NOT NULL,
	`extra_field`         VARCHAR(250) NOT NULL,
	`property_published`  INT(11)      NOT NULL DEFAULT '1',
	`property_number`     VARCHAR(255) NOT NULL,
	PRIMARY KEY (`property_id`),
	KEY `idx_attribute_id` (`attribute_id`),
	KEY `idx_setrequire_selected` (`setrequire_selected`),
	KEY `idx_property_published` (`property_published`),
	KEY `idx_property_number` (`property_number`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Products Attribute Property';

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_stockroom_xref` (
	`section_id`       INT(11)      NOT NULL,
	`section`          VARCHAR(255) NOT NULL,
	`stockroom_id`     INT(11)      NOT NULL,
	`quantity`         INT(11)      NOT NULL,
	`preorder_stock`   INT(11)      NOT NULL,
	`ordered_preorder` INT(11)      NOT NULL,
	KEY `idx_stockroom_id` (`stockroom_id`),
	KEY `idx_common` (`section_id`, `section`, `stockroom_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Product Attribute Stockroom relation';

CREATE TABLE IF NOT EXISTS `#__redshop_product_category_xref` (
	`category_id` INT(11) NOT NULL,
	`product_id`  INT(11) NOT NULL,
	`ordering`    INT(11) NOT NULL,
	KEY `ref_category` (`product_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Product Category Relation';

CREATE TABLE IF NOT EXISTS `#__redshop_product_compare` (
	`compare_id` INT(11) NOT NULL AUTO_INCREMENT,
	`product_id` INT(11) NOT NULL,
	`user_id`    INT(11) NOT NULL,
	PRIMARY KEY (`compare_id`),
	KEY `idx_common` (`user_id`, `product_id`),
	KEY `idx_product_id` (`product_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Product Comparision';

CREATE TABLE IF NOT EXISTS `#__redshop_product_discount_calc` (
	`id`                   INT(11)      NOT NULL AUTO_INCREMENT,
	`product_id`           INT(11)      NOT NULL,
	`area_start`           FLOAT(10, 2) NOT NULL,
	`area_end`             FLOAT(10, 2) NOT NULL,
	`area_price`           DOUBLE       NOT NULL,
	`discount_calc_unit`   VARCHAR(255) NOT NULL,
	`area_start_converted` FLOAT(20, 8) NOT NULL,
	`area_end_converted`   FLOAT(20, 8) NOT NULL,
	PRIMARY KEY (`id`),
	KEY `idx_product_id` (`product_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Product Discount Calculator';

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

CREATE TABLE IF NOT EXISTS `#__redshop_product_download` (
	`product_id`            INT(11)      NOT NULL DEFAULT '0',
	`user_id`               INT(11)      NOT NULL DEFAULT '0',
	`order_id`              INT(11)      NOT NULL DEFAULT '0',
	`end_date`              INT(11)      NOT NULL DEFAULT '0',
	`download_max`          INT(11)      NOT NULL DEFAULT '0',
	`download_id`           VARCHAR(32)  NOT NULL DEFAULT '',
	`file_name`             VARCHAR(255) NOT NULL DEFAULT '',
	`product_serial_number` VARCHAR(255) NOT NULL DEFAULT '',
	PRIMARY KEY (`download_id`),
	KEY `idx_product_id` (`product_id`),
	KEY `idx_user_id` (`user_id`),
	KEY `idx_order_id` (`order_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Downloadable Products';

CREATE TABLE IF NOT EXISTS `#__redshop_product_download_log` (
	`user_id`       INT(11)      NOT NULL,
	`download_id`   VARCHAR(32)  NOT NULL,
	`download_time` INT(11)      NOT NULL,
	`ip`            VARCHAR(255) NOT NULL,
	KEY `idx_download_id` (`download_id`)
)
	ENGINE =MyISAM
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Downloadable Products Logs';

CREATE TABLE IF NOT EXISTS `#__redshop_product_navigator` (
	`navigator_id`     INT(11)      NOT NULL AUTO_INCREMENT,
	`product_id`       INT(11)      NOT NULL,
	`child_product_id` INT(11)      NOT NULL,
	`navigator_name`   VARCHAR(255) NOT NULL,
	`ordering`         INT(11)      NOT NULL,
	PRIMARY KEY (`navigator_id`),
	KEY `idx_product_id` (`product_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Products Navigator';

CREATE TABLE IF NOT EXISTS `#__redshop_product_price` (
	`price_id`             INT(11)        NOT NULL AUTO_INCREMENT,
	`product_id`           INT(11)        NOT NULL,
	`product_price`        DECIMAL(12, 4) NOT NULL,
	`product_currency`     VARCHAR(10)    NOT NULL,
	`cdate`                DATE           NOT NULL,
	`shopper_group_id`     INT(11)        NOT NULL,
	`price_quantity_start` INT(11)        NOT NULL,
	`price_quantity_end`   BIGINT(20)     NOT NULL,
	`discount_price`       DECIMAL(12, 4) NOT NULL,
	`discount_start_date`  INT(11)        NOT NULL,
	`discount_end_date`    INT(11)        NOT NULL,
	PRIMARY KEY (`price_id`),
	KEY `idx_product_id` (`product_id`),
	KEY `idx_shopper_group_id` (`shopper_group_id`),
	KEY `idx_price_quantity_start` (`price_quantity_start`),
	KEY `idx_price_quantity_end` (`price_quantity_end`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Product Price';

CREATE TABLE IF NOT EXISTS `#__redshop_product_rating` (
	`rating_id`    INT(11)      NOT NULL AUTO_INCREMENT,
	`product_id`   INT(11)      NOT NULL DEFAULT '0',
	`title`        VARCHAR(255) NOT NULL,
	`comment`      TEXT         NOT NULL,
	`userid`       INT(11)      NOT NULL DEFAULT '0',
	`time`         INT(11)      NOT NULL DEFAULT '0',
	`user_rating`  TINYINT(1)   NOT NULL DEFAULT '0',
	`favoured`     TINYINT(4)   NOT NULL,
	`published`    TINYINT(4)   NOT NULL,
	`email`        VARCHAR(200) NOT NULL,
	`username`     VARCHAR(255) NOT NULL,
	`company_name` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`rating_id`),
	UNIQUE KEY `product_id` (`product_id`, `userid`),
	KEY `idx_published` (`published`),
	KEY `idx_email` (`email`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8;

CREATE TABLE IF NOT EXISTS `#__redshop_product_related` (
	`related_id` INT(11) NOT NULL,
	`product_id` INT(11) NOT NULL,
	`ordering`   INT(11) NOT NULL,
	KEY `idx_product_id` (`product_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Related Products';

CREATE TABLE IF NOT EXISTS `#__redshop_product_serial_number` (
	`serial_id`     INT(11)      NOT NULL AUTO_INCREMENT,
	`product_id`    INT(11)      NOT NULL,
	`serial_number` VARCHAR(255) NOT NULL,
	`is_used`       TINYINT(1)   NOT NULL DEFAULT '0',
	PRIMARY KEY (`serial_id`),
	KEY `idx_common` (`product_id`, `is_used`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP downloadable product serial numbers';

CREATE TABLE IF NOT EXISTS `#__redshop_product_stockroom_xref` (
	`product_id`       INT(11) NOT NULL,
	`stockroom_id`     INT(11) NOT NULL,
	`quantity`         INT(11) NOT NULL,
	`preorder_stock`   INT(11) NOT NULL,
	`ordered_preorder` INT(11) NOT NULL,
	KEY `idx_stockroom_id` (`stockroom_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Products Stockroom Relation';

CREATE TABLE IF NOT EXISTS `#__redshop_product_subattribute_color` (
	`subattribute_color_id`         INT(11)      NOT NULL AUTO_INCREMENT,
	`subattribute_color_name`       VARCHAR(255) NOT NULL,
	`subattribute_color_price`      DOUBLE       NOT NULL,
	`oprand`                        CHAR(1)      NOT NULL,
	`subattribute_color_image`      VARCHAR(255) NOT NULL,
	`subattribute_id`               INT(11)      NOT NULL,
	`ordering`                      INT(11)      NOT NULL,
	`setdefault_selected`           TINYINT(4)   NOT NULL,
	`extra_field`                   VARCHAR(250) NOT NULL,
	`subattribute_published`        INT(11)      NOT NULL DEFAULT '1',
	`subattribute_color_number`     VARCHAR(255) NOT NULL,
	`subattribute_color_title`      VARCHAR(255) NOT NULL,
	`subattribute_color_main_image` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`subattribute_color_id`),
	KEY `idx_subattribute_id` (`subattribute_id`),
	KEY `idx_subattribute_published` (`subattribute_published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='Product Subattribute Color';

CREATE TABLE IF NOT EXISTS `#__redshop_product_subscribe_detail` (
	`product_subscribe_id` INT(11)    NOT NULL AUTO_INCREMENT,
	`order_id`             INT(11)    NOT NULL,
	`product_id`           INT(11)    NOT NULL,
	`subscription_id`      INT(11)    NOT NULL,
	`user_id`              INT(11)    NOT NULL,
	`start_date`           INT(11)    NOT NULL,
	`end_date`             INT(11)    NOT NULL,
	`order_item_id`        INT(11)    NOT NULL,
	`renewal_reminder`     TINYINT(1) NOT NULL DEFAULT '1',
	PRIMARY KEY (`product_subscribe_id`),
	KEY `idx_common` (`product_id`, `end_date`),
	KEY `idx_order_item_id` (`order_item_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP User product Subscribe detail';

CREATE TABLE IF NOT EXISTS `#__redshop_product_subscription` (
	`subscription_id`     INT(11)     NOT NULL AUTO_INCREMENT,
	`product_id`          INT(11)     NOT NULL,
	`subscription_period` INT(11)     NOT NULL,
	`period_type`         VARCHAR(10) NOT NULL,
	`subscription_price`  DOUBLE      NOT NULL,
	PRIMARY KEY (`subscription_id`),
	KEY `idx_product_id` (`product_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Product Subscription';

CREATE TABLE IF NOT EXISTS `#__redshop_product_tags` (
	`tags_id`      INT(11)      NOT NULL AUTO_INCREMENT,
	`tags_name`    VARCHAR(255) NOT NULL,
	`tags_counter` INT(11)      NOT NULL,
	`published`    TINYINT(4)   NOT NULL,
	PRIMARY KEY (`tags_id`),
	KEY `idx_published` (`published`),
	KEY `idx_tags_name` (`tags_name`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='Product Tags';

CREATE TABLE IF NOT EXISTS `#__redshop_product_tags_xref` (
	`tags_id`    INT(11) NOT NULL,
	`product_id` INT(11) NOT NULL,
	`users_id`   INT(11) NOT NULL,
	KEY `idx_product_id` (`product_id`),
	KEY `idx_users_id` (`users_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='Product Tags Relation With product and user';

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher` (
	`voucher_id`    INT(11)        NOT NULL AUTO_INCREMENT,
	`voucher_code`  VARCHAR(255)   NOT NULL,
	`amount`        DECIMAL(12, 2) NOT NULL DEFAULT '0.00',
	`voucher_type`  VARCHAR(250)   NOT NULL,
	`start_date`    DOUBLE         NOT NULL,
	`end_date`      DOUBLE         NOT NULL,
	`free_shipping` TINYINT(4)     NOT NULL,
	`voucher_left`  INT(11)        NOT NULL,
	`published`     TINYINT(4)     NOT NULL,
	PRIMARY KEY (`voucher_id`),
	KEY `idx_common` (`voucher_code`, `published`, `start_date`, `end_date`),
	KEY `idx_voucher_left` (`voucher_left`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Product Voucher';

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher_transaction` (
	`transaction_voucher_id` INT(11)        NOT NULL AUTO_INCREMENT,
	`voucher_id`             INT(11)        NOT NULL,
	`voucher_code`           VARCHAR(255)   NOT NULL,
	`amount`                 DECIMAL(10, 3) NOT NULL,
	`user_id`                INT(11)        NOT NULL,
	`order_id`               INT(11)        NOT NULL,
	`trancation_date`        INT(11)        NOT NULL,
	`published`              TINYINT(4)     NOT NULL,
	`product_id`             VARCHAR(50)    NOT NULL,
	PRIMARY KEY (`transaction_voucher_id`),
	KEY `idx_voucher_id` (`voucher_id`),
	KEY `idx_voucher_code` (`voucher_code`),
	KEY `idx_amount` (`amount`),
	KEY `idx_user_id` (`user_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Product Voucher Transaction';

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher_xref` (
	`voucher_id` INT(11) NOT NULL,
	`product_id` INT(11) NOT NULL,
	KEY `idx_common` (`voucher_id`, `product_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Products Voucher Relation';

CREATE TABLE IF NOT EXISTS `#__redshop_quotation` (
	`quotation_id`               INT(11)        NOT NULL AUTO_INCREMENT,
	`quotation_number`           VARCHAR(50)    NOT NULL,
	`user_id`                    INT(11)        NOT NULL,
	`user_info_id`               INT(11)        NOT NULL,
	`order_id`                   INT(11)        NOT NULL,
	`quotation_total`            DECIMAL(15, 2) NOT NULL,
	`quotation_subtotal`         DECIMAL(15, 2) NOT NULL,
	`quotation_tax`              DECIMAL(15, 2) NOT NULL,
	`quotation_discount`         DECIMAL(15, 4) NOT NULL,
	`quotation_status`           INT(11)        NOT NULL,
	`quotation_cdate`            INT(11)        NOT NULL,
	`quotation_mdate`            INT(11)        NOT NULL,
	`quotation_note`             TEXT           NOT NULL,
	`quotation_customer_note`    TEXT           NOT NULL,
	`quotation_ipaddress`        VARCHAR(20)    NOT NULL,
	`quotation_encrkey`          VARCHAR(255)   NOT NULL,
	`user_email`                 VARCHAR(255)   NOT NULL,
	`quotation_special_discount` DECIMAL(15, 4) NOT NULL,
	PRIMARY KEY (`quotation_id`),
	KEY `idx_user_id` (`user_id`),
	KEY `idx_order_id` (`order_id`),
	KEY `idx_quotation_status` (`quotation_status`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Quotation';

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_accessory_item` (
	`quotation_item_acc_id` INT(11)        NOT NULL AUTO_INCREMENT,
	`quotation_item_id`     INT(11)        NOT NULL,
	`accessory_id`          INT(11)        NOT NULL,
	`accessory_item_sku`    VARCHAR(255)   NOT NULL,
	`accessory_item_name`   VARCHAR(255)   NOT NULL,
	`accessory_price`       DECIMAL(15, 4) NOT NULL,
	`accessory_vat`         DECIMAL(15, 4) NOT NULL,
	`accessory_quantity`    INT(11)        NOT NULL,
	`accessory_item_price`  DECIMAL(15, 2) NOT NULL,
	`accessory_final_price` DECIMAL(15, 2) NOT NULL,
	`accessory_attribute`   TEXT           NOT NULL,
	PRIMARY KEY (`quotation_item_acc_id`),
	KEY `idx_quotation_item_id` (`quotation_item_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Quotation Accessory item';

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_attribute_item` (
	`quotation_att_item_id` INT(11)        NOT NULL AUTO_INCREMENT,
	`quotation_item_id`     INT(11)        NOT NULL,
	`section_id`            INT(11)        NOT NULL,
	`section`               VARCHAR(250)   NOT NULL,
	`parent_section_id`     INT(11)        NOT NULL,
	`section_name`          VARCHAR(250)   NOT NULL,
	`section_price`         DECIMAL(15, 4) NOT NULL,
	`section_vat`           DECIMAL(15, 4) NOT NULL,
	`section_oprand`        CHAR(1)        NOT NULL,
	`is_accessory_att`      TINYINT(4)     NOT NULL,
	PRIMARY KEY (`quotation_att_item_id`),
	KEY `idx_quotation_item_id` (`quotation_item_id`),
	KEY `idx_section` (`section`),
	KEY `idx_parent_section_id` (`parent_section_id`),
	KEY `idx_is_accessory_att` (`is_accessory_att`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Quotation Attribute item';

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_fields_data` (
	`data_id`           INT(11) NOT NULL AUTO_INCREMENT,
	`fieldid`           INT(11)     DEFAULT NULL,
	`data_txt`          LONGTEXT,
	`quotation_item_id` INT(11)     DEFAULT NULL,
	`section`           VARCHAR(20) DEFAULT NULL,
	PRIMARY KEY (`data_id`),
	KEY `quotation_item_id` (`quotation_item_id`),
	KEY `idx_fieldid` (`fieldid`),
	KEY `idx_quotation_item_id` (`quotation_item_id`),
	KEY `idx_section` (`section`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Quotation USer field';

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_item` (
	`quotation_item_id`   INT(11)        NOT NULL AUTO_INCREMENT,
	`quotation_id`        INT(11)        NOT NULL,
	`product_id`          INT(11)        NOT NULL,
	`product_name`        VARCHAR(255)   NOT NULL,
	`product_price`       DECIMAL(15, 4) NOT NULL,
	`product_excl_price`  DECIMAL(15, 4) NOT NULL,
	`product_final_price` DECIMAL(15, 4) NOT NULL,
	`actualitem_price`    DECIMAL(15, 4) NOT NULL,
	`product_quantity`    INT(11)        NOT NULL,
	`product_attribute`   TEXT           NOT NULL,
	`product_accessory`   TEXT           NOT NULL,
	`mycart_accessory`    TEXT           NOT NULL,
	`product_wrapperid`   INT(11)        NOT NULL,
	`wrapper_price`       DECIMAL(15, 2) NOT NULL,
	`is_giftcard`         TINYINT(4)     NOT NULL,
	PRIMARY KEY (`quotation_item_id`),
	KEY `quotation_id` (`quotation_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Quotation Item';

CREATE TABLE IF NOT EXISTS `#__redshop_sample_request` (
	`request_id`      INT(11)      NOT NULL AUTO_INCREMENT,
	`name`            VARCHAR(250) NOT NULL,
	`email`           VARCHAR(250) NOT NULL,
	`colour_id`       VARCHAR(250) NOT NULL,
	`block`           TINYINT(4)   NOT NULL,
	`reminder_1`      TINYINT(1)   NOT NULL,
	`reminder_2`      TINYINT(1)   NOT NULL,
	`reminder_3`      TINYINT(1)   NOT NULL,
	`reminder_coupon` TINYINT(1)   NOT NULL,
	`registerdate`    INT(11)      NOT NULL,
	PRIMARY KEY (`request_id`),
	KEY `idx_block` (`block`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Sample Request';

CREATE TABLE IF NOT EXISTS `#__redshop_shipping_boxes` (
	`shipping_box_id`       INT(11)        NOT NULL AUTO_INCREMENT,
	`shipping_box_name`     VARCHAR(255)   NOT NULL,
	`shipping_box_length`   DECIMAL(10, 2) NOT NULL,
	`shipping_box_width`    DECIMAL(10, 2) NOT NULL,
	`shipping_box_height`   DECIMAL(10, 2) NOT NULL,
	`shipping_box_priority` INT(11)        NOT NULL,
	`published`             TINYINT(4)     NOT NULL,
	PRIMARY KEY (`shipping_box_id`),
	KEY `idx_published` (`published`),
	KEY `idx_common` (`shipping_box_length`, `shipping_box_width`, `shipping_box_height`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Shipping Boxes';

CREATE TABLE IF NOT EXISTS `#__redshop_shipping_rate` (
	`shipping_rate_id`               INT(11)        NOT NULL AUTO_INCREMENT,
	`shipping_rate_name`             VARCHAR(255)   NOT NULL DEFAULT '',
	`shipping_class`                 VARCHAR(255)   NOT NULL DEFAULT '',
	`shipping_rate_country`          LONGTEXT       NOT NULL,
	`shipping_rate_zip_start`        VARCHAR(20)    NOT NULL,
	`shipping_rate_zip_end`          VARCHAR(20)    NOT NULL,
	`shipping_rate_weight_start`     DECIMAL(10, 2) NOT NULL,
	`company_only`                   TINYINT(4)     NOT NULL,
	`apply_vat`                      TINYINT(4)     NOT NULL,
	`shipping_rate_weight_end`       DECIMAL(10, 2) NOT NULL,
	`shipping_rate_volume_start`     DECIMAL(10, 2) NOT NULL,
	`shipping_rate_volume_end`       DECIMAL(10, 2) NOT NULL,
	`shipping_rate_ordertotal_start` DECIMAL(10, 3) NOT NULL DEFAULT '0.000',
	`shipping_rate_ordertotal_end`   DECIMAL(10, 3) NOT NULL,
	`shipping_rate_priority`         TINYINT(4)     NOT NULL DEFAULT '0',
	`shipping_rate_value`            DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
	`shipping_rate_package_fee`      DECIMAL(10, 2) NOT NULL DEFAULT '0.00',
	`shipping_location_info`         LONGTEXT       NOT NULL,
	`shipping_rate_length_start`     DECIMAL(10, 2) NOT NULL,
	`shipping_rate_length_end`       DECIMAL(10, 2) NOT NULL,
	`shipping_rate_width_start`      DECIMAL(10, 2) NOT NULL,
	`shipping_rate_width_end`        DECIMAL(10, 2) NOT NULL,
	`shipping_rate_height_start`     DECIMAL(10, 2) NOT NULL,
	`shipping_rate_height_end`       DECIMAL(10, 2) NOT NULL,
	`shipping_rate_on_shopper_group` LONGTEXT       NOT NULL,
	`consignor_carrier_code`         VARCHAR(255)   NOT NULL,
	`shipping_tax_group_id`          INT(11)        NOT NULL,
	`deliver_type`                   INT(11)        NOT NULL,
	`economic_displaynumber`         VARCHAR(255)   NOT NULL,
	`shipping_rate_on_product`       LONGTEXT       NOT NULL,
	`shipping_rate_on_category`      LONGTEXT       NOT NULL,
	`shipping_rate_state`            LONGTEXT       NOT NULL,
	PRIMARY KEY (`shipping_rate_id`),
	KEY `shipping_rate_name` (`shipping_rate_name`),
	KEY `shipping_class` (`shipping_class`),
	KEY `shipping_rate_zip_start` (`shipping_rate_zip_start`),
	KEY `shipping_rate_zip_end` (`shipping_rate_zip_end`),
	KEY `company_only` (`company_only`),
	KEY `shipping_rate_value` (`shipping_rate_value`),
	KEY `shipping_tax_group_id` (`shipping_tax_group_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Shipping Rates';

CREATE TABLE IF NOT EXISTS `#__redshop_shopper_group` (
	`shopper_group_id`                   INT(11)      NOT NULL AUTO_INCREMENT,
	`shopper_group_name`                 VARCHAR(32)           DEFAULT NULL,
	`shopper_group_customer_type`        TINYINT(4)   NOT NULL,
	`shopper_group_portal`               TINYINT(4)   NOT NULL,
	`shopper_group_categories`           LONGTEXT     NOT NULL,
	`shopper_group_url`                  VARCHAR(255) NOT NULL,
	`shopper_group_logo`                 VARCHAR(255) NOT NULL,
	`shopper_group_introtext`            LONGTEXT     NOT NULL,
	`shopper_group_desc`                 TEXT,
	`parent_id`                          INT(11)      NOT NULL,
	`default_shipping`                   TINYINT(4)   NOT NULL,
	`default_shipping_rate`              FLOAT(10, 2) NOT NULL,
	`published`                          TINYINT(4)   NOT NULL,
	`shopper_group_cart_checkout_itemid` INT(11)      NOT NULL,
	`shopper_group_cart_itemid`          INT(11)      NOT NULL,
	`shopper_group_quotation_mode`       TINYINT(4)   NOT NULL,
	`show_price_without_vat`             TINYINT(4)   NOT NULL,
	`tax_group_id`                       INT(11)      NOT NULL,
	`apply_product_price_vat`            INT(11)      NOT NULL,
	`show_price`                         VARCHAR(255) NOT NULL DEFAULT 'global',
	`use_as_catalog`                     VARCHAR(255) NOT NULL DEFAULT 'global',
	`is_logged_in`                       INT(11)      NOT NULL DEFAULT '1',
	`shopper_group_manufactures`         TEXT         NOT NULL,
	PRIMARY KEY (`shopper_group_id`),
	KEY `idx_shopper_group_name` (`shopper_group_name`),
	KEY `idx_published` (`published`),
	KEY `idx_parent_id` (`parent_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='Shopper Groups that users can be assigned to';

CREATE TABLE IF NOT EXISTS `#__redshop_siteviewer` (
	`id`           INT(11)      NOT NULL AUTO_INCREMENT,
	`user_id`      INT(11)      NOT NULL,
	`session_id`   VARCHAR(250) NOT NULL,
	`created_date` INT(11)      NOT NULL,
	PRIMARY KEY (`id`),
	KEY `idx_session_id` (`session_id`),
	KEY `idx_created_date` (`created_date`)
)
	ENGINE =MyISAM
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Site Viewer';

CREATE TABLE IF NOT EXISTS `#__redshop_state` (
	`state_id`         INT(11)  NOT NULL AUTO_INCREMENT,
	`country_id`       INT(11)  NOT NULL DEFAULT '1',
	`state_name`       VARCHAR(64)       DEFAULT NULL,
	`state_3_code`     CHAR(3)           DEFAULT NULL,
	`state_2_code`     CHAR(2)           DEFAULT NULL,
	`checked_out`      INT(11)  NOT NULL,
	`checked_out_time` DATETIME NOT NULL,
	`show_state`       INT(11)  NOT NULL DEFAULT '2',
	PRIMARY KEY (`state_id`),
	UNIQUE KEY `state_3_code` (`country_id`, `state_3_code`),
	UNIQUE KEY `state_2_code` (`country_id`, `state_2_code`),
	KEY `idx_country_id` (`country_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='States that are assigned to a country';

CREATE TABLE IF NOT EXISTS `#__redshop_stockroom` (
	`stockroom_id`     INT(11)      NOT NULL AUTO_INCREMENT,
	`stockroom_name`   VARCHAR(250) NOT NULL,
	`min_stock_amount` INT(11)      NOT NULL,
	`stockroom_desc`   LONGTEXT     NOT NULL,
	`creation_date`    DOUBLE       NOT NULL,
	`min_del_time`     INT(11)      NOT NULL,
	`max_del_time`     INT(11)      NOT NULL,
	`show_in_front`    TINYINT(1)   NOT NULL,
	`delivery_time`    VARCHAR(255) NOT NULL,
	`published`        TINYINT(4)   NOT NULL,
	PRIMARY KEY (`stockroom_id`),
	KEY `idx_published` (`published`),
	KEY `idx_min_del_time` (`min_del_time`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Stockroom';

CREATE TABLE IF NOT EXISTS `#__redshop_stockroom_amount_image` (
	`stock_amount_id`            INT(11)      NOT NULL AUTO_INCREMENT,
	`stockroom_id`               INT(11)      NOT NULL,
	`stock_option`               TINYINT(4)   NOT NULL,
	`stock_quantity`             INT(11)      NOT NULL,
	`stock_amount_image`         VARCHAR(255) NOT NULL,
	`stock_amount_image_tooltip` TEXT         NOT NULL,
	PRIMARY KEY (`stock_amount_id`),
	KEY `idx_stockroom_id` (`stockroom_id`),
	KEY `idx_stock_option` (`stock_option`),
	KEY `idx_stock_quantity` (`stock_quantity`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP stockroom amount image';

CREATE TABLE IF NOT EXISTS `#__redshop_subscription_renewal` (
	`renewal_id`     INT(11) NOT NULL AUTO_INCREMENT,
	`product_id`     INT(11) NOT NULL,
	`before_no_days` INT(11) NOT NULL,
	PRIMARY KEY (`renewal_id`),
	KEY `idx_common` (`product_id`, `before_no_days`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Subscription Renewal';

CREATE TABLE IF NOT EXISTS `#__redshop_supplier` (
	`supplier_id`    INT(11)      NOT NULL AUTO_INCREMENT,
	`supplier_name`  VARCHAR(250) NOT NULL,
	`supplier_desc`  LONGTEXT     NOT NULL,
	`supplier_email` VARCHAR(255) NOT NULL,
	`published`      TINYINT(4)   NOT NULL,
	PRIMARY KEY (`supplier_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Supplier';

CREATE TABLE IF NOT EXISTS `#__redshop_tax_group` (
	`tax_group_id`   INT(11)      NOT NULL AUTO_INCREMENT,
	`tax_group_name` VARCHAR(255) NOT NULL,
	`published`      TINYINT(4)   NOT NULL,
	PRIMARY KEY (`tax_group_id`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Tax Group';

CREATE TABLE IF NOT EXISTS `#__redshop_tax_rate` (
	`tax_rate_id`   INT(11)    NOT NULL AUTO_INCREMENT,
	`tax_state`     VARCHAR(64)    DEFAULT NULL,
	`tax_country`   VARCHAR(64)    DEFAULT NULL,
	`mdate`         INT(11)        DEFAULT NULL,
	`tax_rate`      DECIMAL(10, 4) DEFAULT NULL,
	`tax_group_id`  INT(11)    NOT NULL,
	`is_eu_country` TINYINT(4) NOT NULL,
	PRIMARY KEY (`tax_rate_id`),
	KEY `idx_tax_group_id` (`tax_group_id`),
	KEY `idx_tax_country` (`tax_country`),
	KEY `idx_tax_state` (`tax_state`),
	KEY `idx_is_eu_country` (`is_eu_country`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Tax Rates';

CREATE TABLE IF NOT EXISTS `#__redshop_template` (
	`template_id`      INT(11)      NOT NULL AUTO_INCREMENT,
	`template_name`    VARCHAR(250) NOT NULL,
	`template_section` VARCHAR(250) NOT NULL,
	`template_desc`    LONGTEXT     NOT NULL,
	`order_status`     VARCHAR(250) NOT NULL,
	`payment_methods`  VARCHAR(250) NOT NULL,
	`published`        TINYINT(4)   NOT NULL,
	`shipping_methods` VARCHAR(255) NOT NULL,
	`checked_out`      INT(11)      NOT NULL,
	`checked_out_time` DATETIME     NOT NULL,
	PRIMARY KEY (`template_id`),
	KEY `idx_template_section` (`template_section`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Templates Detail';

CREATE TABLE IF NOT EXISTS `#__redshop_textlibrary` (
	`textlibrary_id` INT(11)      NOT NULL AUTO_INCREMENT,
	`text_name`      VARCHAR(255) DEFAULT NULL,
	`text_desc`      VARCHAR(255) DEFAULT NULL,
	`text_field`     TEXT,
	`section`        VARCHAR(255) NOT NULL,
	`published`      TINYINT(4)   NOT NULL,
	PRIMARY KEY (`textlibrary_id`),
	KEY `idx_section` (`section`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP TextLibrary';

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
	`cart_item_id`            INT(11) NOT NULL AUTO_INCREMENT,
	`cart_idx`                INT(11) NOT NULL,
	`cart_id`                 INT(11) NOT NULL,
	`product_id`              INT(11) NOT NULL,
	`product_quantity`        INT(11) NOT NULL,
	`product_wrapper_id`      INT(11) NOT NULL,
	`product_subscription_id` INT(11) NOT NULL,
	`giftcard_id`             INT(11) NOT NULL,
	PRIMARY KEY (`cart_item_id`),
	KEY `idx_cart_id` (`cart_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP User Cart Item';

CREATE TABLE IF NOT EXISTS `#__redshop_users_info` (
	`users_info_id`           INT(11)      NOT NULL AUTO_INCREMENT,
	`user_id`                 INT(11)      NOT NULL,
	`user_email`              VARCHAR(255) NOT NULL,
	`address_type`            VARCHAR(11)  NOT NULL,
	`firstname`               VARCHAR(250) NOT NULL,
	`lastname`                VARCHAR(250) NOT NULL,
	`vat_number`              VARCHAR(250) NOT NULL,
	`tax_exempt`              TINYINT(4)   NOT NULL,
	`shopper_group_id`        INT(11)      NOT NULL,
	`country_code`            VARCHAR(11)  NOT NULL,
	`address`                 VARCHAR(255) NOT NULL,
	`city`                    VARCHAR(50)  NOT NULL,
	`state_code`              VARCHAR(11)  NOT NULL,
	`zipcode`                 VARCHAR(255) NOT NULL,
	`phone`                   VARCHAR(50)  NOT NULL,
	`tax_exempt_approved`     TINYINT(1)   NOT NULL,
	`approved`                TINYINT(1)   NOT NULL,
	`is_company`              TINYINT(4)   NOT NULL,
	`ean_number`              VARCHAR(250) NOT NULL,
	`braintree_vault_number`  VARCHAR(255) NOT NULL,
	`veis_vat_number`         VARCHAR(255) NOT NULL,
	`veis_status`             VARCHAR(255) NOT NULL,
	`company_name`            VARCHAR(255) NOT NULL,
	`requesting_tax_exempt`   TINYINT(4)   NOT NULL,
	`accept_terms_conditions` TINYINT(4)   NOT NULL,
	PRIMARY KEY (`users_info_id`),
	KEY `idx_common` (`address_type`, `user_id`),
	KEY `user_id` (`user_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Users Information';

CREATE TABLE IF NOT EXISTS `#__redshop_wishlist` (
	`wishlist_id`   INT(11)      NOT NULL AUTO_INCREMENT,
	`wishlist_name` VARCHAR(100) NOT NULL,
	`user_id`       INT(11)      NOT NULL,
	`comment`       MEDIUMTEXT   NOT NULL,
	`cdate`         DOUBLE       NOT NULL,
	PRIMARY KEY (`wishlist_id`),
	KEY `idx_user_id` (`user_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP wishlist';

CREATE TABLE IF NOT EXISTS `#__redshop_wishlist_product` (
	`wishlist_product_id` INT(11) NOT NULL AUTO_INCREMENT,
	`wishlist_id`         INT(11) NOT NULL,
	`product_id`          INT(11) NOT NULL,
	`cdate`               INT(11) NOT NULL,
	PRIMARY KEY (`wishlist_product_id`),
	KEY `idx_wishlist_id` (`wishlist_id`),
	KEY `idx_common` (`product_id`, `wishlist_id`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Wishlist Product';

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

CREATE TABLE IF NOT EXISTS `#__redshop_wrapper` (
	`wrapper_id`         INT(11)      NOT NULL AUTO_INCREMENT,
	`product_id`         VARCHAR(255) NOT NULL,
	`category_id`        VARCHAR(250) NOT NULL,
	`wrapper_name`       VARCHAR(255) NOT NULL,
	`wrapper_price`      DOUBLE       NOT NULL,
	`wrapper_image`      VARCHAR(255) NOT NULL,
	`createdate`         INT(11)      NOT NULL,
	`wrapper_use_to_all` TINYINT(4)   NOT NULL,
	`published`          TINYINT(4)   NOT NULL,
	PRIMARY KEY (`wrapper_id`),
	KEY `idx_wrapper_use_to_all` (`wrapper_use_to_all`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP Wrapper';

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export` (
	`xmlexport_id`               INT(11)      NOT NULL AUTO_INCREMENT,
	`filename`                   VARCHAR(255) NOT NULL,
	`display_filename`           VARCHAR(255) NOT NULL,
	`parent_name`                VARCHAR(255) NOT NULL,
	`section_type`               VARCHAR(255) NOT NULL,
	`auto_sync`                  TINYINT(4)   NOT NULL,
	`sync_on_request`            TINYINT(4)   NOT NULL,
	`auto_sync_interval`         INT(11)      NOT NULL,
	`xmlexport_date`             INT(11)      NOT NULL,
	`xmlexport_filetag`          TEXT         NOT NULL,
	`element_name`               VARCHAR(255) DEFAULT NULL,
	`published`                  TINYINT(4)   NOT NULL,
	`use_to_all_users`           TINYINT(4)   NOT NULL,
	`xmlexport_billingtag`       TEXT         NOT NULL,
	`billing_element_name`       VARCHAR(255) NOT NULL,
	`xmlexport_shippingtag`      TEXT         NOT NULL,
	`shipping_element_name`      VARCHAR(255) NOT NULL,
	`xmlexport_orderitemtag`     TEXT         NOT NULL,
	`orderitem_element_name`     VARCHAR(255) NOT NULL,
	`xmlexport_stocktag`         TEXT         NOT NULL,
	`stock_element_name`         VARCHAR(255) NOT NULL,
	`xmlexport_prdextrafieldtag` TEXT         NOT NULL,
	`prdextrafield_element_name` VARCHAR(255) NOT NULL,
	`xmlexport_on_category`      TEXT         NOT NULL,
	PRIMARY KEY (`xmlexport_id`),
	KEY `idx_filename` (`filename`),
	KEY `idx_auto_sync` (`auto_sync`),
	KEY `idx_sync_on_request` (`sync_on_request`),
	KEY `idx_auto_sync_interval` (`auto_sync_interval`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP XML Export';

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export_ipaddress` (
	`xmlexport_ip_id`  INT(11)      NOT NULL AUTO_INCREMENT,
	`xmlexport_id`     INT(11)      NOT NULL,
	`access_ipaddress` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`xmlexport_ip_id`),
	KEY `idx_xmlexport_id` (`xmlexport_id`),
	KEY `idx_access_ipaddress` (`access_ipaddress`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP XML Export Ip Address';

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export_log` (
	`xmlexport_log_id`   INT(11)      NOT NULL AUTO_INCREMENT,
	`xmlexport_id`       INT(11)      NOT NULL,
	`xmlexport_filename` VARCHAR(255) NOT NULL,
	`xmlexport_date`     INT(11)      NOT NULL,
	PRIMARY KEY (`xmlexport_log_id`),
	KEY `idx_xmlexport_id` (`xmlexport_id`),
	KEY `idx_xmlexport_filename` (`xmlexport_filename`)
)
	ENGINE =MyISAM
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP XML Export log';

CREATE TABLE IF NOT EXISTS `#__redshop_xml_import` (
	`xmlimport_id`               INT(11)      NOT NULL AUTO_INCREMENT,
	`filename`                   VARCHAR(255) NOT NULL,
	`display_filename`           VARCHAR(255) NOT NULL,
	`xmlimport_url`              VARCHAR(255) NOT NULL,
	`section_type`               VARCHAR(255) NOT NULL,
	`auto_sync`                  TINYINT(4)   NOT NULL,
	`sync_on_request`            TINYINT(4)   NOT NULL,
	`auto_sync_interval`         INT(11)      NOT NULL,
	`override_existing`          TINYINT(4)   NOT NULL,
	`add_prefix_for_existing`    VARCHAR(50)  NOT NULL,
	`xmlimport_date`             INT(11)      NOT NULL,
	`xmlimport_filetag`          TEXT         NOT NULL,
	`xmlimport_billingtag`       TEXT         NOT NULL,
	`xmlimport_shippingtag`      TEXT         NOT NULL,
	`xmlimport_orderitemtag`     TEXT         NOT NULL,
	`xmlimport_stocktag`         TEXT         NOT NULL,
	`xmlimport_prdextrafieldtag` TEXT         NOT NULL,
	`published`                  TINYINT(4)   NOT NULL,
	`element_name`               VARCHAR(255) NOT NULL,
	`billing_element_name`       VARCHAR(255) NOT NULL,
	`shipping_element_name`      VARCHAR(255) NOT NULL,
	`orderitem_element_name`     VARCHAR(255) NOT NULL,
	`stock_element_name`         VARCHAR(255) NOT NULL,
	`prdextrafield_element_name` VARCHAR(255) NOT NULL,
	`xmlexport_billingtag`       TEXT         NOT NULL,
	`xmlexport_shippingtag`      TEXT         NOT NULL,
	`xmlexport_orderitemtag`     TEXT         NOT NULL,
	PRIMARY KEY (`xmlimport_id`),
	KEY `idx_auto_sync` (`auto_sync`),
	KEY `idx_sync_on_request` (`sync_on_request`),
	KEY `idx_auto_sync_interval` (`auto_sync_interval`),
	KEY `idx_published` (`published`)
)
	ENGINE =InnoDB
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP XML Import';

CREATE TABLE IF NOT EXISTS `#__redshop_xml_import_log` (
	`xmlimport_log_id`   INT(11)      NOT NULL AUTO_INCREMENT,
	`xmlimport_id`       INT(11)      NOT NULL,
	`xmlimport_filename` VARCHAR(255) NOT NULL,
	`xmlimport_date`     INT(11)      NOT NULL,
	PRIMARY KEY (`xmlimport_log_id`),
	KEY `idx_xmlimport_id` (`xmlimport_id`)
)
	ENGINE =MyISAM
	DEFAULT CHARSET =utf8
	COMMENT ='redSHOP XML Import log';

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
