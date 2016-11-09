SET
  FOREIGN_KEY_CHECKS = 0;
ALTER TABLE `#__redshop_customer_question` CHANGE `question_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__rs_customer_question` ADD CONSTRAINT `#__rs_customer_question_fk1`
    FOREIGN KEY (`product_id`)
    REFERENCES `#__redshop_product` (`product_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS = 1;