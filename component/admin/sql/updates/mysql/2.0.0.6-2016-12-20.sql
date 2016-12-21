SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_orders` CHANGE `ship_method_id` `ship_method_id` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL;

SET FOREIGN_KEY_CHECKS = 1;