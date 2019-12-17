SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__redshop_notifystock_users`
-- -----------------------------------------------------
CALL redSHOP_Column_Update('#__redshop_notifystock_users', 'email_not_login', 'email_not_login', 'VARCHAR(150) NULL');

SET FOREIGN_KEY_CHECKS = 1;