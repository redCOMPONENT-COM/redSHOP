<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class Redshop Helper Left_Menu
 *
 * @since  2.0.0.2
 */
class RedshopMenuLeft_Menu
{
	/**
	 * @var  null
	 */
	protected static $view = null;

	/**
	 * @var  null
	 */
	protected static $layout = null;

	/**
	 * @var  RedshopMenu
	 */
	protected static $menu = null;

	/**
	 * Method for render left menu
	 *
	 * @param   bool $disableMenu True for return list of menu. False for return HTML rendered code.
	 *
	 * @return  mixed               Array of menu / HTML code of menu.
	 */
	public static function render($disableMenu = false)
	{
		self::$view   = JFactory::getApplication()->input->getString('view', '');
		self::$layout = JFactory::getApplication()->input->getString('layout', '');

		$active = self::getActive();

		if (is_null(self::$menu))
		{
			self::$menu = new RedshopMenu;

			self::setProductGroup();
			self::setShop();
			self::setOrderGroup();

			if (Redshop::getConfig()->get('USE_STOCKROOM') == 1)
			{
				self::setStockroomGroup();
			}

			self::setDiscountGroup();
			self::setCommunicationGroup();
			self::setShippingGroup();
			self::setUserGroup();
			self::setIEGroup();
			self::setCustomisationGroup();
			self::setCustomerInputGroup();
			self::setAccountGroup();
			self::setStatisticsGroup();
			self::setConfigGroup();

			if ($disableMenu)
			{
				return self::$menu->items;
			}
		}

		return RedshopLayoutHelper::render(
			'component.full.sidebar.menu',
			array(
				'items'  => self::$menu->items,
				'active' => $active
			)
		);
	}

