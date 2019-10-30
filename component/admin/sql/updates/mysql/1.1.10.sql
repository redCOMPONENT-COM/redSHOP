CREATE TABLE IF NOT EXISTS `#__redshop_product_navigator` (
  `navigator_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `child_product_id` int(11) NOT NULL,
  `navigator_name` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`navigator_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='redSHOP Products Navigator';

CREATE TABLE IF NOT EXISTS `#__redshop_notifystock_users` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`product_id` INT NOT NULL ,
`property_id` INT NOT NULL ,
`subproperty_id` INT NOT NULL ,
`user_id` INT NOT NULL ,
`notification_status` INT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE = MYISAM ;