SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_supplier` CHANGE `supplier_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__redshop_supplier` CHANGE `supplier_name` `name` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_supplier` CHANGE `supplier_desc` `description` TEXT NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_supplier` CHANGE `supplier_email` `email` VARCHAR(255) NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_supplier` CHANGE `published` `published` TINYINT(4) NOT NULL DEFAULT 0;
ALTER TABLE `#__redshop_supplier` ADD INDEX `#__rs_idx_supplier_published` (`published` ASC);

ALTER TABLE `#__redshop_supplier` ADD `checked_out` INT(11) NULL DEFAULT NULL AFTER `published`;
ALTER TABLE `#__redshop_supplier` ADD `checked_out_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `checked_out`;
ALTER TABLE `#__redshop_supplier` ADD `created_by` INT(11) NULL DEFAULT NULL AFTER `checked_out_time`;
ALTER TABLE `#__redshop_supplier` ADD `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `created_by`;
ALTER TABLE `#__redshop_supplier` ADD `modified_by` INT(11) NULL DEFAULT NULL AFTER `created_date`;
ALTER TABLE `#__redshop_supplier` ADD `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `modified_by`;

ALTER TABLE `#__redshop_product` ADD INDEX `#__rs_product_supplier_fk1` (`supplier_id` ASC);

SET FOREIGN_KEY_CHECKS = 1;