	/**
	 * Get active view
	 *
	 * @return  array  List of active group and view
	 */
	protected static function getActive()
	{
		switch (self::$view)
		{
			case "product":
			case "product_detail":
			case "prices":
				return array('PRODUCT_LISTING', 'product');
				break;

			case "categories":
				return array('SHOP', 'categories');
				break;

			case "manufacturer":
				return array('PRODUCT_LISTING', 'manufacturer');
				break;

			case "media":
			case 'media_detail':
				return array('SHOP', 'media');
				break;

			/*
			 @TODO: Enable this menu when Product Variants ready
			case "attributes":
			*/
			case "attribute_detail":
				return array('PRODUCT_MANAGEMENT', 'attribute');
				break;

			case "order":
			case "order_detail":
			case "addorder_detail":
			case "opsearch":
			case "barcode":
				return array('ORDER', 'order');
				break;

			case "order_status":
			case "order_statuses":
				return array('ORDER', 'order_status');
				break;

			case "quotation":
			case "addquotation_detail":
				return array('ORDER', 'quotation');
				break;

			case "stockroom":
			case "stockroom_listing":
			case "stockimage":
				return array('STOCKROOM', 'stockroom');
				break;

			case "suppliers":
			case "supplier":
				return array('PRODUCT_LISTING', 'suppliers');
			case "discount":
			case "discount_detail":
			case "mass_discounts":
			case "mass_discount":
				return array('DISCOUNT', 'discount');
				break;

			case "giftcards":
			case "giftcard":
				return array('DISCOUNT', 'giftcards');
				break;

			case "vouchers":
			case "voucher":
				return array('DISCOUNT', 'voucher');
				break;

			case "coupon":
			case "coupon_detail":
				return array('DISCOUNT', 'coupon');
				break;

			case "mail":
			case "mails":
				return array('COMMUNICATION', 'mail');
				break;

			case "newsletter":
			case "newsletter_detail":
			case "newslettersubscr":
			case 'newslettersubscr_detail':
				return array('COMMUNICATION', 'newsletter');
				break;

			case "shipping":
			case "shipping_detail":
			case "shipping_rate":
				return array('SHIPPING', 'shipping_method');
				break;

			case "shipping_box":
			case "shipping_box_detail":
				return array('SHIPPING', 'shipping_box');
				break;

			case "wrapper":
			case "wrapper_detail":
				return array('SHIPPING', 'wrapper');
				break;

			case "user":
			case 'user_detail':
			case "shopper_group":
			case "shopper_group_detail":
				return array('USER', 'user');
				break;

			case "tax_groups":
			case "tax_group":
				return array('PRODUCT_LISTING', 'tax_groups');
				break;

			case "tax_rates":
			case "tax_rate":
				return array('PRODUCT_LISTING', 'tax_rate');
				break;

			case "currency":
			case "currency_detail":
				return array('CUSTOMIZATION', 'currency');
				break;

			case "countries":
			case "country":
				return array('CUSTOMIZATION', 'country');
				break;

			case "states":
			case "state":
				return array('CUSTOMIZATION', 'state');
				break;

			case "zipcode":
			case "zipcode_detail":
				return array('CUSTOMIZATION', 'zipcode');
				break;

			case "importexport":
			case "import":
			case "export":
			case "vmimport":
				return array('IMPORT_EXPORT', 'importexport');
				break;

			case "xmlimport":
			case "xmlexport":
				return array('IMPORT_EXPORT', 'xmlimportexport');
				break;

			case "fields":
			case "fields_detail":
			case "addressfields_listing":
				return array('CUSTOMIZATION', 'fields');
				break;

			case "template":
			case "template_detail":
				return array('CUSTOMIZATION', 'template');
				break;

			case "textlibrary":
			case "textlibrary_detail":
				return array('CUSTOMIZATION', 'textlibrary');
				break;

			case "catalog":
			case "catalog_request":
				return array('CUSTOMIZATION', 'catalog');
				break;

			case "sample":
			case "sample_request":
				return array('CUSTOMIZATION', 'sample');
				break;

			case "producttags":
			case "producttags_detail":
				return array('CUSTOMIZATION', 'producttags');
				break;

			case "attribute_set":
			case "attribute_set_detail":
				return array('CUSTOMIZATION', 'attribute_set');
				break;

			case "questions":
			case "question":
				return array('CUSTOMER_INPUT', 'question');
				break;

			case "rating":
			case "rating_detail":
				return array('CUSTOMER_INPUT', 'rating');
				break;

			case "accountgroup":
			case "accountgroup_detail":
				return array('ACCOUNTING', 'accountgroup');
				break;

			case "statistic_customer":
			case 'statistic':
			case "statistic_order":
			case "statistic_product":
				/**
				 * @TODO: Would enable these statistic when done.
				 * case "statistic_quotation":
				 * case "statistic_variant":
				 */
				return array('STATISTIC', 'statistic');
				break;

			case "configuration":
			case 'update':
			case "access":
				return array('CONFIG', 'configuration');
				break;

			default:
				return array('', '');
				break;
		}
	}

	/**
	 * Set Shop menu
	 *
	 * @return  void
	 */
	protected static function setShop()
	{
		self::$menu->addHeaderItem(
			'index.php?option=com_redshop&view=categories',
			'COM_REDSHOP_CATEGORY_LISTING',
			(self::$view == 'categories') ? true : false,
			null,
			'fa fa-sitemap'
		)
			->addHeaderItem(
				'index.php?option=com_redshop&view=media',
				'COM_REDSHOP_MEDIA_LISTING',
				(self::$view == 'media') ? true : false,
				null,
				'fa fa-picture-o'
			);
	}

