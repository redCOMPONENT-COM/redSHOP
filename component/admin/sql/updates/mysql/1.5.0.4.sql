ALTER TABLE `#__redshop_orders`
ADD `invoice_number` INT(11) NOT NULL
COMMENT 'Order invoice number in chronological order'
AFTER `order_number`;
