ALTER TABLE `#__redshop_discount`
	ADD `name` VARCHAR( 250 ) NOT NULL;

ALTER TABLE `#__redshop_discount`
	ADD INDEX `idx_discount_name` (`name`);