	/**
	 * Set Product Group menu
	 *
	 * @return  void
	 */
	protected static function setProductGroup()
	{
		self::$menu->section('product')
			->title('COM_REDSHOP_PRODUCTS')
			->addItem(
				'index.php?option=com_redshop&view=product',
				'COM_REDSHOP_PRODUCT_MANAGEMENT',
				(self::$view == 'product' && self::$layout == '') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=product&layout=listing',
				'COM_REDSHOP_PRODUCT_PRICE_VIEW',
				(self::$view == 'product' && self::$layout == 'listing') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=manufacturer',
				'COM_REDSHOP_MANUFACTURER_LISTING',
				(self::$view == 'manufacturer') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=suppliers',
				'COM_REDSHOP_SUPPLIER_LISTING',
				(self::$view == 'suppliers') ? true : false
			);

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && JPluginHelper::isEnabled('economic'))
		{
			self::$menu->addItem(
				'index.php?option=com_redshop&view=product&layout=importproduct',
				'COM_REDSHOP_IMPORT_PRODUCTS_TO_ECONOMIC',
				(self::$view == 'product' && self::$layout == 'importproduct') ? true : false
			);

			if (Redshop::getConfig()->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') == 1)
			{
				self::$menu->addItem(
					'index.php?option=com_redshop&view=product&layout=importattribute',
					'COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC',
					(self::$view == 'product' && self::$layout == 'importattribute') ? true : false
				);
			}
		}

		self::$menu->addItem(
			'index.php?option=com_redshop&view=tax_groups',
			'COM_REDSHOP_TAX_GROUP_LISTING',
			(self::$view == 'tax_groups') ? true : false
		)
			->addItem(
				'index.php?option=com_redshop&view=tax_rates',
				'COM_REDSHOP_TAX_RATES_SIDEBAR',
				(self::$view == 'tax_rates') ? true : false
			);

		/**
		 * @TODO: Enable when Product Variants ready
		 * self::setAttributes();
		 */

		self::$menu->group('PRODUCT_LISTING');
	}

	/**
	 * Set Order Group menu
	 *
	 * @return  void
	 */
	protected static function setOrderGroup()
	{
		self::$menu->section('order')
			->title('COM_REDSHOP_ORDER')
			->addItem(
				'index.php?option=com_redshop&view=order',
				'COM_REDSHOP_ORDER_LISTING',
				(self::$view == 'order' && self::$layout == '') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=order&layout=labellisting',
				'COM_REDSHOP_DOWNLOAD_LABEL',
				(self::$view == 'order' && self::$layout == 'labellisting') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=order_statuses',
				'COM_REDSHOP_ORDERSTATUS_LISTING',
				(self::$view == 'order_statuses') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=opsearch',
				'COM_REDSHOP_PRODUCT_ORDER_SEARCH',
				(self::$view == 'opsearch') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=quotation',
				'COM_REDSHOP_QUOTATION_LISTING',
				(self::$view == 'quotation') ? true : false
			);

		self::$menu->group('ORDER');
	}

	/**
	 * Set Stockroom Group menu
	 *
	 * @return  void
	 */
	protected static function setStockroomGroup()
	{
		self::setStockroom();
		self::$menu->group('STOCKROOM');
	}

	/**
	 * Set Discount Group menu
	 *
	 * @return  void
	 */
	protected static function setDiscountGroup()
	{
		self::$menu->section('discount')
			->title('COM_REDSHOP_DISCOUNT')
			->addItem(
				'index.php?option=com_redshop&view=mass_discounts',
				'COM_REDSHOP_MASS_DISCOUNT',
				(self::$view == 'mass_discounts') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=discount',
				'COM_REDSHOP_DISCOUNT_LISTING',
				(self::$view == 'discount' && self::$layout == '') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=discount&layout=product',
				'COM_REDSHOP_DISCOUNT_PRODUCT_LISTING',
				(self::$view == 'discount' && self::$layout == 'product') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=giftcards',
				'COM_REDSHOP_GIFTCARD_LISTING',
				(self::$view == 'giftcards') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=vouchers',
				'COM_REDSHOP_VOUCHER_LISTING',
				(self::$view == 'vouchers') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=coupon',
				'COM_REDSHOP_COUPON_LISTING',
				(self::$view == 'coupon') ? true : false
			)
			->group('DISCOUNT');
	}

