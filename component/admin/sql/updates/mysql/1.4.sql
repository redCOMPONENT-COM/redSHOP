CREATE TABLE IF NOT EXISTS `#__redshop_notifystock_users` (
`id` INT(11) NOT NULL AUTO_INCREMENT,
`product_id` INT NOT NULL ,
`property_id` INT NOT NULL ,
`subproperty_id` INT NOT NULL ,
`user_id` INT NOT NULL ,
`notification_status` INT NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE = MYISAM  DEFAULT CHARSET=utf8;