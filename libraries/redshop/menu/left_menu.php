<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 * @var null
	 */
	protected static $view;

	/**
	 * @var null
	 */
	protected static $layout;

	/**
	 * @var  RedshopMenu
	 */
	protected static $menu;

	/**
	 * Method for render left menu
	 *
	 * @param   bool  $disableMenu  True for return list of menu. False for return HTML rendered code.
	 *
	 * @return  mixed                Array of menu / HTML code of menu.
	 * @throws  Exception
	 */
	public static function render($disableMenu = false)
	{
		self::$view   = JFactory::getApplication()->input->getString('view', '');
		self::$layout = JFactory::getApplication()->input->getString('layout', '');

		$active = self::getActive();

		if (self::$menu === null)
		{
			self::$menu = new RedshopMenu;

			self::setProductGroup();
			self::setShop();
			self::setOrderGroup();

			if (Redshop::getConfig()->getBool('USE_STOCKROOM'))
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
			self::setTool();

			JPluginHelper::importPlugin('redshop_sidebar');
			RedshopHelperUtility::getDispatcher()->trigger('onSidebarMenuPrepare', array(&self::$menu));

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
		$result = array();

		JPluginHelper::importPlugin('redshop_sidebar');
		RedshopHelperUtility::getDispatcher()->trigger('onSideBarGetActive', array(self::$view, &$result));

		if (!empty($result))
		{
			return $result;
		}

		switch (self::$view)
		{
			case 'product':
			case 'product_detail':
			case 'prices':
				return array('PRODUCT_LISTING', 'product');

			case "categories":
				return array('SHOP', 'categories');

			case "manufacturer":
			case "manufacturers":
				return array('PRODUCT_LISTING', 'manufacturer');

			case "media":
			case 'media_detail':
				return array('SHOP', 'media');

			/*
			 @TODO: Enable this menu when Product Variants ready
			case "attributes":
			*/
			case 'attribute_detail':
				return array('PRODUCT_MANAGEMENT', 'attribute');

			case "order":
			case "order_detail":
			case "addorder_detail":
			case "opsearch":
			case "barcode":
				return array('ORDER', 'order');

			case "order_status":
			case "order_statuses":
				return array('ORDER', 'order_status');

			case "quotation":
			case "addquotation_detail":
				return array('ORDER', 'quotation');

			case "stockroom":
			case "stockroom_listing":
			case "stockimage":
				return array('STOCKROOM', 'stockroom');

			case "suppliers":
			case "supplier":
				return array('PRODUCT_LISTING', 'suppliers');

			case "discount":
			case "discounts":
			case "discount_product":
			case "discount_products":
			case "mass_discounts":
			case "mass_discount":
				return array('DISCOUNT', 'discount');

			case "giftcards":
			case "giftcard":
				return array('DISCOUNT', 'giftcards');

			case "vouchers":
			case "voucher":
				return array('DISCOUNT', 'voucher');

			case "coupons":
			case "coupon":
				return array('DISCOUNT', 'coupon');

			case "mail":
			case "mails":
				return array('COMMUNICATION', 'mail');

			case "newsletter":
			case "newsletter_detail":
			case "newslettersubscr":
			case 'newslettersubscr_detail':
				return array('COMMUNICATION', 'newsletter');

			case "shipping":
			case "shipping_detail":
			case "shipping_rate":
				return array('SHIPPING', 'shipping_method');

			case "shipping_box":
			case "shipping_boxes":
				return array('SHIPPING', 'shipping_boxes');

			case "wrapper":
			case "wrapper_detail":
				return array('SHIPPING', 'wrapper');

			case "user":
			case 'user_detail':
			case "shopper_group":
			case "shopper_group_detail":
				return array('USER', 'user');

			case "tax_groups":
			case "tax_group":
				return array('PRODUCT_LISTING', 'tax_groups');

			case "tax_rates":
			case "tax_rate":
				return array('PRODUCT_LISTING', 'tax_rate');

			case "currencies":
			case "currency":
				return array('CUSTOMIZATION', 'currencies');

			case "countries":
			case "country":
				return array('CUSTOMIZATION', 'country');

			case "states":
			case "state":
				return array('CUSTOMIZATION', 'state');

			case "zipcode":
			case "zipcodes":
				return array('CUSTOMIZATION', 'zipcode');

			case "importexport":
			case "import":
			case "export":
			case "import_vm":
				return array('IMPORT_EXPORT', 'importexport');

			case "xmlimport":
			case "xmlexport":
				return array('IMPORT_EXPORT', 'xmlimportexport');

			case "fields":
			case "field":
				return array('CUSTOMIZATION', 'fields');

			case "field_groups":
			case "field_group":
				return array('CUSTOMIZATION', 'field_group');

			case "template":
			case "templates":
				return array('CUSTOMIZATION', 'template');

			case "texts":
			case "text":
				return array('CUSTOMIZATION', 'texts');

			case "catalog":
			case "catalog_request":
				return array('CUSTOMIZATION', 'catalogs');

			case "sample":
			case "sample_request":
				return array('CUSTOMIZATION', 'sample');

			case "producttags":
			case "producttags_detail":
				return array('CUSTOMIZATION', 'producttags');

			case "attribute_set":
			case "attribute_set_detail":
				return array('CUSTOMIZATION', 'attribute_set');

			case "questions":
			case "question":
				return array('CUSTOMER_INPUT', 'question');

			case "rating":
			case "rating_detail":
				return array('CUSTOMER_INPUT', 'rating');

			case "accountgroup":
			case "accountgroup_detail":
				return array('ACCOUNTING', 'accountgroup');

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

			case "tool_image":
			case "tool_update":
				return array('TOOLS', 'tools');

			case "configuration":
			case 'update':
			case "access":
				return array('CONFIG', 'configuration');

			default:
				return array('', '');
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
			self::$view === 'categories',
			null,
			'fa fa-sitemap'
		)
			->addHeaderItem(
				'index.php?option=com_redshop&view=media',
				'COM_REDSHOP_MEDIA_LISTING',
				self::$view === 'media',
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
				self::$view === 'product' && self::$layout === ''
			)
			->addItem(
				'index.php?option=com_redshop&view=product&layout=listing',
				'COM_REDSHOP_PRODUCT_PRICE_VIEW',
				self::$view === 'product' && self::$layout === 'listing'
			)
			->addItem(
				'index.php?option=com_redshop&view=manufacturers',
				'COM_REDSHOP_MANUFACTURER_LISTING',
				self::$view === 'manufacturers'
			)
			->addItem(
				'index.php?option=com_redshop&view=suppliers',
				'COM_REDSHOP_SUPPLIER_LISTING',
				self::$view === 'suppliers'
			);

		if (JPluginHelper::isEnabled('economic') && Redshop::getConfig()->getBool('ECONOMIC_INTEGRATION'))
		{
			self::$menu->addItem(
				'index.php?option=com_redshop&view=product&layout=importproduct',
				'COM_REDSHOP_IMPORT_PRODUCTS_TO_ECONOMIC',
				self::$view === 'product' && self::$layout === 'importproduct'
			);

			if (Redshop::getConfig()->getBool('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC'))
			{
				self::$menu->addItem(
					'index.php?option=com_redshop&view=product&layout=importattribute',
					'COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC',
					self::$view === 'product' && self::$layout === 'importattribute'
				);
			}
		}

		self::$menu->addItem(
			'index.php?option=com_redshop&view=tax_groups',
			'COM_REDSHOP_TAX_GROUP_LISTING',
			self::$view === 'tax_groups'
		)
			->addItem(
				'index.php?option=com_redshop&view=tax_rates',
				'COM_REDSHOP_TAX_RATES_SIDEBAR',
				self::$view === 'tax_rates'
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
				self::$view === 'order' && self::$layout === ''
			)
			->addItem(
				'index.php?option=com_redshop&view=order&layout=labellisting',
				'COM_REDSHOP_DOWNLOAD_LABEL',
				self::$view === 'order' && self::$layout === 'labellisting'
			)
			->addItem(
				'index.php?option=com_redshop&view=order_statuses',
				'COM_REDSHOP_ORDERSTATUS_LISTING',
				self::$view === 'order_statuses'
			)
			->addItem(
				'index.php?option=com_redshop&view=opsearch',
				'COM_REDSHOP_PRODUCT_ORDER_SEARCH',
				self::$view === 'opsearch'
			)
			->addItem(
				'index.php?option=com_redshop&view=quotation',
				'COM_REDSHOP_QUOTATION_LISTING',
				self::$view === 'quotation'
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
				self::$view === 'mass_discounts'
			)
			->addItem(
				'index.php?option=com_redshop&view=discounts',
				'COM_REDSHOP_DISCOUNT_LISTING',
				self::$view === 'discounts'
			)
			->addItem(
				'index.php?option=com_redshop&view=discount_products',
				'COM_REDSHOP_DISCOUNT_PRODUCT_LISTING',
				self::$view === 'discount_products'
			)
			->addItem(
				'index.php?option=com_redshop&view=giftcards',
				'COM_REDSHOP_GIFTCARD_LISTING',
				self::$view === 'giftcards'
			)
			->addItem(
				'index.php?option=com_redshop&view=vouchers',
				'COM_REDSHOP_VOUCHER_LISTING',
				self::$view === 'vouchers'
			)
			->addItem(
				'index.php?option=com_redshop&view=coupons',
				'COM_REDSHOP_COUPON_LISTING',
				self::$view === 'coupons'
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
				self::$view === 'mails'
			)
			->addItem(
				'index.php?option=com_redshop&view=newsletter',
				'COM_REDSHOP_NEWSLETTER_LISTING',
				self::$view === 'newsletter'
			)
			->addItem(
				'index.php?option=com_redshop&view=newslettersubscr',
				'COM_REDSHOP_NEWSLETTER_SUBSCR_LISTING',
				self::$view === 'newslettersubscr'
			)
			->addItem(
				'index.php?option=com_redshop&view=newsletter_detail&layout=statistics',
				'COM_REDSHOP_NEWSLETTER_STATISTICS',
				self::$view === 'newsletter_detail' && self::$layout === 'statistics'
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
				self::$view === 'shipping'
			);

		if (JPluginHelper::isEnabled('economic') && Redshop::getConfig()->getBool('ECONOMIC_INTEGRATION'))
		{
			self::$menu->addItem(
				'index.php?option=com_redshop&view=shipping&task=importeconomic',
				'COM_REDSHOP_IMPORT_RATES_TO_ECONOMIC'
			);
		}

		self::$menu->addItem(
			'index.php?option=com_redshop&view=shipping_boxes',
			'COM_REDSHOP_SHIPPING_BOXES',
			self::$view === 'shipping_boxes'
		)
			->addItem(
				'index.php?option=com_redshop&view=wrapper',
				'COM_REDSHOP_WRAPPER_LISTING',
				self::$view === 'wrapper'
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
				self::$view === 'user'
			)
			->addItem(
				'index.php?option=com_redshop&view=user_detail',
				'COM_REDSHOP_ADD_USER',
				self::$view === 'user_detail'
			)
			->addItem(
				'javascript:userSync();',
				'COM_REDSHOP_USER_SYNC'
			)
			->addItem(
				'index.php?option=com_redshop&view=shopper_group',
				'COM_REDSHOP_SHOPPER_GROUP_LISTING',
				self::$view === 'shopper_group'
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
				self::$view === 'import'
			)
			->addItem(
				'index.php?option=com_redshop&view=export',
				'COM_REDSHOP_DATA_EXPORT',
				self::$view === 'export'
			)
			->addItem(
				'index.php?option=com_redshop&view=xmlimport',
				'COM_REDSHOP_XML_IMPORT',
				self::$view === 'xmlimport'
			)
			->addItem(
				'index.php?option=com_redshop&view=xmlexport',
				'COM_REDSHOP_XML_EXPORT',
				self::$view === 'xmlexport'
			);

		if (JComponentHelper::isInstalled('com_virtuemart') && JComponentHelper::isEnabled('com_virtuemart'))
		{
			self::$menu->addItem(
				'index.php?option=com_redshop&view=import_vm',
				'COM_REDSHOP_IMPORT_FROM_VM',
				self::$view === 'import_vm'
			);

			JFactory::getDocument()->addScriptDeclaration(
				'function vmImport() {
					if (confirm("' . JText::_('COM_REDSHOP_DO_YOU_WANT_TO_IMPORT_VM') . '") == true)
						window.location = "index.php?option=com_redshop&view=import_vm";
				}'
			);
		}

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
				self::$view === 'fields'
			)
			->addItem(
				'index.php?option=com_redshop&view=field_groups',
				'COM_REDSHOP_FIELD_GROUP_MANAGEMENT',
				self::$view === 'field_groups'
			)
			->addItem(
				'index.php?option=com_redshop&view=templates',
				'COM_REDSHOP_TEMPLATE_LISTING',
				self::$view === 'templates'
			)
			->addItem(
				'index.php?option=com_redshop&view=texts',
				'COM_REDSHOP_TEXT_LIBRARY_LISTING',
				self::$view === 'texts'
			)
			->addItem(
				'index.php?option=com_redshop&view=catalogs',
				'COM_REDSHOP_CATALOG',
				(self::$view == 'catalogs') ? true : false
			)
			->addItem(
				'index.php?option=com_redshop&view=sample',
				'COM_REDSHOP_CATALOG_PRODUCT_SAMPLE',
				self::$view === 'sample'
			)
			->addItem(
				'index.php?option=com_redshop&view=sample_request',
				'COM_REDSHOP_SAMPLE_REQUEST',
				self::$view === 'sample_request'
			)
			->addItem(
				'index.php?option=com_redshop&view=producttags',
				'COM_REDSHOP_TAGS_LISTING',
				self::$view === 'producttags'
			)
			->addItem(
				'index.php?option=com_redshop&view=attribute_set',
				'COM_REDSHOP_ATTRIBUTE_SET_LISTING',
				self::$view === 'attribute_set'
			)
			->addItem(
				'index.php?option=com_redshop&view=currencies',
				'COM_REDSHOP_CURRENCY_LISTING',
				self::$view === 'currencies'
			)
			->addItem(
				'index.php?option=com_redshop&view=countries',
				'COM_REDSHOP_COUNTRY_LISTING',
				self::$view === 'countries'
			)
			->addItem(
				'index.php?option=com_redshop&view=states',
				'COM_REDSHOP_STATE_LISTING',
				self::$view === 'states'
			)
			->addItem(
				'index.php?option=com_redshop&view=zipcodes',
				'COM_REDSHOP_ZIPCODE_LISTING',
				self::$view === 'zipcode'
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
				self::$view === 'question'
			)
			->addItem(
				'index.php?option=com_redshop&view=rating',
				'COM_REDSHOP_RATING_REVIEW',
				self::$view === 'rating'
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
					self::$view === 'accountgroup'
				)
				->addItem(
					'index.php?option=com_redshop&view=accountgroup_detail',
					'COM_REDSHOP_ADD_ACCOUNTGROUP',
					self::$view === 'accountgroup_detail'
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
				self::$view === 'statistic_customer' && self::$layout === ''
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic_order',
				'COM_REDSHOP_STATISTIC_ORDER',
				self::$view === 'statistic_order' && self::$layout === ''
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic_product',
				'COM_REDSHOP_STATISTIC_PRODUCT',
				self::$view === 'statistic_product' && self::$layout === ''
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
				self::$view === 'statistic' && self::$layout === ''
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=pageview',
				'COM_REDSHOP_TOTAL_PAGEVIEWERS',
				self::$view === 'statistic' && self::$layout === 'pageview'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=turnover',
				'COM_REDSHOP_TOTAL_TURNOVER',
				self::$view === 'statistic' && self::$layout === 'turnover'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=avrgorder',
				'COM_REDSHOP_AVG_ORDER_AMOUNT_CUSTOMER',
				self::$view === 'statistic' && self::$layout === 'avrgorder'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=amountorder',
				'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_ORDER',
				self::$view === 'statistic' && self::$layout === 'amountorder'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=amountprice',
				'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_PRICE_PER_ORDER',
				self::$view === 'statistic' && self::$layout === 'amountprice'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=amountspent',
				'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_SPENT_IN_TOTAL',
				self::$view === 'statistic' && self::$layout === 'amountspent'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=bestsell',
				'COM_REDSHOP_BEST_SELLERS',
				self::$view === 'statistic' && self::$layout === 'bestsell'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=popularsell',
				'COM_REDSHOP_MOST_VISITED_PRODUCTS',
				self::$view === 'statistic' && self::$layout === 'popularsell'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=newprod',
				'COM_REDSHOP_NEWEST_PRODUCTS',
				self::$view === 'statistic' && self::$layout === 'newprod'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=neworder',
				'COM_REDSHOP_NEWEST_ORDERS',
				self::$view === 'statistic' && self::$layout === 'neworder'
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
				self::$view === 'configuration' && self::$layout === ''
			)
			->addItem(
				'index.php?option=com_redshop&view=configuration&layout=resettemplate',
				'COM_REDSHOP_RESET_TEMPLATE_LBL',
				self::$view === 'configuration' && self::$layout === 'resettemplate'
			)
			->addItem(
				'index.php?option=com_redshop&view=access',
				'COM_REDSHOP_ACCESS_MANAGER',
				self::$view === 'access'
			)
			->addItem(
				'index.php?option=com_config&view=component&component=com_redshop',
				'COM_REDSHOP_BACKEND_ACCESS_CONFIG',
				false
			);

		self::$menu->group('CONFIG');
	}

	/**
	 * Method for set tool
	 *
	 * @return void
	 */
	protected static function setTool()
	{
		self::$menu->section('tools')
			->title('COM_REDSHOP_BACKEND_TOOLS')
			->addItem(
				'index.php?option=com_redshop&view=tool_image',
				'COM_REDSHOP_BACKEND_TOOLS_IMAGE',
				self::$view === 'tool_image'
			)
			->addItem(
				'index.php?option=com_redshop&view=tool_update',
				'COM_REDSHOP_BACKEND_TOOLS_UPDATE',
				self::$view === 'tool_update'
			);

		self::$menu->group('TOOLS');
	}

	/**
	 * Set Stockroom menu
	 *
	 * @return  void
	 */
	protected static function setStockroom()
	{
		if (!Redshop::getConfig()->getBool('USE_STOCKROOM'))
		{
			return;
		}

		self::$menu->section('stockroom')
			->title('COM_REDSHOP_STOCKROOM')
			->addItem(
				'index.php?option=com_redshop&view=stockroom',
				'COM_REDSHOP_STOCKROOM_LISTING',
				self::$view === 'stockroom'
			)
			->addItem(
				'index.php?option=com_redshop&view=stockroom_listing',
				'COM_REDSHOP_STOCKROOM_AMOUNT_LISTING',
				self::$view === 'stockroom_listing'
			)
			->addItem(
				'index.php?option=com_redshop&view=stockimage',
				'COM_REDSHOP_STOCKIMAGE_LISTING',
				self::$view === 'stockimage'
			);

		if (JPluginHelper::isEnabled('economic') && Redshop::getConfig()->getBool('ECONOMIC_INTEGRATION'))
		{
			self::$menu->addItem(
				'index.php?option=com_redshop&view=stockroom_detail&layout=importstock',
				'COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC',
				self::$view === 'stockroom_detail' && self::$layout === 'importstock'
			);
		}

		self::$menu->group('STOCKROOM');
	}
}