	/**
	 * Set Communication Group menu
	 *
	 * @return  void
	 */
	protected static function setCommunicationGroup()
	{
		self::$menu->section('communication')
			->title('COM_REDSHOP_COMMUNICATION')
			->addItem(
				'index.php?option=com_redshop&view=mails',
				'COM_REDSHOP_MAIL_CENTER_LISTING',
				(self::$view == 'mails') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=newsletter',
				'COM_REDSHOP_NEWSLETTER_LISTING',
				(self::$view == 'newsletter') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=newslettersubscr',
				'COM_REDSHOP_NEWSLETTER_SUBSCR_LISTING',
				(self::$view == 'newslettersubscr') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=newsletter_detail&layout=statistics',
				'COM_REDSHOP_NEWSLETTER_STATISTICS',
				(self::$view == 'newsletter_detail' && self::$layout == 'statistics') ? true : false
			)
			->group('COMMUNICATION');
	}

	/**
	 * Set Shipping Group menu
	 *
	 * @return  void
	 */
	protected static function setShippingGroup()
	{
		self::$menu->section('shipping')
			->title('COM_REDSHOP_SHIPPING')
			->addItem(
				'index.php?option=com_redshop&view=shipping',
				'COM_REDSHOP_SHIPPING_METHOD_LISTING',
				(self::$view == 'shipping') ? true : false
			);

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') == 1 && JPluginHelper::isEnabled('economic'))
		{
			self::$menu->addItem(
				'index.php?option=com_redshop&view=shipping&task=importeconomic',
				'COM_REDSHOP_IMPORT_RATES_TO_ECONOMIC'
			);
		}

		self::$menu->addItem(
			'index.php?option=com_redshop&view=shipping_box',
			'COM_REDSHOP_SHIPPING_BOXES',
			(self::$view == 'shipping_box') ? true : false
		)
			->addItem(
				'index.php?option=com_redshop&view=wrapper',
				'COM_REDSHOP_WRAPPER_LISTING',
				(self::$view == 'wrapper') ? true : false
			)
			->group('SHIPPING');
	}

	/**
	 * Set User Group menu
	 *
	 * @return  void
	 */
	protected static function setUserGroup()
	{
		self::$menu->section('user')
			->title('COM_REDSHOP_USER')
			->addItem(
				'index.php?option=com_redshop&view=user',
				'COM_REDSHOP_USER_LISTING',
				(self::$view == 'user') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=user_detail',
				'COM_REDSHOP_ADD_USER',
				(self::$view == 'user_detail') ? true : false
			)
			->addItem(
				'javascript:userSync();',
				'COM_REDSHOP_USER_SYNC'
			)
			->addItem(
				'index.php?option=com_redshop&view=shopper_group',
				'COM_REDSHOP_SHOPPER_GROUP_LISTING',
				(self::$view == 'shopper_group') ? true : false
			);

		JFactory::getDocument()->addScriptDeclaration('
			function userSync() {
				if (confirm("' . JText::_('COM_REDSHOP_DO_YOU_WANT_TO_SYNC') . '") == true)
					window.location = "index.php?option=com_redshop&view=user&sync=1";
			}'
		);

		self::$menu->group('USER');
	}

	/**
	 * Set Import / Export Group menu
	 *
	 * @return  void
	 */
	protected static function setIEGroup()
	{
		self::$menu->section('import')
			->title('COM_REDSHOP_IMPORT_EXPORT')
			->addItem(
				'index.php?option=com_redshop&view=import',
				'COM_REDSHOP_DATA_IMPORT',
				(self::$view == 'import') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=export',
				'COM_REDSHOP_DATA_EXPORT',
				(self::$view == 'export') ? true : false
			)
			->addItem(
				'javascript:vmImport();',
				'COM_REDSHOP_IMPORT_FROM_VM'
			)
			->addItem(
				'index.php?option=com_redshop&view=xmlimport',
				'COM_REDSHOP_XML_IMPORT',
				(self::$view == 'xmlimport') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=xmlexport',
				'COM_REDSHOP_XML_EXPORT',
				(self::$view == 'xmlexport') ? true : false
			);

		JFactory::getDocument()->addScriptDeclaration('
			function vmImport() {
				if (confirm("' . JText::_('COM_REDSHOP_DO_YOU_WANT_TO_IMPORT_VM') . '") == true)
					window.location = "index.php?option=com_redshop&view=import&vm=1";
			}'
		);

		self::$menu->group('IMPORT_EXPORT');
	}

