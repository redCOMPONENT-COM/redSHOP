SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_order_item` ADD INDEX `idx_product_quantity` USING BTREE (`product_id` ASC, `product_quantity` ASC);
ALTER TABLE `#__redshop_product` ADD INDEX `#__rs_prod_publish_parent` (`product_parent_id` ASC, `published` ASC);
ALTER TABLE `#__redshop_product` ADD INDEX `#__rs_prod_publish_parent_special` (`product_parent_id` ASC, `published` ASC, `product_special` ASC);
ALTER TABLE `#__redshop_product_subattribute_color` ADD INDEX `#__rs_sub_prop_common` (`subattribute_id` ASC, `subattribute_published` ASC, `ordering` ASC);

SET FOREIGN_KEY_CHECKS = 1;