SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_orders` CHANGE `ship_method_id` `ship_method_id` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

ALTER TABLE `#__redshop_shopper_group` ADD `checked_out` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_shopper_group` ADD `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_shopper_group` ADD `created_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_shopper_group` ADD `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_shopper_group` ADD `modified_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_shopper_group` ADD `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

SET FOREIGN_KEY_CHECKS = 1;