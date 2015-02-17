ALTER TABLE `#__redshop_product_download` CHANGE `download_id` `download_id` VARCHAR(255)
CHARACTER SET utf8
COLLATE utf8_general_ci NOT NULL DEFAULT '';

UPDATE `#__redshop_currency`
SET `currency_name` = 'Mexican Peso', `currency_code` = 'MXN'
WHERE `currency_id` = 99;