SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `rs_redshop_wishlist_product_item` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Primary key',
  `ref_id` INT(11) NOT NULL COMMENT 'Wishlist Reference ID',
  `attribute_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Product Attribute ID',
  `property_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Product Attribute Property ID',
  `subattribute_id` INT(11) NOT NULL DEFAULT '0' COMMENT 'Product Sub-Attribute ID',
  PRIMARY KEY (`id`),
  UNIQUE `rs_idx_wishlist_prod_item_unique` (`ref_id`, `attribute_id`, `property_id`, `subattribute_id`),
  INDEX `rs_idx_wishlist_prod_item_fk1` (`ref_id` ASC),
  INDEX `rs_idx_wishlist_prod_item_fk2` (`attribute_id` ASC),
  INDEX `rs_idx_wishlist_prod_item_fk3` (`property_id` ASC),
  INDEX `rs_idx_wishlist_prod_item_fk4` (`subattribute_id` ASC),
  CONSTRAINT `rs_rs_wishlist_prod_item_fk1`
    FOREIGN KEY (`ref_id`)
    REFERENCES `rs_redshop_wishlist_product` (`wishlist_product_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  CONSTRAINT `rs_rs_wishlist_prod_item_fk2`
    FOREIGN KEY (`attribute_id`)
    REFERENCES `rs_redshop_product_attribute` (`attribute_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  CONSTRAINT `rs_rs_wishlist_prod_item_fk3`
    FOREIGN KEY (`property_id`)
    REFERENCES `rs_redshop_product_attribute_property` (`property_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  CONSTRAINT `rs_rs_wishlist_prod_item_fk4`
    FOREIGN KEY (`subattribute_id`)
    REFERENCES `rs_redshop_product_subattribute_color` (`subattribute_color_id`)
      ON DELETE CASCADE
      ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Wishlist product item';

SET FOREIGN_KEY_CHECKS = 1;