	/**
	 * Set Customization Group menu
	 *
	 * @return  void
	 */
	protected static function setCustomisationGroup()
	{
		self::$menu->section('custom')
			->title('COM_REDSHOP_CUSTOMIZATION')
			->addItem(
				'index.php?option=com_redshop&view=fields',
				'COM_REDSHOP_FIELDS_LISTING',
				(self::$view == 'fields') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=template',
				'COM_REDSHOP_TEMPLATE_LISTING',
				(self::$view == 'template') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=textlibrary',
				'COM_REDSHOP_TEXT_LIBRARY_LISTING',
				(self::$view == 'textlibrary') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=catalog',
				'COM_REDSHOP_CATALOG',
				(self::$view == 'catalog') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=sample',
				'COM_REDSHOP_CATALOG_PRODUCT_SAMPLE',
				(self::$view == 'sample') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=sample_request',
				'COM_REDSHOP_SAMPLE_REQUEST',
				(self::$view == 'sample_request') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=producttags',
				'COM_REDSHOP_TAGS_LISTING',
				(self::$view == 'producttags') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=attribute_set',
				'COM_REDSHOP_ATTRIBUTE_SET_LISTING',
				(self::$view == 'attribute_set') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=currency',
				'COM_REDSHOP_CURRENCY_LISTING',
				(self::$view == 'currency') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=countries',
				'COM_REDSHOP_COUNTRY_LISTING',
				(self::$view == 'countries') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=states',
				'COM_REDSHOP_STATE_LISTING',
				(self::$view == 'states') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=zipcode',
				'COM_REDSHOP_ZIPCODE_LISTING',
				(self::$view == 'zipcode') ? true : false
			)
			->group('CUSTOMIZATION');
	}

	/**
	 * Set Customer Group menu
	 *
	 * @return  void
	 */
	protected static function setCustomerInputGroup()
	{
		self::$menu->section('questions')
			->title('COM_REDSHOP_CUSTOMER_INPUT')
			->addItem(
				'index.php?option=com_redshop&view=questions',
				'COM_REDSHOP_QUESTION_LISTING',
				(self::$view == 'question') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=rating',
				'COM_REDSHOP_RATING_REVIEW',
				(self::$view == 'rating') ? true : false
			);

		self::$menu->group('CUSTOMER_INPUT');
	}

	/**
	 * Set Account Group menu
	 *
	 * @return  void
	 */
	protected static function setAccountGroup()
	{
		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') && JPluginHelper::isEnabled('economic'))
		{
			self::$menu->section('accountgroup')
				->title('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP')
				->addItem(
					'index.php?option=com_redshop&view=accountgroup',
					'COM_REDSHOP_ACCOUNTGROUP_LISTING',
					(self::$view == 'accountgroup') ? true : false
				)
				->addItem(
					'index.php?option=com_redshop&view=accountgroup_detail',
					'COM_REDSHOP_ADD_ACCOUNTGROUP',
					(self::$view == 'accountgroup_detail') ? true : false
				);

			self::$menu->group('ACCOUNTING');
		}
	}

