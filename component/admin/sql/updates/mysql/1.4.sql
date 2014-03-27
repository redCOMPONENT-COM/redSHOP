ALTER TABLE `#__redshop_quotation`
	ADD `quotation_customer_note` TEXT NOT NULL
	AFTER `quotation_note`;

ALTER TABLE `#__redshop_quotation_item`
	ADD `note` TEXT NOT NULL
	AFTER `is_giftcard`;