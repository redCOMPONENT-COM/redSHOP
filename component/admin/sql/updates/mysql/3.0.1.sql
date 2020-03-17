SET FOREIGN_KEY_CHECKS = 0;
 -- -----------------------------------------------------
-- Table `#__redshop_order_status_log`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_order_status_log', 'by_user_id', 'by_user_id', "INT(11) NOT NULL AFTER `order_id`");

SET FOREIGN_KEY_CHECKS = 1;