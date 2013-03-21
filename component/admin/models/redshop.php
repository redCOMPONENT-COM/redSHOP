<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class redshopModelredshop extends JModel
{
	public $_table_prefix = null;

	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$this->_filteroption = 3;
	}

	public function demoContentInsert()
	{
		$db = JFactory::getDBO();

		$query = "INSERT IGNORE INTO `#__redshop_category` (`category_id`, `category_name`, `category_short_description`, `category_description`, `category_template`, `category_more_template`, `products_per_page`, `category_thumb_image`, `category_full_image`, `metakey`, `metadesc`, `metalanguage_setting`, `metarobot_info`, `pagetitle`, `pageheading`, `sef_url`, `published`, `category_pdate`, `ordering`, `category_back_full_image`, `compare_template_id`, `append_to_global_seo`)
						VALUES
							(1, 'redCOMPONENT', '', '', 5, '5,8', 4, '', '', '', '', '', '', '', '', '', 1, '2009-06-26 04:06:45', 1, '', '0', 'append'),
							(2, 'redMODULES', '', '', 5, '0', 4, '', '', '', '', '', '', '', '', '', 1, '2009-06-26 04:16:31', 2, '', '0', 'append'),
							(3, 'redPLUGINS', '', '', 5, '0', 4, '', '', '', '', '', '', '', '', '', 1, '2009-06-26 04:17:08', 3, '', '0', 'append')";
		$db->setQuery($query);
		$db->query();


		/* Get the current columns for redshop category_xref */
		$q = "SHOW INDEX FROM #__redshop_category_xref";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Key_name');

		if (is_array($cols))
		{
			/* Check if we have the category_parent_id column */
			if (!array_key_exists('category_parent_id', $cols))
			{
				$q = "ALTER IGNORE TABLE `#__redshop_category_xref` ADD UNIQUE (
								`category_parent_id` ,
								`category_child_id`
								)";
				$db->setQuery($q);
				$db->query();
			}
		}

		$query = "INSERT IGNORE INTO `#__redshop_category_xref`
					(`category_parent_id`, `category_child_id`)
					VALUES (0, 3),(0, 2),(0, 1)";
		$db->setQuery($query);
		$db->query();


		$query = "INSERT IGNORE INTO `#__redshop_fields`
					(`field_id`, `field_title`, `field_name`, `field_type`, `field_desc`, `field_class`, `field_section`, `field_maxlength`, `field_cols`, `field_rows`, `field_size`, `field_show_in_front`,`published`, `required`)
					VALUES
					(1, 'Userfield Test', 'userfield-test', '1', '', '', '12', '20', '0', '0', '20', '1', '1', '0')";
		$db->setQuery($query);
		$db->query();


		$query = "INSERT IGNORE INTO `#__redshop_manufacturer` (`manufacturer_id`, `manufacturer_name`, `manufacturer_desc`, `manufacturer_email`, `product_per_page`, `template_id`, `metakey`, `metadesc`, `metalanguage_setting`, `metarobot_info`, `pagetitle`, `pageheading`, `sef_url`, `published`, `ordering`, `manufacturer_url`) VALUES
						(1, 'redweb.dk', '<p>http://redweb.dk</p>', '', 0, 14, '', '', '', '', '', '', '', 1, 1, ''),
						(2, 'redhost.dk', '<p>http://redhost.dk</p>', '', 0, 14, '', '', '', '', '', '', '', 1, 2, ''),
						(3, 'redcomponent.com', '<p>http://redcomponent.com</p>', '', 0, 14, '', '', '', '', '', '', '', 1, 3, '')";
		$db->setQuery($query);
		$db->query();

		$query = "INSERT IGNORE INTO `#__redshop_media`
			(`media_id`, `media_name`, `media_section`, `section_id`, `media_type`, `media_mimetype`, `published`, `media_alternate_text`)
			VALUES (18, '1262876640_redFORM-box.jpg', 'product', 3, 'images', 'image/jpeg', 1, ''),
			(5, '1262693813_11872.jpg', 'product', 0, 'images', 'jpg', 1, ''),
			(6, '1262693813_11873.jpg', 'product', 0, 'images', 'jpg', 1, ''),
			(7, '1262693813_11879.jpg', 'product', 0, 'images', 'jpg', 1, ''),
			(8, '1262693813_11887.jpg', 'product', 0, 'images', 'jpg', 1, ''),
			(9, '1262693813_11920.jpg', 'product', 0, 'images', 'jpg', 1, ''),
			(10, '1262693813_11928.jpg', 'product', 0, 'images', 'jpg', 1, ''),
			(11, '1262693813_11935.jpg', 'product', 0, 'images', 'jpg', 1, ''),
			(12, '1262693813_11936.jpg', 'product', 0, 'images', 'jpg', 1, ''),
			(17, '1262876620_redEVENT-box.jpg', 'product', 2, 'images', 'image/jpeg', 1, ''),
			(16, '1262876429_redEVENT-box.jpg', 'product', 1, 'images', 'image/jpeg', 1, ''),
			(33, '1274444752_redweb-logo.jpg', 'manufacturer', 1, 'images', '', 1, 'redweb.dk'),
			(34, '1274444735_redhost-logo.jpg', 'manufacturer', 2, 'images', '', 1, 'redhost.dk'),
			(35, '1274444723_redcomponent-logo.jpg', 'manufacturer', 3, 'images', '', 1, 'redcomponent.com'),
			(19, '1262876656_redCOMPETITION-box.jpg', 'product', 4, 'images', 'image/jpeg', 1, ''),
			(20, '1262876675_redVMPRODUCTFINDER-box.jpg', 'product', 5, 'images', 'image/jpeg', 1, ''),
			(21, '1262876700_redARTICLEFINDER-box.jpg', 'product', 6, 'images', 'image/jpeg', 1, ''),
			(22, '1262876715_redLINKER-box.jpg', 'product', 7, 'images', 'image/jpeg', 1, ''),
			(23, '1262876737_redVMMASSCART-box.jpg', 'product', 8, 'images', 'image/jpeg', 1, ''),
			(24, '1262876765_redNEWSTAB-box.jpg', 'product', 9, 'images', 'image/jpeg', 1, ''),
			(25, '1262876791_redPLUGINS-box.jpg', 'product', 10, 'images', 'image/jpeg', 1, ''),
			(26, '1262876810_redPLUGINS-box.jpg', 'product', 11, 'images', 'image/jpeg', 1, ''),
			(27, '1262876827_redPLUGINS-box.jpg', 'product', 12, 'images', 'image/jpeg', 1, ''),
			(28, '1262876844_redPLUGINS-box.jpg', 'product', 13, 'images', 'image/jpeg', 1, ''),
			(29, '1262876869_redCOMPONENTS.jpg', 'product', 14, 'images', 'image/jpeg', 1, ''),
			(31, '', 'product', 2, 'images', 'image/jpeg', 1, ''),
			(32, '', 'product', 2, 'images', 'image/jpeg', 1, '')";
		$db->setQuery($query);
		$db->query();

		$published_date = date("Y-m-d H:i:s");

		$query = "INSERT IGNORE INTO `#__redshop_product` (`product_id`, `product_parent_id`, `manufacturer_id`, `supplier_id`, `product_on_sale`, `product_special`, `product_download`, `product_template`, `product_name`, `product_price`, `discount_price`, `discount_stratdate`, `discount_enddate`, `product_number`, `product_type`, `product_s_desc`, `product_desc`, `product_volume`, `product_tax_id`, `published`, `product_thumb_image`, `product_full_image`, `publish_date`, `update_date`, `visited`, `metakey`, `metadesc`, `metalanguage_setting`, `metarobot_info`, `pagetitle`, `pageheading`, `sef_url`, `cat_in_sefurl`, `weight`, `expired`, `not_for_sale`, `use_discount_calc`, `discount_calc_method`, `min_order_product_quantity`, `attribute_set_id`, `product_length`, `product_height`, `product_width`, `product_diameter`, `product_availability_date`, `use_range`, `product_tax_group_id`, `product_download_days`, `product_download_limit`, `product_download_clock`, `product_download_clock_min`, `accountgroup_id`, `quantity_selectbox_value`, `checked_out`, `checked_out_time`, `max_order_product_quantity`, `product_download_infinite`, `product_back_full_image`, `product_back_thumb_image`, `product_preview_image`, `product_preview_back_image`, `preorder`, `append_to_global_seo`) VALUES
					(14, 0, 2, 2, 0, 0, 0, 9, 'redTWITTER', 69, 0, 0, 0, '14', '', '<p>This is the first release of redTWITTER.</p>', '<p>No graphics or images yet just pure access to the first release of the redTWITTER 1.0b8 component and module for all subscribers.</p>\r\n<p><br /><strong>Useage:</strong></p>\r\n<p>Install the component on your website and fill out your twitter account info in the config.</p>\r\n<p>Add the people you wish to follow on twitter on your website.</p>\r\n<p>Notice that the component and module works using cache as twitter only allows so and so many updates per hour - first load can be a tad slow depending on twitter.</p>', 0, 0, 1, '', '1262876869_redCOMPONENTS.jpg', '" . $published_date . "', '" . $published_date . "', 15, '', '', '', '', '', '', '', 1, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(11, 0, 2, 2, 0, 0, 0, 9, 'redTAGLINKER', 69, 0, 0, 0, '11', '', '<p>redTAGLINKER offers an easy to use 1.5 native plugin that allows you to enter a global text string from your website and convert it into an internal search link that works with redTAGSEARCH.</p>', '<p><a href=\"http://redcomponent.com/redtaglinker\">redTAGLINKER</a> offers an easy to use 1.5 native plugin that allows you to enter a global text string from your website and convert it into an internal search link that works with <a href=\"http://redcomponent.com/redsearch\">redTAGSEARCH</a>.<br />Perfect for linking to a list of articles containing the same search words - Can be used for recipies, brands etc. where you got the same name matching on a random amount of articles.</p>\r\n<p>Up to 20 links supported in this version.</p>', 0, 0, 1, '', '1262876810_redPLUGINS-box.jpg', '" . $published_date . "', '" . $published_date . "', 1, '', '', '', '', '', '', '', 3, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(12, 0, 2, 2, 0, 0, 0, 9, 'redTAGSEARCH', 69, 0, 0, 0, '12', '', '<p>redTAGSEARCH offers the backend plugin for redTAGLINKER. Its a Joomla 1.5 native plugin that allows you to enter a global text string from your website and convert it into an internal search.</p>', '<p><a href=\"http://redcomponent.com/redtagsearch\">redTAGSEARCH</a> offers the backend plugin for <a href=\"http://redcomponent.com/redtaglinker\">redTAGLINKER</a>. Its a Joomla&nbsp;1.5 native plugin that allows you to enter a global text string from your website and convert it into an internal search.<br />Perfect for linking to a list of articles containing the same search words - Can be used for recipies, brands etc. where you got the same name matching on a random amount of articles.</p>\r\n<p>Up to 20 links supported in this version.</p>', 0, 0, 1, '', '1262876827_redPLUGINS-box.jpg', '" . $published_date . "', '" . $published_date . "', 3, '', '', '', '', '', '', '', 3, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(13, 0, 2, 2, 0, 0, 0, 9, 'redTITLEREPLACER', 69, 0, 0, 0, '13', '', '<p>redTITLEREPLACER is a easy to use Joomla 1.5 native plugin that allows you to insert {redtitle} into any content element and replace the title of the article with this tag.</p>', '<p><a href=\"http://redcomponent.com/redtitlereplacer\">redTITLEREPLACER</a> is a easy to use Joomla 1.5 native plugin that allows you to insert {redtitle} into any content element and replace the title of the article with this tag.</p>\r\n<p>For perfect use if you dont want the title visible in the top - but prefer perhaps an image in the top and then the title below or some how have a need for placing the title of the article inside the article.</p>', 0, 0, 1, '', '1262876844_redPLUGINS-box.jpg', '" . $published_date . "', '" . $published_date . "', 7, '', '', '', '', '', '', '', 3, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(9, 0, 2, 2, 0, 0, 0, 9, 'redNEWSTAB', 69, 0, 0, 0, '9', '', '<p>redNEWSTAB is a brand new Joomla 1.5 native MVC news module that shows up to 10 different tabs displaying news for specific content sections and or categories.</p>', '<p><a href=\"http://redcomponent.com/rednewstab\">redNEWSTAB</a> is a brand new&nbsp;Joomla 1.5 native MVC&nbsp;news module that shows up to 10 different tabs displaying news for specific content sections and or categories.</p>\r\n<p> </p>\r\n<p>Install the&nbsp;module and in seconds your users can tab thier way trough the latest articles on your website with ease.</p>\r\n<p> </p>\r\n<p><a href=\"http://redcomponent.com/rednewstab\">redNEWSTAB</a> includes the following features:</p>\r\n<ul>\r\n<li>Set date format in backend</li>\r\n<li>Show or hide date</li>\r\n<li>Name each tab individually</li>\r\n<li>Set module width</li>\r\n<li>Set character limit </li>\r\n<li>100% MVC Structural build - make your over template overrides</li>\r\n<li>and much more...</li>\r\n</ul>', 0, 0, 1, '', '1262876765_redNEWSTAB-box.jpg', '" . $published_date . "', '2012-04-04 15:08:56', 3, '', '', '', '', '', '', '', 2, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(10, 0, 2, 2, 0, 0, 0, 9, 'redSIMPLELINKER', 69, 0, 0, 0, '10', '', '<p>redSIMPLELINKER offers an easy to use 1.5 native plugin that allows you to enter a global text string from your website and convert it into a link.</p>', '<p><a href=\"http://redcomponent.com/redsimplelinker\">redSIMPLELINKER</a> offers an easy to use 1.5 native plugin that allows you to enter a global text string from your website and convert it into a link.</p>\r\n<p>Up to 20 links supported in this version.</p>\r\n<p>Notice the <a href=\"http://redcomponent.com/redlinker\">redLINKER</a> component + plugin offers an entirely&nbsp;dynamical and unlimited functionality.</p>', 0, 0, 1, '', '1262876791_redPLUGINS-box.jpg', '" . $published_date . "', '" . $published_date . "', 2, '', '', '', '', '', '', '', 3, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(8, 0, 2, 2, 0, 0, 0, 9, 'redVMMASSCART', 69, 0, 0, 0, '8', '', '<p>redVMMASSCART is a brand new Joomla 1.5 native MVC module for VirtueMart that allows your customer to type in product sku''s in the cart module to add multiple products to the cart on the same time.</p>', '<p><a href=\"http://redcomponent.com/redvmmasscart\">redVMMASSCART</a> is a brand new&nbsp;Joomla 1.5 native MVC&nbsp;module for&nbsp;VirtueMart that allows your customer to type in product skus in the cart module to add multiple products to the cart on the same time.</p>\r\n<p> </p>\r\n<p>Install the&nbsp;module and in seconds your customers can quickly bulk add products to the cart.</p>\r\n<p> </p>\r\n<p><a href=\"http://redcomponent.com/redvmmasscart\">redVMMASSCART</a> includes the following features:</p>\r\n<ul>\r\n<li>Set widht and&nbsp;height of text area in the module</li>\r\n<li>Set button text and tooltip in the module </li>\r\n<li>100% MVC Structural build - make your over template overrides</li>\r\n<li>and much more...</li>\r\n</ul>', 0, 0, 1, '', '1262876737_redVMMASSCART-box.jpg', '" . $published_date . "', '2012-04-05 11:37:26', 6, '', '', '', '', '', '', '', 2, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(7, 0, 2, 2, 0, 0, 0, 9, 'redLINKER', 69, 0, 0, 0, '7', '', '<p>redLINKER is a brand new Joomla 1.5 native MVC component and plugin that allows you to enter a word that you want to replace either by another word or by a link. Save tons of hours going trough your entire website to create links for internal linkbuilding</p>', '<p><a href=\"http://redcomponent.com/redlinker\">redLINKER</a> is a brand new Joomla 1.5 native MVC&nbsp;component and plugin&nbsp;that allows you to enter a word that you want to replace either by another word or by a link. Save tons of hours going trough your entire website to create links for internal linkbuilding or link to articles or external pages on specific words. It only takes a few seconds to create a site wide replacement! <a href=\"http://redcomponent.com/redlinker\">redLINKER</a> replaces the earlier released static plugin with a fixed limit on 20 replacements.</p>\r\n<p> </p>\r\n<p>Install the&nbsp;component and in seconds you you can create links on specific words on your entire website or you can use it to update generic text strings on your entire website without having to go trough hundreds of articles.</p>\r\n<p> </p>\r\n<p><a href=\"http://redcomponent.com/redlinker\">redLINKER</a> includes the following features:</p>\r\n<ul>\r\n<li>Create unlimited replacements</li>\r\n<li>Based on regular expressions</li>\r\n<li>Replace with text or link</li>\r\n<li>Set target for links</li>\r\n<li>100% MVC Structural build - make your over template overrides</li>\r\n<li>and much more...</li>\r\n</ul>\r\n<p> </p>\r\n<p><strong>Licensing:</strong></p>\r\n<p><a href=\"http://redcomponent.com/redlinker\">redLINKER</a> is licensed under GPL v.2 but requires a subscription to download.</p>\r\n<p><strong><br />Requirements:</strong></p>\r\n<p>The extension is not garanteed to work under lesser versions of PHP than PHP 5.</p>', 0, 0, 1, '', '1262876715_redLINKER-box.jpg', '" . $published_date . "', '" . $published_date . "', 3, '', '', '', '', '', '', '', 1, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(5, 0, 2, 2, 0, 0, 0, 9, 'redVMPRODUCTFINDER', 69, 0, 0, 0, '5', '', '<p>redVMPRODUCTFINDER is a brand new Joomla 1.5 native MVC component for VirtueMart that allows you to build your own virtual search engine for your webshop or webshopcatalog.</p>', '<p><a href=\"http://redcomponent.com/redvmproductfinder\">redVMPRODUCTFINDER</a> is a brand new&nbsp;Joomla 1.5 native MVC component for VirtueMart that allows you to build your own virtual search engine for your webshop or webshopcatalog.</p>\r\n<p> </p>\r\n<p>Install the component and in seconds you can be on your way in building you own front end search engine for VirtueMart - and the best of it all is we havent changed a single line of code in VirtueMart!</p>\r\n<p><br /><a href=\"http://redcomponent.com/redvmproductfinder\">redVMPRODUCTFINDER</a> has native SEF support and generates on the fly SEF urls for all search resultats so you can use it to create your own custom virtual categories for your webshop along with creating an ocean of pure searchablility for your webshop.</p>\r\n<p><br />Make it possible to search on Size, Colors, Flavours, Material, Price Ranges or what ever you feel would make it easier for your webshop users to find what they are looking for using <a href=\"http://redcomponent.com/redvmproductfinder\">redVMPRODUCTFINDER</a>!</p>\r\n<p><a href=\"http://redcomponent.com/redvmproductfinder\">redVMPRODUCTFINDER</a> includes the following features:</p>\r\n<ul>\r\n<li>Unlimited amount of Types, Tags and Associations</li>\r\n<li>Dynamical options for&nbsp;search type (Dropdown and Checkbox)</li>\r\n<li>Select to show long or short product description from VirtueMart products</li>\r\n<li>100% control over layout trough the unique CSS tagging system</li>\r\n<li>SEF / SH404Sef native support</li>\r\n<li>Refine search function</li>\r\n<li>Link to search result function with SEF urls created on the fly!!!</li>\r\n<li>100% MVC Structural build - make your over template overrides</li>\r\n<li>English and Danish languages (looking for more - <a href=\"http://redcomponent.com/contact\">contact me</a> if you are interested in translating to your native langauge).</li>\r\n<li>and much more...</li>\r\n</ul>', 0, 0, 1, '', '1262876675_redVMPRODUCTFINDER-box.jpg', '" . $published_date . "', '" . $published_date . "', 0, '', '', '', '', '', '', '', 1, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(6, 0, 2, 2, 0, 0, 0, 9, 'redARTICLEFINDER', 69, 0, 0, 0, '6', '', '<p>redARTICLEFINDER is a brand new Joomla 1.5 native MVC component for Joomla that allows you to build your own virtual search engine for your articles.</p>', '<p><a href=\"http://redcomponent.com/redarticlefinder\">redARTICLEFINDER</a> is a brand new&nbsp;Joomla 1.5 native MVC component for&nbsp;Joomla that allows you to build your own virtual search engine for your articles.</p>\r\n<p>Install the component and in seconds you can be on your way in building you own front end search engine for&nbsp;Joomla based on virtual search types and tags you associate to your articles.</p>\r\n<p><br /><a href=\"http://redcomponent.com/redarticlefinder\">redARTICLEFINDER</a> has native SEF support and generates on the fly SEF urls for all search resultats so you can use it to create your own custom virtual categories for your Joomla website.</p>\r\n<p><br />Make it possible to search on&nbsp;virtual categories that spread across real&nbsp;sections or&nbsp;categories in Joomla and without having the articles actually containing any information related to the actual search&nbsp;by using <a href=\"http://redcomponent.com/redarticlefinder\">redARTICLEFINDER</a>!</p>\r\n<p><a href=\"http://redcomponent.com/redarticlefinder\">redARTICLEFINDER</a> includes the following features:</p>\r\n<ul>\r\n<li>Unlimited amount of Types, Tags and Associations</li>\r\n<li>Dynamical options for&nbsp;search type (Dropdown and Checkbox)</li>\r\n<li>Select to show&nbsp;article link&nbsp;or full text content from the article</li>\r\n<li>100% control over layout trough the unique CSS tagging system</li>\r\n<li>SEF / SH404Sef native support</li>\r\n<li>Refine search function</li>\r\n<li>Link to search result function with SEF urls created on the fly!!!</li>\r\n<li>100% MVC Structural build - make your over template overrides</li>\r\n<li>English and Danish languages (looking for more - <a href=\"http://redcomponent.com/contact\">contact me</a> if you are interested in translating to your native langauge).</li>\r\n<li>and much more...</li>\r\n</ul>\r\n<p><strong><br /></strong></p>\r\n<p><strong>Licensing:</strong></p>\r\n<p><a href=\"http://redcomponent.com/redarticlefinder\">redARTICLEFINDER</a> is licensed under GPL v.2 but requires a subscription to download.</p>\r\n<p><strong><br />Requirements:</strong></p>\r\n<p>The extension is not garanteed to work under lesser vsioners of PHP then PHP 5.</p>', 0, 0, 1, '', '1262876700_redARTICLEFINDER-box.jpg', '" . $published_date . "', '2012-04-05 10:34:17', 9, '', '', '', '', '', '', '', 1, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(3, 0, 2, 2, 0, 0, 0, 9, 'redFORM', 69, 0, 0, 0, '3', '', '<p>redFORM is a brand new Joomla 1.5 native MVC component that makes doing simple and dynamical forms as easy as childs play in joomla.</p>', '<p><a href=\"http://redcomponent.com/redform\">redFORM</a> is a brand new Joomla 1.5 native MVC component that makes doing&nbsp;simple and dynamical forms&nbsp;as easy as childs play in joomla.<br />Install the component and content plugin and in a matter of 2 minuttes you can setup your very own joomla powered forms.</p>\r\n<p> </p>\r\n<p><a href=\"http://redcomponent.com/redform\">redFORM</a> includes the following features:</p>\r\n<ul>\r\n<li>Unlimited amount of&nbsp;Forms,&nbsp;Fields and Inputs </li>\r\n<li>Dynamical options for&nbsp;input type (Radio, Checkbox, Textfield, Textarea, Email, Username, Fullname, Fileupload, Select, Multiselect) </li>\r\n<li>Complete statistics over forms filled and&nbsp;newsletter attendance </li>\r\n<li>Notification text, emails and admin notification with option to email form data</li>\r\n<li>Integration with the open source mailinglist project <a href=\"http://www.phplist.com/\" target=\"_blank\"><span style=\"color: #a10f15;\">PHPlist</span></a> and the Joomla 1.5 native&nbsp;components <a href=\"http://extensions.chillcreations.com/ccnewsletter/ccnewsletter-spsnewsletter-newsletters-joomla-15.html\" target=\"_blank\">ccNewsletter</a> and <a href=\"http://www.acajoom.com/\" target=\"_blank\">Acajoom</a>. </li>\r\n<li>Multiple mailinglist integrations per form - allow for multiple list signups in the same form.</li>\r\n<li>Integration with <a href=\"http://redcomponent.com/redform\" target=\"_blank\">Bigo Captcha</a>. </li>\r\n<li>Integration with <a href=\"http://redcomponent.com/redevent\">redEVENT</a> - Run all your submission forms from redEVENT trough redFORM.</li>\r\n<li>Integration with <a href=\"http://redcomponent.com/redcompetition\">redCOMPETITION</a> - Run all your submission forms from redCOMPETITION trough redFORM.</li>\r\n<li>Content plugin for easy adding of&nbsp;form(s) to _ALL_ joomla content that supports content plugins </li>\r\n<li>Add custom styles to input fields trough backend and style in template css </li>\r\n<li>English, German, Dutch and&nbsp;Danish languages (looking for more - <a href=\"http://redcomponent.com/contact\"><span style=\"color: #a10f15;\">contact me</span></a> if you are&nbsp;interested in translating to your native langauge). </li>\r\n<li>and much more...</li>\r\n</ul>', 0, 0, 1, '', '1262876640_redFORM-box.jpg', '" . $published_date . "', '2012-04-05 11:26:23', 7, '', '', '', '', '', '', '', 1, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(4, 0, 2, 2, 0, 0, 0, 9, 'redCOMPETITION', 69, 0, 0, 0, '4', '', '<p>redCOMPETITION is a brand new Joomla 1.5 native MVC component that makes doing competitions as easy as childs play in joomla.</p>', '<p><a href=\"http://redcomponent.com/redcompetition\">redCOMPETITION</a> is a brand new Joomla 1.5 native MVC component that makes doing competitions as easy as childs play in joomla.</p>\r\n<p>Install the component and content plugin and in a matter of 2 minuttes you can setup your very own joomla powered competitions.</p>\r\n<p><a href=\"http://redcomponent.com/redcompetition\">redCOMPETITION</a> includes the following features:</p>\r\n<ul>\r\n<li>Unlimited amount of Competitions, Questions and Answers</li>\r\n<li>Dynamical options for answer type (Radio, Checkbox, Textfield or&nbsp;Textarea)</li>\r\n<li>Complete statistics over signups and newsletter attendance</li>\r\n<li>Draw a winner functionality</li>\r\n<li>Auto email looser and&nbsp;winners or only winners</li>\r\n<li>Auto email new registrations on sign up</li>\r\n<li>Integration with the open source mailinglist project <a href=\"http://www.phplist.com/\" target=\"_blank\">PHPlist</a> and the Joomla 1.5 native&nbsp;component <a href=\"http://extensions.chillcreations.com/ccnewsletter/ccnewsletter-spsnewsletter-newsletters-joomla-15.html\" target=\"_blank\"><span style=\"color: #a10f15;\">ccNewsletter</span></a> </li>\r\n<li>Optional form fields for contact information - chose if the elements in the form should be included or not</li>\r\n<li>Content plugin for easy adding of competition to _ALL_ joomla content that supports content plugins</li>\r\n<li>Add custom styles to input fields trough backend and style in template css</li>\r\n<li>English and&nbsp;Danish languages (looking for more - <a href=\"http://redcomponent.com/contact\">contact me</a> if you are&nbsp;interested in translating to your native langauge).</li>\r\n<li>and much more...</li>\r\n</ul>', 0, 0, 1, '', '1262876656_redCOMPETITION-box.jpg', '" . $published_date . "', '2012-04-05 10:56:22', 6, '', '', '', '', '', '', '', 1, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(2, 0, 2, 2, 0, 0, 0, 9, 'redEVENT2', 69, 0, 0, 0, '2', '', '<p>Today we are announcing the first public release of redEVENT v2.0b1, redFORM v2.0b1, Latest Calendar Module v2.0b1 and Minicalendar Module v2.0b1.</p>', '<p>Today we are announcing the first public release of redEVENT v2.0b1, redFORM v2.0b1, Latest Calendar Module v2.0b1 and Minicalendar Module v2.0b1.</p>\r\n<p> </p>\r\n<p>The release is&nbsp;introduce drastic changes to redEVENT.</p>\r\n<p> </p>\r\n<p>The new features list include:</p>\r\n<ul>\r\n<li>Endless amount of repeating events on different time and space - No more&nbsp;attachtment of an event to a single venue!</li>\r\n<li>JomSocial integration</li>\r\n<li>3 New views (Upcoming, Upcoming per venue, Calendar)</li>\r\n<li>Multiple registrations through contactperson</li>\r\n<li>Dynamic limits for contact persons per event</li>\r\n<li>5 different signup forms (Webform, Email, Formal Email, Phone, External)</li>\r\n<li>PDF Generation for email signup</li>\r\n<li>New confirmation screens</li>\r\n<li>Complete control over the graphical view of _ALL_ Submission, Confirmation screens</li>\r\n<li>Complete control over all emails</li>\r\n<li>All new tagging systems that allow dynamical use of tags on all levels</li>\r\n<li>Improved attendee control (Add or edit attendees)</li>\r\n<li>Multiple Categories</li>\r\n<li>Venue Categories</li>\r\n<li>Parent/Child Categories</li>\r\n<li>Parent/Child Venue Categories</li>\r\n<li>Library text templating system</li>\r\n<li>+ All the old features from <a href=\"http://redcomponent.com/redevent\">version 1</a>!</li>\r\n<li>And much more!</li>\r\n</ul>\r\n<p>Eventhandling in Joomla will never be the same!!!</p>', 0, 0, 1, '', '1262876620_redEVENT-box.jpg', '" . $published_date . "', '2012-04-05 10:56:16', 24, '                                                                                                                                                                                ', '                                                                                                                                                                                ', '                                                                                                                                                                                ', '                                                                                                                                                                                ', '', '', '', 1, 0.000, 0, 0, 0, '', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', '', 'append'),
					(1, 0, 2, 0, 0, 0, 0, 9, 'redEVENT', 69, 0, 0, 0, '1', 'product', '<p>redEVENT is a brand new Joomla 1.5 native MVC event component. Build over the popular but yet limited event component eventlist, the redEVENT fork along with its 100% integration to redFORM has taken the ease and flexibility of creating and managing event</p>', '<p><a href=\"http://redcomponent.com/redevent\">redEVENT</a> is a brand new Joomla 1.5 native MVC event component. Build over the popular but yet limited event component <a href=\"http://www.schlu.net/\">eventlist</a>, the <a href=\"http://redcomponent.com/redevent\">redEVENT</a> fork along with its 100% integration to <a href=\"http://redcomponent.com/redform\">redFORM</a> has taken the ease and flexibility of creating and managing events and bookings to a whole new level.</p>\r\n<p align=\"justify\">Super dynamical with the simple yet powerfull and customizable input forms from <a href=\"http://redcomponent.com/redform\">redFORM</a>, you can make small simple signup forms or you can go all the way and do full registration formulars for the attendees to your events. Along with the new options of newsletter integration and dynamical waitinglists along with the ability to manually alter and cuztomize the frontend list of attendees on each event, there never has been more flexibility in Event handling in Joomla then now!</p>\r\n<p align=\"justify\"><a href=\"http://redcomponent.com/redevent\">redEVENT</a> takes it basis on the component <a href=\"http://www.schlu.net/\">Eventlist</a> and the work of Christoph Lukes from <a href=\"http://www.schlu.net/\">Schlu.net</a> and as such we here from <a href=\"http://redcomponent.com/\">redCOMPONENT</a> give credits to Christoph for his work - However as we where met with requirements for a much higher level of functionality and flexibility from our customers and with a vision to integrate the event form handling into <a href=\"http://redcomponent.com/redform\">redFORM</a> we decided to do a full fork of <a href=\"http://www.schlu.net/\">Eventlist</a> and <a href=\"http://redcomponent.com/redevent\">redEVENT</a> was born. <a href=\"http://redcomponent.com/redevent\">redEVENT</a> is developed upon the basis of <a href=\"http://www.schlu.net/\">Eventlist 1.0b</a> however due to the extensive changes made in the component it will not be possible to update the event component along the paths of <a href=\"http://www.schlu.net/\">Eventlist</a> in the future and instead <a href=\"http://redcomponent.com/redevent\">redEVENT</a> will take its own path and live in the wonderful world of <a href=\"http://redcomponent.com/\">Joomla Extensions</a>.</p>\r\n<p><a href=\"http://redcomponent.com/redevent\">redEVENT</a> is released and is in a stable state. The list of abilities in <a href=\"http://redcomponent.com/redevent\">redEVENT</a> that makes it unique compared to its predecessor <a href=\"http://www.schlu.net/\">Eventlist</a>, is included in the following:</p>\r\n<ul>\r\n<li>Unlimited amount of events</li>\r\n<li>Allow registration with or without Joomla User creation</li>\r\n<li>Allow registration and cancelation using Joomla User creation</li>\r\n<li>Waitinglist on individual events - Set waitinglist per event!</li>\r\n<li>Individual confirmation- and registrationemails</li>\r\n<li>Confirmation trough email confirmation link</li>\r\n<li>Unlimited amount of Forms, Fields and Inputs by integration to <a href=\"http://redcomponent.com/redform\">redFORM</a></li>\r\n<li>Dynamical options for input type (Radio, Checkbox, Textfield, Textarea, Email, Username, Fullname)</li>\r\n<li>Dynamical frontend attendee lists using the fields you made in the form (you made in redFORM) used by the event</li>\r\n<li>Admin notification and option to send on formular data to the admin</li>\r\n<li>Integration with the open source mailinglist project <a href=\"http://www.phplist.com/\" target=\"_blank\"><span style=\"color: #a10f15;\">PHPlist</span></a> and the Joomla 1.5 native components <a href=\"http://extensions.chillcreations.com/ccnewsletter/ccnewsletter-spsnewsletter-newsletters-joomla-15.html\" target=\"_blank\">ccNewsletter</a> and <a href=\"http://www.acajoom.com/\" target=\"_blank\">Acajoom</a>.</li>\r\n<li>Add custom styles to input fields trough backend and style in template css</li>\r\n<li>English and Danish languages (looking for more - <a href=\"http://redcomponent.com/contact\"><span style=\"color: #a10f15;\">contact me</span></a> if you are interested in translating to your native langauge).</li>\r\n<li>and much more...</li>\r\n</ul>', 0, 0, 1, '', '1262876429_redEVENT-box.jpg', '" . $published_date . "', '" . $published_date . "', 22, '', '', '', '', '', '', '', 1, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append')";
		$db->setQuery($query);
		$db->query();


		$query = "INSERT IGNORE INTO `#__redshop_product_accessory` (`accessory_id`, `product_id`, `child_product_id`, `accessory_price`, `oprand`, `setdefault_selected`, `ordering`, `category_id`) VALUES
						(32, 2, 3, 1, '', 0, 0, 0),
						(21, 1, 12, 10, '-', 0, 0, 0),
						(20, 1, 3, 10, '-', 0, 0, 0)";
		$db->setQuery($query);
		$db->query();

		$query = "INSERT IGNORE INTO `#__redshop_product_attribute` (`attribute_id`, `attribute_name`, `attribute_required`, `allow_multiple_selection`, `hide_attribute_price`, `product_id`, `ordering`, `attribute_set_id`, `display_type`, `attribute_published`) VALUES
						(3, 'Subscription', 0, 0, 0, 2, 0, 0, '', 1),
						(4, 'Subscription', 1, 0, 0, 1, 0, 0, 'dropdown', 1)";
		$db->setQuery($query);
		$db->query();

		$query = "INSERT IGNORE INTO `#__redshop_product_attribute_property` (`property_id`, `attribute_id`, `property_name`, `property_price`, `oprand`, `property_image`, `property_main_image`, `ordering`, `setdefault_selected`, `setrequire_selected`, `setmulti_selected`, `setdisplay_type`, `property_number`) VALUES
						(3, 3, '1 Year', 100, '+', '3_globus.gif', '', 0, 0, 0, 0, '', ''),
						(4, 3, '2 Year', 100, '+', '', '', 0, 0, 0, 0, '', ''),
						(5, 3, '3 Year', 100, '+', '', '', 0, 0, 0, 0, '', ''),
						(6, 4, '1 Year', 125, '+', '6_11408.jpg', '', 0, 0, 0, 0, 'dropdown', ''),
						(8, 4, '2 Year', 175, '+', '', '', 1, 0, 0, 0, 'dropdown', '')";
		$db->setQuery($query);
		$db->query();

		/* Get the current columns for redshop category_xref */
		$q = "SHOW INDEX FROM #__redshop_product_category_xref";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Key_name');

		if (is_array($cols))
		{
			/* Check if we have the category_parent_id column */
			if (!array_key_exists('category_id', $cols))
			{
				$q = "ALTER TABLE `#__redshop_product_category_xref` ADD UNIQUE (
							`category_id` ,
							`product_id`
							)";
				$db->setQuery($q);
				$db->query();
			}
		}

		$query = "INSERT IGNORE INTO `#__redshop_product_category_xref` (`category_id`, `product_id`, `ordering`) VALUES
						(1, 14, 6),
						(1, 6, 8),
						(1, 5, 5),
						(1, 4, 4),
						(1, 3, 3),
						(1, 7, 7),
						(1, 2, 2),
						(3, 13, 0),
						(3, 12, 0),
						(3, 11, 0),
						(3, 10, 0),
						(2, 9, 0),
						(2, 8, 0),
						(1, 1, 1)";
		$db->setQuery($query);
		$db->query();


		$query = "INSERT IGNORE INTO `#__redshop_product_rating`
					(`rating_id`, `product_id`, `title`, `comment`, `userid`, `time`, `user_rating`, `favoured`, `published`)
					VALUES (1, 1, 'super', 'Flot flot flot...', 64, 1262695786, 4, 1, 1)";
		$db->setQuery($query);
		$db->query();


		/* Get the current columns for redshop product related */
		$q = "SHOW INDEX FROM #__redshop_product_related";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Key_name');

		if (is_array($cols))
		{
			/* Check if we have the category_parent_id column */
			if (!array_key_exists('related_id', $cols))
			{
				$q = "ALTER TABLE `#__redshop_product_related` ADD UNIQUE (
								`related_id` ,
								`product_id`
								)";
				$db->setQuery($q);
				$db->query();
			}
		}

		$query = "INSERT IGNORE INTO `#__redshop_product_related`
					(`related_id`, `product_id`) VALUES
					(1, 2),(3, 2),(2, 1),(3, 1)";
		$db->setQuery($query);
		$db->query();

		/* Get the current columns for redshop product stockroom  */
		$q = "SHOW INDEX FROM #__redshop_product_stockroom_xref";
		$db->setQuery($q);
		$cols = $db->loadObjectList('Key_name');

		if (is_array($cols))
		{
			/* Check if we have the category_parent_id column */
			if (!array_key_exists('product_id', $cols))
			{
				$q = "ALTER TABLE `#__redshop_product_stockroom_xref` ADD UNIQUE (
								`product_id` ,
								`stockroom_id`
								)";
				$db->setQuery($q);
				$db->query();
			}
		}

		$query = "INSERT IGNORE INTO `#__redshop_product_stockroom_xref`
					(`product_id`, `stockroom_id`, `quantity`) VALUES
					(2, 1, 100)";
		$db->setQuery($query);
		$db->query();

		return true;
		/*********************************************************/
	}

	public function getNewcustomers()
	{
		$this->_table_prefix = '#__redshop_';
		$custquery = "SELECT *  FROM " . $this->_table_prefix . "users_info ORDER BY users_info_id DESC LIMIT 0, 5";
		$this->_db->setQuery($custquery);

		return $this->_db->loadObjectlist();
	}

	public function getNeworders()
	{
		$query = 'SELECT o.*,CONCAT(u.firstname," ",u.lastname) AS name FROM #__redshop_order_users_info AS u '
			. 'LEFT JOIN #__redshop_orders AS o ON u.order_id = o.order_id AND u.address_type="BT" '
			. 'ORDER BY o.order_id desc limit 0, 5';
		$this->_db->setQuery($query);
		$rows = $this->_db->loadObjectList();

		return $rows;
	}

	public function getUser($user_id)
	{
		$this->_table_prefix = '#__';
		$userquery = "SELECT name  FROM " . $this->_table_prefix . "users where id=" . $user_id;
		$this->_db->setQuery($userquery);

		return $this->_db->loadObject();
	}

	public function gettotalOrder($id = 0)
	{
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$userquery = "SELECT SUM(order_total) AS order_total, count(*) AS tot_order FROM " . $this->_table_prefix . "orders "
			. "WHERE `user_info_id`='" . $id . "' ";
		$this->_db->setQuery($userquery);

		return $this->_db->loadObject();
	}

	public function gettotalAmount($user_id)
	{
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$query = 'SELECT  SUM(o.order_total) AS order_total '
			. 'FROM ' . $this->_table_prefix . 'orders AS o '
			. 'LEFT JOIN ' . $this->_table_prefix . 'users_info as uf ON o.user_id =uf.user_id'
			. ' AND address_type LIKE "BT" '
			. 'WHERE o.user_id = ' . $user_id . ' and  (o.order_status = "C" OR o.order_status = "PR" OR o.order_status = "S")';
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	public function getavgAmount($user_id)
	{
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$query = 'SELECT  (SUM(o.order_total)/ COUNT( DISTINCT o.user_id ) ) AS avg_order '
			. 'FROM ' . $this->_table_prefix . 'orders AS o '
			. 'WHERE o.user_id =' . $user_id . ' and (o.order_status = "C" OR o.order_status = "PR" OR o.order_status = "S") ';
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	public function getUserinfo($user_id)
	{
		$this->_table_prefix = '#__' . TABLE_PREFIX . '_';
		$userquery = "SELECT CONCAT(firstname,' ',lastname) as name  FROM " . $this->_table_prefix .
			"users_info where address_type='BT' and user_id=" . $user_id;
		$this->_db->setQuery($userquery);

		return $this->_db->loadObject();
	}
}
