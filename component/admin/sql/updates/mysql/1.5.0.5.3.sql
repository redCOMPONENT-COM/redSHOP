ALTER TABLE `#__redshop_order_payment` DROP INDEX idx_order_id;
ALTER TABLE `#__redshop_order_payment` ADD UNIQUE(`order_id`);
