SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Column_Update('#__redshop_state', 'state_id', 'id', "INT(11) NOT NULL AUTO_INCREMENT");

SET FOREIGN_KEY_CHECKS = 1;