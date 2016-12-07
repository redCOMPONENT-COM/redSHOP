SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_mass_discount` CHANGE `mass_discount_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__redshop_mass_discount` CHANGE `discount_type` `type` TINYINT(4) NOT NULL;
ALTER TABLE `#__redshop_mass_discount` CHANGE `discount_amount` `amount` DOUBLE(10,2) NOT NULL;
ALTER TABLE `#__redshop_mass_discount` CHANGE `discount_startdate` `start_date` INT(11) NOT NULL;
ALTER TABLE `#__redshop_mass_discount` CHANGE `discount_enddate` `end_date` INT(11) NOT NULL;
ALTER TABLE `#__redshop_mass_discount` CHANGE `discount_name` `name` VARCHAR(255) NOT NULL;

ALTER TABLE `#__redshop_mass_discount` ADD `checked_out` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_mass_discount` ADD `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_mass_discount` ADD `created_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_mass_discount` ADD `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_mass_discount` ADD `modified_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_mass_discount` ADD `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

SET FOREIGN_KEY_CHECKS = 1;