ALTER TABLE `#__redshop_product_attribute` ADD `attribute_show_fe` TINYINT(1) NULL DEFAULT '1' AFTER `attribute_description`;
ALTER TABLE `#__redshop_product_attribute_property` ADD `property_show_fe` TINYINT(1) NULL DEFAULT '1' AFTER `property_number`;
ALTER TABLE `#__redshop_product_subattribute_color` ADD `subattribute_show_fe` TINYINT(1) NULL DEFAULT '1' AFTER `subattribute_color_main_image`;