SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `redshop_redshop2`
--

-- --------------------------------------------------------

--
-- Table structure for table `ptnsc_redshop_product`
--

CREATE TABLE `ptnsc_redshop_product` (
  `id` int(11) NOT NULL COMMENT 'Product ID',
  `number` varchar(250) NOT NULL COMMENT 'Product number',
  `parent_id` int(11) NOT NULL COMMENT 'Product relationship. Will be moved and replaced by nestled',
  `manufacturer_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `template_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `tax_group_id` int(11) NOT NULL,
  `attribute_set_id` int(11) NOT NULL,
  `account_group_id` int(11) NOT NULL,
  `featured` bit(1) DEFAULT b'0' COMMENT 'Is this featured product',
  `type` varchar(20) NOT NULL COMMENT 'Type of product',
  `title` varchar(255) NOT NULL COMMENT 'Product name',
  `alias` varchar(255) NOT NULL COMMENT 'Product name alias',
  `description` longtext,
  `short_description` text,
  `price` double NOT NULL,
  `discount_price` double NOT NULL,
  `discount_stratdate` int(11) NOT NULL,
  `discount_enddate` int(11) NOT NULL,
  `images` text NOT NULL,
  `product_full_image` varchar(250) NOT NULL,
  `product_thumb_image` varchar(250) NOT NULL,
  `product_back_full_image` varchar(250) NOT NULL,
  `product_back_thumb_image` varchar(250) NOT NULL,
  `product_preview_image` varchar(250) NOT NULL,
  `product_preview_back_image` varchar(250) NOT NULL,
  `created` datetime NOT NULL,
  `created_by` int(11) NOT NULL COMMENT 'Created by user_id',
  `created_by_alias` varchar(255) NOT NULL,
  `modified` datetime NOT NULL,
  `modified_by` datetime NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL,
  `publish_up` datetime NOT NULL,
  `publish_down` datetime NOT NULL,
  `state` tinyint(4) NOT NULL,
  `hits` int(11) NOT NULL DEFAULT '0' COMMENT 'Number of views by users on this product',
  `on_sale` bit(1) NOT NULL COMMENT 'Is this on sale product',
  `not_for_sale` bit(1) NOT NULL,
  `is_expired` bit(1) NOT NULL,
  `availability_date` datetime NOT NULL,
  `cat_in_sefurl` int(11) NOT NULL,
  `sef_url` varchar(255) NOT NULL,
  `length` decimal(10,0) NOT NULL,
  `height` decimal(10,0) NOT NULL,
  `width` decimal(10,0) NOT NULL,
  `weight` float NOT NULL,
  `diameter` decimal(10,0) NOT NULL,
  `download` bit(1) NOT NULL DEFAULT b'0' COMMENT 'Is this downloadable product',
  `download_days` int(11) NOT NULL,
  `download_limit` int(11) NOT NULL,
  `download_clock_min` int(11) NOT NULL,
  `download_infinite` bit(1) NOT NULL COMMENT 'Allow product download without any limits',
  `volume` double NOT NULL,
  `min_order_product_quantity` int(11) NOT NULL,
  `min_per_product_total` int(11) NOT NULL,
  `max_order_product_quantity` int(11) NOT NULL,
  `quantity_selectbox_value` varchar(255) NOT NULL,
  `allow_decimal_piece` int(11) NOT NULL,
  `discount_calc_method` varchar(255) NOT NULL,
  `use_discount_calc` bit(1) NOT NULL,
  `use_range` tinyint(4) NOT NULL,
  `metakey` varchar(255) DEFAULT NULL,
  `metadesc` varchar(255) DEFAULT NULL,
  `metalanguage_setting` text,
  `metarobot_info` text,
  `pagetitle` varchar(160) DEFAULT NULL,
  `canonical_url` varchar(255) DEFAULT NULL,
  `preorder` text NOT NULL,
  `append_to_global_seo` enum('append','prepend','replace','') NOT NULL DEFAULT 'append',
  `params` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ptnsc_redshop_product`
--
ALTER TABLE `ptnsc_redshop_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `template` (`template_id`),
  ADD KEY `tax_group` (`tax_group_id`),
  ADD KEY `created_by` (`created_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ptnsc_redshop_product`
--
ALTER TABLE `ptnsc_redshop_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Product ID';
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ptnsc_redshop_product`
--
ALTER TABLE `ptnsc_redshop_product`
  ADD CONSTRAINT `created_by` FOREIGN KEY (`created_by`) REFERENCES `ptnsc_users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `manufacturer` FOREIGN KEY (`manufacturer_id`) REFERENCES `ptnsc_redshop_manufacturer` (`manufacturer_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `supplier` FOREIGN KEY (`supplier_id`) REFERENCES `ptnsc_redshop_supplier` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `tax_group` FOREIGN KEY (`tax_group_id`) REFERENCES `ptnsc_redshop_tax_group` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `template` FOREIGN KEY (`template_id`) REFERENCES `ptnsc_redshop_template` (`template_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;