ALTER TABLE `#__redshop_fields`
	CHANGE `field_section` `field_section` INT NOT NULL ;
ALTER TABLE `#__redshop_giftcard`
	ADD `free_shipping` TINYINT NOT NULL ;
