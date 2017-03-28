SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `#__redshop_media`
  ADD INDEX `#__rs_idx_media_common` (`section_id` ASC, `media_section` ASC, `media_type` ASC, `published` ASC, `ordering` ASC)
  USING BTREE;

SET FOREIGN_KEY_CHECKS = 1;