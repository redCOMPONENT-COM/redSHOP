ALTER TABLE `#__redshop_orders`
	ADD `invoice_number` VARCHAR( 255 ) NOT NULL
	COMMENT 'Formatted Order Invoice for final use'
	AFTER `order_number` , ADD INDEX `idx_orders_invoice_number` (`invoice_number`);

ALTER TABLE `#__redshop_orders`
	ADD `invoice_number_chrono` INT NOT NULL
	COMMENT 'Order invoice number in chronological order'
	AFTER `order_number` , ADD INDEX `idx_orders_invoice_number_chrono` (`invoice_number_chrono`);
