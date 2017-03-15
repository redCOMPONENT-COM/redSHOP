SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_number` `number` VARCHAR(50) NOT NULL;
ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_total` `total` DECIMAL(15,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_subtotal` `subtotal` DECIMAL(15,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_tax` `tax` DECIMAL(15,2) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_discount` `discount` DECIMAL(15,4) NOT NULL DEFAULT '0.0000';
ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_status` `status` INT(11) NOT NULL;
ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_note` `note` TEXT NOT NULL;
ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_customer_note` `customer_note` TEXT NOT NULL;
ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_ipaddress` `ipaddress` VARCHAR(20) NOT NULL;
ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_encrkey` `encrkey` VARCHAR(255) NOT NULL;
ALTER TABLE `#__redshop_quotation` CHANGE COLUMN `quotation_special_discount` `special_discount` DECIMAL(15,4) NOT NULL DEFAULT '0.0000';

SET FOREIGN_KEY_CHECKS = 1;