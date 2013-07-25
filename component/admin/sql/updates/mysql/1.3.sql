ALTER TABLE `#__redshop_product_subattribute_color` MODIFY `subattribute_color_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER IGNORE TABLE #__redshop_product_attribute_property ADD `extra_field` VARCHAR( 250 ) NOT NULL;
ALTER IGNORE TABLE #__redshop_product_subattribute_color ADD `extra_field` VARCHAR( 250 ) NOT NULL;

ALTER IGNORE TABLE #__redshop_tax_group ADD INDEX `idx_published` (`published`);

ALTER IGNORE TABLE #__redshop_category ADD INDEX `idx_published` (`published`);

ALTER IGNORE TABLE #__redshop_media ADD INDEX `idx_section_id` (`section_id`);
ALTER IGNORE TABLE #__redshop_media ADD INDEX `idx_media_section` (`media_section`);
ALTER IGNORE TABLE #__redshop_media ADD INDEX `idx_media_type` (`media_type`);
ALTER IGNORE TABLE #__redshop_media ADD INDEX `idx_media_name` (`media_name`);

ALTER IGNORE TABLE #__redshop_product ADD INDEX `idx_manufacturer_id` (`manufacturer_id`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `idx_product_price` (`product_price`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `idx_discount_price` (`discount_price`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `idx_discount_stratdate` (`discount_stratdate`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `idx_discount_enddate` (`discount_enddate`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `idx_visited` (`visited`);
ALTER IGNORE TABLE #__redshop_product ADD INDEX `idx_published` (`published`, `expired`, `product_parent_id`);

ALTER IGNORE TABLE #__redshop_attribute_set ADD INDEX `idx_published` (`published`);

ALTER IGNORE TABLE #__redshop_product_attribute ADD INDEX `idx_product_id` (`product_id`);
ALTER IGNORE TABLE #__redshop_product_attribute ADD INDEX `idx_attribute_name` (`attribute_name`);
ALTER IGNORE TABLE #__redshop_product_attribute ADD INDEX `idx_attribute_set_id` (`attribute_set_id`);
ALTER IGNORE TABLE #__redshop_product_attribute ADD INDEX `idx_attribute_published` (`attribute_published`);

ALTER IGNORE TABLE #__redshop_product_attribute_property ADD INDEX `idx_attribute_id` (`attribute_id`);
ALTER IGNORE TABLE #__redshop_product_attribute_property ADD INDEX `idx_setrequire_selected` (`setrequire_selected`);
ALTER IGNORE TABLE #__redshop_product_attribute_property ADD INDEX `idx_property_published` (`property_published`);

ALTER IGNORE TABLE #__redshop_product_category_xref ADD INDEX `idx_category_id` (`category_id`);

ALTER IGNORE TABLE #__redshop_product_price ADD INDEX `idx_product_id` (`product_id`);
ALTER IGNORE TABLE #__redshop_product_price ADD INDEX `idx_shopper_group_id` (`shopper_group_id`);
ALTER IGNORE TABLE #__redshop_product_price ADD INDEX `idx_price_quantity_start` (`price_quantity_start`);
ALTER IGNORE TABLE #__redshop_product_price ADD INDEX `idx_price_quantity_end` (`price_quantity_end`);

ALTER IGNORE TABLE #__redshop_product_stockroom_xref ADD INDEX `idx_product_id` (`product_id`);
ALTER IGNORE TABLE #__redshop_product_stockroom_xref ADD INDEX `idx_stockroom_id` (`stockroom_id`);
ALTER IGNORE TABLE #__redshop_product_stockroom_xref ADD INDEX `idx_quantity` (`quantity`);

ALTER IGNORE TABLE #__redshop_tax_rate ADD INDEX `idx_tax_group_id` (`tax_group_id`);
ALTER IGNORE TABLE #__redshop_tax_rate ADD INDEX `idx_tax_country` (`tax_country`);
ALTER IGNORE TABLE #__redshop_tax_rate ADD INDEX `idx_tax_state` (`tax_state`);

ALTER IGNORE TABLE #__redshop_users_info ADD INDEX `idx_address_type` (`address_type`);

ALTER IGNORE TABLE #__redshop_product_rating ADD INDEX `idx_published` (`published`);

ALTER IGNORE TABLE #__redshop_discount_product_shoppers ADD INDEX `idx_shopper_group_id` (`shopper_group_id`);

ALTER IGNORE TABLE #__redshop_product_accessory ADD INDEX `idx_product_id` (`product_id`);

ALTER IGNORE TABLE #__redshop_discount_product ADD INDEX `idx_published` (`published`);
ALTER IGNORE TABLE #__redshop_discount_product ADD INDEX `idx_start_date` (`start_date`);
ALTER IGNORE TABLE #__redshop_discount_product ADD INDEX `idx_end_date` (`end_date`);

ALTER IGNORE TABLE #__redshop_manufacturer ADD INDEX `idx_published` (`published`);

ALTER IGNORE TABLE #__redshop_orders ADD INDEX `idx_order_payment_status` (`order_payment_status`);
ALTER IGNORE TABLE #__redshop_orders ADD INDEX `idx_order_status` (`order_status`);

ALTER IGNORE TABLE #__redshop_siteviewer ADD INDEX `idx_session_id` (`session_id`);

ALTER IGNORE TABLE #__redshop_pageviewer ADD INDEX `idx_session_id` (`session_id`);

ALTER IGNORE TABLE #__redshop_template ADD INDEX `idx_template_section` (`template_section`);
ALTER IGNORE TABLE #__redshop_template ADD INDEX `idx_published` (`published`);

ALTER IGNORE TABLE #__redshop_fields ADD INDEX `idx_field_section` (`field_section`);
ALTER IGNORE TABLE #__redshop_fields ADD INDEX `idx_published` (`published`);

ALTER IGNORE TABLE #__redshop_product_subattribute_color ADD INDEX `idx_subattribute_published` ( `subattribute_published` , `subattribute_id`);

ALTER IGNORE TABLE #__redshop_product_attribute_stockroom_xref ADD INDEX `idx_section` ( `section_id` , `section` , `quantity`);
ALTER IGNORE TABLE #__redshop_product_attribute_stockroom_xref ADD INDEX `idx_stockroom_id` ( `stockroom_id`);
