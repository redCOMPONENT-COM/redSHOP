SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_product` CHANGE `product_type` `product_type` VARCHAR(250) NOT NULL;

SET FOREIGN_KEY_CHECKS = 1;