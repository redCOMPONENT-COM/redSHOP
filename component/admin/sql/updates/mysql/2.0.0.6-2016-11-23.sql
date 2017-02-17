SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_tax_rate` CHANGE `tax_rate_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__redshop_tax_rate` ADD `name` VARCHAR(255) NOT NULL DEFAULT '' AFTER `id`;

ALTER TABLE `#__redshop_tax_rate` ADD `checked_out` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_tax_rate` ADD `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_tax_rate` ADD `created_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_tax_rate` ADD `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

SET FOREIGN_KEY_CHECKS = 1;