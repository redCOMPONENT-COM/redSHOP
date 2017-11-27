SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Index_Remove('#__redshop_product_rating', 'product_id');
CALL redSHOP_Index_Unique_Add('#__redshop_product_rating', 'product_id', "(`product_id`, `userid`, `email`)");

SET FOREIGN_KEY_CHECKS = 1;