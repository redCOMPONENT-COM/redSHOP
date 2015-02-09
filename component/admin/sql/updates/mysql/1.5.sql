ALTER TABLE `#__redshop_product_download` CHANGE `download_id` `download_id` VARCHAR(255)
CHARACTER SET utf8
COLLATE utf8_general_ci NOT NULL DEFAULT '';

REPLACE INTO `#__redshop_currency` (`currency_id`, `currency_name`, `currency_code`) VALUES
	(99, 'Mexican Peso', 'MXN');