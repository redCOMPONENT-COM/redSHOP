<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class leftmenu
{
	public static function render()
	{
		$menu = RedshopAdminMenu::getInstance();

		$active = self::getActive();

		self::setConfigGroup();
		self::setProductGroup();
		self::setOrderGroup();
		self::setDiscountGroup();
		self::setCommunicationGroup();
		self::setShippingGroup();
		self::setUserGroup();
		self::setVatGroup();
		self::setIEGroup();
		self::setCustomisationGroup();
		self::setCustomerInputGroup();
		self::setAccountGroup();
		self::setStatisticsGroup();

		return JLayoutHelper::render('menu.main', array('items' => $menu->items, 'active' => $active));
	}

	protected static function getActive()
	{
		$view = JFactory::getApplication()->input->getCmd('view');

		$active = '';

		switch ($view)
		{
			case "product":
			case "product_detail":
			case "prices":
			case "mass_discount_detail":
			case "mass_discount":
				$active = 'product';
				break;

			case "category":
				$active = 'category';
				break;

			case "manufacturer":
			case "manufacturer_detail":
				$active = 'manufacturer';
				break;

			case "media":
				$active = 'media';
				break;

			case "order":
			case "order_detail":
			case "addorder_detail":
			case "orderstatus":
			case "orderstatus_detail":
			case "opsearch":
			case "barcode":

				$active = 'order';
				break;

			case "quotation":
			case "quotation_detail":
				$active = 'quotation';
				break;

			case "stockroom":
			case "stockroom_listing":
			case "stockimage":
				$active = 'stockroom';
				break;

			case "supplier":
			case "supplier_detail":
				$active = 'supplier';
				break;

			case "discount":
				$active = 'discount';
				break;

			case "giftcards":
			case "giftcard":
				$active = 'giftcards';
				break;

			case "voucher":
				$active = 'voucher';
				break;

			case "coupon":
			case "coupon_detail":
				$active = 'coupon';
				break;

			case "mail":
				$active = 'mail';
				break;

			case "newsletter":
			case "newslettersubscr":
				$active = 'newsletter';
				break;

			case "shipping":
			case "shipping_rate":
				$active = 'shipping';
				break;

			case "shipping_box":
				$active = 'shipping_box';
				break;

			case "shipping_detail":
				$active = 'shipping_detail';
				break;

			case "wrapper":
				$active = 'wrapper';
				break;

			case "user":
			case "shopper_group":
				$active = 'user';
				break;

			case "accessmanager":
			case 'accessmanager_detail':
				$active = 'accessmanager';
				break;

			case "tax_group":
			case "tax_group_detail":
			case "tax":
				$active = 'tax_group';
				break;

			case "currency":
			case "currency_detail":
				$active = 'currency';
				break;

			case "country":
			case "country_detail":
				$active = 'country';
				break;

			case "state":
			case "state_detail":
				$active = 'state';
				break;

			case "zipcode":
			case "zipcode_detail":
				$active = 'zipcode';
				break;

			case "importexport":
			case "import":
			case "export":
			case "vmimport":
				$active = 'importexport';
				break;

			case "xmlimport":
			case "xmlexport":
				$active = 'xmlimport';
				break;

			case "fields":
			case "addressfields_listing":
				$active = 'fields';
				break;

			case "template":
				$active = 'template';
				break;

			case "textlibrary":
				$active = 'textlibrary';
				break;

			case "catalog":
			case "catalog_request":
				$active = 'catalog';
				break;

			case "sample":
			case "sample_request":
				$active = 'sample';
				break;

			case "producttags":
			case "producttags_detail":
				$active = 'producttags';
				break;

			case "attribute_set":
			case "attribute_set_detail":
				$active = 'attribute_set';
				break;

			case "question":
			case "question_detail":
			case "answer":
			case "answer_detail":
				$active = 'question';
				break;

			case "rating":
				$active = 'rating';
				break;

			case "accountgroup":
			case "accountgroup_detail":
				$active = 'accountgroup';
				break;

			case "statistic":
				$active = 'statistic';
				break;

			case "configuration":
			case 'update':
				$active = 'configuration';
				break;

			default:
				$active = '';
				break;
		}

		return $active;
	}

	protected static function setProductGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		self::setProduct();
		self::setCategory();
		self::setManufacturer();
		self::setMedia();

		$menu->group('COM_REDSHOP_PRODUCT_MANAGEMENT');
	}

	protected static function setOrderGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		self::setOrder();
		self::setQuotation();
		self::setStockroom();
		self::setSupplier();

		$menu->group('COM_REDSHOP_ORDER');
	}

	protected static function setDiscountGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		self::setDiscount();
		self::setGiftCard();
		self::setVoucher();
		self::setCoupon();

		$menu->group('COM_REDSHOP_DISCOUNT');
	}

	protected static function setCommunicationGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		self::setMail();
		self::setNewsLetter();

		$menu->group('COM_REDSHOP_COMMUNICATION');
	}

	protected static function setShippingGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		self::setShipping();
		self::setShippingBox();
		self::setWrapper();

		$menu->group('COM_REDSHOP_SHIPPING');
	}

	protected static function setUserGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		$menu->section('user')
			->title('COM_REDSHOP_USER')
			->addItem(
				'index.php?option=com_redshop&view=user',
				'COM_REDSHOP_USER_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=user_detail',
				'COM_REDSHOP_ADD_USER'
			)
			->addItem(
				'javascript:userSync();',
				'COM_REDSHOP_USER_SYNC'
			)
			->addItem(
				'index.php?option=com_redshop&view=shopper_group',
				'COM_REDSHOP_SHOPPER_GROUP_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=shopper_group_detail',
				'COM_REDSHOP_ADD_SHOPPER_GROUP'
			);

		if (ENABLE_BACKENDACCESS)
		{
			$menu->section('accessmanager')
				->title('COM_REDSHOP_ACCESS_MANAGER')
				->addItem(
					'index.php?option=com_redshop&view=accessmanager',
					'COM_REDSHOP_ACCESS_MANAGER'
				);
		}

		JFactory::getDocument()->addScriptDeclaration('
			function userSync() {
				if (confirm("' . JText::_('COM_REDSHOP_DO_YOU_WANT_TO_SYNC') . '") == true)
					window.location = "index.php?option=com_redshop&view=user&sync=1";
			}
		');

		$menu->group('COM_REDSHOP_USER');
	}

	protected static function setVatGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		self::setTax();
		self::setCurrency();
		self::setCountry();
		self::setState();
		self::setZipcode();

		$menu->group('COM_REDSHOP_VAT_AND_CURRENCY');
	}

	protected static function setIEGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		$menu->section('import')
			->title('COM_REDSHOP_IMPORT_EXPORT')
			->addItem(
				'index.php?option=com_redshop&view=import',
				'COM_REDSHOP_DATA_IMPORT'
			)
			->addItem(
				'index.php?option=com_redshop&view=export',
				'COM_REDSHOP_DATA_EXPORT'
			)
			->addItem(
				'javascript:vmImport();',
				'COM_REDSHOP_IMPORT_FROM_VM'
			);

		$menu->section('xmlimport')
			->title('COM_REDSHOP_XML_IMPORT_EXPORT')
			->addItem(
				'index.php?option=com_redshop&view=xmlimport',
				'COM_REDSHOP_XML_IMPORT'
			)
			->addItem(
				'index.php?option=com_redshop&view=xmlexport',
				'COM_REDSHOP_XML_EXPORT'
			);

		JFactory::getDocument()->addScriptDeclaration('
			function vmImport() {
				if (confirm("' . JText::_('COM_REDSHOP_DO_YOU_WANT_TO_IMPORT_VM') . '") == true)
					window.location = "index.php?option=com_redshop&view=import&vm=1";
			}
		');

		$menu->group('COM_REDSHOP_IMPORT_EXPORT');
	}

	protected static function setCustomisationGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		self::setCustomFields();
		self::setTemplate();
		self::setTextLibrary();
		self::setCatelogue();
		self::setProductSample();
		self::setCustomerTags();
		self::setAttributeBank();

		$menu->group('COM_REDSHOP_CUSTOMIZATION');
	}

	protected static function setCustomerInputGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		$menu->section('question')
			->title('COM_REDSHOP_QUESTION')
			 ->addItem(
					'index.php?option=com_redshop&view=question',
					'COM_REDSHOP_QUESTION_LISTING'
				);

		$menu->section('rating')
			->title('COM_REDSHOP_REVIEW')
			->addItem(
				'index.php?option=com_redshop&view=rating',
				'COM_REDSHOP_RATING_REVIEW'
			);

		$menu->group('COM_REDSHOP_CUSTOMER_INPUT');
	}

	protected static function setAccountGroup()
	{
		if (ECONOMIC_INTEGRATION && JPluginHelper::isEnabled('economic'))
		{
			$menu = RedshopAdminMenu::getInstance()->init();

			$menu->section('question')
				->title('COM_REDSHOP_ECONOMIC_ACCOUNT_GROUP')
				->addItem(
					'index.php?option=com_redshop&view=accountgroup',
					'COM_REDSHOP_ACCOUNTGROUP_LISTING'
				)
				->addItem(
					'index.php?option=com_redshop&view=accountgroup_detail',
					'COM_REDSHOP_ADD_ACCOUNTGROUP'
				);

			$menu->group('COM_REDSHOP_ACCOUNTING');
		}
	}

	protected static function setStatisticsGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		$menu->section('statistic')
			->title('COM_REDSHOP_STATISTIC')
			->addItem(
				'index.php?option=com_redshop&view=statistic',
				'COM_REDSHOP_TOTAL_VISITORS'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=pageview',
				'COM_REDSHOP_TOTAL_PAGEVIEWERS'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=turnover',
				'COM_REDSHOP_TOTAL_TURNOVER'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=avrgorder',
				'COM_REDSHOP_AVG_ORDER_AMOUNT_CUSTOMER'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=amountorder',
				'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_ORDER'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=amountprice',
				'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_OF_PRICE_PER_ORDER'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=amountspent',
				'COM_REDSHOP_TOP_CUSTOMER_AMOUNT_SPENT_IN_TOTAL'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=bestsell',
				'COM_REDSHOP_BEST_SELLERS'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=popularsell',
				'COM_REDSHOP_MOST_VISITED_PRODUCTS'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=newprod',
				'COM_REDSHOP_NEWEST_PRODUCTS'
			)
			->addItem(
				'index.php?option=com_redshop&view=statistic&layout=neworder',
				'COM_REDSHOP_NEWEST_ORDERS'
			);

		$menu->group('COM_REDSHOP_STATISTIC');
	}

	protected static function setConfigGroup()
	{
		$menu = RedshopAdminMenu::getInstance()->init();

		$menu->section('configuration')
			->title('COM_REDSHOP_CONFIG')
			->addItem(
				'index.php?option=com_redshop&view=configuration',
				'COM_REDSHOP_RESHOP_CONFIGURATION'
			)
			->addItem(
				'index.php?option=com_redshop&wizard=1',
				'COM_REDSHOP_START_CONFIGURATION_WIZARD'
			)
			->addItem(
				'index.php?option=com_redshop&view=configuration&layout=resettemplate',
				'COM_REDSHOP_RESET_TEMPLATE_LBL'
			)
			->addItem(
				'index.php?option=com_redshop&view=update',
				'COM_REDSHOP_UPDATE_TITLE'
			);

		$menu->group('COM_REDSHOP_CONFIG');
	}

	protected static function setProduct()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('product')
			->title('COM_REDSHOP_PRODUCTS')
			->addItem(
				'index.php?option=com_redshop&view=product',
				'COM_REDSHOP_PRODUCT_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=product&layout=listing',
				'COM_REDSHOP_PRODUCT_PRICE_VIEW'
			)
			->addItem(
				'index.php?option=com_redshop&view=product_detail',
				'COM_REDSHOP_ADD_PRODUCT'
			)
			->addItem(
				'index.php?option=com_redshop&view=mass_discount_detail',
				'COM_REDSHOP_ADD_MASS_DISCOUNT'
			)
			->addItem(
				'index.php?option=com_redshop&view=mass_discount',
				'COM_REDSHOP_MASS_DISCOUNT'
			);

		if (ECONOMIC_INTEGRATION == 1 && JPluginHelper::isEnabled('economic'))
		{
			$menu->section('product')
				->addItem(
					'index.php?option=com_redshop&view=product&layout=importproduct',
					'COM_REDSHOP_IMPORT_PRODUCTS_TO_ECONOMIC'
				);

			if (ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC == 1)
			{
				$menu->section('product')
				->addItem(
					'index.php?option=com_redshop&view=product&layout=importattribute',
					'COM_REDSHOP_IMPORT_ATTRIBUTES_TO_ECONOMIC'
				);
			}
		}
	}

	protected static function setCategory()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('category')
			->title('COM_REDSHOP_CATEGORY')
			->addItem(
				'index.php?option=com_redshop&view=category',
				'COM_REDSHOP_CATEGORY_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=category_detail',
				'COM_REDSHOP_ADD_CATEGORY'
			);
	}

	protected static function setManufacturer()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('manufacturer')
			->title('COM_REDSHOP_MANUFACTURER')
			->addItem(
				'index.php?option=com_redshop&view=manufacturer',
				'COM_REDSHOP_MANUFACTURER_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=manufacturer_detail',
				'COM_REDSHOP_ADD_MANUFACTURER'
			);
	}

	protected static function setMedia()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('media')
			->title('COM_REDSHOP_MEDIA')
			->addItem(
				'index.php?option=com_redshop&view=media',
				'COM_REDSHOP_MEDIA_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=media_detail',
				'COM_REDSHOP_ADD_MEDIA_ITEM'
			)
			->addItem(
				'index.php?option=com_redshop&view=media_detail',
				'COM_REDSHOP_BULK_UPLOAD'
			);
	}

	protected static function setOrder()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('order')
			->title('COM_REDSHOP_ORDER')
			->addItem(
				'index.php?option=com_redshop&view=order',
				'COM_REDSHOP_ORDER_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=addorder_detail',
				'COM_REDSHOP_ADD_ORDER'
			)
			->addItem(
				'index.php?option=com_redshop&view=order&layout=labellisting',
				'COM_REDSHOP_DOWNLOAD_LABEL'
			)
			->addItem(
				'index.php?option=com_redshop&view=orderstatus',
				'COM_REDSHOP_ORDERSTATUS_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=opsearch',
				'COM_REDSHOP_PRODUCT_ORDER_SEARCH'
			)
			->addItem(
				'index.php?option=com_redshop&view=barcode',
				'COM_REDSHOP_BARCODE'
			)
			->addItem(
				'index.php?option=com_redshop&view=barcode&layout=barcode_order',
				'COM_REDSHOP_BARCODE_ORDER'
			);
	}

	protected static function setQuotation()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('quotation')
			->title('COM_REDSHOP_QUOTATION')
			->addItem(
				'index.php?option=com_redshop&view=quotation',
				'COM_REDSHOP_QUOTATION_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=addquotation_detail',
				'COM_REDSHOP_ADD_QUOTATION'
			);
	}

	protected static function setStockroom()
	{
		if (USE_STOCKROOM == 0)
		{
			return;
		}

		$menu = RedshopAdminMenu::getInstance();

		$menu->section('stockroom')
			->title('COM_REDSHOP_STOCKROOM')
			->addItem(
				'index.php?option=com_redshop&view=stockroom',
				'COM_REDSHOP_STOCKROOM_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=stockroom_detail',
				'COM_REDSHOP_ADD_STOCKROOM'
			)
			->addItem(
				'index.php?option=com_redshop&view=stockroom_listing',
				'COM_REDSHOP_STOCKROOM_AMOUNT_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=stockimage',
				'COM_REDSHOP_STOCKIMAGE_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=stockimage_detail',
				'COM_REDSHOP_ADD_STOCKIMAGE'
			);

		if (ECONOMIC_INTEGRATION && JPluginHelper::isEnabled('economic'))
		{
			$menu->addItem(
				'index.php?option=com_redshop&view=stockroom_detail&layout=importstock',
				'COM_REDSHOP_IMPORT_STOCK_FROM_ECONOMIC'
			);
		}
	}

	protected static function setSupplier()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('supplier')
			->title('COM_REDSHOP_SUPPLIER')
			->addItem(
				'index.php?option=com_redshop&view=supplier',
				'COM_REDSHOP_SUPPLIER_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=supplier_detail',
				'COM_REDSHOP_ADD_SUPPLIER'
			);
	}

	protected static function setDiscount()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('discount')
			->title('COM_REDSHOP_DISCOUNT')
			->addItem(
				'index.php?option=com_redshop&view=discount',
				'COM_REDSHOP_DISCOUNT_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=discount_detail',
				'COM_REDSHOP_ADD_DISCOUNT'
			)
			->addItem(
				'index.php?option=com_redshop&view=discount&layout=product',
				'COM_REDSHOP_DISCOUNT_PRODUCT_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=discount_detail&layout=product',
				'COM_REDSHOP_ADD_DISCOUNT_PRODUCT'
			);
	}

	protected static function setGiftCard()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('giftcards')
			->title('COM_REDSHOP_GIFTCARD')
			->addItem(
				'index.php?option=com_redshop&view=giftcards',
				'COM_REDSHOP_GIFTCARD_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=giftcard&task=giftcard.edit',
				'COM_REDSHOP_ADD_GIFTCARD'
			);
	}

	protected static function setVoucher()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('voucher')
			->title('COM_REDSHOP_VOUCHER')
			->addItem(
				'index.php?option=com_redshop&view=voucher',
				'COM_REDSHOP_VOUCHER_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=voucher_detail',
				'COM_REDSHOP_ADD_VOUCHER'
			);
	}

	protected static function setCoupon()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('coupon')
			->title('COM_REDSHOP_COUPON')
			->addItem(
				'index.php?option=com_redshop&view=coupon',
				'COM_REDSHOP_COUPON_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=coupon_detail',
				'COM_REDSHOP_ADD_COUPON'
			);
	}

	protected static function setMail()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('mail')
			->title('COM_REDSHOP_MAIL_CENTER')
			->addItem(
				'index.php?option=com_redshop&view=mail',
				'COM_REDSHOP_MAIL_CENTER_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=mail_detail',
				'COM_REDSHOP_ADD_MAIL_CENTER'
			);
	}

	protected static function setNewsLetter()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('newsletter')
			->title('COM_REDSHOP_NEWSLETTER')
			->addItem(
				'index.php?option=com_redshop&view=newsletter',
				'COM_REDSHOP_NEWSLETTER_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=newsletter_detail',
				'COM_REDSHOP_ADD_NEWSLETTER'
			)
			->addItem(
				'index.php?option=com_redshop&view=newslettersubscr',
				'COM_REDSHOP_NEWSLETTER_SUBSCR_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=newslettersubscr_detail',
				'COM_REDSHOP_ADD_NEWSLETTER_SUBSCR'
			)
			->addItem(
				'index.php?option=com_redshop&view=newsletter_detail&layout=statistics',
				'COM_REDSHOP_NEWSLETTER_STATISTICS'
			);
	}

	protected static function setShipping()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('shipping')
			->title('COM_REDSHOP_SHIPPING_METHOD')
			->addItem(
				'index.php?option=com_redshop&view=shipping',
				'COM_REDSHOP_SHIPPING_METHOD_LISTING'
			)
			->addItem(
				'index.php?option=com_installer',
				'COM_REDSHOP_ADD_SHIPPING_METHOD'
			);

		if (ECONOMIC_INTEGRATION == 1 && JPluginHelper::isEnabled('economic'))
		{
			$menu->section('shipping')
				->addItem(
					'index.php?option=com_redshop&view=shipping&task=importeconomic',
					'COM_REDSHOP_IMPORT_RATES_TO_ECONOMIC'
				);
		}
	}

	protected static function setShippingBox()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('shipping_box')
			->title('COM_REDSHOP_SHIPPING_BOX')
			->addItem(
				'index.php?option=com_redshop&view=shipping_box',
				'COM_REDSHOP_SHIPPING_BOXES'
			)
			->addItem(
				'index.php?option=com_redshop&view=shipping_box_detail',
				'COM_REDSHOP_ADD_SHIPPING_BOXES'
			);
	}

	protected static function setWrapper()
	{
		$menu = RedshopAdminMenu::getInstance();

		$menu->section('wrapper')
			->title('COM_REDSHOP_WRAPPER')
			->addItem(
				'index.php?option=com_redshop&view=wrapper',
				'COM_REDSHOP_WRAPPER_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=wrapper_detail',
				'COM_REDSHOP_ADD_WRAPPER'
			);
	}

	protected static function setTax()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('tax_group')
			->title('COM_REDSHOP_TAX_GROUP')
			->addItem(
				'index.php?option=com_redshop&view=tax_group',
				'COM_REDSHOP_TAX_GROUP_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=tax_group_detail',
				'COM_REDSHOP_TAX_GROUP_DETAIL'
			);
	}

	protected static function setCurrency()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('currency')
			->title('COM_REDSHOP_CURRENCY')
			->addItem(
				'index.php?option=com_redshop&view=currency',
				'COM_REDSHOP_CURRENCY_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=currency_detail',
				'COM_REDSHOP_ADD_CURRENCY'
			);
	}

	protected static function setCountry()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('country')
			->title('COM_REDSHOP_COUNTRY')
			->addItem(
				'index.php?option=com_redshop&view=country',
				'COM_REDSHOP_COUNTRY_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=country_detail',
				'COM_REDSHOP_ADD_COUNTRY'
			);
	}

	protected static function setState()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('state')
			->title('COM_REDSHOP_STATE')
			->addItem(
				'index.php?option=com_redshop&view=state',
				'COM_REDSHOP_STATE_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=state_detail',
				'COM_REDSHOP_ADD_STATE'
			);
	}

	protected static function setZipcode()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('zipcode')
			->title('COM_REDSHOP_ZIPCODE')
			->addItem(
				'index.php?option=com_redshop&view=zipcode',
				'COM_REDSHOP_ZIPCODE_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=zipcode_detail',
				'COM_REDSHOP_ADD_ZIPCODE'
			);
	}

	protected static function setCustomFields()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('fields')
			->title('COM_REDSHOP_FIELDS')
			->addItem(
				'index.php?option=com_redshop&view=fields',
				'COM_REDSHOP_FIELDS_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=fields_detail',
				'COM_REDSHOP_ADD_FIELD'
			);
	}

	protected static function setTemplate()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('template')
			->title('COM_REDSHOP_TEMPLATE')
			->addItem(
				'index.php?option=com_redshop&view=template',
				'COM_REDSHOP_TEMPLATE_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=template_detail',
				'COM_REDSHOP_ADD_TEMPLATE'
			);
	}

	protected static function setTextLibrary()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('textlibrary')
			->title('COM_REDSHOP_TEXT_LIBRARY')
			->addItem(
				'index.php?option=com_redshop&view=textlibrary',
				'COM_REDSHOP_TEXT_LIBRARY_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=textlibrary_detail',
				'COM_REDSHOP_ADD_TEXT_LIBRARY_TAG'
			);
	}

	protected static function setCatelogue()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('catalog')
			->title('COM_REDSHOP_CATALOG_MANAGEMENT')
			->addItem(
				'index.php?option=com_redshop&view=catalog',
				'COM_REDSHOP_CATALOG'
			)
			->addItem(
				'index.php?option=com_redshop&view=catalog_request',
				'COM_REDSHOP_CATALOG_REQUEST'
			);
	}

	protected static function setProductSample()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('sample')
			->title('COM_REDSHOP_COLOUR_SAMPLE_MANAGEMENT')
			->addItem(
				'index.php?option=com_redshop&view=sample',
				'COM_REDSHOP_CATALOG_PRODUCT_SAMPLE'
			)
			->addItem(
				'index.php?option=com_redshop&view=sample_request',
				'COM_REDSHOP_SAMPLE_REQUEST'
			);
	}

	protected static function setCustomerTags()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('producttags')
			->title('COM_REDSHOP_TAGS')
			 ->addItem(
					'index.php?option=com_redshop&view=producttags',
					'COM_REDSHOP_TAGS_LISTING'
				);
	}

	protected static function setAttributeBank()
	{
		$menu = RedshopAdminMenu::getInstance();
		$menu->section('attribute_set')
			->title('COM_REDSHOP_ATTRIBUTE_BANK')
			->addItem(
				'index.php?option=com_redshop&view=attribute_set',
				'COM_REDSHOP_ATTRIBUTE_SET_LISTING'
			)
			->addItem(
				'index.php?option=com_redshop&view=attribute_set_detail',
				'COM_REDSHOP_ADD_ATTRIBUTE_SET'
			);
	}
}
