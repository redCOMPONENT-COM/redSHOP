ALTER TABLE `#__redshop_media` CHANGE `media_id` `id` INT(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `jos_redshop_media` ADD `title` VARCHAR(255) NULL AFTER `id`;
ALTER TABLE `jos_redshop_media` ADD `checked_out` INT(11) NULL AFTER `mimetype`;
ALTER TABLE `jos_redshop_media` ADD `checked_out_time` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP  AFTER `mimetype`;
ALTER TABLE `jos_redshop_media` ADD `modified_by` INT(11) NULL AFTER `checked_out_time`;
ALTER TABLE `jos_redshop_media` ADD `modified_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `modified_by`;