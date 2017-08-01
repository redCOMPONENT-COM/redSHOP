SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `#__redshop_accessmanager`;

ALTER TABLE `#__redshop_category` CHANGE `category_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__redshop_category` CHANGE `category_name` `name` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_category` CHANGE `category_short_description` `short_description` TEXT NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_category` CHANGE `category_description` `description` TEXT NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_category` CHANGE `published` `published` TINYINT(4) NOT NULL DEFAULT 0;
ALTER TABLE `#__redshop_category` CHANGE `category_template` `template` INT(11) NOT NULL DEFAULT 0;
ALTER TABLE `#__redshop_category` CHANGE `category_more_template` `more_template` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_category` CHANGE `category_pdate` `category_pdate` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

ALTER TABLE `#__redshop_category` ADD `alias` VARCHAR(400) NOT NULL DEFAULT '' AFTER `append_to_global_seo`;
ALTER TABLE `#__redshop_category` ADD `path` VARCHAR(255) NOT NULL DEFAULT '' AFTER `alias`;
ALTER TABLE `#__redshop_category` ADD `asset_id` INT(11) NULL DEFAULT '0' COMMENT 'FK to the #__assets table.'  AFTER `path`;
ALTER TABLE `#__redshop_category` ADD `parent_id` INT(11) NULL DEFAULT '0' AFTER `asset_id`;
ALTER TABLE `#__redshop_category` ADD `level` INT(11) NOT NULL DEFAULT '0' AFTER `parent_id`;
ALTER TABLE `#__redshop_category` ADD `lft` INT(11) NOT NULL DEFAULT '0' AFTER `level`;
ALTER TABLE `#__redshop_category` ADD `rgt` INT(11) NOT NULL DEFAULT '0' AFTER `lft`;

ALTER TABLE `#__redshop_category` ADD `checked_out` INT(11) NULL DEFAULT NULL AFTER `rgt`;
ALTER TABLE `#__redshop_category` ADD `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out`;
ALTER TABLE `#__redshop_category` ADD `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out_time`;
ALTER TABLE `#__redshop_category` ADD `created_by` INT(11) NULL DEFAULT NULL AFTER `created_date`;
ALTER TABLE `#__redshop_category` ADD `modified_by` INT(11) NULL DEFAULT NULL AFTER `created_by`;
ALTER TABLE `#__redshop_category` ADD `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `modified_by`;
ALTER TABLE `#__redshop_category` ADD `publish_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `modified_date`;
ALTER TABLE `#__redshop_category` ADD `publish_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `publish_up`;

ALTER TABLE `#__redshop_category` ADD INDEX `#__rs_idx_category_published` (`published` ASC);
ALTER TABLE `#__redshop_category` ADD INDEX `#__rs_idx_left_right` (`lft` ASC, `rgt` ASC);
ALTER TABLE `#__redshop_category` ADD INDEX `#__rs_idx_alias` (`alias` ASC);
ALTER TABLE `#__redshop_category` ADD INDEX `#__rs_idx_path` (`path` ASC);

ALTER TABLE `#__redshop_fields` CHANGE `field_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__redshop_fields` CHANGE `field_title` `title` VARCHAR(250) NOT NULL;
ALTER TABLE `#__redshop_fields` CHANGE `field_name` `name` VARCHAR(250) NOT NULL;
ALTER TABLE `#__redshop_fields` CHANGE `field_type` `type` VARCHAR(20) NOT NULL;
ALTER TABLE `#__redshop_fields` CHANGE `field_desc` `desc` LONGTEXT NOT NULL;
ALTER TABLE `#__redshop_fields` CHANGE `field_class` `class` VARCHAR(20) NOT NULL;
ALTER TABLE `#__redshop_fields` CHANGE `field_section` `section` VARCHAR(20) NOT NULL;
ALTER TABLE `#__redshop_fields` CHANGE `field_maxlength` `maxlength` INT(11) NOT NULL;
ALTER TABLE `#__redshop_fields` CHANGE `field_cols` `cols` INT(11) NOT NULL;
ALTER TABLE `#__redshop_fields` CHANGE `field_rows` `rows` INT(11) NOT NULL;
ALTER TABLE `#__redshop_fields` CHANGE `field_size` `size` TINYINT(4) NOT NULL;
ALTER TABLE `#__redshop_fields` CHANGE `field_show_in_front` `show_in_front` TINYINT(4) NOT NULL;
ALTER TABLE `#__redshop_fields` ADD `publish_up` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `published`;
ALTER TABLE `#__redshop_fields` ADD `publish_down` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `publish_up`;
ALTER TABLE `#__redshop_fields` ADD `checked_out` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_fields` ADD `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_fields` ADD `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_fields` ADD `created_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_fields` ADD `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_fields` ADD `modified_by` INT(11) NULL DEFAULT NULL;

ALTER TABLE `#__redshop_fields` DROP INDEX `idx_published`;
ALTER TABLE `#__redshop_fields` DROP INDEX `idx_field_section`;
ALTER TABLE `#__redshop_fields` DROP INDEX `idx_field_type`;
ALTER TABLE `#__redshop_fields` DROP INDEX `idx_required`;
ALTER TABLE `#__redshop_fields` DROP INDEX `idx_field_name`;
ALTER TABLE `#__redshop_fields` DROP INDEX `idx_field_show_in_front`;
ALTER TABLE `#__redshop_fields` DROP INDEX `idx_display_in_product`;

ALTER TABLE `#__redshop_fields` ADD INDEX `#__rs_idx_field_published` (`published` ASC);
ALTER TABLE `#__redshop_fields` ADD INDEX `#__rs_idx_field_section` (`section` ASC);
ALTER TABLE `#__redshop_fields` ADD INDEX `#__rs_idx_field_type` (`type` ASC);
ALTER TABLE `#__redshop_fields` ADD INDEX `#__rs_idx_field_required` (`required` ASC);
ALTER TABLE `#__redshop_fields` ADD INDEX `#__rs_idx_field_name` (`name` ASC);
ALTER TABLE `#__redshop_fields` ADD INDEX `#__rs_idx_field_show_in_front` (`show_in_front` ASC);
ALTER TABLE `#__redshop_fields` ADD INDEX `#__rs_idx_field_display_in_product` (`display_in_product` ASC);

ALTER TABLE `#__redshop_product_attribute` ADD `attribute_description` VARCHAR(255) NOT NULL DEFAULT '' AFTER `attribute_name`;

SET FOREIGN_KEY_CHECKS = 1;