	/**
	 * Set Statistics Group menu
	 *
	 * @return  void
	 */
	protected static function setStatisticsGroup()
	{
		self::$menu->section('statistic')
			->title('COM_REDSHOP_STATISTIC')
			->addItem(
				'index.php?option=com_redshop&view=statistic_customer',
				'COM_REDSHOP_STATISTIC_CUSTOMER',
				(self::$view == 'statistic_customer' && self::$layout == '') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic_order',
				'COM_REDSHOP_STATISTIC_ORDER',
				(self::$view == 'statistic_order' && self::$layout == '') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic_product',
				'COM_REDSHOP_STATISTIC_PRODUCT',
				(self::$view == 'statistic_product' && self::$layout == '') ? true : false
			)
			/**
			 * @TODO: Enable this menu when done.
			 */
			/*
			->addItem(
				'index.php?option=com_redshop&view=statistic_variant',
				'COM_REDSHOP_STATISTIC_PRODUCT_VARIANT',
				(self::$view == 'statistic_variant' && self::$layout == '') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic_quotation',
				'COM_REDSHOP_STATISTIC_QUOTATION',
				(self::$view == 'statistic_quotation' && self::$layout == '') ? true : false
			)
			*/
			->addItem(
				'index.php?option=com_redshop&view=statistic',
				'COM_REDSHOP_TOTAL_VISITORS',
				(self::$view == 'statistic' && self::$layout == '') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=pageview',
				'COM_REDSHOP_TOTAL_PAGEVIEWERS',
				(self::$view == 'statistic' && self::$layout == 'pageview') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=turnover',
				'COM_REDSHOP_TOTAL_TURNOVER',
				(self::$view == 'statistic' && self::$layout == 'turnover') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=avrgorder',
				'COM_REDSHOP_AVG_ORDER_AMOUNT_CUSTOMER',
				(self::$view == 'statistic' && self::$layout == 'avrgorder') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=amountorder',
				'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_ORDER',
				(self::$view == 'statistic' && self::$layout == 'amountorder') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=amountprice',
				'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_PRICE_PER_ORDER',
				(self::$view == 'statistic' && self::$layout == 'amountprice') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=amountspent',
				'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_SPENT_IN_TOTAL',
				(self::$view == 'statistic' && self::$layout == 'amountspent') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=bestsell',
				'COM_REDSHOP_BEST_SELLERS',
				(self::$view == 'statistic' && self::$layout == 'bestsell') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=popularsell',
				'COM_REDSHOP_MOST_VISITED_PRODUCTS',
				(self::$view == 'statistic' && self::$layout == 'popularsell') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=newprod',
				'COM_REDSHOP_NEWEST_PRODUCTS',
				(self::$view == 'statistic' && self::$layout == 'newprod') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=neworder',
				'COM_REDSHOP_NEWEST_ORDERS',
				(self::$view == 'statistic' && self::$layout == 'neworder') ? true : false
			);

		self::$menu->group('STATISTIC');
	}

	/**
	 * Method for set configuration group
	 *
	 * @return void
	 */
	protected static function setConfigGroup()
	{
		self::$menu->section('configuration')
			->title('COM_REDSHOP_CONFIG')
			->addItem(
				'index.php?option=com_redshop&view=configuration',
				'COM_REDSHOP_RESHOP_CONFIGURATION',
				(self::$view == 'configuration' && self::$layout == '') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=configuration&layout=resettemplate',
				'COM_REDSHOP_RESET_TEMPLATE_LBL',
				(self::$view == 'configuration' && self::$layout == 'resettemplate') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=access',
				'COM_REDSHOP_ACCESS_MANAGER',
				(self::$view == 'access') ? true : false
			)
			->addItem(
				'index.php?option=com_config&view=component&component=com_redshop',
				'COM_REDSHOP_BACKEND_ACCESS_CONFIG',
				false
			);

		self::$menu->group('CONFIG');
	}

	/**
	 * Set Stockroom menu
	 *
	 * @return  void
	 */
	protected static function setStockroom()
	{
		if (Redshop::getConfig()->get('USE_STOCKROOM') == 0)
		{
			return;
		}

		self::$menu->section('stockroom')
			->title('COM_REDSHOP_STOCKROOM')
			->addItem(
				'index.php?option=com_redshop&view=stockroom',
				'COM_REDSHOP_STOCKROOM_LISTING',
				(self::$view == 'stockroom') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=stockroom_listing',
				'COM_REDSHOP_STOCKROOM_AMOUNT_LISTING',
				(self::$view == 'stockroom_listing') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=stockimage',
				'COM_REDSHOP_STOCKIMAGE_LISTING',
				(self::$view == 'stockimage') ? true : false
			);

		if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION') && JPluginHelper::isEnabled('economic'))
		{
			self::$menu->addItem(
				'index.php?option=com_redshop&view=stockroom_detail&layout=importstock',
				'COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC',
				(self::$view == 'stockroom_detail' && self::$layout == 'importstock') ? true : false
			);
		}
	}
}
