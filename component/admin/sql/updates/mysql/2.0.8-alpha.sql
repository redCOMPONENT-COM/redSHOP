SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_template`
  ADD COLUMN `twig_support` TINYINT(1) NOT NULL DEFAULT 0 AFTER `shipping_methods`;

ALTER TABLE `#__redshop_template`
  ADD COLUMN `twig_enable` TINYINT(1) NOT NULL DEFAULT 0 AFTER `twig_support`;

ALTER TABLE `#__redshop_template`
  ADD COLUMN `created_by` INT(11) NULL DEFAULT NULL;

ALTER TABLE `#__redshop_template`
  ADD COLUMN `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

ALTER TABLE `#__redshop_template`
  ADD COLUMN `modified_by` INT(11) NULL DEFAULT NULL;

ALTER TABLE `#__redshop_template`
  ADD COLUMN `modified_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00';

SET FOREIGN_KEY_CHECKS = 1;