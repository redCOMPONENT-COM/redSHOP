SET FOREIGN_KEY_CHECKS = 0;

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
ALTER TABLE `#__redshop_fields` ADD INDEX `#__rs_idx_field_display_in_product` (`display_in_product` ASC));

SET FOREIGN_KEY_CHECKS = 1;