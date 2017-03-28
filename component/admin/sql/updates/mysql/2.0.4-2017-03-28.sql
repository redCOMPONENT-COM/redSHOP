SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_category` 
	ADD `asset_id` INT(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT 'FK to the #__assets table.'  AFTER `category_id`;

SET FOREIGN_KEY_CHECKS = 1;