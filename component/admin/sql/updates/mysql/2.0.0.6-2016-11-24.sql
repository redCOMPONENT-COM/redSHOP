SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_order_status` DROP INDEX `order_status_code`;
ALTER TABLE `#__redshop_order_status` DROP INDEX `idx_published`;

ALTER TABLE `#__redshop_order_status` CHANGE `published` `published` TINYINT(4) NOT NULL DEFAULT 0;
ALTER TABLE `#__redshop_order_status` ADD INDEX `#__rs_idx_order_status_published` (`published` ASC);
ALTER TABLE `#__redshop_order_status` ADD UNIQUE INDEX `#__rs_idx_order_status_code` (`order_status_code` ASC);

ALTER TABLE `#__redshop_order_status` ADD `checked_out` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_order_status` ADD `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_order_status` ADD `created_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_order_status` ADD `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';
ALTER TABLE `#__redshop_order_status` ADD `modified_by` INT(11) NULL DEFAULT NULL;
ALTER TABLE `#__redshop_order_status` ADD `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

SET FOREIGN_KEY_CHECKS = 1;