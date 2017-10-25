SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `#__redshop_redshopb_xref` ;
CREATE TABLE IF NOT EXISTS `#__redshop_redshopb_xref` (
	`redshop_product_id` int(11) NOT NULL,
	`redshopb_product_id` int(11) NOT NULL,
	PRIMARY KEY (`redshop_product_id`,`redshopb_product_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

DROP TABLE IF EXISTS `#__redshop_redshopb_manufacturer_xref` ;
CREATE TABLE IF NOT EXISTS `#__redshop_redshopb_manufacturer_xref` (
	`redshop_manufacturer_id` int(11) NOT NULL,
	`redshopb_manufacturer_id` int(11) NOT NULL,
	PRIMARY KEY (`redshop_manufacturer_id`,`redshopb_manufacturer_id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

SET FOREIGN_KEY_CHECKS = 1;
