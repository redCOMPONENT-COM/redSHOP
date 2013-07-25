--
-- Table structure for table `#__redshop_economic_accountgroup`
--

CREATE TABLE IF NOT EXISTS `#__redshop_economic_accountgroup` (
	`accountgroup_id` INT( 11 ) NOT NULL auto_increment,
	`accountgroup_name` VARCHAR( 255 ) NOT NULL ,
	`economic_vat_account` VARCHAR( 255 ) NOT NULL ,
	`economic_nonvat_account` VARCHAR( 255 ) NOT NULL ,
	`economic_discount_nonvat_account` VARCHAR( 255 ) NOT NULL,
	`economic_shipping_vat_account` VARCHAR( 255 ) NOT NULL ,
	`economic_shipping_nonvat_account` VARCHAR( 255 ) NOT NULL ,
	`economic_discount_product_number` VARCHAR( 255 ) NOT NULL ,
	`published` TINYINT( 4 ) NOT NULL,
	PRIMARY KEY  (`accountgroup_id`)
)DEFAULT CHARSET=utf8 COMMENT='redSHOP Economic Account Group';


--
-- Table structure for table `#__redshop_accessmanager`
--


CREATE TABLE IF NOT EXISTS `#__redshop_accessmanager` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`section_name` VARCHAR( 256 ) NOT NULL ,
`gid` INT( 11 ) NOT NULL ,
`view` ENUM( '1', '0' ) NULL ,
`add` ENUM( '1', '0' ) NULL ,
`edit` ENUM( '1', '0' ) NULL ,
`delete` ENUM( '1', '0' ) NULL
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Access Manager';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_quotation_accessory_item`
--

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_accessory_item` (
  `quotation_item_acc_id` int(11) NOT NULL auto_increment,
  `quotation_item_id` int(11) NOT NULL,
  `accessory_id` int(11) NOT NULL,
  `accessory_item_sku` varchar(255) NOT NULL,
  `accessory_item_name` varchar(255) NOT NULL,
  `accessory_price` decimal(15,4) NOT NULL,
  `accessory_vat` decimal(15,4) NOT NULL,
  `accessory_quantity` int(11) NOT NULL,
  `accessory_item_price` decimal(15,2) NOT NULL,
  `accessory_final_price` decimal(15,2) NOT NULL,
  `accessory_attribute` text NOT NULL,
  PRIMARY KEY  (`quotation_item_acc_id`)
)DEFAULT CHARSET=utf8 COMMENT='redSHOP Quotation Accessory item';


-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_quotation_attribute_item`
--

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_attribute_item` (
  `quotation_att_item_id` int(11) NOT NULL auto_increment,
  `quotation_item_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `section` varchar(250) NOT NULL,
  `parent_section_id` int(11) NOT NULL,
  `section_name` varchar(250) NOT NULL,
  `section_price` decimal(15,4) NOT NULL,
  `section_vat` decimal(15,4) NOT NULL,
  `section_oprand` char(1) NOT NULL,
  `is_accessory_att` tinyint(4) NOT NULL,
  PRIMARY KEY  (`quotation_att_item_id`)
)DEFAULT CHARSET=utf8 COMMENT='redSHOP Quotation Attribute item';

--
-- Table structure for table `#__redshop_order_attribute_item`
--

