ALTER TABLE `#__redshop_media` CHANGE `media_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__redshop_media` CHANGE `media_name` `name` VARCHAR(250) NOT NULL;
ALTER TABLE `#__redshop_media` CHANGE `media_alternate_text` `alternate_text` VARCHAR(255) NOT NULL;
ALTER TABLE `#__redshop_media` CHANGE `media_section` `section` VARCHAR(20) NOT NULL;
ALTER TABLE `#__redshop_media` CHANGE `media_type` `type` VARCHAR(255) NOT NULL;
ALTER TABLE `#__redshop_media` CHANGE `media_mimetype` `mimetype` VARCHAR(20) NOT NULL;
ALTER TABLE `#__redshop_media` ADD `title` VARCHAR(255) NULL AFTER `id`;
ALTER TABLE `#__redshop_media` ADD `youtube_id` INT(11) NULL AFTER `alternate_text`;
ALTER TABLE `#__redshop_media` ADD `scope` VARCHAR(255) NULL AFTER `youtube_id`;
ALTER TABLE `#__redshop_media` ADD `checked_out` INT(11) NULL AFTER `mimetype`;
ALTER TABLE `#__redshop_media` ADD `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'  AFTER `mimetype`;
ALTER TABLE `#__redshop_media` ADD `created_by` INT(11) NULL AFTER `checked_out_time`;
ALTER TABLE `#__redshop_media` ADD `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `created_by`;
ALTER TABLE `#__redshop_media` ADD `modified_by` INT(11) NULL AFTER `created_date`;
ALTER TABLE `#__redshop_media` ADD `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `modified_by`;
ALTER TABLE `#__redshop_media` DROP INDEX `idx_media_section`;
ALTER TABLE `#__redshop_media` DROP INDEX `idx_media_type`;
ALTER TABLE `#__redshop_media` DROP INDEX `idx_media_name`;
ALTER TABLE `#__redshop_media` DROP INDEX `#___rs_idx_media_common`;
ALTER TABLE `#__redshop_media` ADD INDEX `idx_section` (`section` ASC);
ALTER TABLE `#__redshop_media` ADD INDEX `idx_type` (`type` ASC);
ALTER TABLE `#__redshop_media` ADD INDEX `idx_name` (`name` ASC);
ALTER TABLE `#__redshop_media` ADD INDEX `#__rs_idx_common` USING BTREE (`section_id` ASC, `section` ASC, `type` ASC, `published` ASC, `ordering` ASC));