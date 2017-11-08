ALTER TABLE `#__redshop_product_subattribute_color`
	MODIFY `subattribute_color_name` VARCHAR(255) CHARACTER SET utf8
	COLLATE utf8_general_ci;
ALTER TABLE `#__redshop_product_attribute_property`
	ADD `extra_field` VARCHAR( 250 ) NOT NULL;
ALTER TABLE `#__redshop_product_subattribute_color`
	ADD `extra_field` VARCHAR( 250 ) NOT NULL;
ALTER TABLE `#__redshop_product`
	ADD `minimum_per_product_total` INT( 11 ) NOT NULL;
