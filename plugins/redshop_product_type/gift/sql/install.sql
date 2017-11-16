CREATE TABLE IF NOT EXISTS `#__redshop_product_gift` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`product_id` int(11) NOT NULL,
	`gift_id` int(11) NOT NULL,
	`quantity` int(11) NOT NULL,
	`quantity_from` int(11) NOT NULL,
	`quantity_to` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='redSHOP Product Gift';

CREATE TABLE IF NOT EXISTS `#__redshop_order_gift` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`order_item_id` int(11) NOT NULL,
	`gift_id` int(11) NOT NULL,
	`product_id` int(11) NOT NULL,
	`quantity` int(11) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
