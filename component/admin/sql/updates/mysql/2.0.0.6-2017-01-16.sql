SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_order_item` ADD INDEX `idx_product_quantity` USING BTREE (`product_id` ASC, `product_quantity` ASC);

SET FOREIGN_KEY_CHECKS = 1;