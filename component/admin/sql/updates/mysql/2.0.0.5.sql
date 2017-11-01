SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_customer_question` CHANGE `question_id` `id` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `#__redshop_customer_question` DROP INDEX idx_published;
ALTER TABLE `#__redshop_customer_question` DROP INDEX idx_parent_id;
ALTER TABLE `#__redshop_customer_question` DROP INDEX idx_product_id;

ALTER TABLE `#__redshop_customer_question` ADD INDEX `#__rs_idx_published` (`published` ASC);
ALTER TABLE `#__redshop_customer_question` ADD INDEX `#__rs_idx_product_id` (`product_id` ASC);
ALTER TABLE `#__redshop_customer_question` ADD INDEX `#__rs_idx_parent_id` (`parent_id` ASC);

ALTER TABLE `#__redshop_customer_question` ADD CONSTRAINT `#__rs_customer_question_fk1`
    FOREIGN KEY (`product_id`)
    REFERENCES `#__redshop_product` (`product_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE;

ALTER TABLE `#__redshop_siteviewer` ENGINE = InnoDB;

ALTER TABLE `#__redshop_pageviewer` ENGINE = InnoDB;

ALTER TABLE `#__redshop_cart` ADD PRIMARY KEY(`session_id`, `product_id`, `section`);

SET FOREIGN_KEY_CHECKS = 1;