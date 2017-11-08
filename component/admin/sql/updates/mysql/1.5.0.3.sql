SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_product_rating`
DROP INDEX `product_id`,
ADD UNIQUE `product_id` (`product_id`, `userid`, `email`);

SET FOREIGN_KEY_CHECKS = 1;