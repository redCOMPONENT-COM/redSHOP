SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_tax_group` CHANGE `tax_group_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__redshop_tax_group` CHANGE `tax_group_name` `name` VARCHAR(255) NOT NULL;

ALTER TABLE `#__redshop_tax_group` ADD `checked_out` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_tax_group` ADD `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_tax_group` ADD `created_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_tax_group` ADD `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_tax_group` ADD `modified_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_tax_group` ADD `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

SET FOREIGN_KEY_CHECKS = 1;