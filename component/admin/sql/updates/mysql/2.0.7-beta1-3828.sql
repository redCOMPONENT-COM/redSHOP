SET FOREIGN_KEY_CHECKS = 0;

-- -----------------------------------------------------
-- Tags
-- -----------------------------------------------------
INSERT IGNORE INTO `#__content_types`
(`type_title`, `type_alias`, `table`, `rules`, `field_mappings`, `router`, `content_history_options`)
VALUES
('redSHOP', 'com_redshop.product', '{"special":{"dbtable":"#__redshop_product","key":"product_id"}}', '', '{"common":{"core_content_item_id":"product_id","core_title":"product_name","core_state":"published","core_catid":"cat_in_sefurl"}}', 'RedshopHelperRoute::getProductRoute', '');

SET FOREIGN_KEY_CHECKS = 1;