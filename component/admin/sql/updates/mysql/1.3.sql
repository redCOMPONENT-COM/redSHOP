ALTER TABLE `#__redshop_product_subattribute_color` MODIFY `subattribute_color_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER IGNORE TABLE #__redshop_product_attribute_property ADD `extra_field` VARCHAR( 250 ) NOT NULL;
ALTER IGNORE TABLE #__redshop_product_subattribute_color ADD `extra_field` VARCHAR( 250 ) NOT NULL;

ALTER IGNORE TABLE #__redshop_tax_group ADD INDEX `published` (`published`);

ALTER IGNORE TABLE #__redshop_category ADD INDEX `published` (`published`);
ALTER IGNORE TABLE #__redshop_category ADD INDEX `ordering` (`ordering`);

ALTER IGNORE TABLE #__redshop_media ADD INDEX `section_id` (`section_id`);
ALTER IGNORE TABLE #__redshop_media ADD INDEX `media_section` (`media_section`);
ALTER IGNORE TABLE #__redshop_media ADD INDEX `media_type` (`media_type`);
ALTER IGNORE TABLE #__redshop_media ADD INDEX `media_name` (`media_name`);

ALTER IGNORE TABLE #__redshop_product ADD INDEX `product_parent_id` (`product_parent_id`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `manufacturer_id` (`manufacturer_id`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `product_price` (`product_price`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `discount_price` (`discount_price`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `discount_stratdate` (`discount_stratdate`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `discount_enddate` (`discount_enddate`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `visited` (`visited`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `published` (`published`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `expired` (`expired`);

ALTER IGNORE TABLE #__redshop_attribute_set ADD INDEX `published` (`published`);

ALTER IGNORE TABLE #__redshop_product_attribute ADD INDEX `product_id` (`product_id`);
ALTER IGNORE TABLE #__redshop_product_attribute ADD INDEX `attribute_name` (`attribute_name`);
ALTER IGNORE TABLE #__redshop_product_attribute ADD INDEX `ordering` (`ordering`);
ALTER IGNORE TABLE #__redshop_product_attribute ADD INDEX `attribute_set_id` (`attribute_set_id`);
ALTER IGNORE TABLE #__redshop_product_attribute ADD INDEX `attribute_published` (`attribute_published`);

ALTER IGNORE TABLE #__redshop_product_attribute_property ADD INDEX `attribute_id` (`attribute_id`);
ALTER IGNORE TABLE #__redshop_product_attribute_property ADD INDEX `ordering` (`ordering`);
ALTER IGNORE TABLE #__redshop_product_attribute_property ADD INDEX `setrequire_selected` (`setrequire_selected`);
ALTER IGNORE TABLE #__redshop_product_attribute_property ADD INDEX `property_published` (`property_published`);

ALTER IGNORE TABLE #__redshop_product_category_xref ADD INDEX `category_id` (`category_id`);
ALTER IGNORE TABLE #__redshop_product_category_xref ADD INDEX `ordering` (`ordering`);

ALTER IGNORE TABLE #__redshop_product_price ADD INDEX `product_id` (`product_id`);
ALTER IGNORE TABLE #__redshop_product_price ADD INDEX `shopper_group_id` (`shopper_group_id`);
ALTER IGNORE TABLE #__redshop_product_price ADD INDEX `price_quantity_start` (`price_quantity_start`);
ALTER IGNORE TABLE #__redshop_product_price ADD INDEX `price_quantity_end` (`price_quantity_end`);

ALTER IGNORE TABLE #__redshop_product_stockroom_xref ADD INDEX `product_id` (`product_id`);

ALTER IGNORE TABLE #__redshop_tax_rate ADD INDEX `tax_group_id` (`tax_group_id`);
ALTER IGNORE TABLE #__redshop_tax_rate ADD INDEX `tax_country` (`tax_country`);
ALTER IGNORE TABLE #__redshop_tax_rate ADD INDEX `tax_state` (`tax_state`);

ALTER IGNORE TABLE #__redshop_users_info ADD INDEX `address_type` (`address_type`);

ALTER IGNORE TABLE #__redshop_product_rating ADD INDEX `published` (`published`);

ALTER IGNORE TABLE #__redshop_discount_product_shoppers ADD INDEX `shopper_group_id` (`shopper_group_id`);

ALTER IGNORE TABLE #__redshop_product_accessory ADD INDEX `product_id` (`product_id`);

ALTER TABLE `#__redshop_discount_product` MODIFY `category_ids` VARCHAR(255);
ALTER IGNORE TABLE #__redshop_discount_product ADD INDEX `published` (`published`);
ALTER IGNORE TABLE #__redshop_discount_product ADD INDEX `start_date` (`start_date`);
ALTER IGNORE TABLE #__redshop_discount_product ADD INDEX `end_date` (`end_date`);
ALTER IGNORE TABLE #__redshop_discount_product ADD INDEX `category_ids` (`category_ids`);
