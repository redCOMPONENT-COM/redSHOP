ALTER TABLE `#__redshop_quotation`
	ADD `quotation_customer_note` TEXT NOT NULL
	AFTER `quotation_note`;
ALTER TABLE `#__redshop_product`
	ADD `allow_decimal_piece` int(4) NOT NULL;
