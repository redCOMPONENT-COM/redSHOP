ALTER TABLE `#__redshop_discount`
	ADD `name` VARCHAR( 250 ) NOT NULL;
	AFTER `discount_id` , ADD INDEX `idx_discount_name` (`name`);
