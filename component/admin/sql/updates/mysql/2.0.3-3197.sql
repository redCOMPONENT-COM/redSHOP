SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_textlibrary` CHANGE `textlibrary_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__redshop_textlibrary` CHANGE `text_name` `name` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_textlibrary` CHANGE `text_desc` `description` VARCHAR(255) NOT NULL DEFAULT '';

ALTER TABLE `#__redshop_textlibrary` DROP INDEX `idx_section`;
ALTER TABLE `#__redshop_textlibrary` DROP INDEX `idx_published`;

ALTER TABLE `#__redshop_textlibrary` ADD INDEX `#__rs_tl_fk1` (`section` ASC);
ALTER TABLE `#__redshop_textlibrary` ADD INDEX `#__rs_tl_fk2` (`published` ASC);

ALTER TABLE `#__redshop_textlibrary` ADD `created_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_textlibrary` ADD `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_textlibrary` ADD `modified_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_textlibrary` ADD `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_textlibrary` ADD `checked_out` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_textlibrary` ADD `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

SET FOREIGN_KEY_CHECKS = 1;