CREATE TABLE IF NOT EXISTS `#__redshop_order_attribute_item` (
  `order_att_item_id` int(11) NOT NULL auto_increment,
  `order_item_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `section` varchar(250) NOT NULL,
  `parent_section_id` int(11) NOT NULL,
  `section_name` varchar(250) NOT NULL,
  `section_price` decimal(15,4) NOT NULL,
  `section_vat` decimal(15,4) NOT NULL,
  `section_oprand` char(1) NOT NULL,
  `is_accessory_att` tinyint(4) NOT NULL,
  PRIMARY KEY  (`order_att_item_id`)
)DEFAULT CHARSET=utf8 COMMENT='redSHOP order Attribute item';

--
-- Table structure for table `#__redshop_xml_export_ipaddress`
--

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export_ipaddress` (
  `xmlexport_ip_id` int(11) NOT NULL auto_increment,
  `xmlexport_id` int(11) NOT NULL,
  `access_ipaddress` varchar(255) NOT NULL,
  PRIMARY KEY  (`xmlexport_ip_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP XML Export Ip Address';


--
-- Table structure for table `#__redshop_xml_import_log`
--


CREATE TABLE IF NOT EXISTS `#__redshop_xml_import_log` (
  `xmlimport_log_id` int(11) NOT NULL auto_increment,
  `xmlimport_id` int(11) NOT NULL,
  `xmlimport_filename` varchar(255) NOT NULL,
  `xmlimport_date` int(11) NOT NULL,
  PRIMARY KEY  (`xmlimport_log_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP XML Import log';

--
-- Table structure for table `#__redshop_xml_export_log`
--

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export_log` (
  `xmlexport_log_id` int(11) NOT NULL auto_increment,
  `xmlexport_id` int(11) NOT NULL,
  `xmlexport_filename` varchar(255) NOT NULL,
  `xmlexport_date` int(11) NOT NULL,
  PRIMARY KEY  (`xmlexport_log_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP XML Export log';

--
-- Table structure for table `#__redshop_xml_import`
--

CREATE TABLE IF NOT EXISTS `#__redshop_xml_import` (
  `xmlimport_id` int(11) NOT NULL auto_increment,
  `filename` varchar(255) NOT NULL,
  `display_filename` varchar(255) NOT NULL,
  `xmlimport_url` varchar(255) NOT NULL,
  `section_type` varchar(255) NOT NULL,
  `auto_sync` tinyint(4) NOT NULL,
  `sync_on_request` tinyint(4) NOT NULL,
  `auto_sync_interval` int(11) NOT NULL,
  `override_existing` tinyint(4) NOT NULL,
  `add_prefix_for_existing` varchar(50) NOT NULL,
  `xmlimport_date` int(11) NOT NULL,
  `xmlimport_filetag` text NOT NULL,
  `xmlimport_billingtag` text NOT NULL,
  `xmlimport_shippingtag` text NOT NULL,
  `xmlimport_orderitemtag` text NOT NULL,
  `xmlimport_stocktag` text NOT NULL,
  `xmlimport_prdextrafieldtag` text NOT NULL,
  `published` tinyint(4) NOT NULL,
  `element_name` VARCHAR( 255 ) NOT NULL,
  `billing_element_name` VARCHAR( 255 ) NOT NULL,
  `shipping_element_name` VARCHAR( 255 ) NOT NULL,
  `orderitem_element_name` VARCHAR( 255 ) NOT NULL,
  `stock_element_name` VARCHAR( 255 ) NOT NULL,
  `prdextrafield_element_name` VARCHAR( 255 ) NOT NULL,
  PRIMARY KEY  (`xmlimport_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP XML Import';

--
-- Table structure for table `#__redshop_xml_export`
--

CREATE TABLE IF NOT EXISTS `#__redshop_xml_export` (
  `xmlexport_id` int(11) NOT NULL auto_increment,
  `filename` varchar(255) NOT NULL,
  `display_filename` varchar(255) NOT NULL,
  `parent_name` VARCHAR(255) NOT NULL,
  `section_type` varchar(255) NOT NULL,
  `auto_sync` tinyint(4) NOT NULL,
  `sync_on_request` tinyint(4) NOT NULL,
  `auto_sync_interval` int(11) NOT NULL,
  `xmlexport_date` int(11) NOT NULL,
  `xmlexport_filetag` text NOT NULL,
  `element_name` VARCHAR( 255 ),
  `published` tinyint(4) NOT NULL,
  `use_to_all_users` tinyint(4) NOT NULL,
  `xmlexport_billingtag` text NOT NULL,
  `billing_element_name` varchar(255) NOT NULL,
  `xmlexport_shippingtag` text NOT NULL,
  `shipping_element_name` varchar(255) NOT NULL,
  `xmlexport_orderitemtag` text NOT NULL,
  `orderitem_element_name` varchar(255) NOT NULL,
  `xmlexport_stocktag` text NOT NULL,
  `stock_element_name` VARCHAR( 255 ) NOT NULL,
  `xmlexport_prdextrafieldtag` TEXT NOT NULL,
  `prdextrafield_element_name` VARCHAR( 255 ) NOT NULL,
  PRIMARY KEY  (`xmlexport_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP XML Export';


--
-- Table structure for table `#__redshop_customer_question`
--

CREATE TABLE IF NOT EXISTS `#__redshop_customer_question` (
	`question_id` INT( 11 ) NOT NULL auto_increment,
	`parent_id` INT( 11 ) NOT NULL ,
	`product_id` INT( 11 ) NOT NULL ,
	`question` LONGTEXT NOT NULL ,
	`user_id` INT( 11 ) NOT NULL ,
	`user_name` VARCHAR( 255 ) NOT NULL ,
	`user_email` VARCHAR( 255 ) NOT NULL ,
	`published` TINYINT( 4 ) NOT NULL ,
	`question_date` INT( 11 ) NOT NULL ,
	`ordering` INT( 11 ) NOT NULL,
	PRIMARY KEY  (`question_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Customer Question';
-- --------------------------------------------------------


--
-- Table structure for table `#__redshop_quotation_fields_data`
--

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_fields_data` (
  `data_id` int(11) NOT NULL auto_increment,
  `fieldid` int(11) default NULL,
  `data_txt` longtext,
  `quotation_item_id` int(11) default NULL,
  `section` varchar(20) default NULL,

  PRIMARY KEY  (`data_id`),


















  KEY `quotation_item_id` (`quotation_item_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Quotation USer field';
-- --------------------------------------------------------


--
-- Table structure for table `#__redshop_quotation`
--

CREATE TABLE IF NOT EXISTS `#__redshop_quotation` (
	`quotation_id` INT( 11 ) NOT NULL auto_increment,
	`quotation_number` VARCHAR( 50 ) NOT NULL ,
	`user_id` INT( 11 ) NOT NULL ,
	`user_info_id` INT( 11 ) NOT NULL ,
	`order_id` INT( 11 ) NOT NULL,
	`quotation_total` DECIMAL( 15, 2 ) NOT NULL ,
	`quotation_subtotal` DECIMAL( 15, 2 ) NOT NULL ,
	`quotation_tax` DECIMAL( 15, 2 ) NOT NULL,
	`quotation_discount` DECIMAL( 15, 4 ) NOT NULL,
	`quotation_status` INT( 11 ) NOT NULL ,
	`quotation_cdate` INT( 11 ) NOT NULL ,
	`quotation_mdate` INT( 11 ) NOT NULL ,
	`quotation_note` TEXT NOT NULL,
	`quotation_ipaddress` VARCHAR( 20 ) NOT NULL,
	`quotation_encrkey` VARCHAR( 255 ) NOT NULL,
	PRIMARY KEY  (`quotation_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Quotation';
-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_quotation_item`
--

CREATE TABLE IF NOT EXISTS `#__redshop_quotation_item` (
	`quotation_item_id` INT( 11 ) NOT NULL auto_increment,
	`quotation_id` INT( 11 ) NOT NULL ,
	`product_id` INT( 11 ) NOT NULL ,
	`product_name` VARCHAR( 255 ) NOT NULL ,
	`product_price` DECIMAL( 15, 4 ) NOT NULL ,
	`product_excl_price` DECIMAL( 15, 4 ) NOT NULL ,
	`product_final_price` DECIMAL( 15, 4 ) NOT NULL ,
	`actualitem_price` DECIMAL( 15, 4 ) NOT NULL,
	`product_quantity` INT( 11 ) NOT NULL ,
	`product_attribute` TEXT NOT NULL ,
	`product_accessory` TEXT NOT NULL ,
	`mycart_accessory` TEXT NOT NULL,
	`product_wrapperid` INT( 11 ) NOT NULL ,
	`wrapper_price` DECIMAL( 15, 2 ) NOT NULL,
	`is_giftcard` TINYINT( 4 ) NOT NULL,
	PRIMARY KEY  (`quotation_item_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Quotation Item';
-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_tax_group`
--

CREATE TABLE IF NOT EXISTS `#__redshop_tax_group` (
	`tax_group_id` int(11) NOT NULL auto_increment,
	`tax_group_name` VARCHAR( 255 ) NOT NULL ,
	`published` TINYINT NOT NULL,
	PRIMARY KEY  (`tax_group_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Tax Group';
-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_wrapper`
--

CREATE TABLE IF NOT EXISTS `#__redshop_wrapper` (
  `wrapper_id` int(11) NOT NULL auto_increment,
  `product_id` VARCHAR( 250 ) NOT NULL,
  `category_id` VARCHAR( 250 ) NOT NULL,
  `wrapper_name` varchar(255) NOT NULL,
  `wrapper_price` double NOT NULL,
  `wrapper_image` varchar(255) NOT NULL,
  `createdate` INT( 11 ) NOT NULL,
  `wrapper_use_to_all` TINYINT( 4 ) NOT NULL,
  `published` TINYINT( 4 ) NOT NULL,
  PRIMARY KEY  (`wrapper_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Wrapper';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_pageviewer`
--

CREATE TABLE IF NOT EXISTS `#__redshop_pageviewer` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` INT( 11 ) NOT NULL,
  `session_id` varchar(250) NOT NULL,
  `section` varchar(250) NOT NULL,
  `section_id` INT( 11 ) NOT NULL,
  `hit` INT( 11 ) NOT NULL,
  `created_date` INT( 11 ) NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Page Viewer';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_mass_discount`
--

CREATE TABLE IF NOT EXISTS `#__redshop_mass_discount` (
`mass_discount_id` INT NOT NULL AUTO_INCREMENT,
`discount_product` LONGTEXT NOT NULL ,
`category_id` LONGTEXT NOT NULL ,
`manufacturer_id` LONGTEXT NOT NULL,
`discount_type` TINYINT NOT NULL ,
`discount_amount` DOUBLE( 10, 2 ) NOT NULL ,
`discount_startdate` INT NOT NULL ,
`discount_enddate` INT NOT NULL,
PRIMARY KEY (`mass_discount_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Page Viewer';

--
-- Table structure for table `#__redshop_giftcard`
--

CREATE TABLE IF NOT EXISTS `#__redshop_giftcard` (
  `giftcard_id` int(11) NOT NULL auto_increment,
  `giftcard_name` varchar(255) NOT NULL,
  `giftcard_price` decimal(10,3) NOT NULL,
  `giftcard_value` decimal(10,3) NOT NULL,
  `giftcard_validity` int(11) NOT NULL,
  `giftcard_date` int(11) NOT NULL,
  `giftcard_bgimage` varchar(255) NOT NULL,
  `giftcard_image` varchar(255) NOT NULL,
  `published` int(11) NOT NULL,
  `giftcard_desc` longtext NOT NULL,
  `customer_amount` int(11) NOT NULL,
  PRIMARY KEY  (`giftcard_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Giftcard';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_coupons_transaction`
--

CREATE TABLE IF NOT EXISTS `#__redshop_coupons_transaction` (
  `transaction_coupon_id` INT NOT NULL auto_increment,
  `coupon_id` INT NOT NULL ,
  `coupon_code` VARCHAR( 255 ) NOT NULL ,
  `coupon_value` DECIMAL( 10, 3 ) NOT NULL ,
  `userid` INT NOT NULL ,
  `trancation_date` INT NOT NULL,
  `published` INT NOT NULL,
  PRIMARY KEY (`transaction_coupon_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Coupons Transaction';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_siteviewer`
--

CREATE TABLE IF NOT EXISTS `#__redshop_siteviewer` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` INT( 11 ) NOT NULL,
  `session_id` varchar(250) NOT NULL,
  `created_date` INT( 11 ) NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Site Viewer';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_catalog`
--

CREATE TABLE IF NOT EXISTS `#__redshop_catalog` (
  `catalog_id` int(11) NOT NULL auto_increment,
  `catalog_name` varchar(250) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`catalog_id`)
) DEFAULT CHARSET=utf8  COMMENT='redSHOP Catalog' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_catalog_colour`
--

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_colour` (
  `colour_id` int(11) NOT NULL auto_increment,
  `sample_id` int(11) NOT NULL,
  `code_image` varchar(250) NOT NULL,
  `is_image` tinyint(4) NOT NULL,
  PRIMARY KEY  (`colour_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Catalog Colour' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_catalog_request`
--

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_request` (
  `catalog_user_id` int(11) NOT NULL auto_increment,
  `catalog_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `registerDate` int(11) NOT NULL,
  `block` tinyint(4) NOT NULL,
  `reminder_1` tinyint(4) NOT NULL,
  `reminder_2` tinyint(4) NOT NULL,
  `reminder_3` tinyint(4) NOT NULL,
  PRIMARY KEY  (`catalog_user_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Catalog Request' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_catalog_sample`
--

CREATE TABLE IF NOT EXISTS `#__redshop_catalog_sample` (
  `sample_id` int(11) NOT NULL auto_increment,
  `sample_name` varchar(100) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`sample_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Catalog Sample' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_category`
--

CREATE TABLE IF NOT EXISTS `#__redshop_category` (
  `category_id` int(11) NOT NULL auto_increment,
  `category_name` varchar(250) NOT NULL,
  `category_short_description` longtext NOT NULL,
  `category_description` longtext NOT NULL,
  `category_template` int(11) NOT NULL,
  `category_more_template` varchar(255) NOT NULL,
  `products_per_page` int(11) NOT NULL,
  `category_thumb_image` varchar(250) NOT NULL,
  `category_full_image` varchar(250) NOT NULL,
  `metakey` varchar(250) NOT NULL,
  `metadesc` longtext NOT NULL,
  `metalanguage_setting` text NOT NULL,
  `metarobot_info` text NOT NULL,
  `pagetitle` text NOT NULL,
  `pageheading` longtext NOT NULL,
  `sef_url` text NOT NULL,
  `published` tinyint(4) NOT NULL,
  `category_pdate` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `ordering` int(11) NOT NULL,
  `canonical_url` text NOT NULL,
  PRIMARY KEY  (`category_id`)
)   DEFAULT CHARSET=utf8 COMMENT='redSHOP Category'  ;


-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_category_xref`
--

CREATE TABLE IF NOT EXISTS `#__redshop_category_xref` (
  `category_parent_id` int(11) NOT NULL default '0',
  `category_child_id` int(11) NOT NULL default '0',
  KEY `category_xref_category_parent_id` (`category_parent_id`),
  KEY `category_xref_category_child_id` (`category_child_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Category relation';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_cart`
--

CREATE TABLE IF NOT EXISTS `#__redshop_cart` (
  `session_id` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `section` VARCHAR( 250 ) NOT NULL,
  `qty` int(11) NOT NULL,
  `time` double NOT NULL
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Cart';

--
-- Table structure for table `#__redshop_container`
--

CREATE TABLE IF NOT EXISTS `#__redshop_container` (
  `container_id` int(11) NOT NULL auto_increment,
  `container_name` varchar(250) NOT NULL,
  `manufacture_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `container_desc` longtext NOT NULL,
  `creation_date` double NOT NULL,
  `min_del_time` int(11) NOT NULL,
  `max_del_time` int(11) NOT NULL,
  `container_volume` double NOT NULL,
  `stockroom_id` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY  (`container_id`)
)   DEFAULT CHARSET=utf8 COMMENT='redSHOP Container' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_container_product_xref`
--

CREATE TABLE IF NOT EXISTS `#__redshop_container_product_xref` (
  `container_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  UNIQUE KEY `container_id` (`container_id`,`product_id`)
)   DEFAULT CHARSET=utf8 COMMENT='redSHOP Container Product Relation' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_country`
--

CREATE TABLE IF NOT EXISTS `#__redshop_country` (
  `country_id` int(11) NOT NULL auto_increment,
  `country_name` varchar(64) default NULL,
  `country_3_code` char(3) default NULL,
  `country_2_code` char(2) default NULL,
  `country_jtext` VARCHAR( 255 ) NOT NULL,
  PRIMARY KEY  (`country_id`),
  KEY `idx_country_name` (`country_name`)
)   DEFAULT CHARSET=utf8 COMMENT='Country records' ;




--
-- Dumping data for table `#__redshop_country`
--

INSERT IGNORE INTO `#__redshop_country` (`country_id`, `country_name`, `country_3_code`, `country_2_code`) VALUES
(1, 'Afghanistan', 'AFG', 'AF'),
(2, 'Albania', 'ALB', 'AL'),
(3, 'Algeria', 'DZA', 'DZ'),
(4, 'American Samoa', 'ASM', 'AS'),
(5, 'Andorra', 'AND', 'AD'),
(6, 'Angola', 'AGO', 'AO'),
(7, 'Anguilla', 'AIA', 'AI'),
(8, 'Antarctica', 'ATA', 'AQ'),
(9, 'Antigua and Barbuda', 'ATG', 'AG'),
(10, 'Argentina', 'ARG', 'AR'),
(11, 'Armenia', 'ARM', 'AM'),
(12, 'Aruba', 'ABW', 'AW'),
(13, 'Australia', 'AUS', 'AU'),
(14, 'Austria', 'AUT', 'AT'),
(15, 'Azerbaijan', 'AZE', 'AZ'),
(16, 'Bahamas', 'BHS', 'BS'),
(17, 'Bahrain', 'BHR', 'BH'),
(18, 'Bangladesh', 'BGD', 'BD'),
(19, 'Barbados', 'BRB', 'BB'),
(20, 'Belarus', 'BLR', 'BY'),
(21, 'Belgium', 'BEL', 'BE'),
(22, 'Belize', 'BLZ', 'BZ'),
(23, 'Benin', 'BEN', 'BJ'),
(24, 'Bermuda', 'BMU', 'BM'),
(25, 'Bhutan', 'BTN', 'BT'),
(26, 'Bolivia', 'BOL', 'BO'),
(27, 'Bosnia and Herzegowina', 'BIH', 'BA'),
(28, 'Botswana', 'BWA', 'BW'),
(29, 'Bouvet Island', 'BVT', 'BV'),
(30, 'Brazil', 'BRA', 'BR'),
(31, 'British Indian Ocean Territory', 'IOT', 'IO'),
(32, 'Brunei Darussalam', 'BRN', 'BN'),
(33, 'Bulgaria', 'BGR', 'BG'),
(34, 'Burkina Faso', 'BFA', 'BF'),
(35, 'Burundi', 'BDI', 'BI'),
(36, 'Cambodia', 'KHM', 'KH'),
(37, 'Cameroon', 'CMR', 'CM'),
(38, 'Canada', 'CAN', 'CA'),
(39, 'Cape Verde', 'CPV', 'CV'),
(40, 'Cayman Islands', 'CYM', 'KY'),
(41, 'Central African Republic', 'CAF', 'CF'),
(42, 'Chad', 'TCD', 'TD'),
(43, 'Chile', 'CHL', 'CL'),
(44, 'China', 'CHN', 'CN'),
(45, 'Christmas Island', 'CXR', 'CX'),
(46, 'Cocos (Keeling) Islands', 'CCK', 'CC'),
(47, 'Colombia', 'COL', 'CO'),
(48, 'Comoros', 'COM', 'KM'),
(49, 'Congo', 'COG', 'CG'),
(50, 'Cook Islands', 'COK', 'CK'),
(51, 'Costa Rica', 'CRI', 'CR'),
(52, 'Cote D''Ivoire', 'CIV', 'CI'),
(53, 'Croatia', 'HRV', 'HR'),
(54, 'Cuba', 'CUB', 'CU'),
(55, 'Cyprus', 'CYP', 'CY'),
(56, 'Czech Republic', 'CZE', 'CZ'),
(57, 'Denmark', 'DNK', 'DK'),
(58, 'Djibouti', 'DJI', 'DJ'),
(59, 'Dominica', 'DMA', 'DM'),
(60, 'Dominican Republic', 'DOM', 'DO'),
(62, 'Ecuador', 'ECU', 'EC'),
(63, 'Egypt', 'EGY', 'EG'),
(64, 'El Salvador', 'SLV', 'SV'),
(65, 'Equatorial Guinea', 'GNQ', 'GQ'),
(66, 'Eritrea', 'ERI', 'ER'),
(67, 'Estonia', 'EST', 'EE'),
(68, 'Ethiopia', 'ETH', 'ET'),
(69, 'Falkland Islands (Malvinas)', 'FLK', 'FK'),
(70, 'Faroe Islands', 'FRO', 'FO'),
(71, 'Fiji', 'FJI', 'FJ'),
(72, 'Finland', 'FIN', 'FI'),
(73, 'France', 'FRA', 'FR'),
(75, 'French Guiana', 'GUF', 'GF'),
(76, 'French Polynesia', 'PYF', 'PF'),
(77, 'French Southern Territories', 'ATF', 'TF'),
(78, 'Gabon', 'GAB', 'GA'),
(79, 'Gambia', 'GMB', 'GM'),
(80, 'Georgia', 'GEO', 'GE'),
(81, 'Germany', 'DEU', 'DE'),
(82, 'Ghana', 'GHA', 'GH'),
(83, 'Gibraltar', 'GIB', 'GI'),
(84, 'Greece', 'GRC', 'GR'),
(85, 'Greenland', 'GRL', 'GL'),
(86, 'Grenada', 'GRD', 'GD'),
(87, 'Guadeloupe', 'GLP', 'GP'),
(88, 'Guam', 'GUM', 'GU'),
(89, 'Guatemala', 'GTM', 'GT'),
(90, 'Guinea', 'GIN', 'GN'),
(91, 'Guinea-bissau', 'GNB', 'GW'),
(92, 'Guyana', 'GUY', 'GY'),
(93, 'Haiti', 'HTI', 'HT'),
(94, 'Heard and Mc Donald Islands', 'HMD', 'HM'),
(95, 'Honduras', 'HND', 'HN'),
(96, 'Hong Kong', 'HKG', 'HK'),
(97, 'Hungary', 'HUN', 'HU'),
(98, 'Iceland', 'ISL', 'IS'),
(99, 'India', 'IND', 'IN'),
(100, 'Indonesia', 'IDN', 'ID'),
(101, 'Iran (Islamic Republic of)', 'IRN', 'IR'),
(102, 'Iraq', 'IRQ', 'IQ'),
(103, 'Ireland', 'IRL', 'IE'),
(104, 'Israel', 'ISR', 'IL'),
(105, 'Italy', 'ITA', 'IT'),
(106, 'Jamaica', 'JAM', 'JM'),
(107, 'Japan', 'JPN', 'JP'),
(108, 'Jordan', 'JOR', 'JO'),
(109, 'Kazakhstan', 'KAZ', 'KZ'),
(110, 'Kenya', 'KEN', 'KE'),
(111, 'Kiribati', 'KIR', 'KI'),
(112, 'Korea, Democratic People''s Republic of', 'PRK', 'KP'),
(113, 'Korea, Republic of', 'KOR', 'KR'),
(114, 'Kuwait', 'KWT', 'KW'),
(115, 'Kyrgyzstan', 'KGZ', 'KG'),
(116, 'Lao People''s Democratic Republic', 'LAO', 'LA'),
(117, 'Latvia', 'LVA', 'LV'),
(118, 'Lebanon', 'LBN', 'LB'),
(119, 'Lesotho', 'LSO', 'LS'),
(120, 'Liberia', 'LBR', 'LR'),
(121, 'Libyan Arab Jamahiriya', 'LBY', 'LY'),
(122, 'Liechtenstein', 'LIE', 'LI'),
(123, 'Lithuania', 'LTU', 'LT'),
(124, 'Luxembourg', 'LUX', 'LU'),
(125, 'Macau', 'MAC', 'MO'),
(126, 'Macedonia, The Former Yugoslav Republic of', 'MKD', 'MK'),
(127, 'Madagascar', 'MDG', 'MG'),
(128, 'Malawi', 'MWI', 'MW'),
(129, 'Malaysia', 'MYS', 'MY'),
(130, 'Maldives', 'MDV', 'MV'),
(131, 'Mali', 'MLI', 'ML'),
(132, 'Malta', 'MLT', 'MT'),
(133, 'Marshall Islands', 'MHL', 'MH'),
(134, 'Martinique', 'MTQ', 'MQ'),
(135, 'Mauritania', 'MRT', 'MR'),
(136, 'Mauritius', 'MUS', 'MU'),
(137, 'Mayotte', 'MYT', 'YT'),
(138, 'Mexico', 'MEX', 'MX'),
(139, 'Micronesia, Federated States of', 'FSM', 'FM'),
(140, 'Moldova, Republic of', 'MDA', 'MD'),
(141, 'Monaco', 'MCO', 'MC'),
(142, 'Mongolia', 'MNG', 'MN'),
(143, 'Montserrat', 'MSR', 'MS'),
(144, 'Morocco', 'MAR', 'MA'),
(145, 'Mozambique', 'MOZ', 'MZ'),
(146, 'Myanmar', 'MMR', 'MM'),
(147, 'Namibia', 'NAM', 'NA'),
(148, 'Nauru', 'NRU', 'NR'),
(149, 'Nepal', 'NPL', 'NP'),

(150, 'Netherlands', 'NLD', 'NL'),
(151, 'Netherlands Antilles', 'ANT', 'AN'),
(152, 'New Caledonia', 'NCL', 'NC'),
(153, 'New Zealand', 'NZL', 'NZ'),
(154, 'Nicaragua', 'NIC', 'NI'),
(155, 'Niger', 'NER', 'NE'),
(156, 'Nigeria', 'NGA', 'NG'),
(157, 'Niue', 'NIU', 'NU'),
(158, 'Norfolk Island', 'NFK', 'NF'),
(159, 'Northern Mariana Islands', 'MNP', 'MP'),
(160, 'Norway', 'NOR', 'NO'),
(161, 'Oman', 'OMN', 'OM'),
(162, 'Pakistan', 'PAK', 'PK'),
(163, 'Palau', 'PLW', 'PW'),
(164, 'Panama', 'PAN', 'PA'),
(165, 'Papua New Guinea', 'PNG', 'PG'),
(166, 'Paraguay', 'PRY', 'PY'),
(167, 'Peru', 'PER', 'PE'),
(168, 'Philippines', 'PHL', 'PH'),
(169, 'Pitcairn', 'PCN', 'PN'),
(170, 'Poland', 'POL', 'PL'),
(171, 'Portugal', 'PRT', 'PT'),
(172, 'Puerto Rico', 'PRI', 'PR'),
(173, 'Qatar', 'QAT', 'QA'),
(174, 'Reunion', 'REU', 'RE'),
(175, 'Romania', 'ROM', 'RO'),
(176, 'Russian Federation', 'RUS', 'RU'),
(177, 'Rwanda', 'RWA', 'RW'),
(178, 'Saint Kitts and Nevis', 'KNA', 'KN'),
(179, 'Saint Lucia', 'LCA', 'LC'),
(180, 'Saint Vincent and the Grenadines', 'VCT', 'VC'),
(181, 'Samoa', 'WSM', 'WS'),
(182, 'San Marino', 'SMR', 'SM'),
(183, 'Sao Tome and Principe', 'STP', 'ST'),
(184, 'Saudi Arabia', 'SAU', 'SA'),
(185, 'Senegal', 'SEN', 'SN'),
(186, 'Seychelles', 'SYC', 'SC'),
(187, 'Sierra Leone', 'SLE', 'SL'),
(188, 'Singapore', 'SGP', 'SG'),
(189, 'Slovakia (Slovak Republic)', 'SVK', 'SK'),
(190, 'Slovenia', 'SVN', 'SI'),
(191, 'Solomon Islands', 'SLB', 'SB'),
(192, 'Somalia', 'SOM', 'SO'),
(193, 'South Africa', 'ZAF', 'ZA'),
(194, 'South Georgia and the South Sandwich Islands', 'SGS', 'GS'),
(195, 'Spain', 'ESP', 'ES'),
(196, 'Sri Lanka', 'LKA', 'LK'),
(197, 'St. Helena', 'SHN', 'SH'),
(198, 'St. Pierre and Miquelon', 'SPM', 'PM'),
(199, 'Sudan', 'SDN', 'SD'),
(200, 'Suriname', 'SUR', 'SR'),
(201, 'Svalbard and Jan Mayen Islands', 'SJM', 'SJ'),
(202, 'Swaziland', 'SWZ', 'SZ'),
(203, 'Sweden', 'SWE', 'SE'),
(204, 'Switzerland', 'CHE', 'CH'),
(205, 'Syrian Arab Republic', 'SYR', 'SY'),
(206, 'Taiwan', 'TWN', 'TW'),
(207, 'Tajikistan', 'TJK', 'TJ'),
(208, 'Tanzania, United Republic of', 'TZA', 'TZ'),
(209, 'Thailand', 'THA', 'TH'),
(210, 'Togo', 'TGO', 'TG'),
(211, 'Tokelau', 'TKL', 'TK'),
(212, 'Tonga', 'TON', 'TO'),
(213, 'Trinidad and Tobago', 'TTO', 'TT'),
(214, 'Tunisia', 'TUN', 'TN'),
(215, 'Turkey', 'TUR', 'TR'),
(216, 'Turkmenistan', 'TKM', 'TM'),
(217, 'Turks and Caicos Islands', 'TCA', 'TC'),
(218, 'Tuvalu', 'TUV', 'TV'),
(219, 'Uganda', 'UGA', 'UG'),
(220, 'Ukraine', 'UKR', 'UA'),
(221, 'United Arab Emirates', 'ARE', 'AE'),
(222, 'United Kingdom', 'GBR', 'GB'),
(223, 'United States', 'USA', 'US'),
(224, 'United States Minor Outlying Islands', 'UMI', 'UM'),
(225, 'Uruguay', 'URY', 'UY'),
(226, 'Uzbekistan', 'UZB', 'UZ'),
(227, 'Vanuatu', 'VUT', 'VU'),
(228, 'Vatican City State (Holy See)', 'VAT', 'VA'),
(229, 'Venezuela', 'VEN', 'VE'),
(230, 'Viet Nam', 'VNM', 'VN'),
(231, 'Virgin Islands (British)', 'VGB', 'VG'),
(232, 'Virgin Islands (U.S.)', 'VIR', 'VI'),
(233, 'Wallis and Futuna Islands', 'WLF', 'WF'),
(234, 'Western Sahara', 'ESH', 'EH'),
(235, 'Yemen', 'YEM', 'YE'),
(237, 'The Democratic Republic of Congo', 'DRC', 'DC'),
(238, 'Zambia', 'ZMB', 'ZM'),
(239, 'Zimbabwe', 'ZWE', 'ZW'),
(241, 'Jersey', 'XJE', 'XJ'),
(242, 'St. Barthelemy', 'XSB', 'XB'),
(245, 'Aland Islands', 'ALA', 'AX'),
(246, 'Guernsey', 'GGY', 'GG'),
(247, 'Saint Martin (French part)', 'MAF', 'MF'),
(248, 'Timor-Leste', 'TLS', 'TL'),
(249, 'Serbia', 'SRB', 'RS'),
(250, 'Isle of Man', 'IMN', 'IM'),
(251, 'Montenegro', 'MNE', 'ME'),
(252, 'Palestinian Territory, Occupied', 'PSE', 'PS');



-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_coupons`
--

CREATE TABLE IF NOT EXISTS `#__redshop_coupons` (
  `coupon_id` int(16) NOT NULL auto_increment,
  `coupon_code` varchar(32) NOT NULL default '',
  `percent_or_total` tinyint(4) NOT NULL,
  `coupon_value` decimal(12,2) NOT NULL default '0.00',
  `start_date` double NOT NULL,
  `end_date` double NOT NULL,
  `coupon_type` tinyint(4) NOT NULL COMMENT '0 - Global, 1 - User Specific',
  `userid` int(11) NOT NULL,
  `coupon_left` INT NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`coupon_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Coupons' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_cron`
--

CREATE TABLE IF NOT EXISTS `#__redshop_cron` (
  `id` int(11) NOT NULL auto_increment,
  `date` date NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`id`)
)   DEFAULT CHARSET=utf8  COMMENT='redSHOP Cron Job' ;


--
-- Dumping data for table `#__redshop_cron`
--
INSERT IGNORE INTO `#__redshop_cron` (`id`, `date`, `published`) VALUES
(1, '2009-08-12', 1);

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_currency`
--

CREATE TABLE IF NOT EXISTS `#__redshop_currency` (
  `currency_id` int(11) NOT NULL auto_increment,
  `currency_name` varchar(64) default NULL,
  `currency_code` char(3) default NULL,
  PRIMARY KEY  (`currency_id`),
  KEY `idx_currency_name` (`currency_name`)
)   DEFAULT CHARSET=utf8 COMMENT='redSHOP Currency Detail'   ;


--
-- Dumping data for table `#__redshop_currency`
--

INSERT IGNORE INTO `#__redshop_currency` (`currency_id`, `currency_name`, `currency_code`) VALUES
(1, 'Andorran Peseta', 'ADP'),
(2, 'United Arab Emirates Dirham', 'AED'),
(3, 'Afghanistan Afghani', 'AFA'),
(4, 'Albanian Lek', 'ALL'),
(5, 'Netherlands Antillian Guilder', 'ANG'),
(6, 'Angolan Kwanza', 'AOK'),
(7, 'Argentine Peso', 'ARS'),
(9, 'Australian Dollar', 'AUD'),
(10, 'Aruban Florin', 'AWG'),
(11, 'Barbados Dollar', 'BBD'),
(12, 'Bangladeshi Taka', 'BDT'),
(14, 'Bulgarian Lev', 'BGL'),
(15, 'Bahraini Dinar', 'BHD'),
(16, 'Burundi Franc', 'BIF'),
(17, 'Bermudian Dollar', 'BMD'),
(18, 'Brunei Dollar', 'BND'),
(19, 'Bolivian Boliviano', 'BOB'),
(20, 'Brazilian Real', 'BRL'),
(21, 'Bahamian Dollar', 'BSD'),
(22, 'Bhutan Ngultrum', 'BTN'),
(23, 'Burma Kyat', 'BUK'),
(24, 'Botswanian Pula', 'BWP'),
(25, 'Belize Dollar', 'BZD'),
(26, 'Canadian Dollar', 'CAD'),
(27, 'Swiss Franc', 'CHF'),
(28, 'Chilean Unidades de Fomento', 'CLF'),
(29, 'Chilean Peso', 'CLP'),
(30, 'Yuan (Chinese) Renminbi', 'CNY'),
(31, 'Colombian Peso', 'COP'),
(32, 'Costa Rican Colon', 'CRC'),
(33, 'Czech Koruna', 'CZK'),
(34, 'Cuban Peso', 'CUP'),
(35, 'Cape Verde Escudo', 'CVE'),
(36, 'Cyprus Pound', 'CYP'),
(40, 'Danish Krone', 'DKK'),
(41, 'Dominican Peso', 'DOP'),
(42, 'Algerian Dinar', 'DZD'),
(43, 'Ecuador Sucre', 'ECS'),
(44, 'Egyptian Pound', 'EGP'),
(46, 'Ethiopian Birr', 'ETB'),
(47, 'Euro', 'EUR'),
(49, 'Fiji Dollar', 'FJD'),
(50, 'Falkland Islands Pound', 'FKP'),
(52, 'British Pound', 'GBP'),
(53, 'Ghanaian Cedi', 'GHC'),
(54, 'Gibraltar Pound', 'GIP'),
(55, 'Gambian Dalasi', 'GMD'),
(56, 'Guinea Franc', 'GNF'),
(58, 'Guatemalan Quetzal', 'GTQ'),
(59, 'Guinea-Bissau Peso', 'GWP'),
(60, 'Guyanan Dollar', 'GYD'),
(61, 'Hong Kong Dollar', 'HKD'),
(62, 'Honduran Lempira', 'HNL'),
(63, 'Haitian Gourde', 'HTG'),
(64, 'Hungarian Forint', 'HUF'),
(65, 'Indonesian Rupiah', 'IDR'),
(66, 'Irish Punt', 'IEP'),
(67, 'Israeli Shekel', 'ILS'),
(68, 'Indian Rupee', 'INR'),
(69, 'Iraqi Dinar', 'IQD'),
(70, 'Iranian Rial', 'IRR'),
(73, 'Jamaican Dollar', 'JMD'),
(74, 'Jordanian Dinar', 'JOD'),
(75, 'Japanese Yen', 'JPY'),
(76, 'Kenyan Schilling', 'KES'),
(77, 'Kampuchean (Cambodian) Riel', 'KHR'),
(78, 'Comoros Franc', 'KMF'),
(79, 'North Korean Won', 'KPW'),
(80, '(South) Korean Won', 'KRW'),
(81, 'Kuwaiti Dinar', 'KWD'),
(82, 'Cayman Islands Dollar', 'KYD'),
(83, 'Lao Kip', 'LAK'),
(84, 'Lebanese Pound', 'LBP'),
(85, 'Sri Lanka Rupee', 'LKR'),
(86, 'Liberian Dollar', 'LRD'),
(87, 'Lesotho Loti', 'LSL'),
(89, 'Libyan Dinar', 'LYD'),
(90, 'Moroccan Dirham', 'MAD'),
(91, 'Malagasy Franc', 'MGF'),
(92, 'Mongolian Tugrik', 'MNT'),
(93, 'Macau Pataca', 'MOP'),
(94, 'Mauritanian Ouguiya', 'MRO'),
(95, 'Maltese Lira', 'MTL'),
(96, 'Mauritius Rupee', 'MUR'),
(97, 'Maldive Rufiyaa', 'MVR'),
(98, 'Malawi Kwacha', 'MWK'),
(99, 'Mexican Peso', 'MXP'),
(100, 'Malaysian Ringgit', 'MYR'),
(101, 'Mozambique Metical', 'MZM'),
(102, 'Nigerian Naira', 'NGN'),
(103, 'Nicaraguan Cordoba', 'NIC'),
(105, 'Norwegian Kroner', 'NOK'),
(106, 'Nepalese Rupee', 'NPR'),
(107, 'New Zealand Dollar', 'NZD'),
(108, 'Omani Rial', 'OMR'),
(109, 'Panamanian Balboa', 'PAB'),
(110, 'Peruvian Nuevo Sol', 'PEN'),
(111, 'Papua New Guinea Kina', 'PGK'),
(112, 'Philippine Peso', 'PHP'),
(113, 'Pakistan Rupee', 'PKR'),
(114, 'Polish Złoty', 'PLN'),
(116, 'Paraguay Guarani', 'PYG'),
(117, 'Qatari Rial', 'QAR'),
(118, 'Romanian Leu', 'RON'),
(119, 'Rwanda Franc', 'RWF'),
(120, 'Saudi Arabian Riyal', 'SAR'),
(121, 'Solomon Islands Dollar', 'SBD'),
(122, 'Seychelles Rupee', 'SCR'),
(123, 'Sudanese Pound', 'SDP'),
(124, 'Swedish Krona', 'SEK'),
(125, 'Singapore Dollar', 'SGD'),
(126, 'St. Helena Pound', 'SHP'),
(127, 'Sierra Leone Leone', 'SLL'),
(128, 'Somali Schilling', 'SOS'),
(129, 'Suriname Guilder', 'SRG'),
(130, 'Sao Tome and Principe Dobra', 'STD'),
(131, 'Russian Ruble', 'RUB'),
(132, 'El Salvador Colon', 'SVC'),
(133, 'Syrian Potmd', 'SYP'),
(134, 'Swaziland Lilangeni', 'SZL'),
(135, 'Thai Bath', 'THB'),
(136, 'Tunisian Dinar', 'TND'),
(137, 'Tongan Pa''anga', 'TOP'),
(138, 'East Timor Escudo', 'TPE'),
(139, 'Turkish Lira', 'TRY'),
(140, 'Trinidad and Tobago Dollar', 'TTD'),
(141, 'Taiwan Dollar', 'TWD'),
(142, 'Tanzanian Schilling', 'TZS'),
(143, 'Uganda Shilling', 'UGS'),
(144, 'US Dollar', 'USD'),
(145, 'Uruguayan Peso', 'UYP'),
(146, 'Venezualan Bolivar', 'VEB'),
(147, 'Vietnamese Dong', 'VND'),
(148, 'Vanuatu Vatu', 'VUV'),
(149, 'Samoan Tala', 'WST'),
(150, 'Democratic Yemeni Dinar', 'YDD'),
(151, 'Yemeni Rial', 'YER'),
(152, 'New Yugoslavia Dinar', 'YUD'),
(153, 'South African Rand', 'ZAR'),
(154, 'Zambian Kwacha', 'ZMK'),
(155, 'Zaire Zaire', 'ZRZ'),
(156, 'Zimbabwe Dollar', 'ZWD'),
(157, 'Slovak Koruna', 'SKK'),
(158, 'Armenian Dram', 'AMD');




-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_discount`
--

CREATE TABLE IF NOT EXISTS `#__redshop_discount` (
  `discount_id` int(11) NOT NULL auto_increment,
  `amount` int(11) NOT NULL,
  `condition` tinyint(1) NOT NULL default '1',
  `discount_amount` DECIMAL( 10, 2 ) NOT NULL,
  `discount_type` tinyint(4) NOT NULL,
  `start_date` DOUBLE NOT NULL,
  `end_date` DOUBLE NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`discount_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Discount' ;



--
-- Table structure for table `#__redshop_discount_product`
--

CREATE TABLE IF NOT EXISTS `#__redshop_discount_product` (
  `discount_product_id` int(11) NOT NULL auto_increment,
  `amount` int(11) NOT NULL,
  `condition` tinyint(1) NOT NULL default '1',
  `discount_amount` DECIMAL( 10, 2 ) NOT NULL,
  `discount_type` tinyint(4) NOT NULL,
  `start_date` double NOT NULL,
  `end_date` double NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`discount_product_id`)
) DEFAULT CHARSET=utf8 ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_discount_product_shoppers`
--

CREATE TABLE IF NOT EXISTS `#__redshop_discount_product_shoppers` (
  `discount_product_id` int(11) NOT NULL,
  `shopper_group_id` int(11) NOT NULL
) DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__redshop_discount_shoppers`
--

CREATE TABLE IF NOT EXISTS `#__redshop_discount_shoppers` (
  `discount_id` int(11) NOT NULL,
  `shopper_group_id` int(11) NOT NULL
) DEFAULT CHARSET=utf8;

--
-- Table structure for table `#__redshop_fields`
--

CREATE TABLE IF NOT EXISTS `#__redshop_fields` (
  `field_id` int(11) NOT NULL auto_increment,
  `field_title` varchar(250) NOT NULL,
  `field_name` varchar(20) NOT NULL,
  `field_type` varchar(20) NOT NULL,
  `field_desc` longtext NOT NULL,
  `field_class` varchar(20) NOT NULL,
  `field_section` varchar(20) NOT NULL,
  `field_maxlength` int(11) NOT NULL,
  `field_cols` int(11) NOT NULL,
  `field_rows` int(11) NOT NULL,
  `field_size` tinyint(4) NOT NULL,
  `field_show_in_front` tinyint(4) NOT NULL,
  `required` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`field_id`)
)   DEFAULT CHARSET=utf8   COMMENT='redSHOP Fields' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_fields_data`
--

CREATE TABLE IF NOT EXISTS `#__redshop_fields_data` (
  `data_id` int(11) NOT NULL auto_increment,
  `fieldid` int(11) default NULL,
  `data_txt` longtext,
  `itemid` int(11) default NULL,
  `section` varchar(20) default NULL,
  `alt_text` varchar(255) NOT NULL,
  `image_link` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  PRIMARY KEY  (`data_id`),
  KEY `itemid` (`itemid`)
)   DEFAULT CHARSET=utf8 COMMENT='redSHOP Fields Data' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_fields_value`
--

CREATE TABLE IF NOT EXISTS `#__redshop_fields_value` (
  `value_id` int(11) NOT NULL auto_increment,
  `field_id` int(11) NOT NULL,
  `field_value` varchar(250) NOT NULL,
  `field_name` varchar(250) NOT NULL,
  PRIMARY KEY  (`value_id`)
)   DEFAULT CHARSET=utf8  COMMENT='redSHOP Fields Value' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_mail`
--

CREATE TABLE IF NOT EXISTS `#__redshop_mail` (
  `mail_id` int(11) NOT NULL auto_increment,
  `mail_name` varchar(255) NOT NULL,
  `mail_subject` varchar(255) NOT NULL,
  `mail_section` varchar(255) NOT NULL,
  `mail_order_status` varchar(11) NOT NULL,
  `mail_body` longtext NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`mail_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Mail Center' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_manufacturer`
--

CREATE TABLE IF NOT EXISTS `#__redshop_manufacturer` (
  `manufacturer_id` int(11) NOT NULL auto_increment,
  `manufacturer_name` varchar(250) NOT NULL,
  `manufacturer_desc` longtext NOT NULL,
  `manufacturer_email` varchar(250) NOT NULL,
  `product_per_page` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metalanguage_setting` text NOT NULL,
  `metarobot_info` text NOT NULL,
  `pagetitle` text NOT NULL,
  `pageheading` text NOT NULL,
  `sef_url` text NOT NULL,
  `published` int(11) NOT NULL,
  `ordering` INT NOT NULL ,
  PRIMARY KEY  (`manufacturer_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Manufacturer' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_media`
--

CREATE TABLE IF NOT EXISTS `#__redshop_media` (
  `media_id` int(11) NOT NULL auto_increment,
  `media_name` varchar(250) NOT NULL,
  `media_alternate_text` varchar(255) NOT NULL,
  `media_section` varchar(20) NOT NULL,
  `section_id` int(11) NOT NULL,
  `media_type` varchar(250) NOT NULL,
  `media_mimetype` varchar(20) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY  (`media_id`)
)   DEFAULT CHARSET=utf8 COMMENT='redSHOP Media'  ;

--
-- Table structure for table `#__redshop_media_download`
--

CREATE TABLE IF NOT EXISTS `#__redshop_media_download` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `media_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Media Additional Downloadable Files' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_newsletter`
--

CREATE TABLE IF NOT EXISTS `#__redshop_newsletter` (
  `newsletter_id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,

  `body` longtext NOT NULL,
  `template_id` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`newsletter_id`)
)   DEFAULT CHARSET=utf8 COMMENT='redSHOP Newsletter' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_newsletter_subscription`
--

CREATE TABLE IF NOT EXISTS `#__redshop_newsletter_subscription` (
  `subscription_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `newsletter_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `checkout` TINYINT NOT NULL,
  `published` int(11) NOT NULL,
  PRIMARY KEY  (`subscription_id`)
)   DEFAULT CHARSET=utf8 COMMENT='redSHOP Newsletter subscribers'  ;

--
-- Table structure for table `#__redshop_newsletter_tracker`
--


CREATE TABLE IF NOT EXISTS `#__redshop_newsletter_tracker` (
  `tracker_id` int(11) NOT NULL auto_increment,
  `newsletter_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `subscriber_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `read` tinyint(4) NOT NULL,
  `date` double NOT NULL,
  PRIMARY KEY  (`tracker_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Newsletter Tracker'  ;

-- --------------------------------------------------------
--
-- Table structure for table `#__redshop_newsletter_tracker`
--


CREATE TABLE IF NOT EXISTS `#__redshop_newsletter_tracker` (
  `tracker_id` int(11) NOT NULL auto_increment,
  `newsletter_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `subscriber_name` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `read` tinyint(4) NOT NULL,
  `date` double NOT NULL,
  PRIMARY KEY  (`tracker_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Newsletter Tracker'  ;

--
-- Table structure for table `#__redshop_orders`
--

CREATE TABLE IF NOT EXISTS `#__redshop_orders` (
  `order_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `order_number` varchar(32) default NULL,
  `user_info_id` varchar(32) default NULL,
  `order_total` decimal(15,2) NOT NULL default '0.00',
  `order_subtotal` decimal(15,5) default NULL,
  `order_tax` decimal(10,2) default NULL,
  `order_tax_details` text NOT NULL,
  `order_shipping` decimal(10,2) default NULL,
  `order_shipping_tax` decimal(10,2) default NULL,
  `coupon_discount` decimal(12,2) NOT NULL default '0.00',
  `order_discount` decimal(12,2) NOT NULL default '0.00',
  `special_discount_amount` decimal(12,2) NOT NULL,
  `payment_dicount` decimal(12,2) NOT NULL,
  `order_status` varchar(5) default NULL,
  `order_payment_status` varchar(25) NOT NULL,
  `cdate` int(11) default NULL,
  `mdate` int(11) default NULL,
  `ship_method_id` varchar(255) default NULL,
  `customer_note` text NOT NULL,
  `ip_address` varchar(15) NOT NULL default '',
  `encr_key` varchar(255) NOT NULL,
  `split_payment` int(11) NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `mail1_status` TINYINT( 1 ) NOT NULL,
  `mail2_status` TINYINT( 1 ) NOT NULL,
  `mail3_status` TINYINT( 1 ) NOT NULL,
  `special_discount` DECIMAL( 10, 2 ) NOT NULL,
  `payment_discount` DECIMAL( 10, 2 ) NOT NULL,
  `is_booked` TINYINT( 1 ) NOT NULL,
  `order_label_create` TINYINT( 1 ) NOT NULL,
  `vm_order_number` VARCHAR( 32 ) NOT NULL,
  `requisition_number` VARCHAR( 255 ) NOT NULL,
  `bookinvoice_number` INT(11) NOT NULL,
  `bookinvoice_date` INT(11) NOT NULL,
  PRIMARY KEY  (`order_id`),
  KEY `idx_orders_user_id` (`user_id`),
  KEY `idx_orders_order_number` (`order_number`),
  KEY `idx_orders_user_info_id` (`user_info_id`),
  KEY `idx_orders_ship_method_id` (`ship_method_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Order Detail' ;

CREATE TABLE IF NOT EXISTS `#__redshop_stockroom_amount_image` (
`stock_amount_id` INT( 11 ) NOT NULL AUTO_INCREMENT,
`stockroom_id` INT( 11 ) NOT NULL,
`stock_option` TINYINT( 4 ) NOT NULL,
`stock_quantity` INT NOT NULL,
`stock_amount_image` VARCHAR( 255 ) NOT NULL,
`stock_amount_image_tooltip` TEXT NOT NULL,
PRIMARY KEY  (`stock_amount_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP stockroom amount image' ;


--
-- Table structure for table `#__redshop_order_item`
--

CREATE TABLE IF NOT EXISTS `#__redshop_order_item` (
  `order_item_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) default NULL,
  `user_info_id` varchar(32) default NULL,
  `supplier_id` int(11) default NULL,
  `product_id` int(11) default NULL,
  `order_item_sku` varchar(64) NOT NULL default '',
  `order_item_name` varchar(255) NOT NULL default '',
  `product_quantity` int(11) default NULL,
  `product_item_price` decimal(15,4) default NULL,
  `product_item_price_excl_vat` decimal(15,4) default NULL,
  `product_final_price` decimal(12,4) NOT NULL default '0.00',
  `order_item_currency` varchar(16) default NULL,
  `order_status` varchar(250) default NULL,
  `customer_note` text NOT NULL,
  `cdate` int(11) default NULL,
  `mdate` int(11) default NULL,
  `product_attribute` text,
  `product_accessory` text NOT NULL,
  `delivery_time` int(11) NOT NULL,
  `container_id` int(11) NOT NULL,
  `stockroom_id` VARCHAR( 255 ) NOT NULL,
  `stockroom_quantity` VARCHAR( 255 ) NOT NULL,
  `is_split` tinyint(1) NOT NULL,
  `attribute_image` TEXT NOT NULL,
  PRIMARY KEY  (`order_item_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Order Item Detail' ;

--
-- Table structure for table `#__redshop_order_acc_item`
--

CREATE TABLE IF NOT EXISTS `#__redshop_order_acc_item` (
`order_item_acc_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`order_item_id` INT NOT NULL ,
`product_id` INT NOT NULL ,
`order_acc_item_sku` VARCHAR( 255 ) NOT NULL ,
`order_acc_item_name` VARCHAR( 255 ) NOT NULL ,
`order_acc_price` DECIMAL( 15,4 ) NOT NULL ,
`order_acc_vat` DECIMAL( 15,4 ) NOT NULL ,
`product_quantity` INT NOT NULL ,
`product_acc_item_price` DECIMAL( 15,4 ) NOT NULL ,
`product_acc_final_price` DECIMAL( 15,4 ) NOT NULL ,
`product_attribute` TEXT NOT NULL
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Order Accessory Item Detail' ;
-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_order_payment`
--

CREATE TABLE IF NOT EXISTS `#__redshop_order_payment` (
  `payment_order_id` bigint(20) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL default '0',
  `payment_method_id` int(11) default NULL,
  `order_payment_code` varchar(30) NOT NULL default '',
  `order_payment_cardname` blob NOT NULL,
  `order_payment_number` blob,
  `order_payment_ccv` blob NOT NULL,
  `order_payment_amount` double(10,2) NOT NULL,
  `order_payment_expire` int(11) default NULL,
  `order_payment_name` varchar(255) default NULL,
  `order_payment_trans_id` text NOT NULL,
  `authorize_status` varchar(255) default NULL,
  `order_transfee` double(10,2) NOT NULL,
  PRIMARY KEY  (`payment_order_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Order Payment Detail' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_order_status_log`
--

CREATE TABLE IF NOT EXISTS `#__redshop_order_status_log` (
  `order_status_log_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL,
  `order_status` varchar(5) NOT NULL,
  `order_payment_status` varchar(25) NOT NULL,
  `date_changed` int(11) NOT NULL,
  `customer_note` text NOT NULL,
  PRIMARY KEY  (`order_status_log_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Orders Status history';

--
-- Table structure for table `#__redshop_order_status`
--

CREATE TABLE IF NOT EXISTS `#__redshop_order_status` (
  `order_status_id` int(11) NOT NULL auto_increment,
  `order_status_code` varchar(64) NOT NULL,
  `order_status_name` varchar(64) default NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`order_status_id`),
  UNIQUE KEY `order_status_code` (`order_status_code`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Orders Status';


-- --------------------------------------------------------


--
-- Dumping data for table `#__redshop_order_status`
--

INSERT IGNORE INTO `#__redshop_order_status` (`order_status_id`, `order_status_code`, `order_status_name`, `published`) VALUES
(1, 'P', 'Pending', 1),
(2, 'C', 'Confirmed', 1),

(3, 'X', 'Cancelled', 1),
(4, 'R', 'Refunded', 1),
(5, 'S', 'Shipped', 1),
(6, 'RD', 'Ready for delivery', 1),
(7, 'RD1', 'Ready for 1st delivery', 1),
(8, 'RD2', 'Ready for 2nd delivery', 1),
(9, 'ACCP', 'Awaiting credit card payment', 1),
(10, 'APP', 'Awaiting paypal payment', 1),
(11, 'ABT', 'Awaiting bank transfer', 1),
(12, 'PR', 'Payment received', 1),
(13, 'RC', 'Reclamation', 1),
(14, 'PS', 'Partially shipped', 1),
(15, 'RT', 'Returned', 1),
(16, 'PRT', 'Partially Returned', 1),
(17, 'PRC', 'Partially Reclamation', 1);

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_order_users_info`
--

CREATE TABLE IF NOT EXISTS `#__redshop_order_users_info` (
  `order_info_id` int(11) NOT NULL auto_increment,
  `users_info_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `address_type` varchar(255) NOT NULL,
  `vat_number` varchar(250) NOT NULL,
  `tax_exempt` tinyint(4) NOT NULL,
  `shopper_group_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country_code` varchar(11) NOT NULL,
  `state_code` varchar(11) NOT NULL,
  `zipcode` int(11) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `tax_exempt_approved` tinyint(1) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `is_company` tinyint(4) NOT NULL,
  `user_email` VARCHAR( 255 ) NOT NULL ,
  `company_name` VARCHAR( 255 ) NOT NULL,
  `ean_number` VARCHAR( 250 ) NOT NULL,
  PRIMARY KEY  (`order_info_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Order User Information' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_payment_method`
--


CREATE TABLE IF NOT EXISTS `#__redshop_payment_method` (
  `payment_method_id` int(11) NOT NULL auto_increment,
  `plugin` varchar(100) NOT NULL,
  `payment_method_name` varchar(255) default NULL,
  `payment_class` varchar(50) NOT NULL default '',
  `payment_method_code` varchar(8) default NULL,
  `published` tinyint(1) default NULL,
  `is_creditcard` tinyint(1) NOT NULL default '0',
  `payment_discount_is_percent` tinyint(4) NOT NULL,
  `payment_price` float(10,2) NOT NULL,
  `payment_extrainfo` text NOT NULL,
  `payment_passkey` blob NOT NULL,
  `params` text NOT NULL,
  `ordering` int(11) NOT NULL,
  `shopper_group` varchar(250) NOT NULL,
  `accepted_credict_card` varchar(255) NOT NULL,
  `payment_oprand` varchar(50) NOT NULL,
  PRIMARY KEY  (`payment_method_id`)
)  DEFAULT CHARSET=utf8 COMMENT='redSHOP Payment Method';


-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_product`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product` (
  `product_id` int(11) NOT NULL auto_increment,
  `product_parent_id` INT NOT NULL,
  `manufacturer_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `product_on_sale` tinyint(4) NOT NULL,
  `product_special` TINYINT NOT NULL,
  `product_download` TINYINT NOT NULL,
  `product_template` int(11) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `product_price` double NOT NULL,
  `discount_price` double NOT NULL,
  `discount_stratdate` int(11) NOT NULL,
  `discount_enddate` int(11) NOT NULL,
  `product_number` varchar(250) NOT NULL,
  `product_type` varchar(20) NOT NULL,
  `product_s_desc` longtext NOT NULL,
  `product_desc` longtext NOT NULL,
  `product_volume` double NOT NULL,
  `product_tax_id` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `product_thumb_image` varchar(250) NOT NULL,
  `product_full_image` varchar(250) NOT NULL,
  `publish_date` datetime NOT NULL,
  `update_date` timestamp NOT NULL default '0000-00-00 00:00:00' on update CURRENT_TIMESTAMP,
  `visited` int(11) NOT NULL,
  `metakey` text NOT NULL,
  `metadesc` text NOT NULL,
  `metalanguage_setting` text NOT NULL,
  `metarobot_info` text NOT NULL,
  `pagetitle` text NOT NULL,
  `pageheading` text NOT NULL,
  `sef_url` text NOT NULL,
  `cat_in_sefurl` int(11) NOT NULL,
  `weight` float(10,3) NOT NULL,
  `expired` TINYINT NOT NULL,
  `not_for_sale` TINYINT NOT NULL,
  `use_discount_calc` tinyint(4) NOT NULL,
  `discount_calc_method` varchar(255) NOT NULL,
  `min_order_product_quantity` int(11) NOT NULL,
  `attribute_set_id` INT(11) NOT NULL,
  `product_length` decimal(10,2) NOT NULL,
  `product_height` decimal(10,2) NOT NULL,
  `product_width` decimal(10,2) NOT NULL,
  `product_diameter` DECIMAL( 10, 2 ) NOT NULL,
  `product_availability_date` int(11) NOT NULL,
  `use_range` TINYINT NOT NULL,
  `product_tax_group_id` int(11) NOT NULL,
  `product_download_days` int(11) NOT NULL,
  `product_download_limit` int(11) NOT NULL,
  `product_download_clock` int(11) NOT NULL,
  `product_download_clock_min` int(11) NOT NULL,
  `accountgroup_id` int(11) NOT NULL,
  `canonical_url` text NOT NULL,
  `minimum_per_product_total` int(11) NOT NULL,
  PRIMARY KEY  (`product_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Products';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_product_accessory`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_accessory` (
  `accessory_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `child_product_id` int(11) NOT NULL,
  `accessory_price` double NOT NULL,
  `oprand` char(1) NOT NULL,
  `setdefault_selected` TINYINT( 4 ) NOT NULL,
  PRIMARY KEY  (`accessory_id`)
) DEFAULT CHARSET=utf8  COMMENT='redSHOP Products Accessory';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_product_attribute`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute` (
  `attribute_id` int(11) NOT NULL auto_increment,
  `attribute_name` varchar(250) NOT NULL,
  `attribute_required` TINYINT NOT NULL,
  `allow_multiple_selection` tinyint(1) NOT NULL,
  `hide_attribute_price` tinyint(1) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ordering` INT NOT NULL,
  `attribute_set_id` INT NOT NULL,
  `display_type` varchar(255) NOT NULL,
  PRIMARY KEY  (`attribute_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Products Attribute';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_product_attribute_property`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_property` (
  `property_id` int(11) NOT NULL auto_increment,
  `attribute_id` int(11) NOT NULL,
  `property_name` varchar(255) NOT NULL,
  `property_price` double NOT NULL,
  `oprand` char(1) NOT NULL default '+',
  `property_image` varchar(255) NOT NULL,
  `property_main_image` varchar(255) NOT NULL,
  `ordering` INT NOT NULL,
  `setdefault_selected` tinyint(4) NOT NULL,
  `setrequire_selected` tinyint(3) NOT NULL,
  `setmulti_selected` tinyint(4) NOT NULL,
  `setdisplay_type` varchar(255) NOT NULL,
  `extra_field` VARCHAR( 250 ) NOT NULL,
  `property_published` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`property_id`)
)  DEFAULT CHARSET=utf8  COMMENT='redSHOP Products Attribute Property';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_product_category_xref`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_category_xref` (
  `category_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `ordering` int(11) NOT NULL
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Product Category Relation';


--
-- Table structure for table `#__redshop_product_price`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_price` (
  `price_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `product_price` decimal(12,4) NOT NULL,
  `product_currency` varchar(10) NOT NULL,
  `cdate` date NOT NULL,
  `shopper_group_id` int(11) NOT NULL,
  `price_quantity_start` INT NOT NULL,
  `price_quantity_end` INT NOT NULL,
  `discount_price` DECIMAL( 12, 4 ) NOT NULL ,
  `discount_start_date` INT( 11 ) NOT NULL ,
  `discount_end_date` INT( 11 ) NOT NULL,
  PRIMARY KEY  (`price_id`)
) DEFAULT CHARSET=latin1 COMMENT='redSHOP Product Price' ;


--
-- Table structure for table `#__redshop_product_compare`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_compare` (
  `compare_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY  (`compare_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Product Comparision';


--
-- Table structure for table `#__redshop_product_discount_calc`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_discount_calc` (
  `id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `area_start` float(10,2) NOT NULL,
  `area_end` float(10,2) NOT NULL,
  `area_price` double NOT NULL,
  `discount_calc_unit` varchar(255) NOT NULL,
  `area_start_converted` float(10,2) NOT NULL,
  `area_end_converted` float(10,2) NOT NULL,
  PRIMARY KEY  (`id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Product Discount Calculator';

--
-- Table structure for table `#__redshop_product_discount_calc_extra`
--

CREATE TABLE IF NOT EXISTS  `#__redshop_product_discount_calc_extra` (
`pdcextra_id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`option_name` VARCHAR( 255 ) NOT NULL ,
`oprand` CHAR( 1 ) NOT NULL ,
`price` FLOAT( 10, 2 ) NOT NULL ,
`product_id` INT NOT NULL
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Product Discount Calculator Extra Value';

--
-- Table structure for table `#__redshop_product_download`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_download` (
  `product_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `order_id` int(11) NOT NULL default '0',
  `end_date` int(11) NOT NULL default '0',
  `download_max` int(11) NOT NULL default '0',
  `download_id` varchar(32) NOT NULL default '',
  `file_name` varchar(255) NOT NULL default '',
  `product_serial_number` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`download_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Downloadable Products' ;


CREATE TABLE IF NOT EXISTS `#__redshop_product_download_log` (
`user_id` INT NOT NULL ,
`download_id` VARCHAR( 32 ) NOT NULL ,
`download_time` INT NOT NULL ,
`ip` VARCHAR( 255 ) NOT NULL
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Downloadable Products Logs' ;

--
-- Table structure for table `#__redshop_product_rating`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_rating` (
  `rating_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `comment` text NOT NULL,
  `userid` int(11) NOT NULL default '0',
  `time` int(11) NOT NULL default '0',
  `user_rating` tinyint(1) NOT NULL default '0',
  `favoured` tinyint(4) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`rating_id`),
  UNIQUE KEY `product_id` (`product_id`,`userid`)
) DEFAULT CHARSET=utf8  ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_product_related`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_related` (
  `related_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Related Products';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_product_stockroom_xref`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_stockroom_xref` (
  `product_id` int(11) NOT NULL,
  `stockroom_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Products Stockroom Relation';

--
-- Table structure for table `#__redshop_product_subattribute_color`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_subattribute_color` (
  `subattribute_color_id` int(11) NOT NULL auto_increment,
  `subattribute_color_name` varchar(255) NOT NULL,
  `subattribute_color_price` DECIMAL( 12, 2 ) default NULL,
  `oprand` char(1) NOT NULL,
  `subattribute_color_image` varchar(255) NOT NULL,
  `subattribute_id` int(11) NOT NULL,
  `ordering` INT NOT NULL,
  `setdefault_selected` tinyint(4) NOT NULL,
  `extra_field` varchar(250) NOT NULL,
  `subattribute_published` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY  (`subattribute_color_id`)
) DEFAULT CHARSET=utf8 COMMENT='Product Subattribute Color' ;



--
-- Table structure for table `#__redshop_product_tags`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_tags` (
  `tags_id` int(11) NOT NULL auto_increment,
  `tags_name` varchar(255) NOT NULL,
  `tags_counter` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`tags_id`)
) DEFAULT CHARSET=utf8 COMMENT='Product Tags' ;



--
-- Table structure for table `#__redshop_product_tags_xref`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_tags_xref` (
  `tags_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL
) DEFAULT CHARSET=utf8 COMMENT='Product Tags Relation With product and user' ;

--
-- Table structure for table `#__redshop_product_voucher`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher` (
  `voucher_id` int(11) NOT NULL auto_increment,
  `voucher_code` VARCHAR( 255 ) NOT NULL,
  `amount` decimal(12,2) NOT NULL default '0.00',
  `voucher_type` varchar(250) character set latin1 NOT NULL,
  `start_date` double NOT NULL,
  `end_date` double NOT NULL,
  `free_shipping` tinyint(4) NOT NULL,
  `voucher_left` INT NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`voucher_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Product Voucher';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_product_voucher_xref`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher_xref` (
  `voucher_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Products Voucher Relation';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_sample_request`
--

CREATE TABLE IF NOT EXISTS `#__redshop_sample_request` (
  `request_id` int(11) NOT NULL auto_increment,
  `name` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `colour_id` varchar(250) NOT NULL,
  `block` tinyint(4) NOT NULL,
  `reminder_1` tinyint(1) NOT NULL,
  `reminder_2` tinyint(1) NOT NULL,
  `reminder_3` tinyint(1) NOT NULL,
  `reminder_coupon` tinyint(1) NOT NULL,
  `registerdate` int(11) NOT NULL,
  PRIMARY KEY  (`request_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Sample Request';

-- --------------------------------------------------------
--
-- Table structure for table `#__redshop_shipping_boxes`
--

CREATE TABLE IF NOT EXISTS `#__redshop_shipping_boxes` (
  `shipping_box_id` int(11) NOT NULL auto_increment,
  `shipping_box_name` varchar(255) NOT NULL,
  `shipping_box_length` decimal(10,2) NOT NULL,
  `shipping_box_width` decimal(10,2) NOT NULL,
  `shipping_box_height` decimal(10,2) NOT NULL,
  `shipping_box_priority` int(11) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`shipping_box_id`)
) DEFAULT CHARSET=utf8  COMMENT='redSHOP Shipping Boxes';


-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_shipping_rate`
--

CREATE TABLE IF NOT EXISTS `#__redshop_shipping_rate` (
  `shipping_rate_id` int(11) NOT NULL auto_increment,
  `shipping_rate_name` varchar(255) NOT NULL default '',
  `shipping_class` varchar(255) NOT NULL default '',
  `shipping_rate_country` LONGTEXT NOT NULL,
  `shipping_rate_zip_start` varchar(20) NOT NULL,
  `shipping_rate_zip_end` varchar(20) NOT NULL,
  `shipping_rate_weight_start` decimal(10,2) NOT NULL,
  `company_only` TINYINT NOT NULL,
   `apply_vat` TINYINT NOT NULL,
  `shipping_rate_weight_end` decimal(10,2) NOT NULL,
  `shipping_rate_volume_start` decimal(10,2) NOT NULL,
  `shipping_rate_volume_end` decimal(10,2) NOT NULL,
  `shipping_rate_ordertotal_start` decimal(10,3) NOT NULL default '0.000',
  `shipping_rate_ordertotal_end` decimal(10,3) NOT NULL,
  `shipping_rate_priority` tinyint(4) NOT NULL default '0',
  `shipping_rate_value` decimal(10,2) NOT NULL default '0.00',
  `shipping_rate_package_fee` decimal(10,2) NOT NULL default '0.00',
  `shipping_location_info` LONGTEXT NOT NULL,
  `shipping_rate_length_start` decimal(10,2) NOT NULL,
  `shipping_rate_length_end` decimal(10,2) NOT NULL,
  `shipping_rate_width_start` decimal(10,2) NOT NULL,
  `shipping_rate_width_end` decimal(10,2) NOT NULL,
  `shipping_rate_height_start` decimal(10,2) NOT NULL,
  `shipping_rate_height_end` decimal(10,2) NOT NULL,
  `shipping_rate_on_shopper_group` LONGTEXT NOT NULL,
  `consignor_carrier_code` varchar(255) NOT NULL,
  PRIMARY KEY  (`shipping_rate_id`)
) DEFAULT CHARSET=utf8  COMMENT='redSHOP Shipping Rates';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_shopper_group`
--

CREATE TABLE IF NOT EXISTS `#__redshop_shopper_group` (
  `shopper_group_id` int(11) NOT NULL auto_increment,
  `shopper_group_name` varchar(32) default NULL,
  `shopper_group_customer_type` TINYINT NOT NULL,
  `shopper_group_portal` TINYINT NOT NULL,
  `shopper_group_categories` LONGTEXT NOT NULL,
  `shopper_group_url` VARCHAR( 255 ) NOT NULL,
  `shopper_group_logo` VARCHAR( 255 ) NOT NULL,
  `shopper_group_introtext` LONGTEXT NOT NULL,
  `shopper_group_desc` text,
  `parent_id` int(11) NOT NULL,
  `default_shipping` TINYINT NOT NULL,
  `default_shipping_rate` FLOAT( 10, 2 ) NOT NULL,
  `published` tinyint(4) NOT NULL,
  `shopper_group_cart_checkout_itemid`  INT NOT NULL,
  `shopper_group_cart_itemid` INT NOT NULL,
  `shopper_group_quotation_mode` TINYINT(4) NOT NULL,
  PRIMARY KEY  (`shopper_group_id`),
  KEY `idx_shopper_group_name` (`shopper_group_name`)
) DEFAULT CHARSET=utf8 COMMENT='Shopper Groups that users can be assigned to' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_state`
--

CREATE TABLE IF NOT EXISTS `#__redshop_state` (
  `state_id` int(11) NOT NULL auto_increment,
  `country_id` int(11) NOT NULL default '1',
  `state_name` varchar(64) default NULL,
  `state_3_code` char(3) default NULL,
  `state_2_code` char(2) default NULL,
  PRIMARY KEY  (`state_id`),
  UNIQUE KEY `state_3_code` (`country_id`,`state_3_code`),
  UNIQUE KEY `state_2_code` (`country_id`,`state_2_code`),
  KEY `idx_country_id` (`country_id`)
) DEFAULT CHARSET=utf8 COMMENT='States that are assigned to a country' ;



--
-- Dumping data for table `#__redshop_state`
--

INSERT IGNORE INTO `#__redshop_state` (`state_id`, `country_id`, `state_name`, `state_3_code`, `state_2_code`) VALUES
(1, 223, 'Alabama', 'ALA', 'AL'),
(2, 223, 'Alaska', 'ALK', 'AK'),
(3, 223, 'Arizona', 'ARZ', 'AZ'),
(4, 223, 'Arkansas', 'ARK', 'AR'),
(5, 223, 'California', 'CAL', 'CA'),
(6, 223, 'Colorado', 'COL', 'CO'),
(7, 223, 'Connecticut', 'CCT', 'CT'),
(8, 223, 'Delaware', 'DEL', 'DE'),
(9, 223, 'District Of Columbia', 'DOC', 'DC'),
(10, 223, 'Florida', 'FLO', 'FL'),
(11, 223, 'Georgia', 'GEA', 'GA'),
(12, 223, 'Hawaii', 'HWI', 'HI'),
(13, 223, 'Idaho', 'IDA', 'ID'),
(14, 223, 'Illinois', 'ILL', 'IL'),
(15, 223, 'Indiana', 'IND', 'IN'),
(16, 223, 'Iowa', 'IOA', 'IA'),
(17, 223, 'Kansas', 'KAS', 'KS'),
(18, 223, 'Kentucky', 'KTY', 'KY'),
(19, 223, 'Louisiana', 'LOA', 'LA'),
(20, 223, 'Maine', 'MAI', 'ME'),
(21, 223, 'Maryland', 'MLD', 'MD'),
(22, 223, 'Massachusetts', 'MSA', 'MA'),
(23, 223, 'Michigan', 'MIC', 'MI'),
(24, 223, 'Minnesota', 'MIN', 'MN'),
(25, 223, 'Mississippi', 'MIS', 'MS'),
(26, 223, 'Missouri', 'MIO', 'MO'),
(27, 223, 'Montana', 'MOT', 'MT'),
(28, 223, 'Nebraska', 'NEB', 'NE'),
(29, 223, 'Nevada', 'NEV', 'NV'),
(30, 223, 'New Hampshire', 'NEH', 'NH'),
(31, 223, 'New Jersey', 'NEJ', 'NJ'),
(32, 223, 'New Mexico', 'NEM', 'NM'),
(33, 223, 'New York', 'NEY', 'NY'),
(34, 223, 'North Carolina', 'NOC', 'NC'),
(35, 223, 'North Dakota', 'NOD', 'ND'),
(36, 223, 'Ohio', 'OHI', 'OH'),
(37, 223, 'Oklahoma', 'OKL', 'OK'),
(38, 223, 'Oregon', 'ORN', 'OR'),
(39, 223, 'Pennsylvania', 'PEA', 'PA'),
(40, 223, 'Rhode Island', 'RHI', 'RI'),
(41, 223, 'South Carolina', 'SOC', 'SC'),
(42, 223, 'South Dakota', 'SOD', 'SD'),
(43, 223, 'Tennessee', 'TEN', 'TN'),
(44, 223, 'Texas', 'TXS', 'TX'),
(45, 223, 'Utah', 'UTA', 'UT'),
(46, 223, 'Vermont', 'VMT', 'VT'),
(47, 223, 'Virginia', 'VIA', 'VA'),
(48, 223, 'Washington', 'WAS', 'WA'),
(49, 223, 'West Virginia', 'WEV', 'WV'),
(50, 223, 'Wisconsin', 'WIS', 'WI'),
(51, 223, 'Wyoming', 'WYO', 'WY'),
(52, 38, 'Alberta', 'ALB', 'AB'),
(53, 38, 'British Columbia', 'BRC', 'BC'),
(54, 38, 'Manitoba', 'MAB', 'MB'),
(55, 38, 'New Brunswick', 'NEB', 'NB'),
(56, 38, 'Newfoundland and Labrador', 'NFL', 'NL'),
(57, 38, 'Northwest Territories', 'NWT', 'NT'),
(58, 38, 'Nova Scotia', 'NOS', 'NS'),
(59, 38, 'Nunavut', 'NUT', 'NU'),
(60, 38, 'Ontario', 'ONT', 'ON'),
(61, 38, 'Prince Edward Island', 'PEI', 'PE'),
(62, 38, 'Quebec', 'QEC', 'QC'),
(63, 38, 'Saskatchewan', 'SAK', 'SK'),
(64, 38, 'Yukon', 'YUT', 'YT'),
(65, 222, 'England', 'ENG', 'EN'),
(66, 222, 'Northern Ireland', 'NOI', 'NI'),
(67, 222, 'Scotland', 'SCO', 'SD'),
(68, 222, 'Wales', 'WLS', 'WS'),
(69, 13, 'Australian Capital Territory', 'ACT', 'AT'),
(70, 13, 'New South Wales', 'NSW', 'NW'),
(71, 13, 'Northern Territory', 'NOT', 'NT'),
(72, 13, 'Queensland', 'QLD', 'QL'),
(73, 13, 'South Australia', 'SOA', 'SA'),
(74, 13, 'Tasmania', 'TAS', 'TA'),
(75, 13, 'Victoria', 'VIC', 'VI'),
(76, 13, 'Western Australia', 'WEA', 'WA'),
(77, 138, 'Aguascalientes', 'AGS', 'AG'),
(78, 138, 'Baja California Norte', 'BCN', 'BN'),
(79, 138, 'Baja California Sur', 'BCS', 'BS'),
(80, 138, 'Campeche', 'CAM', 'CA'),
(81, 138, 'Chiapas', 'CHI', 'CS'),
(82, 138, 'Chihuahua', 'CHA', 'CH'),
(83, 138, 'Coahuila', 'COA', 'CO'),
(84, 138, 'Colima', 'COL', 'CM'),
(85, 138, 'Distrito Federal', 'DFM', 'DF'),
(86, 138, 'Durango', 'DGO', 'DO'),
(87, 138, 'Guanajuato', 'GTO', 'GO'),
(88, 138, 'Guerrero', 'GRO', 'GU'),
(89, 138, 'Hidalgo', 'HGO', 'HI'),
(90, 138, 'Jalisco', 'JAL', 'JA'),
(91, 138, 'México (Estado de)', 'EDM', 'EM'),
(92, 138, 'Michoacán', 'MCN', 'MI'),
(93, 138, 'Morelos', 'MOR', 'MO'),
(94, 138, 'Nayarit', 'NAY', 'NY'),
(95, 138, 'Nuevo León', 'NUL', 'NL'),
(96, 138, 'Oaxaca', 'OAX', 'OA'),
(97, 138, 'Puebla', 'PUE', 'PU'),
(98, 138, 'Querétaro', 'QRO', 'QU'),
(99, 138, 'Quintana Roo', 'QUR', 'QR'),
(100, 138, 'San Luis Potosí', 'SLP', 'SP'),
(101, 138, 'Sinaloa', 'SIN', 'SI'),
(102, 138, 'Sonora', 'SON', 'SO'),
(103, 138, 'Tabasco', 'TAB', 'TA'),
(104, 138, 'Tamaulipas', 'TAM', 'TM'),
(105, 138, 'Tlaxcala', 'TLX', 'TX'),
(106, 138, 'Veracruz', 'VER', 'VZ'),
(107, 138, 'Yucatán', 'YUC', 'YU'),
(108, 138, 'Zacatecas', 'ZAC', 'ZA'),
(109, 30, 'Acre', 'ACR', 'AC'),
(110, 30, 'Alagoas', 'ALG', 'AL'),
(111, 30, 'Amapá', 'AMP', 'AP'),
(112, 30, 'Amazonas', 'AMZ', 'AM'),
(113, 30, 'Bahía', 'BAH', 'BA'),
(114, 30, 'Ceará', 'CEA', 'CE'),
(115, 30, 'Distrito Federal', 'DFB', 'DF'),
(116, 30, 'Espirito Santo', 'ESS', 'ES'),
(117, 30, 'Goiás', 'GOI', 'GO'),
(118, 30, 'Maranhão', 'MAR', 'MA'),
(119, 30, 'Mato Grosso', 'MAT', 'MT'),
(120, 30, 'Mato Grosso do Sul', 'MGS', 'MS'),
(121, 30, 'Minas Geraís', 'MIG', 'MG'),
(122, 30, 'Paraná', 'PAR', 'PR'),
(123, 30, 'Paraíba', 'PRB', 'PB'),
(124, 30, 'Pará', 'PAB', 'PA'),
(125, 30, 'Pernambuco', 'PER', 'PE'),
(126, 30, 'Piauí', 'PIA', 'PI'),
(127, 30, 'Rio Grande do Norte', 'RGN', 'RN'),
(128, 30, 'Rio Grande do Sul', 'RGS', 'RS'),
(129, 30, 'Rio de Janeiro', 'RDJ', 'RJ'),
(130, 30, 'Rondônia', 'RON', 'RO'),
(131, 30, 'Roraima', 'ROR', 'RR'),
(132, 30, 'Santa Catarina', 'SAC', 'SC'),
(133, 30, 'Sergipe', 'SER', 'SE'),
(134, 30, 'São Paulo', 'SAP', 'SP'),
(135, 30, 'Tocantins', 'TOC', 'TO'),
(136, 44, 'Anhui', 'ANH', '34'),
(137, 44, 'Beijing', 'BEI', '11'),
(138, 44, 'Chongqing', 'CHO', '50'),
(139, 44, 'Fujian', 'FUJ', '35'),
(140, 44, 'Gansu', 'GAN', '62'),
(141, 44, 'Guangdong', 'GUA', '44'),
(142, 44, 'Guangxi Zhuang', 'GUZ', '45'),
(143, 44, 'Guizhou', 'GUI', '52'),
(144, 44, 'Hainan', 'HAI', '46'),
(145, 44, 'Hebei', 'HEB', '13'),
(146, 44, 'Heilongjiang', 'HEI', '23'),
(147, 44, 'Henan', 'HEN', '41'),
(148, 44, 'Hubei', 'HUB', '42'),
(149, 44, 'Hunan', 'HUN', '43'),

(150, 44, 'Jiangsu', 'JIA', '32'),
(151, 44, 'Jiangxi', 'JIX', '36'),
(152, 44, 'Jilin', 'JIL', '22'),
(153, 44, 'Liaoning', 'LIA', '21'),
(154, 44, 'Nei Mongol', 'NML', '15'),
(155, 44, 'Ningxia Hui', 'NIH', '64'),
(156, 44, 'Qinghai', 'QIN', '63'),
(157, 44, 'Shandong', 'SNG', '37'),
(158, 44, 'Shanghai', 'SHH', '31'),
(159, 44, 'Shaanxi', 'SHX', '61'),
(160, 44, 'Sichuan', 'SIC', '51'),
(161, 44, 'Tianjin', 'TIA', '12'),
(162, 44, 'Xinjiang Uygur', 'XIU', '65'),
(163, 44, 'Xizang', 'XIZ', '54'),
(164, 44, 'Yunnan', 'YUN', '53'),
(165, 44, 'Zhejiang', 'ZHE', '33'),
(166, 104, 'Gaza Strip', 'GZS', 'GZ'),
(167, 104, 'West Bank', 'WBK', 'WB'),
(168, 104, 'Other', 'OTH', 'OT'),
(169, 151, 'St. Maarten', 'STM', 'SM'),
(170, 151, 'Bonaire', 'BNR', 'BN'),
(171, 151, 'Curacao', 'CUR', 'CR'),
(172, 175, 'Alba', 'ABA', 'AB'),
(173, 175, 'Arad', 'ARD', 'AR'),
(174, 175, 'Arges', 'ARG', 'AG'),
(175, 175, 'Bacau', 'BAC', 'BC'),
(176, 175, 'Bihor', 'BIH', 'BH'),
(177, 175, 'Bistrita-Nasaud', 'BIS', 'BN'),
(178, 175, 'Botosani', 'BOT', 'BT'),
(179, 175, 'Braila', 'BRL', 'BR'),
(180, 175, 'Brasov', 'BRA', 'BV'),
(181, 175, 'Bucuresti', 'BUC', 'B'),
(182, 175, 'Buzau', 'BUZ', 'BZ'),
(183, 175, 'Calarasi', 'CAL', 'CL'),
(184, 175, 'Caras Severin', 'CRS', 'CS'),
(185, 175, 'Cluj', 'CLJ', 'CJ'),
(186, 175, 'Constanta', 'CST', 'CT'),
(187, 175, 'Covasna', 'COV', 'CV'),
(188, 175, 'Dambovita', 'DAM', 'DB'),
(189, 175, 'Dolj', 'DLJ', 'DJ'),
(190, 175, 'Galati', 'GAL', 'GL'),
(191, 175, 'Giurgiu', 'GIU', 'GR'),
(192, 175, 'Gorj', 'GOR', 'GJ'),
(193, 175, 'Hargita', 'HRG', 'HR'),
(194, 175, 'Hunedoara', 'HUN', 'HD'),
(195, 175, 'Ialomita', 'IAL', 'IL'),
(196, 175, 'Iasi', 'IAS', 'IS'),
(197, 175, 'Ilfov', 'ILF', 'IF'),
(198, 175, 'Maramures', 'MAR', 'MM'),
(199, 175, 'Mehedinti', 'MEH', 'MH'),
(200, 175, 'Mures', 'MUR', 'MS'),
(201, 175, 'Neamt', 'NEM', 'NT'),
(202, 175, 'Olt', 'OLT', 'OT'),
(203, 175, 'Prahova', 'PRA', 'PH'),
(204, 175, 'Salaj', 'SAL', 'SJ'),
(205, 175, 'Satu Mare', 'SAT', 'SM'),
(206, 175, 'Sibiu', 'SIB', 'SB'),
(207, 175, 'Suceava', 'SUC', 'SV'),
(208, 175, 'Teleorman', 'TEL', 'TR'),
(209, 175, 'Timis', 'TIM', 'TM'),
(210, 175, 'Tulcea', 'TUL', 'TL'),
(211, 175, 'Valcea', 'VAL', 'VL'),
(212, 175, 'Vaslui', 'VAS', 'VS'),
(213, 175, 'Vrancea', 'VRA', 'VN'),
(214, 105, 'Agrigento', 'AGR', 'AG'),
(215, 105, 'Alessandria', 'ALE', 'AL'),
(216, 105, 'Ancona', 'ANC', 'AN'),
(217, 105, 'Aosta', 'AOS', 'AO'),
(218, 105, 'Arezzo', 'ARE', 'AR'),
(219, 105, 'Ascoli Piceno', 'API', 'AP'),
(220, 105, 'Asti', 'AST', 'AT'),
(221, 105, 'Avellino', 'AVE', 'AV'),
(222, 105, 'Bari', 'BAR', 'BA'),
(223, 105, 'Belluno', 'BEL', 'BL'),
(224, 105, 'Benevento', 'BEN', 'BN'),
(225, 105, 'Bergamo', 'BEG', 'BG'),
(226, 105, 'Biella', 'BIE', 'BI'),
(227, 105, 'Bologna', 'BOL', 'BO'),
(228, 105, 'Bolzano', 'BOZ', 'BZ'),
(229, 105, 'Brescia', 'BRE', 'BS'),
(230, 105, 'Brindisi', 'BRI', 'BR'),
(231, 105, 'Cagliari', 'CAG', 'CA'),
(232, 105, 'Caltanissetta', 'CAL', 'CL'),
(233, 105, 'Campobasso', 'CBO', 'CB'),
(234, 105, 'Carbonia-Iglesias', 'CAR', 'CI'),
(235, 105, 'Caserta', 'CAS', 'CE'),
(236, 105, 'Catania', 'CAT', 'CT'),
(237, 105, 'Catanzaro', 'CTZ', 'CZ'),
(238, 105, 'Chieti', 'CHI', 'CH'),
(239, 105, 'Como', 'COM', 'CO'),
(240, 105, 'Cosenza', 'COS', 'CS'),
(241, 105, 'Cremona', 'CRE', 'CR'),
(242, 105, 'Crotone', 'CRO', 'KR'),
(243, 105, 'Cuneo', 'CUN', 'CN'),
(244, 105, 'Enna', 'ENN', 'EN'),
(245, 105, 'Ferrara', 'FER', 'FE'),
(246, 105, 'Firenze', 'FIR', 'FI'),
(247, 105, 'Foggia', 'FOG', 'FG'),
(248, 105, 'Forli-Cesena', 'FOC', 'FC'),
(249, 105, 'Frosinone', 'FRO', 'FR'),
(250, 105, 'Genova', 'GEN', 'GE'),
(251, 105, 'Gorizia', 'GOR', 'GO'),
(252, 105, 'Grosseto', 'GRO', 'GR'),
(253, 105, 'Imperia', 'IMP', 'IM'),
(254, 105, 'Isernia', 'ISE', 'IS'),
(255, 105, 'L''Aquila', 'AQU', 'AQ'),
(256, 105, 'La Spezia', 'LAS', 'SP'),
(257, 105, 'Latina', 'LAT', 'LT'),
(258, 105, 'Lecce', 'LEC', 'LE'),
(259, 105, 'Lecco', 'LCC', 'LC'),
(260, 105, 'Livorno', 'LIV', 'LI'),
(261, 105, 'Lodi', 'LOD', 'LO'),
(262, 105, 'Lucca', 'LUC', 'LU'),
(263, 105, 'Macerata', 'MAC', 'MC'),
(264, 105, 'Mantova', 'MAN', 'MN'),
(265, 105, 'Massa-Carrara', 'MAS', 'MS'),
(266, 105, 'Matera', 'MAA', 'MT'),
(267, 105, 'Medio Campidano', 'MED', 'VS'),
(268, 105, 'Messina', 'MES', 'ME'),
(269, 105, 'Milano', 'MIL', 'MI'),
(270, 105, 'Modena', 'MOD', 'MO'),
(271, 105, 'Napoli', 'NAP', 'NA'),
(272, 105, 'Novara', 'NOV', 'NO'),
(273, 105, 'Nuoro', 'NUR', 'NU'),
(274, 105, 'Ogliastra', 'OGL', 'OG'),
(275, 105, 'Olbia-Tempio', 'OLB', 'OT'),
(276, 105, 'Oristano', 'ORI', 'OR'),
(277, 105, 'Padova', 'PDA', 'PD'),
(278, 105, 'Palermo', 'PAL', 'PA'),
(279, 105, 'Parma', 'PAA', 'PR'),
(280, 105, 'Pavia', 'PAV', 'PV'),
(281, 105, 'Perugia', 'PER', 'PG'),
(282, 105, 'Pesaro e Urbino', 'PES', 'PU'),
(283, 105, 'Pescara', 'PSC', 'PE'),
(284, 105, 'Piacenza', 'PIA', 'PC'),
(285, 105, 'Pisa', 'PIS', 'PI'),
(286, 105, 'Pistoia', 'PIT', 'PT'),
(287, 105, 'Pordenone', 'POR', 'PN'),
(288, 105, 'Potenza', 'PTZ', 'PZ'),
(289, 105, 'Prato', 'PRA', 'PO'),
(290, 105, 'Ragusa', 'RAG', 'RG'),
(291, 105, 'Ravenna', 'RAV', 'RA'),
(292, 105, 'Reggio Calabria', 'REG', 'RC'),
(293, 105, 'Reggio Emilia', 'REE', 'RE'),
(294, 105, 'Rieti', 'RIE', 'RI'),
(295, 105, 'Rimini', 'RIM', 'RN'),
(296, 105, 'Roma', 'ROM', 'RM'),
(297, 105, 'Rovigo', 'ROV', 'RO'),
(298, 105, 'Salerno', 'SAL', 'SA'),
(299, 105, 'Sassari', 'SAS', 'SS'),
(300, 105, 'Savona', 'SAV', 'SV'),
(301, 105, 'Siena', 'SIE', 'SI'),
(302, 105, 'Siracusa', 'SIR', 'SR'),
(303, 105, 'Sondrio', 'SOO', 'SO'),
(304, 105, 'Taranto', 'TAR', 'TA'),
(305, 105, 'Teramo', 'TER', 'TE'),
(306, 105, 'Terni', 'TRN', 'TR'),
(307, 105, 'Torino', 'TOR', 'TO'),
(308, 105, 'Trapani', 'TRA', 'TP'),
(309, 105, 'Trento', 'TRE', 'TN'),
(310, 105, 'Treviso', 'TRV', 'TV'),
(311, 105, 'Trieste', 'TRI', 'TS'),
(312, 105, 'Udine', 'UDI', 'UD'),
(313, 105, 'Varese', 'VAR', 'VA'),
(314, 105, 'Venezia', 'VEN', 'VE'),
(315, 105, 'Verbano Cusio Ossola', 'VCO', 'VB'),
(316, 105, 'Vercelli', 'VER', 'VC'),
(317, 105, 'Verona', 'VRN', 'VR'),
(318, 105, 'Vibo Valenzia', 'VIV', 'VV'),
(319, 105, 'Vicenza', 'VII', 'VI'),
(320, 105, 'Viterbo', 'VIT', 'VT'),
(321, 195, 'A Coruña', 'ACO', '15'),
(322, 195, 'Alava', 'ALA', '01'),
(323, 195, 'Albacete', 'ALB', '02'),
(324, 195, 'Alicante', 'ALI', '03'),
(325, 195, 'Almeria', 'ALM', '04'),
(326, 195, 'Asturias', 'AST', '33'),
(327, 195, 'Avila', 'AVI', '05'),
(328, 195, 'Badajoz', 'BAD', '06'),
(329, 195, 'Baleares', 'BAL', '07'),
(330, 195, 'Barcelona', 'BAR', '08'),
(331, 195, 'Burgos', 'BUR', '09'),
(332, 195, 'Caceres', 'CAC', '10'),
(333, 195, 'Cadiz', 'CAD', '11'),
(334, 195, 'Cantabria', 'CAN', '39'),
(335, 195, 'Castellon', 'CAS', '12'),
(336, 195, 'Ceuta', 'CEU', '51'),
(337, 195, 'Ciudad Real', 'CIU', '13'),
(338, 195, 'Cordoba', 'COR', '14'),
(339, 195, 'Cuenca', 'CUE', '16'),
(340, 195, 'Girona', 'GIR', '17'),
(341, 195, 'Granada', 'GRA', '18'),
(342, 195, 'Guadalajara', 'GUA', '19'),
(343, 195, 'Guipuzcoa', 'GUI', '20'),
(344, 195, 'Huelva', 'HUL', '21'),
(345, 195, 'Huesca', 'HUS', '22'),
(346, 195, 'Jaen', 'JAE', '23'),
(347, 195, 'La Rioja', 'LRI', '26'),
(348, 195, 'Las Palmas', 'LPA', '35'),
(349, 195, 'Leon', 'LEO', '24'),
(350, 195, 'Lleida', 'LLE', '25'),
(351, 195, 'Lugo', 'LUG', '27'),
(352, 195, 'Madrid', 'MAD', '28'),
(353, 195, 'Malaga', 'MAL', '29'),
(354, 195, 'Melilla', 'MEL', '52'),
(355, 195, 'Murcia', 'MUR', '30'),
(356, 195, 'Navarra', 'NAV', '31'),
(357, 195, 'Ourense', 'OUR', '32'),
(358, 195, 'Palencia', 'PAL', '34'),
(359, 195, 'Pontevedra', 'PON', '36'),
(360, 195, 'Salamanca', 'SAL', '37'),
(361, 195, 'Santa Cruz de Tenerife', 'SCT', '38'),
(362, 195, 'Segovia', 'SEG', '40'),
(363, 195, 'Sevilla', 'SEV', '41'),
(364, 195, 'Soria', 'SOR', '42'),
(365, 195, 'Tarragona', 'TAR', '43'),
(366, 195, 'Teruel', 'TER', '44'),
(367, 195, 'Toledo', 'TOL', '45'),
(368, 195, 'Valencia', 'VAL', '46'),
(369, 195, 'Valladolid', 'VLL', '47'),
(370, 195, 'Vizcaya', 'VIZ', '48'),
(371, 195, 'Zamora', 'ZAM', '49'),
(372, 195, 'Zaragoza', 'ZAR', '50'),
(373, 11, 'Aragatsotn', 'ARG', 'AG'),
(374, 11, 'Ararat', 'ARR', 'AR'),
(375, 11, 'Armavir', 'ARM', 'AV'),
(376, 11, 'Gegharkunik', 'GEG', 'GR'),
(377, 11, 'Kotayk', 'KOT', 'KT'),
(378, 11, 'Lori', 'LOR', 'LO'),
(379, 11, 'Shirak', 'SHI', 'SH'),
(380, 11, 'Syunik', 'SYU', 'SU'),
(381, 11, 'Tavush', 'TAV', 'TV'),
(382, 11, 'Vayots-Dzor', 'VAD', 'VD'),
(383, 11, 'Yerevan', 'YER', 'ER'),
(384, 99, 'Andaman & Nicobar Islands', 'ANI', 'AI'),
(385, 99, 'Andhra Pradesh', 'AND', 'AN'),
(386, 99, 'Arunachal Pradesh', 'ARU', 'AR'),
(387, 99, 'Assam', 'ASS', 'AS'),
(388, 99, 'Bihar', 'BIH', 'BI'),
(389, 99, 'Chandigarh', 'CHA', 'CA'),
(390, 99, 'Chhatisgarh', 'CHH', 'CH'),
(391, 99, 'Dadra & Nagar Haveli', 'DAD', 'DD'),
(392, 99, 'Daman & Diu', 'DAM', 'DA'),
(393, 99, 'Delhi', 'DEL', 'DE'),
(394, 99, 'Goa', 'GOA', 'GO'),
(395, 99, 'Gujarat', 'GUJ', 'GU'),
(396, 99, 'Haryana', 'HAR', 'HA'),
(397, 99, 'Himachal Pradesh', 'HIM', 'HI'),
(398, 99, 'Jammu & Kashmir', 'JAM', 'JA'),
(399, 99, 'Jharkhand', 'JHA', 'JH'),
(400, 99, 'Karnataka', 'KAR', 'KA'),
(401, 99, 'Kerala', 'KER', 'KE'),
(402, 99, 'Lakshadweep', 'LAK', 'LA'),
(403, 99, 'Madhya Pradesh', 'MAD', 'MD'),
(404, 99, 'Maharashtra', 'MAH', 'MH'),
(405, 99, 'Manipur', 'MAN', 'MN'),
(406, 99, 'Meghalaya', 'MEG', 'ME'),
(407, 99, 'Mizoram', 'MIZ', 'MI'),
(408, 99, 'Nagaland', 'NAG', 'NA'),
(409, 99, 'Orissa', 'ORI', 'OR'),
(410, 99, 'Pondicherry', 'PON', 'PO'),
(411, 99, 'Punjab', 'PUN', 'PU'),
(412, 99, 'Rajasthan', 'RAJ', 'RA'),
(413, 99, 'Sikkim', 'SIK', 'SI'),
(414, 99, 'Tamil Nadu', 'TAM', 'TA'),
(415, 99, 'Tripura', 'TRI', 'TR'),
(416, 99, 'Uttaranchal', 'UAR', 'UA'),
(417, 99, 'Uttar Pradesh', 'UTT', 'UT'),
(418, 99, 'West Bengal', 'WES', 'WE'),
(419, 101, 'Ahmadi va Kohkiluyeh', 'BOK', 'BO'),
(420, 101, 'Ardabil', 'ARD', 'AR'),
(421, 101, 'Azarbayjan-e Gharbi', 'AZG', 'AG'),
(422, 101, 'Azarbayjan-e Sharqi', 'AZS', 'AS'),
(423, 101, 'Bushehr', 'BUS', 'BU'),
(424, 101, 'Chaharmahal va Bakhtiari', 'CMB', 'CM'),
(425, 101, 'Esfahan', 'ESF', 'ES'),
(426, 101, 'Fars', 'FAR', 'FA'),
(427, 101, 'Gilan', 'GIL', 'GI'),
(428, 101, 'Gorgan', 'GOR', 'GO'),
(429, 101, 'Hamadan', 'HAM', 'HA'),
(430, 101, 'Hormozgan', 'HOR', 'HO'),
(431, 101, 'Ilam', 'ILA', 'IL'),
(432, 101, 'Kerman', 'KER', 'KE'),
(433, 101, 'Kermanshah', 'BAK', 'BA'),
(434, 101, 'Khorasan-e Junoubi', 'KHJ', 'KJ'),
(435, 101, 'Khorasan-e Razavi', 'KHR', 'KR'),
(436, 101, 'Khorasan-e Shomali', 'KHS', 'KS'),
(437, 101, 'Khuzestan', 'KHU', 'KH'),
(438, 101, 'Kordestan', 'KOR', 'KO'),
(439, 101, 'Lorestan', 'LOR', 'LO'),
(440, 101, 'Markazi', 'MAR', 'MR'),
(441, 101, 'Mazandaran', 'MAZ', 'MZ'),
(442, 101, 'Qazvin', 'QAS', 'QA'),
(443, 101, 'Qom', 'QOM', 'QO'),
(444, 101, 'Semnan', 'SEM', 'SE'),
(445, 101, 'Sistan va Baluchestan', 'SBA', 'SB'),
(446, 101, 'Tehran', 'TEH', 'TE'),
(447, 101, 'Yazd', 'YAZ', 'YA'),
(448, 101, 'Zanjan', 'ZAN', 'ZA'),
(449, 170, 'Dolnośląskie', 'DOL', 'DO'),
(450, 170, 'Kujawsko-Pomorskie', 'KUJ', 'KU'),
(451, 170, 'Lubelskie', 'LUB', 'LU'),
(452, 170, 'Lubuskie', 'LBU', 'LB'),
(453, 170, 'Łódzkie', 'LOD', 'LO'),
(454, 170, 'Małopolskie', 'MAL', 'MP'),
(455, 170, 'Mazowieckie', 'MAZ', 'MZ'),
(456, 170, 'Opolskie', 'OPO', 'OP'),
(457, 170, 'Podkarpackie', 'PDK', 'PK'),
(458, 170, 'Podlaskie', 'PDL', 'PL'),
(459, 170, 'Pomorskie', 'POM', 'PO'),
(460, 170, 'Śląskie', 'SLA', 'SL'),
(461, 170, 'Świętokrzyskie', 'SWI', 'SW'),
(462, 170, 'Warmińsko-Mazurskie', 'WAR', 'WA'),
(463, 170, 'Wielkopolskie', 'WIE', 'WI'),
(464, 170, 'Zachodniopomorskie', 'ZAC', 'ZA');



-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_stockroom`
--

CREATE TABLE IF NOT EXISTS `#__redshop_stockroom` (
  `stockroom_id` int(11) NOT NULL auto_increment,
  `stockroom_name` varchar(250) character set latin1 NOT NULL,
  `min_stock_amount` int(11) NOT NULL,
  `stockroom_desc` longtext character set latin1 NOT NULL,
  `creation_date` double NOT NULL,
  `min_del_time` int(11) NOT NULL,
  `max_del_time` int(11) NOT NULL,
  `show` tinyint(1) NOT NULL,
  `delivery_time` VARCHAR( 255 ) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`stockroom_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Stockroom';




INSERT IGNORE INTO `#__redshop_stockroom` (`stockroom_id`, `stockroom_name`, `stockroom_desc`, `published`) VALUES
(1, 'default', 'This is redshop default stockroom', '1');


-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_stockroom_container_xref`
--

CREATE TABLE IF NOT EXISTS `#__redshop_stockroom_container_xref` (
  `stockroom_id` int(11) NOT NULL,
  `container_id` int(11) NOT NULL
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Stockroom Container Relation';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_supplier`
--

CREATE TABLE IF NOT EXISTS `#__redshop_supplier` (
  `supplier_id` int(11) NOT NULL auto_increment,
  `supplier_name` varchar(250) NOT NULL,
  `supplier_desc` longtext NOT NULL,
  `supplier_email` VARCHAR( 255 ) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`supplier_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Supplier';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_tax_rate`
--

CREATE TABLE IF NOT EXISTS `#__redshop_tax_rate` (
  `tax_rate_id` int(11) NOT NULL auto_increment,
  `tax_state` varchar(64) default NULL,
  `tax_country` varchar(64) default NULL,
  `mdate` int(11) default NULL,
  `tax_rate` decimal(10,4) default NULL,
  `tax_group_id` int(11) NOT NULL,
  `is_eu_country` TINYINT( 4 ) NOT NULL,
  PRIMARY KEY  (`tax_rate_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Tax Rates';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_template`
--


CREATE TABLE IF NOT EXISTS `#__redshop_template` (
  `template_id` int(11) NOT NULL auto_increment,
  `template_name` varchar(250) NOT NULL,
  `template_section` varchar(250) NOT NULL,
  `template_desc` longtext NOT NULL,
  `order_status` varchar(250) NOT NULL,
  `payment_methods` varchar(250) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`template_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Templates Detail';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_textlibrary`
--

CREATE TABLE IF NOT EXISTS `#__redshop_textlibrary` (
  `textlibrary_id` int(11) NOT NULL auto_increment,
  `text_name` varchar(255) default NULL,
  `text_desc` varchar(255) default NULL,
  `text_field` text,
  `section` varchar(255) NOT NULL,
  `published` tinyint(4) NOT NULL,
  PRIMARY KEY  (`textlibrary_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP TextLibrary';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_users_info`
--

CREATE TABLE IF NOT EXISTS `#__redshop_users_info` (
  `users_info_id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `user_email` VARCHAR( 255 ) NOT NULL,
  `address_type` varchar(11) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `vat_number` varchar(250) NOT NULL,
  `tax_exempt` tinyint(4) NOT NULL,
  `shopper_group_id` int(11) NOT NULL,
  `country_code` varchar(11) NOT NULL,
  `address` VARCHAR( 255 ) NOT NULL,
  `city` VARCHAR( 50 ) NOT NULL,
  `state_code` varchar(11) NOT NULL,
  `zipcode` int(11) NOT NULL,
  `phone` VARCHAR( 50 ) NOT NULL,
  `tax_exempt_approved` tinyint(1) NOT NULL,
  `approved` tinyint(1) NOT NULL,
  `is_company` tinyint(4) NOT NULL,
  `ean_number` VARCHAR( 250 ) NOT NULL,
  `braintree_vault_number` VARCHAR( 255 ) NOT NULL,
  `veis_vat_number` VARCHAR( 255 ) NOT NULL,
  `veis_status` VARCHAR( 255 ) NOT NULL,
  PRIMARY KEY  (`users_info_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Users Information' ;

-- --------------------------------------------------------
--
-- Table structure for table `#__redshop_wishlist_userfielddata`
--
CREATE TABLE IF NOT EXISTS `#__redshop_wishlist_userfielddata` (
`fieldid` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`wishlist_id` INT( 11 ) NOT NULL ,
`product_id` INT( 11 ) NOT NULL ,
`userfielddata` TEXT NOT NULL ,
PRIMARY KEY ( `fieldid` )
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Wishlist Product userfielddata' ;


--
-- Table structure for table `#__redshop_wishlist`
--

CREATE TABLE IF NOT EXISTS `#__redshop_wishlist` (
	`wishlist_id` int(11) NOT NULL auto_increment,
	`product_id` INT NOT NULL ,
	`user_id` INT NOT NULL ,
	`comment` MEDIUMTEXT NOT NULL ,
	`cdate` DOUBLE NOT NULL,
	PRIMARY KEY  (`wishlist_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP wishlist';

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_product_attribute_stockroom_xref`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_stockroom_xref`  (
`section_id` int( 11 ) NOT NULL ,
`section` varchar( 255 ) NOT NULL ,
`stockroom_id` int( 11 ) NOT NULL ,
`quantity` int( 11 ) NOT NULL
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Product Attribute Stockroom relation' ;

-- --------------------------------------------------------

--
-- Table structure for table `#__redshop_product_attribute_price`
--

CREATE TABLE IF NOT EXISTS `#__redshop_product_attribute_price` (
  `price_id` int(11) NOT NULL auto_increment,
  `section_id` int(11) NOT NULL,
  `section` varchar(255) NOT NULL,
  `product_price` decimal(12,2) NOT NULL,
  `product_currency` varchar(10) NOT NULL,
  `cdate` int(11) NOT NULL,
  `shopper_group_id` int(11) NOT NULL,
  `price_quantity_start` int(11) NOT NULL,
  `price_quantity_end` int(11) NOT NULL,
  `discount_price` DECIMAL( 12, 4 ) NOT NULL ,
  `discount_start_date` INT( 11 ) NOT NULL ,
  `discount_end_date` INT( 11 ) NOT NULL,
  PRIMARY KEY  (`price_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Product Attribute Price' ;


CREATE TABLE IF NOT EXISTS `#__redshop_wishlist_product` (
`wishlist_product_id` INT NOT NULL AUTO_INCREMENT ,
`wishlist_id` INT NOT NULL ,
`product_id` INT NOT NULL ,
`cdate` INT NOT NULL ,
PRIMARY KEY ( `wishlist_product_id` )
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Wishlist Product' ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_voucher_transaction` (
`transaction_voucher_id` INT NOT NULL AUTO_INCREMENT ,
`voucher_id` INT NOT NULL ,
`voucher_code` VARCHAR( 255 ) NOT NULL ,
`amount` DECIMAL( 10, 3 ) NOT NULL ,
`user_id` INT NOT NULL ,
`order_id` INT NOT NULL ,
`trancation_date` INT NOT NULL ,
`published` TINYINT NOT NULL ,
PRIMARY KEY ( `transaction_voucher_id` )
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Product Voucher Transaction';

CREATE TABLE IF NOT EXISTS `#__redshop_product_subscription` (
  `subscription_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `subscription_period` int(11) NOT NULL,
  `period_type` varchar(10) NOT NULL,
  `subscription_price` double NOT NULL,
  PRIMARY KEY  (`subscription_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Product Subscription' ;

CREATE TABLE IF NOT EXISTS `#__redshop_subscription_renewal` (
  `renewal_id` int(11) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `before_no_days` int(11) NOT NULL,
  PRIMARY KEY  (`renewal_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Subscription Renewal' ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_subscribe_detail` (
  `product_subscribe_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `start_date` int(11) NOT NULL,
  `end_date` int(11) NOT NULL,
  `order_item_id` int(11) NOT NULL,
  PRIMARY KEY  (`product_subscribe_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP User product Subscribe detail' ;

CREATE TABLE IF NOT EXISTS `#__redshop_attribute_set` (
`attribute_set_id` INT NOT NULL auto_increment,
`attribute_set_name` VARCHAR( 255 ) NOT NULL ,
`published` TINYINT NOT NULL,
PRIMARY KEY  (`attribute_set_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP Attribute set detail' ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_serial_number` (
`serial_id` INT NOT NULL AUTO_INCREMENT,
`product_id` INT NOT NULL ,
`serial_number` VARCHAR( 255 ) NOT NULL ,
`is_used` TINYINT( 1 ) NOT NULL DEFAULT '0',
PRIMARY KEY  (`serial_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP downloadable product serial numbers' ;

 CREATE TABLE IF NOT EXISTS `#__redshop_usercart` (
  `cart_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `cdate` int(11) NOT NULL,
  `mdate` int(11) NOT NULL,
  PRIMARY KEY (`cart_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP User Cart Item' ;

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_accessory_item` (
  `cart_acc_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_item_id` int(11) NOT NULL,
  `accessory_id` int(11) NOT NULL,
  `accessory_quantity` int(11) NOT NULL,
  PRIMARY KEY (`cart_acc_item_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP User Cart Accessory Item' ;

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_attribute_item` (
  `cart_att_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_item_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `section` varchar(25) NOT NULL,
  `parent_section_id` int(11) NOT NULL,
  `is_accessory_att` tinyint(4) NOT NULL,
  PRIMARY KEY (`cart_att_item_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP User cart Attribute Item' ;

CREATE TABLE IF NOT EXISTS `#__redshop_usercart_item` (
  `cart_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `cart_idx` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `product_wrapper_id` int(11) NOT NULL,
  `product_subscription_id` int(11) NOT NULL,
  `giftcard_id` int(11) NOT NULL,
  PRIMARY KEY (`cart_item_id`)
) DEFAULT CHARSET=utf8 COMMENT='redSHOP User Cart Item' ;
--
-- Delete expire Country from table '#__redshop_country'

CREATE TABLE IF NOT EXISTS `#__redshop_orderbarcode_log` (
  `log_id` int(11) NOT NULL auto_increment,
  `order_id` int(11) NOT NULL,
  `barcode` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `search_date` datetime NOT NULL,
  PRIMARY KEY  (`log_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;


CREATE TABLE IF NOT EXISTS `#__redshop_zipcode` (
  `zipcode_id` int(11) NOT NULL auto_increment,
  `country_code` varchar(10) NOT NULL default '',
  `state_code` varchar(10) NOT NULL default '',
  `city_name` varchar(64) default NULL,
  `zipcode` varchar(255) default NULL,
  `zipcodeto` varchar(255) default NULL,
  PRIMARY KEY  (`zipcode_id`),
  KEY `zipcode` (`zipcode`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

CREATE TABLE IF NOT EXISTS `#__redshop_product_navigator` (
  `navigator_id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `child_product_id` int(11) NOT NULL,
  `navigator_name` varchar(255) NOT NULL,
  `ordering` int(11) NOT NULL,
  PRIMARY KEY (`navigator_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='redSHOP Products Navigator';

--

DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'XET' AND `country_2_code` = 'XE' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'TMP' AND `country_2_code` = 'TP' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'FXX' AND `country_2_code` = 'FX' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'YUG' AND `country_2_code` = 'YU' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'XSE' AND `country_2_code` = 'XU' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'XCA' AND `country_2_code` = 'XC' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'XSE' AND `country_2_code` = 'XU' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'XCA' AND `country_2_code` = 'XC' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'FXX' AND `country_2_code` = 'FX' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'YUG' AND `country_2_code` = 'YU' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'XSE' AND `country_2_code` = 'XU' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'XCA' AND `country_2_code` = 'XC' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'XSE' AND `country_2_code` = 'XU' LIMIT 1 ;
DELETE FROM `#__redshop_country` WHERE `country_3_code` = 'XCA' AND `country_2_code` = 'XC' LIMIT 1 ;

CREATE TABLE IF NOT EXISTS `#__redshop_ordernumber_track` (
`trackdatetime` DATETIME NOT NULL
) ENGINE = MYISAM DEFAULT CHARSET=utf8 COMMENT='redSHOP Order number track';
