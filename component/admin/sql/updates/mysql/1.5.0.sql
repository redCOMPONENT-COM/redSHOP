SET FOREIGN_KEY_CHECKS = 0;

CALL redSHOP_Column_Update('#__redshop_product_download', 'download_id', 'download_id', "VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''");

UPDATE `#__redshop_currency`
  SET `currency_name` = 'Mexican Peso', `currency_code` = 'MXN'
  WHERE `currency_id` = 99;

SET FOREIGN_KEY_CHECKS = 1;