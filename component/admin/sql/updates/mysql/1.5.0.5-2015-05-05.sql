ALTER TABLE `#__redshop_orders`
	ADD `invoice_number` VARCHAR( 255 ) NOT NULL
	COMMENT 'Formatted Order Invoice for final use'
	AFTER `order_number`;

ALTER TABLE `#__redshop_orders`
	ADD `invoice_number_chrono` INT NOT NULL
	COMMENT 'Order invoice number in chronological order'
	AFTER `order_number`;
