set FOREIGN_KEY_CHECKS=0;

ALTER TABLE `#__redshop_product_stockroom_xref`
  ADD INDEX `idx_product_id` (`product_id` ASC);

ALTER TABLE `#__redshop_product_stockroom_xref`
  ADD INDEX `idx_quantity` (`quantity` ASC);

set FOREIGN_KEY_CHECKS=0;
