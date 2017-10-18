CREATE TABLE IF NOT EXISTS `#__redshop_redcomponent_subscription` (
  `product_id` int(11) NOT NULL,
  `subscriptions` varchar(255) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='redSHOP redCOMPONENT Subscription';

CREATE TABLE IF NOT EXISTS `#__redshop_redcomponent_subscription_user` (
    `id` int(11) NOT NULL,
	  `user_id` int(11) NOT NULL,
	  `order_id` int(11) NOT NULL,
	  `product_id` int(11) NOT NULL,
	  `date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
