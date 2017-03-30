SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_category` CHANGE `category_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__redshop_category` CHANGE `category_name` `name` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_category` CHANGE `category_short_description` `short_description` TEXT NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_category` CHANGE `category_description` `description` TEXT NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_category` CHANGE `published` `published` TINYINT(4) NOT NULL DEFAULT 0;
ALTER TABLE `#__redshop_category` CHANGE `category_template` `template` INT(11) NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_category` CHANGE `category_more_template` `more_template` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_category` ADD INDEX `#__rs_idx_category_published` (`published` ASC);

ALTER TABLE `#__redshop_category` ADD `asset_id` INT(11) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.'  AFTER `append_to_global_seo`;
ALTER TABLE `#__redshop_category` ADD `parent_id` INT(11) NOT NULL DEFAULT '0' AFTER `asset_id`;
ALTER TABLE `#__redshop_category` ADD `level` INT(11) NOT NULL DEFAULT '1' AFTER `parent_id`;
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

SET FOREIGN_KEY_CHECKS = 1;