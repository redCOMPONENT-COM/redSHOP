<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Model
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopModelRedshop extends RedshopModel
{
	public $_table_prefix = null;

	/**
	 * RedshopModelRedshop constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_table_prefix = '#__redshop_';
		$this->_filteroption = 3;
	}

	/**
	 * Method for insert demo content
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function demoContentInsert()
	{
		$db            = JFactory::getDbo();
		$categoryTable = RedshopTable::getAdminInstance('Category');

		// Category insert
		$categoryTable->id                   = null;
		$categoryTable->name                 = 'Events and Forms';
		$categoryTable->template             = 5;
		$categoryTable->more_template        = '5,8';
		$categoryTable->products_per_page    = 4;
		$categoryTable->published            = 1;
		$categoryTable->ordering             = 1;
		$categoryTable->append_to_global_seo = 'append';
		$categoryTable->setLocation(RedshopHelperCategory::getRootId(), 'last-child');
		$categoryTable->store();

		$firstCatId = $categoryTable->id;

		// Category insert
		$categoryTable->reset();
		$categoryTable->id                   = null;
		$categoryTable->name                 = 'CCK and e-Commerce';
		$categoryTable->template             = 5;
		$categoryTable->products_per_page    = 4;
		$categoryTable->published            = 1;
		$categoryTable->ordering             = 2;
		$categoryTable->append_to_global_seo = 'append';
		$categoryTable->setLocation(RedshopHelperCategory::getRootId(), 'last-child');
		$categoryTable->store();

		$secondCatId = $categoryTable->id;

		// Category insert
		$categoryTable->reset();
		$categoryTable->id                   = null;
		$categoryTable->name                 = 'Templates';
		$categoryTable->template             = 8;
		$categoryTable->products_per_page    = 6;
		$categoryTable->published            = 1;
		$categoryTable->ordering             = 3;
		$categoryTable->append_to_global_seo = 'append';
		$categoryTable->setLocation(RedshopHelperCategory::getRootId(), 'last-child');
		$categoryTable->store();

		$thirdCatId = $categoryTable->id;

		unset($categoryTable);

		$query = "INSERT IGNORE INTO `#__redshop_fields`
					(`id`, `title`, `name`, `type`, `desc`, `class`, `section`, `maxlength`, `cols`, `rows`, `size`, `show_in_front`,`published`, `required`)
					VALUES
					(1, 'Userfield Test', 'rs_userfield_test', '1', '', '', '12', '20', '0', '0', '20', '1', '1', '0')";
		$db->setQuery($query);
		$db->execute();


		$query = "INSERT IGNORE INTO `#__redshop_manufacturer` (`manufacturer_id`, `manufacturer_name`, `manufacturer_desc`, `manufacturer_email`, `product_per_page`, `template_id`, `metakey`, `metadesc`, `metalanguage_setting`, `metarobot_info`, `pagetitle`, `pageheading`, `sef_url`, `published`, `ordering`, `manufacturer_url`) VALUES
						(1, 'redweb.dk', '<p>http://redweb.dk</p>', '', 0, 14, '', '', '', '', '', '', '', 1, 1, ''),
						(2, 'redhost.dk', '<p>http://redhost.dk</p>', '', 0, 14, '', '', '', '', '', '', '', 1, 2, ''),
						(3, 'redcomponent.com', '<p>http://redcomponent.com</p>', '', 0, 14, '', '', '', '', '', '', '', 1, 3, '')";
		$db->setQuery($query);
		$db->execute();

		$query = "INSERT IGNORE INTO `#__redshop_media`
			(`media_id`, `media_name`, `media_section`, `section_id`, `media_type`, `media_mimetype`, `published`, `media_alternate_text`)
			VALUES (16, '1262876429_redEVENT-box.jpg', 'product', 1, 'images', 'image/jpeg', 1, ''),
			(20, '1262876675_redVMPRODUCTFINDER-box.jpg', 'product', 5, 'images', 'image/jpeg', 1, ''),
			(23, '1262876737_redVMMASSCART-box.jpg', 'product', 8, 'images', 'image/jpeg', 1, ''),
			(33, '1274444752_redweb-logo.jpg', 'manufacturer', 1, 'images', '', 1, 'redweb.dk'),
			(34, '1274444735_redhost-logo.jpg', 'manufacturer', 2, 'images', '', 1, 'redhost.dk'),
			(35, '1274444723_redcomponent-logo.jpg', 'manufacturer', 3, 'images', '', 1, 'redcomponent.com'),
			(45, '1421054444_bakery-demo-400-400.jpg', 'product', 10, 'images', 'image/jpeg', 1, ''),
			(46, '1421054762_carpenter-demo-400-400.jpg', 'product', 11, 'images', 'image/jpeg', 1, ''),
			(47, '1421055027_fashionstore-demo-400-400.jpg', 'product', 8, 'images', 'image/jpeg', 1, ''),
			(48, '1421055222_gadgets-demo-400-400.jpg', 'product', 12, 'images', 'image/jpeg', 1, ''),
			(49, '1421055392_kidswear-demo-400-400.jpg', 'product', 13, 'images', 'image/jpeg', 1, ''),
			(50, '1421055573_shoemaniac-demo-400-400.jpg', 'product', 14, 'images', 'image/jpeg', 1, ''),
			(51, '1421055894_valentine-demo-400-400.jpg', 'product', 9, 'images', 'image/jpeg', 1, ''),
			(52, '1421064966_appearance-top.png', 'product', 3, 'images', 'image/png', 1, ''),
			(53, 'redCOMPONENTS.jpg', 'product', 2, 'images', '', 1, ''),
			(54, 'redCOMPONENTS.jpg', 'product', 4, 'images', '', 1, ''),
			(55, 'redCOMPONENTS.jpg', 'product', 7, 'images', '', 1, ''),
			(56, 'redCOMPONENTS.jpg', 'product', 6, 'images', '', 1, '')
			";
		$db->setQuery($query);
		$db->execute();

		$query = "INSERT IGNORE INTO `#__redshop_product` (`product_id`, `product_parent_id`, `manufacturer_id`, `supplier_id`, `product_on_sale`, `product_special`, `product_download`, `product_template`, `product_name`, `product_price`, `discount_price`, `discount_stratdate`, `discount_enddate`, `product_number`, `product_type`, `product_s_desc`, `product_desc`, `product_volume`, `product_tax_id`, `published`, `product_thumb_image`, `product_full_image`, `publish_date`, `update_date`, `visited`, `metakey`, `metadesc`, `metalanguage_setting`, `metarobot_info`, `pagetitle`, `pageheading`, `sef_url`, `cat_in_sefurl`, `weight`, `expired`, `not_for_sale`, `use_discount_calc`, `discount_calc_method`, `min_order_product_quantity`, `attribute_set_id`, `product_length`, `product_height`, `product_width`, `product_diameter`, `product_availability_date`, `use_range`, `product_tax_group_id`, `product_download_days`, `product_download_limit`, `product_download_clock`, `product_download_clock_min`, `accountgroup_id`, `quantity_selectbox_value`, `checked_out`, `checked_out_time`, `max_order_product_quantity`, `product_download_infinite`, `product_back_full_image`, `product_back_thumb_image`, `product_preview_image`, `product_preview_back_image`, `preorder`, `append_to_global_seo`) VALUES
					(1, 0, 2, 0, 0, 0, 0, 9, 'redEVENT', 48, 0, 0, 0, '1', 'product', '<p>redEVENT is a Joomla 3 native MVC event component. Build over the popular but yet limited event component eventlist, the redEVENT fork along with its 100% integration to redFORM has taken the ease and flexibility of creating and managing event</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redevent\">redEVENT</a> is a Joomla 3 native MVC event component. Build over the popular but yet limited event component <a href=\"http://www.schlu.net/\">eventlist</a>, the <a href=\"http://redcomponent.com/redcomponent/redevent\">redEVENT</a> fork along with its 100% integration to <a href=\"http://redcomponent.com/redcomponent/redform\">redFORM</a> has taken the ease and flexibility of creating and managing events and bookings to a whole new level.</p>\r\n<p align=\"justify\">Super dynamical with the simple yet powerful and customizable input forms from <a href=\"http://redcomponent.com/redcomponent/redform\">redFORM</a>, you can make small simple signup forms or you can go all the way and do full registration formulars for the attendees to your events. Along with the new options of newsletter integration and dynamical waitinglists along with the ability to manually alter and customise the frontend list of attendees on each event, there never has been more flexibility in Event handling in Joomla then now!</p>\r\n<p align=\"justify\"><a href=\"http://redcomponent.com/redcomponent/redevent\">redEVENT</a> takes it basis on the component <a href=\"http://www.schlu.net/\">Eventlist</a> and the work of Christoph Lukes from <a href=\"http://www.schlu.net/\">Schlu.net</a> and as such we here from <a href=\"http://redcomponent.com/\">redCOMPONENT</a> give credits to Christoph for his work - However as we where met with requirements for a much higher level of functionality and flexibility from our customers and with a vision to integrate the event form handling into <a href=\"http://redcomponent.com/redcomponent/redform\">redFORM</a> we decided to do a full fork of <a href=\"http://www.schlu.net/\">Eventlist</a> and <a href=\"http://redcomponent.com/redcomponent/redevent\">redEVENT</a> was born. <a href=\"http://redcomponent.com/redcomponent/redevent\">redEVENT</a> is developed upon the basis of <a href=\"http://www.schlu.net/\">Eventlist 1.0b</a> however due to the extensive changes made in the component it will not be possible to update the event component along the paths of <a href=\"http://www.schlu.net/\">Eventlist</a> in the future and instead <a href=\"http://redcomponent.com/redcomponent/redevent\">redEVENT</a> will take its own path and live in the wonderful world of <a href=\"http://redcomponent.com/\">Joomla Extensions</a>.</p>\r\n<p><a href=\"http://redcomponent.com/redcomponent/redevent\">redEVENT</a> is released and is in a stable state. The list of abilities in <a href=\"http://redcomponent.com/redcomponent/redevent\">redEVENT</a> that makes it unique compared to its predecessor <a href=\"http://www.schlu.net/\">Eventlist</a>, is included in the following:</p>\r\n<ul>\r\n<li>Unlimited amount of events</li>\r\n<li>Allow registration with or without Joomla User creation</li>\r\n<li>Allow registration and cancellation using Joomla User creation</li>\r\n<li>Waitinglist on individual events - Set waitinglist per event!</li>\r\n<li>Individual confirmation- and registration emails</li>\r\n<li>Confirmation trough email confirmation link</li>\r\n<li>Unlimited amount of Forms, Fields and Inputs by integration to <a href=\"http://redcomponent.com/redcomponent/redform\">redFORM</a></li>\r\n<li>Dynamical options for input type (Radio, Checkbox, Textfield, Textarea, Email, Username, Fullname)</li>\r\n<li>Dynamical frontend attendee lists using the fields you made in the form (you made in redFORM) used by the event</li>\r\n<li>Admin notification and option to send on formular data to the admin</li>\r\n<li>Integration with the open source mailinglist project <a href=\"http://www.phplist.com/\" target=\"_blank\"><span style=\"color: #a10f15;\">PHPlist</span></a> and the Joomla native components <a href=\"http://extensions.chillcreations.com/ccnewsletter/ccnewsletter-spsnewsletter-newsletters-joomla-15.html\" target=\"_blank\">ccNewsletter</a> and <a href=\"http://www.acajoom.com/\" target=\"_blank\">Acajoom</a>.</li>\r\n<li>Add custom styles to input fields trough backend and style in template css</li>\r\n<li>and much more...</li>\r\n</ul>', 0, 0, 1, '', '1262876429_redEVENT-box.jpg', '2014-12-08 15:26:01', '2015-01-12 12:58:09', 23, '', '', '', '', '', '', '', 1, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(2, 0, 2, 0, 0, 0, 0, 9, 'redFORM', 36, 0, 0, 0, '2', 'product', '<p>redFORM for Joomla! 3.</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redform\">redFORM</a> is an advanced form system for Joomla 2.5 and Joomla 3 This forms extension let you make different kind of forms in minutes. It is easy and fast to customize your own forms and only integrate the specific fields, that you need.</p>\r\n<p><a href=\"http://redcomponent.com/redcomponent/redform\">redFORM</a> integrates with redEVENT allowing you to add payment to your events</p>\r\n<p>Some of the features include:</p>\r\n<ul>\r\n<li>Unlimited amount of Forms</li>\r\n<li>Payment integrations</li>\r\n<li>Complete statistics over forms</li>\r\n<li>Integration with redEVENT</li>\r\n<li>And much more..</li>\r\n</ul>', 0, 0, 1, '', 'redCOMPONENTS.jpg', '2014-12-08 15:26:01', '2015-01-12 13:19:00', 25, '', '', '', '', '', '', '', 1, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(3, 0, 2, 0, 0, 0, 0, 9, 'redCOOKIE', 24, 0, 0, 0, '3', 'product', '<p>redCOOKIE is a Joomla 3 plugin that make it easy to add cookie accept to your site.</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redcookie\">redCOOKIE</a> is a Joomla 3 plugin that let you easily add cookie accept functionality to your site.</p>\r\n<p>To meet the requirement of the EU cookie directive website owners can use the plugin to add accept cookie functionality to their site in a matter of minutes.<br /><br />The <a href=\"http://redcomponent.com/redcomponent/redcookie\"><span style=\"text-decoration: underline;\"><span style=\"color: #0066cc;\">redCOOKIE plugin</span></span></a> provide you with 3 different options to show the accept message.</p>\r\n<ol>\r\n<li>Top bar</li>\r\n<li>Side slider</li>\r\n<li>Bottom box</li>\r\n</ol>\r\n<p>Within the plugin configuration you can select the option you prefer, and with a few clicks you can choose your own background and text colours of the accept box. You can read in detail about the settings in our <a href=\"http://wiki.redcomponent.com/index.php?title=RedCOOKIE\" target=\"_blank\"><span style=\"text-decoration: underline;\"><span style=\"color: #0066cc;\">redCOOKIE documentation</span></span></a></p>', 0, 0, 1, '', '1421064966_appearance-top.png', '2014-12-08 15:26:01', '2015-01-12 12:41:05', 9, '', '', '', '', '', '', '', 1, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(4, 0, 2, 0, 0, 0, 0, 9, 'redSLIDER', 24, 0, 0, 0, '4', 'product', '<p>redSLIDER is a unique responsive slider extension for Joomla! 3.</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redslider\">redSLIDER</a> is a Joomla 3 component and module that let you manage and show responsive sliders in your site.</p>\r\n<p>With redSLIDER you can manage galleries of slides in the component, and each slide can use an own template. The associated module will let you show the galleries in multiple ways according to the settings you choose.</p>\r\n<p>redSLIDER can be extended with extension specific plugins, which will allow you to show a form or a product directly in a slide. Learn much more on how to use redSLIDER, and see how it is easily setup and configured in our <a href=\"http://wiki.redcomponent.com/index.php?title=RedSLIDER\">documentation wiki</a></p>', 0, 0, 1, '', 'redCOMPONENTS.jpg', '2014-12-08 15:26:01', '2015-01-12 13:24:35', 7, '', '', '', '', '', '', '', 1, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(5, 0, 1, 0, 0, 0, 0, 9, 'redITEM', 48, 0, 0, 0, '5', 'product', '<p>redITEM is a CCK for Joomla that let you create any type of content</p>', '<p><a href=\"http://redcomponent.com/redcomponent/reditem\">redITEM</a> is a CCK component developed by redCOMPONENT.com which allows you to create your own items, fields, categories and groups in Joomla. Based on its predecessor, redITEM, the new redITEM 2 now has new useful features that can help you easily manage your own items, which can be anything from books to airplanes..</p>\r\n<p> </p>\r\n<p><a href=\"http://redcomponent.com/redcomponent/reditem\">redITEM</a> includes the following features:</p>\r\n<ul>\r\n<li>Create types, categories, items and custom fields for items.</li>\r\n<li>Diverse types of custom fields available</li>\r\n<li>Create templates for displaying categories and items</li>\r\n<li>MVC structural build allows users to create overrides</li>\r\n<li>and much more...</li>\r\n</ul>', 0, 0, 1, '', '1262876869_redCOMPONENTS.jpg', '2014-12-08 15:26:01', '2015-01-12 13:47:36', 2, '', '', '', '', '', '', '', 2, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(6, 0, 1, 0, 0, 0, 0, 9, 'redSHOP', 48, 0, 0, 0, '6', 'product', '<p>redSHOP is the leading webshop solution for Joomla. You get an advanced and fully integrated native webshop, with a built-in template system allowing you to style your shop to your exact needs. A feature rich and powerful shop</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redshop\">redSHOP</a> is the perfect e-Commerce solution to use for your website. It is the most advanced webshop extension, and it is highly flexible in the built-in templating system and the site design can be adjusted without limitations</p>\r\n<p>redSHOP is the better solution whether you run a small webshop or a large scale webshop with high load.</p>\r\n<p><a href=\"http://redcomponent.com/redcomponent/redshop\">redSHOP</a> includes the following features:</p>\r\n<ul>\r\n<li>No limitations to design and templateability</li>\r\n<li>Offer gift certificates and gift wrapping</li>\r\n<li>Multiple currencies</li>\r\n<li>Add pictures in different sizes</li>\r\n<li>Multiple payment and shipping methods</li>\r\n<li>Send out newsletters and catalogues</li>\r\n<li>Statistics and Search Engine Optimization</li>\r\n<li>Create action e-mails</li>\r\n<li>Ready-to-use and easy-to-manage webshop</li>\r\n<li>Supports multiple languages</li>\r\n<li>and much more...</li>\r\n</ul>\r\n<p> </p>\r\n<p><strong>Extendable:</strong></p>\r\n<p><a href=\"http://redcomponent.com/redcomponent/redshop\">redSHOP</a> has +100 extensions, like payment plugins, shipping plugins and <a href=\"http://templates.redcomponent.com/\">redSHOP ready templates</a>.</p>', 0, 0, 1, '', 'redCOMPONENTS.jpg', '2014-12-08 15:26:01', '2015-01-12 14:36:40', 10, '', '', '', '', '', '', '', 2, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(7, 0, 1, 0, 0, 0, 0, 9, 'redCORE', 0, 0, 0, 0, '7', 'product', '<p>redCORE is a multifunctional Development Tool and library for Joomla CMS</p>', '<p><a href=\"https://github.com/redCOMPONENT-COM/redCORE\">redCORE</a> is a Rapid Application Development library for redCOMPONENT extensions..</p>\r\n<p>The main aim of redCORE is to provide a mature and abstracted layer for development that will act as a base model for any future redCOMPONENT extension beeing developed.</p>\r\n<p>There is no convention over configuration in redCORE, unlike its counterparts, because we need more complex structures and hierachy and to solve more complex problems in redCORE.</p>\r\n<p>redCORE is a quicker and more uniform way of creating extensions while adding some very interesting libraries and features to Joomla.</p>\r\n<p>redCORE is not a rapid application development tool based on conventions to automatically create output.</p>\r\n<p>redCORE based extensions is forward and backward compatible and works for Joomla 2.5 and 3.x.</p>\r\n<p>Some of the features include:</p>\r\n<ul>\r\n<li>Do More with less code</li>\r\n<li>Your extensions look and behave exactly same on J25 and J33 and all future versions of Joomla!</li>\r\n<li>Download and use redCORE in your extensions and benefit from our already tested and proven code.</li>\r\n<li>redCORE is not replacing Joomla! functionality, it is extending it.</li>\r\n<li>Built-in translation functionality</li>\r\n<li>and much much more...</li>\r\n</ul>\r\n<p> </p>\r\n<p>Read more in the <a href=\"https://github.com/redCOMPONENT-COM/redCORE/wiki\">redCORE public GitHub wiki</a></p>', 0, 0, 1, '', 'redCOMPONENTS.jpg', '2014-12-08 15:26:01', '2015-01-12 13:37:20', 4, '', '', '', '', '', '', '', 2, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(8, 0, 3, 0, 0, 0, 0, 9, 'redFASHIONSTORE', 50, 0, 0, 0, '8', 'product', '<p>redFASHIONSTORE shopping template for redSHOP and joomla</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redshop/templates/the-fashion-store\">redFASHIONSTORE</a> is a great looking template that can be used for clothing and fashion stores.</p>\r\n<p>The combination of a template that is already styled for redSHOP, and the easy to use Quickstart packages, you can have a great looking site in minutes. Ready to adjust to your specific needs.</p>\r\n<p>Try visit the <a href=\"http://templates.redcomponent.com/#redfashionstore\"><span style=\"color: #0088cc;\">demo of redFASHIONSTORE</span></a> and see the Quickstart package in action.</p>', 0, 0, 1, '', '1421055027_fashionstore-demo-400-400.jpg', '2014-12-08 15:26:01', '2015-01-12 11:40:26', 7, '', '', '', '', '', '', '', 3, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(9, 0, 3, 0, 0, 0, 0, 9, 'redVALENTINE', 50, 0, 0, 0, '9', 'product', '<p>redVALENTINE shopping template for redSHOP and joomla</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redshop/templates/valentine\"><span style=\"color: #0088cc;\">redVALENTINE</span></a> is an warm and hearthy shopping template which is perfectly suited for a giftstore or other luxury items.</p>\r\n<p>The combination of a template that is already styled for redSHOP, and the easy to use Quickstart packages, you can have a great looking site in minutes. Ready to adjust to your specific needs.</p>\r\n<p>Try visit the <a href=\"http://templates.redcomponent.com/#redvalentine\"><span style=\"color: #0088cc;\">demo of redVALENTINE</span></a> and see the Quickstart package in action.</p>', 0, 0, 1, '', '1421055894_valentine-demo-400-400.jpg', '2014-12-08 15:26:01', '2015-01-12 12:08:39', 4, '', '', '', '', '', '', '', 3, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(10, 0, 3, 0, 0, 0, 0, 9, 'redBAKERY', 50, 0, 0, 0, '10', 'product', '<p>redBAKERY is a shopping template for redSHOP and joomla that is perfectly suited for a delicious food webshop.</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redshop/templates/bakery\">redBAKERY</a> is a joomla template that include redSHOP styling, and has a tasteful food theme making it suitable for food, coffee, tea or similar shopping sites.</p>\r\n<p>The combination of a template that is already styled for redSHOP, and the easy to use Quickstart packages, you can have a great looking site in minutes. Ready to adjust to your specific needs.</p>\r\n<p>Try visit the demo of <a href=\"http://templates.redcomponent.com/#redbakery\">redBAKERY</a> and see the Quickstart package in action. </p>', 0, 0, 1, '', '1421054444_bakery-demo-400-400.jpg', '2014-12-08 15:26:01', '2015-01-12 11:25:10', 3, '', '', '', '', '', '', '', 3, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(11, 0, 3, 0, 0, 0, 0, 9, 'redCARPENTER', 50, 0, 0, 0, '11', 'product', '<p>redCARPENTER is a shopping template for redSHOP and joomla</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redshop/templates/carpenter\">redCARPENTER</a> has a stylish construction, DIY or tooling theme, that make it a good fit for a wide range of websites.</p>\r\n<p>The combination of a template that is already styled for redSHOP, and the easy to use Quickstart packages, you can have a great looking site in minutes. Ready to adjust to your specific needs.</p>\r\n<p>Try visit the <a href=\"http://templates.redcomponent.com/#redcarpenter\">demo of redCARPENTER</a> and see the Quickstart package in action.</p>', 0, 0, 1, '', '1421054762_carpenter-demo-400-400.jpg', '2014-12-08 15:26:01', '2015-01-12 11:32:15', 3, '', '', '', '', '', '', '', 3, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(12, 0, 3, 0, 0, 0, 0, 9, 'redGADGETS', 50, 0, 0, 0, '12', 'product', '<p>redGADGETS is a tech styled shopping template for redSHOP and joomla</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redshop/templates/gadgets\">redGADGETS</a> is a gadgets inspired template that is well suited for your webshops, with its visual tech appearance. Your users will love it.</p>\r\n<p>The combination of a template that is already styled for redSHOP, and the easy to use Quickstart packages, you can have a great looking site in minutes. Ready to adjust to your specific needs.</p>\r\n<p>Try visit the <a href=\"http://templates.redcomponent.com/#redgadgets\"><span style=\"color: #0088cc;\">demo of redGADGETS</span></a> and see the Quickstart package in action.</p>', 0, 0, 1, '', '1421055222_gadgets-demo-400-400.jpg', '2014-12-08 15:26:01', '2015-01-12 11:46:44', 5, '', '', '', '', '', '', '', 3, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(13, 0, 3, 0, 0, 0, 0, 9, 'redKIDSWEAR', 50, 0, 0, 0, '13', 'product', '<p>redKIDSWEAR is a shopping template for redSHOP and joomla</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redshop/templates/kidswear\">redKIDSWEAR</a> is a bright and joyful shop template, perfectly suited for a kids clothing shop.</p>\r\n<p>The combination of a template that is already styled for redSHOP, and the easy to use Quickstart packages, you can have a great looking site in minutes. Ready to adjust to your specific needs.</p>\r\n<p>Try visit the <a href=\"http://templates.redcomponent.com/#redkidswear\"><span style=\"color: #0088cc;\">demo of redKIDSWEAR</span></a> and see the Quickstart package in action.</p>', 0, 0, 1, '', '1421055392_kidswear-demo-400-400.jpg', '2014-12-08 15:26:01', '2015-01-12 11:57:02', 8, '', '', '', '', '', '', '', 3, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append'),
					(14, 0, 3, 0, 0, 0, 0, 9, 'redSHOEMANIAC', 50, 0, 0, 0, '14', 'product', '<p>redSHOEMANIAC is a shopping template for redSHOP and joomla</p>', '<p><a href=\"http://redcomponent.com/redcomponent/redshop/templates/shoemaniac\"><span style=\"color: #0088cc;\">redSHOEMANIAC</span></a> is an elegant styled shopping template which is perfectly suited for a shoestore or other accessories.</p>\r\n<p>The combination of a template that is already styled for redSHOP, and the easy to use Quickstart packages, you can have a great looking site in minutes. Ready to adjust to your specific needs.</p>\r\n<p>Try visit the <a href=\"http://templates.redcomponent.com/#redshoemaniac\"><span style=\"color: #0088cc;\">demo of redSHOEMANIAC</span></a> and see the Quickstart package in action.</p>', 0, 0, 1, '', '1421055573_shoemaniac-demo-400-400.jpg', '2014-12-08 15:26:01', '2015-01-12 12:01:33', 16, '', '', '', '', '', '', '', 3, 0.000, 0, 0, 0, '0', 0, 0, '0.00', '0.00', '0.00', '0.00', 0, 0, 0, 0, 0, 0, 0, 0, '', 0, '0000-00-00 00:00:00', 0, 0, '', '', '', '', 'global', 'append')";
		$db->setQuery($query);
		$db->execute();


		$query = "INSERT IGNORE INTO `#__redshop_product_accessory` (`accessory_id`, `product_id`, `child_product_id`, `accessory_price`, `oprand`, `setdefault_selected`, `ordering`, `category_id`) VALUES
					(21, 1, 12, 0, '-', 0, 0, 0),
					(32, 2, 3, 0, '-', 0, 0, 0),
					(33, 1, 2, 0, '-', 0, 0, 0)";
		$db->setQuery($query);
		$db->execute();

		$query = "INSERT IGNORE INTO `#__redshop_product_attribute` (`attribute_id`, `attribute_name`, `attribute_required`, `allow_multiple_selection`, `hide_attribute_price`, `product_id`, `ordering`, `attribute_set_id`, `display_type`, `attribute_published`) VALUES
						(3, 'Subscription', 0, 0, 0, 2, 0, 0, 'dropdown', 1),
						(4, 'Subscription', 1, 0, 0, 1, 0, 0, 'dropdown', 1)";
		$db->setQuery($query);
		$db->execute();

		$query = "INSERT IGNORE INTO `#__redshop_product_attribute_property` (`property_id`, `attribute_id`, `property_name`, `property_price`, `oprand`, `property_image`, `property_main_image`, `ordering`, `setdefault_selected`, `setrequire_selected`, `setmulti_selected`, `setdisplay_type`, `property_number`) VALUES
						(3, 3, '1 Year', 100, '+', '3_globus.gif', '', 0, 0, 0, 0, 'dropdown', ''),
						(4, 3, '2 Year', 100, '+', '', '', 0, 0, 0, 0, 'dropdown', ''),
						(5, 3, '3 Year', 100, '+', '', '', 0, 0, 0, 0, 'dropdown', ''),
						(6, 4, '1 Year', 125, '+', '6_11408.jpg', '', 0, 0, 0, 0, 'dropdown', ''),
						(8, 4, '2 Year', 175, '+', '', '', 1, 0, 0, 0, 'dropdown', '')";
		$db->setQuery($query);
		$db->execute();

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
				$db->execute();
			}
		}

		$query = "INSERT IGNORE INTO `#__redshop_product_category_xref` (`category_id`, `product_id`, `ordering`) VALUES
						($firstCatId, 1, 1),
						($firstCatId, 2, 2),
						($firstCatId, 3, 3),
						($firstCatId, 4, 4),
						($secondCatId, 5, 1),
						($secondCatId, 6, 2),
						($secondCatId, 7, 3),
						($thirdCatId, 8, 1),
						($thirdCatId, 9, 2),
						($thirdCatId, 10, 0),
						($thirdCatId, 11, 0),
						($thirdCatId, 12, 0),
						($thirdCatId, 13, 0),
						($thirdCatId, 14, 3)";
		$db->setQuery($query);
		$db->execute();

		/*Get the first user_id from #__redshop_users_info table then insert to userid field of demo rating content in #__redshop_product_rating table */
		$query = "SELECT user_id FROM `#__redshop_users_info` LIMIT 1";
		$db->setQuery($query);
		$first_id = $db->loadResult();
		$query    = "INSERT IGNORE INTO `#__redshop_product_rating`
					(`rating_id`, `product_id`, `title`, `comment`, `userid`, `time`, `user_rating`, `favoured`, `published`)
					VALUES (1, 1, 'high quality product', 'Flot flot flot...', " . $first_id . ", 1262695786, 4, 1, 1)";
		$db->setQuery($query);
		$db->execute();

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
				$db->setQuery($q)->execute();
			}
		}

		$query = "INSERT IGNORE INTO `#__redshop_product_related`
					(`related_id`, `product_id`) VALUES
					(0, 3),(0, 4),(0, 5),(0, 6),(0, 7),(0, 8),(0, 9),(0, 10),(0, 11),(0, 12),(0, 13),(0, 14),(1, 2),(2, 1),(3, 1),(3, 2)";
		$db->setQuery($query)->execute();

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
				$db->setQuery($q)->execute();
			}
		}

		$query = "INSERT IGNORE INTO `#__redshop_product_stockroom_xref`
					(`product_id`, `stockroom_id`, `quantity`) VALUES
					(2, 1, 100)";
		$db->setQuery($query)->execute();

		return true;
	}

	/**
	 * Get New Customers for Dashboard view
	 *
	 * @return  array New Customers.
	 */
	public function getNewcustomers()
	{
		$query = $this->_db->getQuery(true);

		$query->select('*')
			->from($this->_db->qn('#__redshop_users_info'))
			->order($this->_db->qn('users_info_id') . ' DESC');

		$this->_db->setQuery($query, 0, 10);

		return $this->_db->loadObjectlist();
	}

	/**
	 * Get New Order for Dashboard view
	 *
	 * @return  array New Order.
	 */
	public function getNeworders()
	{
		$query = $this->_db->getQuery(true);
		$query->select(
			array(
				$this->_db->qn('o.order_id'),
				$this->_db->qn('o.order_total'),
				$this->_db->qn('o.order_status'),
				$this->_db->qn('o.order_payment_status'),
				$this->_db->qn('os.order_status_name'),
				'CONCAT(' . $this->_db->qn('u.firstname') . '," ",' . $this->_db->qn('u.lastname') . ') AS name'
			)
		)
			->from($this->_db->qn('#__redshop_order_users_info', 'u'))
			->innerJoin($this->_db->qn('#__redshop_orders', 'o') . ' ON ' . $this->_db->qn('u.order_id') . '=' . $this->_db->qn('o.order_id') . ' AND ' . $this->_db->qn('u.address_type') . '="BT"')
			->innerJoin($this->_db->qn('#__redshop_order_status', 'os') . ' ON ' . $this->_db->qn('os.order_status_code') . '=' . $this->_db->qn('o.order_status'))
			->order($this->_db->qn('o.order_id') . ' DESC');

		$this->_db->setQuery($query, 0, 10);

		$rows = $this->_db->loadObjectList();

		return $rows;
	}

	public function getUser($user_id)
	{
		$this->_table_prefix = '#__';
		$userquery           = "SELECT name  FROM " . $this->_table_prefix . "users where id=" . $user_id;
		$this->_db->setQuery($userquery);

		return $this->_db->loadObject();
	}

	public function gettotalOrder($id = 0)
	{
		$this->_table_prefix = '#__redshop_';
		$userquery           = "SELECT SUM(order_total) AS order_total, count(*) AS tot_order FROM " . $this->_table_prefix . "orders "
			. "WHERE `user_info_id`='" . $id . "' ";
		$this->_db->setQuery($userquery);

		return $this->_db->loadObject();
	}

	public function gettotalAmount($user_id)
	{
		$this->_table_prefix = '#__redshop_';
		$query               = 'SELECT  SUM(o.order_total) AS order_total '
			. 'FROM ' . $this->_table_prefix . 'orders AS o '
			. 'LEFT JOIN ' . $this->_table_prefix . 'users_info as uf ON o.user_id =uf.user_id'
			. ' AND address_type LIKE "BT" '
			. 'WHERE o.user_id = ' . $user_id . ' and  (o.order_status = "C" OR o.order_status = "PR" OR o.order_status = "S")';
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	public function getavgAmount($user_id)
	{
		$this->_table_prefix = '#__redshop_';
		$query               = 'SELECT  (SUM(o.order_total)/ COUNT( DISTINCT o.user_id ) ) AS avg_order '
			. 'FROM ' . $this->_table_prefix . 'orders AS o '
			. 'WHERE o.user_id =' . $user_id . ' and (o.order_status = "C" OR o.order_status = "PR" OR o.order_status = "S") ';
		$this->_db->setQuery($query);

		return $this->_db->loadObject();
	}

	public function getUserinfo($user_id)
	{
		$this->_table_prefix = '#__redshop_';
		$userquery           = "SELECT CONCAT(firstname,' ',lastname) as name  FROM " . $this->_table_prefix .
			"users_info where address_type='BT' and user_id=" . $user_id;
		$this->_db->setQuery($userquery);

		return $this->_db->loadObject();
	}

	/**
	 * Get Statistic (Total orders, members, sales) for Dashboard view
	 *
	 * @return  array  Statistics chart.
	 */
	public function getStatisticDashboard()
	{
		$db = JFactory::getDbo();

		// Todo: We didn't use JDatabase because $query->unionAll() is not working, please change to use $query->unionAll() when Joomla fixed it
		$query = 'SELECT SUM(' . $db->qn('order_total') . ') AS total
			FROM ' . $db->qn('#__redshop_orders') . '
			WHERE (' . $db->qn('order_status') . ' = ' . $db->q('C')
			. ' OR '
			. $db->qn('order_status') . ' = ' . $db->q('PR')
			. ' OR '
			. $db->qn('order_status') . ' = ' . $db->q('S') . ')
			UNION ALL (
				SELECT COUNT(' . $db->qn('order_id') . ')
				FROM ' . $db->qn('#__redshop_orders') . '
			)
			UNION ALL (
				SELECT COUNT(' . $db->qn('users_info_id') . ')
				FROM ' . $db->qn('#__redshop_users_info') . '
			)
			UNION ALL (
			SELECT COUNT(' . $db->qn('id') . ')
			FROM ' . $db->qn('#__redshop_siteviewer') . ')';

		$db->setQuery($query);

		return $db->loadColumn();
	}
}
