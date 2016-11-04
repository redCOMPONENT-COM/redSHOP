SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_country` CHANGE `country_name` `country_name` VARCHAR(64) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE `#__redshop_country` CHANGE `country_3_code` `country_3_code` CHAR(3) CHARACTER SET utf8 NOT NULL;
ALTER TABLE `#__redshop_country` CHANGE `country_2_code` `country_2_code` CHAR(2) CHARACTER SET utf8 NOT NULL;
ALTER TABLE `#__redshop_country` DROP INDEX `idx_country_2_code`;
ALTER TABLE `#__redshop_country` DROP INDEX `idx_country_3_code`;
ALTER TABLE `#__redshop_country` ADD UNIQUE INDEX `#__rs_idx_country_3_code` (`country_3_code` ASC);
ALTER TABLE `#__redshop_country` ADD UNIQUE INDEX `#__rs_idx_country_2_code` (`country_2_code` ASC);

ALTER TABLE `#__redshop_state` DROP INDEX `state_3_code`;
ALTER TABLE `#__redshop_state` DROP INDEX `state_2_code`;
ALTER TABLE `#__redshop_state` ADD UNIQUE INDEX `#__rs_idx_state_3_code` (`country_id` ASC, `state_3_code` ASC);
ALTER TABLE `#__redshop_state` ADD UNIQUE INDEX `#__rs_idx_state_2_code` (`country_id` ASC, `state_2_code` ASC);

SET FOREIGN_KEY_CHECKS = 1;