SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Column_Update('#__redshop_quotation', 'quotation_customer_note', 'quotation_customer_note', "TEXT NOT NULL AFTER `quotation_note`");

CALL redSHOP_Column_Update('#__redshop_product', 'allow_decimal_piece', 'allow_decimal_piece', "INT(4) NOT NULL");
CALL redSHOP_Index_Remove('#__redshop_product', 'product_number');

CALL redSHOP_Index_Remove('#__redshop_country', 'idx_country_name');

CALL redSHOP_Index_Remove('#__redshop_currency', 'idx_currency_name');

CALL redSHOP_Column_Remove('#__redshop_order_item', 'container_id');

CALL redSHOP_Column_Update('#__redshop_usercart_item', 'attribs', 'attribs', "VARCHAR(5120) NOT NULL COMMENT 'Specified user attributes related with current item'");

CALL redSHOP_Column_Update('#__redshop_orders', 'invoice_number', 'invoice_number', "VARCHAR( 255 ) NOT NULL COMMENT 'Formatted Order Invoice for final use' AFTER `order_number`");
CALL redSHOP_Column_Update('#__redshop_orders', 'invoice_number_chrono', 'invoice_number_chrono', " INT NOT NULL COMMENT 'Order invoice number in chronological order' AFTER `order_number`");
CALL redSHOP_Index_Add('#__redshop_orders', 'idx_orders_invoice_number', '(`invoice_number` ASC)');
CALL redSHOP_Index_Add('#__redshop_orders', 'idx_orders_invoice_number_chrono', '(`invoice_number_chrono` ASC)');

CALL redSHOP_Index_Unique_Add('#__redshop_order_payment', 'order_id', "(`order_id` ASC)");
CALL redSHOP_Index_Remove('#__redshop_order_payment', 'idx_order_id');

CALL redSHOP_Column_Update('#__redshop_discount', 'name', 'name', "VARCHAR(250) NOT NULL");
CALL redSHOP_Index_Add('#__redshop_discount', 'idx_discount_name', '(`name` ASC)');

CALL redSHOP_Column_Update('#__redshop_giftcard', 'free_shipping', 'free_shipping', "TINYINT NOT NULL");

CALL redSHOP_Index_Add('#__redshop_product_stockroom_xref', 'idx_product_id', '(`product_id` ASC)');
CALL redSHOP_Index_Add('#__redshop_product_stockroom_xref', 'idx_quantity', '(`quantity` ASC)');


SET FOREIGN_KEY_CHECKS = 1;