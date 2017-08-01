CREATE TABLE IF NOT EXISTS `#__redshop_product_bundle` (
  `product_id` int(11) NOT NULL,
  `bundle_id` int(11) NOT NULL,
  `bundle_name` varchar(250) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`bundle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='redSHOP Product Bundle';

CREATE TABLE IF NOT EXISTS `#__redshop_order_bundle` (
	  `order_item_id` int(11) NOT NULL,
	  `bundle_id` int(11) NOT NULL,
	  `product_id` int(11) NOT NULL,
	  `property_id` int(11) NOT NULL,
  PRIMARY KEY (`order_item_id`,`bundle_id`,`product_id`,`property_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `#__redshop_template` (`template_name`, `template_section`, `template_desc`, `order_status`, `payment_methods`, `published`, `shipping_methods`, `checked_out`, `checked_out_time`) VALUES
	('bundle', 'bundle_template', '<div class="attribute_listing table-responsive"><table class="table table-striped"><thead><tr><th>{property_number_lbl}</th><th>{property_name_lbl}</th><th></th><th> </th></tr></thead><tbody><tr><td>{bundle_number}</td><td>{bundle_name}</td><td align="center">{bundle_stock_amount_image}</td><td>{bundle_select}</td></tr><tr><td>{property_number}</td><td>{property_name}</td><td align="center">{property_stock_image}</td><td>{property_select}</td></tr></tbody></table></div>', '', '', 1, '', 0, '0000-00-00 00:00:00');