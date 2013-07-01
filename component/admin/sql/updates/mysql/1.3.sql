ALTER TABLE `#__redshop_product_subattribute_color` MODIFY `subattribute_color_name` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci;
ALTER IGNORE TABLE `#__redshop_product_attribute_property` ADD `extra_field` VARCHAR( 250 ) NOT NULL;
ALTER IGNORE TABLE `#__redshop_product_subattribute_color` ADD `extra_field` VARCHAR( 250 ) NOT NULL;

--
-- Subscription Feature Tables and Field
--

ALTER IGNORE TABLE `#__redshop_users_info` ADD  `user_subscription` int(11) NOT NULL COMMENT 'Shopper Group Id from subscription';

--
-- Table structure for table `#__redshop_subscription`
--
CREATE TABLE IF NOT EXISTS `#__redshop_subscription` (
  `subscription_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Subscription Auto Increment Id',
  `product_id` int(11) NOT NULL COMMENT 'Product Id of Product Table',
  `subscription_period` int(11) NOT NULL COMMENT 'Define Subscription period',
  `subscription_period_unit` varchar(255) NOT NULL COMMENT 'Define Subscription type which can be "year", "month", "day"',
  `subscription_period_lifetime` tinyint(1) NOT NULL COMMENT 'One time Subscription',
  `subscription_applicable_products` text NOT NULL COMMENT 'Applicable Product in Current Subscription',
  `subscription_applicable_categories` text NOT NULL COMMENT 'Applicable Category in Current Subscription',
  `joomla_acl_groups` varchar(255) NOT NULL COMMENT 'Joomla ACL Group for of user when they will subscribe',
  `fallback_joomla_acl_groups` varchar(255) NOT NULL COMMENT 'Joomla ACL Group for of user when they will Unsubscribe',
  `shoppergroup` int(11) NOT NULL COMMENT 'redSHOP Shopper Group for of user when they will subscribe',
  `fallback_shoppergroup` int(11) NOT NULL COMMENT 'redSHOP Shopper Group for of user when they will Unsubscribe',
  PRIMARY KEY (`subscription_id`),
  UNIQUE KEY `product_id` (`product_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

--
-- Add Extra Field Information
--
INSERT IGNORE INTO `#__redshop_fields` (`field_id`, `field_title`, `field_name`, `field_type`, `field_desc`, `field_class`, `field_section`, `field_maxlength`, `field_cols`, `field_rows`, `field_size`, `field_show_in_front`, `required`, `published`, `ordering`, `display_in_product`, `display_in_checkout`) VALUES
(null, 'rs_product_type', 'rs_product_type', '1', '', '', '1', 12, 12, 12, 123, 1, 0, 1, 1, 0, 0),
(null, 'rs_icon', 'rs_icon', '9', '', '', '1', 0, 0, 0, 0, 1, 0, 1, 2, 0, 0);
