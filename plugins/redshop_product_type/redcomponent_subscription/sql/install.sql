SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `#__redshop_redcomponent_subscription` (
  `product_id` int(11) NOT NULL,
  `subscriptions` varchar(255) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='redSHOP redCOMPONENT Subscription';

SET FOREIGN_KEY_CHECKS = 1;