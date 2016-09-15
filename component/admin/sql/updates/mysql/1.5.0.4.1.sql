CREATE TABLE IF NOT EXISTS `#__redshop_usercart_item` (
	`cart_item_id` int(11) NOT NULL AUTO_INCREMENT,
	`cart_idx` int(11) NOT NULL,
	`cart_id` int(11) NOT NULL,
	`product_id` int(11) NOT NULL,
	`product_quantity` int(11) NOT NULL,
	`product_wrapper_id` int(11) NOT NULL,
	`product_subscription_id` int(11) NOT NULL,
	`giftcard_id` int(11) NOT NULL,
	PRIMARY KEY (`cart_item_id`),
	KEY `idx_cart_id` (`cart_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='redSHOP User Cart Item';
