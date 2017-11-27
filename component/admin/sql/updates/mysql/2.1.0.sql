SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Column_Update('#__redshop_product', 'use_individual_payment_method', 'use_individual_payment_method', "INT(4) NOT NULL DEFAULT 0");

SET FOREIGN_KEY_CHECKS = 1;