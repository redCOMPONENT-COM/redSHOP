SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Column_Update('#__redshop_customer_question', 'question_id', 'id', "INT(11) NOT NULL AUTO_INCREMENT");

CALL redSHOP_Index_Remove('#__redshop_customer_question', 'idx_published');
CALL redSHOP_Index_Remove('#__redshop_customer_question', 'idx_parent_id');
CALL redSHOP_Index_Remove('#__redshop_customer_question', 'idx_product_id');

CALL redSHOP_Index_Add('#__redshop_customer_question', '#__rs_idx_published', "(`published` ASC)");
CALL redSHOP_Index_Add('#__redshop_customer_question', '#__rs_idx_product_id', "(`product_id` ASC)");
CALL redSHOP_Index_Add('#__redshop_customer_question', '#__rs_idx_parent_id', "(`parent_id` ASC)");

ALTER TABLE `#__redshop_customer_question` ADD CONSTRAINT `#__rs_customer_question_fk1`
    FOREIGN KEY (`product_id`)
    REFERENCES `#__redshop_product` (`product_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE `#__redshop_siteviewer` ENGINE = InnoDB;

ALTER TABLE `#__redshop_pageviewer` ENGINE = InnoDB;

ALTER TABLE `#__redshop_cart` ADD PRIMARY KEY(`session_id`, `product_id`, `section`);

SET FOREIGN_KEY_CHECKS = 1;