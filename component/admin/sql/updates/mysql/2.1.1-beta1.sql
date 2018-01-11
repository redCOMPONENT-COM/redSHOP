SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Table `#__redshop_fields_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `#__redshop_fields_group` ;

CREATE TABLE IF NOT EXISTS `#__redshop_fields_group` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(125) NOT NULL,
  `description` VARCHAR(1024) NOT NULL DEFAULT '',
  `section` VARCHAR(20) NOT NULL,
  `ordering` INT(11) NOT NULL DEFAULT 0,
  `published` TINYINT(4) NOT NULL DEFAULT 0,
  `created_by` INT(11) NULL DEFAULT NULL,
  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `checked_out` INT(11) NULL DEFAULT NULL,
  `checked_out_time` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` INT(11) NULL DEFAULT NULL,
  `modified_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  INDEX `#__rs_feld_group_idx1` (`section` ASC),
  INDEX `#__rs_feld_group_idx2` (`published` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Custom fields groups';

CALL redSHOP_Column_Update('#__redshop_fields', 'groupId', 'groupId', "INT(11) NULL DEFAULT NULL AFTER `section`");
CALL redSHOP_Index_Add('#__redshop_fields', '#__rs_field_fk1', "(`groupId` ASC)");
CALL redSHOP_Constraint_Update('#__redshop_fields', '#__rs_field_fk1', 'groupId', '#__redshop_fields_group', 'id', 'CASCADE', 'SET NULL');

SET FOREIGN_KEY_CHECKS = 1;