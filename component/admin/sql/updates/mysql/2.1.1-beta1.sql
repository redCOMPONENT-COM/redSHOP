SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS `#__redshop_fields_group` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(125) NOT NULL,
  `description` TEXT NULL,
  `section` VARCHAR(125) NOT NULL,
  `created_by` INT(11) NULL,
  `created_date` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
  `checked_out` INT(11) NULL,
  `checked_out_time` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
  `modified_date` DATETIME NULL DEFAULT '0000-00-00 00:00:00',
  `modified_by` INT(11) NULL,
  `ordering` INT(11) NULL DEFAULT 0,
  `published` TINYINT(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Custom fields\' groups';

CALL redSHOP_Column_Update('#__redshop_fields', 'groupId', 'groupId', "INT NOT NULL DEFAULT '0' AFTER `section`");

SET FOREIGN_KEY_CHECKS = 1;