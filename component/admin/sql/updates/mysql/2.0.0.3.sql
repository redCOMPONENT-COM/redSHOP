SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_country` CHANGE `country_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `#__redshop_state` CHANGE `country_id` `country_id` INT(11) NULL;
ALTER TABLE `#__redshop_state` ADD INDEX(`country_id`);
ALTER TABLE
  `#__redshop_state` ADD CONSTRAINT `#__rs_state_country_fk1` FOREIGN KEY(`country_id`) REFERENCES `#__redshop_country`(`id`) ON DELETE SET NULL ON UPDATE CASCADE;

SET FOREIGN_KEY_CHECKS = 1;
