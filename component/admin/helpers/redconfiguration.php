<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


class Redconfiguration
{
	public $defArray = null;

	public $configPath = null;

	public $configDistPath = null;

	public $configBkpPath = null;

	public $configTmpPath = null;

	public $configDefPath = null;

	public $cfgData = null;

	public $countryList = null;

	protected static $instance = null;

	/**
	 * Returns the RedConfiguration object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  RedConfiguration  The RedConfiguration object
	 *
	 * @since   1.6
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new static;
		}

		return self::$instance;
	}

	/**
	 * define default path
	 *
	 */
	public function __construct()
	{
		$basepath             = JPATH_SITE . '/administrator/components/com_redshop/helpers/';
		$this->configPath     = $basepath . 'redshop.cfg.php';
		$this->configDistPath = $basepath . 'wizard/redshop.cfg.dist.php';
		$this->configBkpPath  = $basepath . 'wizard/redshop.cfg.bkp.php';
		$this->configTmpPath  = $basepath . 'wizard/redshop.cfg.tmp.php';
		$this->configDefPath  = $basepath . 'wizard/redshop.cfg.def.php';

		if (!defined('JSYSTEM_IMAGES_PATH'))
		{
			define('JSYSTEM_IMAGES_PATH', JURI::root() . 'media/system/images/');
		}

		if (!defined('REDSHOP_ADMIN_IMAGES_ABSPATH'))
		{
			define('REDSHOP_ADMIN_IMAGES_ABSPATH', JURI::root() . 'administrator/components/com_redshop/assets/images/');
		}

		if (!defined('REDSHOP_FRONT_IMAGES_ABSPATH'))
		{
			define('REDSHOP_FRONT_IMAGES_ABSPATH', JURI::root() . 'components/com_redshop/assets/images/');
		}

		if (!defined('REDSHOP_FRONT_IMAGES_RELPATH'))
		{
			define('REDSHOP_FRONT_IMAGES_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/images/');
		}

		if (!defined('REDSHOP_FRONT_DOCUMENT_ABSPATH'))
		{
			define('REDSHOP_FRONT_DOCUMENT_ABSPATH', JURI::root() . 'components/com_redshop/assets/document/');
		}

		if (!defined('REDSHOP_FRONT_DOCUMENT_RELPATH'))
		{
			define('REDSHOP_FRONT_DOCUMENT_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/document/');
		}
	}

	/**
	 * check configuration file exist or not
	 *
	 * @return boolean
	 */
	public function isCFGFile()
	{
		if (!file_exists($this->configPath))
		{
			return false;
		}

		require_once $this->configPath;

		return true;
	}

	/**
	 * check table exist
	 *
	 * @return boolean
	 */
	public function isCFGTable()
	{
		$db    = JFactory::getDbo();
		$query = 'show tables like "' . $db->getPrefix() . 'redshop_configuration"';
		$db->setQuery($query);
		$result = $db->loadResult();

		if (count($result) <= 0)
		{
			return false;
		}

		return true;
	}

	/**
	 * write configuration table data to file
	 *
	 * @param   array $org Config additional variables to merge
	 *
	 * @return void
	 */
	public function setCFGTableData($org = array())
	{
		$db = JFactory::getDbo();

		// GetData From table
		$query = "SELECT * FROM #__redshop_configuration WHERE id = 1";
		$db->setQuery($query);
		$cfgdata = $db->loadAssoc();

		// Prepare data from table
		$data = $this->redshopCFGData($cfgdata);

		if (count($org) > 0)
		{
			$data = array_merge($org, $data);
		}

		$this->defineCFGVars($data);
		$this->writeCFGFile();
	}

	/**
	 * load Default configuration file
	 *
	 * @return boolean
	 */
	public function loadDefaultCFGFile()
	{
		if ($this->isCFGFile())
		{
			if (copy($this->configPath, $this->configBkpPath))
			{
				if (!copy($this->configDistPath, $this->configPath))
				{
					return false;
				}
			}
		}
		else
		{
			if (!copy($this->configDistPath, $this->configPath))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * manage configuration file during installation
	 *
	 * @param   array $org Config additional variables to merge
	 *
	 * @return boolean
	 */
	public function manageCFGFile($org = array())
	{
		if ($this->isCFGFile())
		{
			if (count($org) > 0)
			{
				/* Set last param as false to ensure the last line is empty and not containing '?>' at the end of the file*/
				$this->defineCFGVars($org, false);
				$this->updateCFGFile();
			}
		}
		else
		{
			if ($this->isCFGTable())
			{
				$this->setCFGTableData($org);
			}
			else
			{
				$this->loadDefaultCFGFile();
			}
		}

		return true;
	}

	/**
	 * Define Configuration file. We are preparing define text on this function.
	 *
	 * @param   array   $data   Configuration Data associative array
	 * @param   boolean $bypass Don't write anything and simply bypass if it is set to true.
	 *
	 * @return  void
	 */
	public function defineCFGVars($data, $bypass = false)
	{
		$this->cfgData = "";

		foreach ($data as $key => $value)
		{
			if (!defined($key) || $bypass)
			{
				$this->cfgData .= "define('" . $key . "', '" . addslashes($value) . "');\n";
			}
		}

		return;
	}

	/**
	 * Write prepared data into a file.
	 *
	 * @return  boolean  True when file successfully saved.
	 */
	public function writeCFGFile()
	{
		if ($fp = fopen($this->configPath, "w"))
		{
			// Cleaning <?php and ?\> tag from the code
			$this->cfgData = str_replace(array('<?php', '?>', "\n"), '', $this->cfgData);

			// Now, adding <?php tag at the top of the file.
			$this->cfgData = "<?php \n" . $this->cfgData;

			fwrite($fp, $this->cfgData, strlen($this->cfgData));
			fclose($fp);

			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Update Configuration file with new parameter.
	 * This function is specially use during upgrading redSHOP and need to put new configuration params.
	 *
	 * @return  boolean  True when file successfully updated.
	 */
	public function updateCFGFile()
	{
		if ($fp = fopen($this->configPath, "a"))
		{
			fputs($fp, $this->cfgData, strlen($this->cfgData));
			fclose($fp);

			return true;
		}

		else
		{
			return false;
		}
	}

	/**
	 * Backup Configuration file before running wizard.
	 *
	 * @return  boolean  True on successfully backed up.
	 */
	public function backupCFGFile()
	{
		if ($this->isCFGFile())
		{
			if (!copy($this->configPath, $this->configBkpPath))
			{
				return false;
			}
		}

		else
		{
			if (!copy($this->configDistPath, $this->configPath))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Try to find if temp configuration file is available. This function is for wizard.
	 *
	 * @return  boolean  True when file is exist.
	 */
	public function isTmpFile()
	{
		if (file_exists($this->configTmpPath))
		{
			if ($this->isTMPFileWritable())
			{
				require_once $this->configTmpPath;

				return true;
			}
		}
		else
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_REDSHOP_TMP_FILE_NOT_FOUND'), 'error');
		}

		return false;
	}

	/**
	 * Check if temp file is writeable or not.
	 *
	 * @return  boolean  True if file is writeable.
	 */
	public function isTMPFileWritable()
	{
		if (!is_writable($this->configTmpPath))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_REDSHOP_TMP_FILE_NOT_WRITABLE'), 'error');

			return false;
		}

		return true;
	}

	/**
	 * Check if definition file is available or not.
	 *
	 * @return  boolean  True if file is exist.
	 */
	public function isDEFFile()
	{
		if (file_exists($this->configDefPath))
		{
			if ($this->isDEFFileWritable())
			{
				require_once $this->configDefPath;

				return true;
			}
		}
		else
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_REDSHOP_DEF_FILE_NOT_FOUND'), 'error');
		}

		return false;
	}

	/**
	 * Check for def file is writeable or not.
	 *
	 * @return  boolean  True if file is writeable.
	 */
	public function isDEFFileWritable()
	{
		if (!is_writable($this->configDefPath))
		{
			JFactory::getApplication()->enqueueMessage(JText::_('COM_REDSHOP_REDSHOP_DEF_FILE_NOT_WRITABLE'), 'error');

			return false;
		}

		return true;
	}

	/**
	 * Restore configuration file from temp file.
	 *
	 * @return  boolean  True if file is restored.
	 */
	public function storeFromTMPFile()
	{
		global $temparray;
		global $defaultarray;

		if ($this->isTmpFile() && $this->isDEFFile())
		{
			$ncfgdata     = array_merge($defaultarray, $temparray);
			$config_array = $this->redshopCFGData($ncfgdata);
			$this->defineCFGVars($config_array, true);
			$this->backupCFGFile();

			if (!$this->writeCFGFile())
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * This function will define relation between keys and define variables.
	 * This needs to be updated when you want new variable in configuration.
	 *
	 * @param   array $d Associative array of values. Typically a from $_POST.
	 *
	 * @return  array      Associative array of configuration variables which are ready to write.
	 */
	public function redshopCFGData($d)
	{
		$d['booking_order_status'] = (isset($d['booking_order_status'])) ? $d['booking_order_status'] : 0;

		$config_array = array(
			"PI"                              => 3.14,
			"ADMINISTRATOR_EMAIL"             => $d["administrator_email"],
			"THUMB_WIDTH"                     => $d["thumb_width"],
			"THUMB_HEIGHT"                    => $d["thumb_height"],
			"THUMB_WIDTH_2"                   => $d["thumb_width_2"],
			"THUMB_HEIGHT_2"                  => $d["thumb_height_2"],
			"THUMB_WIDTH_3"                   => $d["thumb_width_3"],
			"THUMB_HEIGHT_3"                  => $d["thumb_height_3"],
			"CATEGORY_PRODUCT_THUMB_WIDTH"    => $d["category_product_thumb_width"],
			"CATEGORY_PRODUCT_THUMB_HEIGHT"   => $d["category_product_thumb_height"],
			"CATEGORY_PRODUCT_THUMB_WIDTH_2"  => $d["category_product_thumb_width_2"],
			"CATEGORY_PRODUCT_THUMB_HEIGHT_2" => $d["category_product_thumb_height_2"],
			"CATEGORY_PRODUCT_THUMB_WIDTH_3"  => $d["category_product_thumb_width_3"],
			"CATEGORY_PRODUCT_THUMB_HEIGHT_3" => $d["category_product_thumb_height_3"],
			"RELATED_PRODUCT_THUMB_WIDTH"     => $d["related_product_thumb_width"],
			"RELATED_PRODUCT_THUMB_HEIGHT"    => $d["related_product_thumb_height"],
			"RELATED_PRODUCT_THUMB_WIDTH_2"   => $d["related_product_thumb_width_2"],
			"RELATED_PRODUCT_THUMB_HEIGHT_2"  => $d["related_product_thumb_height_2"],
			"RELATED_PRODUCT_THUMB_WIDTH_3"   => $d["related_product_thumb_width_3"],
			"RELATED_PRODUCT_THUMB_HEIGHT_3"  => $d["related_product_thumb_height_3"],
			"ATTRIBUTE_SCROLLER_THUMB_WIDTH"  => $d["attribute_scroller_thumb_width"],
			"ATTRIBUTE_SCROLLER_THUMB_HEIGHT" => $d["attribute_scroller_thumb_height"],
			"COMPARE_PRODUCT_THUMB_WIDTH"     => $d["compare_product_thumb_width"],
			"COMPARE_PRODUCT_THUMB_HEIGHT"    => $d["compare_product_thumb_height"],
			"ACCESSORY_THUMB_HEIGHT"          => $d["accessory_thumb_height"],
			"ACCESSORY_THUMB_WIDTH"           => $d["accessory_thumb_width"],
			"ACCESSORY_THUMB_HEIGHT_2"        => $d["accessory_thumb_height_2"],
			"ACCESSORY_THUMB_WIDTH_2"         => $d["accessory_thumb_width_2"],
			"ACCESSORY_THUMB_HEIGHT_3"        => $d["accessory_thumb_height_3"],
			"ACCESSORY_THUMB_WIDTH_3"         => $d["accessory_thumb_width_3"],

			"DEFAULT_AJAX_DETAILBOX_TEMPLATE"     => $d["default_ajax_detailbox_template"],
			"ASTERISK_POSITION"                   => 0,
			"MANUFACTURER_THUMB_WIDTH"            => $d["manufacturer_thumb_width"],
			"MANUFACTURER_THUMB_HEIGHT"           => $d["manufacturer_thumb_height"],
			"MANUFACTURER_PRODUCT_THUMB_WIDTH"    => $d["manufacturer_product_thumb_width"],
			"MANUFACTURER_PRODUCT_THUMB_HEIGHT"   => $d["manufacturer_product_thumb_height"],
			"MANUFACTURER_PRODUCT_THUMB_WIDTH_2"  => $d["manufacturer_product_thumb_width_2"],
			"MANUFACTURER_PRODUCT_THUMB_HEIGHT_2" => $d["manufacturer_product_thumb_height_2"],
			"MANUFACTURER_PRODUCT_THUMB_WIDTH_3"  => $d["manufacturer_product_thumb_width_3"],
			"MANUFACTURER_PRODUCT_THUMB_HEIGHT_3" => $d["manufacturer_product_thumb_height_3"],

			"CART_THUMB_WIDTH"          => $d["cart_thumb_width"],
			"CART_THUMB_HEIGHT"         => $d["cart_thumb_height"],
			"SHOW_TERMS_AND_CONDITIONS" => $d["show_terms_and_conditions"],

			"GIFTCARD_THUMB_WIDTH"       => $d["giftcard_thumb_width"],
			"GIFTCARD_THUMB_HEIGHT"      => $d["giftcard_thumb_height"],
			"GIFTCARD_LIST_THUMB_WIDTH"  => $d["giftcard_list_thumb_width"],
			"GIFTCARD_LIST_THUMB_HEIGHT" => $d["giftcard_list_thumb_height"],

			"PRODUCT_MAIN_IMAGE"          => $d["product_main_image"],
			"PRODUCT_MAIN_IMAGE_HEIGHT"   => $d["product_main_image_height"],
			"PRODUCT_MAIN_IMAGE_2"        => $d["product_main_image_2"],
			"PRODUCT_MAIN_IMAGE_HEIGHT_2" => $d["product_main_image_height_2"],
			"PRODUCT_MAIN_IMAGE_3"        => $d["product_main_image_3"],
			"PRODUCT_MAIN_IMAGE_HEIGHT_3" => $d["product_main_image_height_3"],

			"PRODUCT_ADDITIONAL_IMAGE"          => $d["product_additional_image"],
			"PRODUCT_ADDITIONAL_IMAGE_HEIGHT"   => $d["product_additional_image_height"],
			"PRODUCT_ADDITIONAL_IMAGE_2"        => $d["product_additional_image_2"],
			"PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2" => $d["product_additional_image_height_2"],
			"PRODUCT_ADDITIONAL_IMAGE_3"        => $d["product_additional_image_3"],
			"PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3" => $d["product_additional_image_height_3"],

			"PRODUCT_PREVIEW_IMAGE_WIDTH"      => $d["product_preview_image_width"],
			"PRODUCT_PREVIEW_IMAGE_HEIGHT"     => $d["product_preview_image_height"],
			"DEFAULT_STOCKAMOUNT_THUMB_WIDTH"  => $d['default_stockamount_thumb_width'],
			"DEFAULT_STOCKAMOUNT_THUMB_HEIGHT" => $d['default_stockamount_thumb_height'],

			"CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH"  => $d["category_product_preview_image_width"],
			"CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT" => $d["category_product_preview_image_height"],

			"PRODUCT_COMPARE_LIMIT"  => $d["product_compare_limit"],
			"PRODUCT_DOWNLOAD_LIMIT" => $d["product_download_limit"],
			"PRODUCT_DOWNLOAD_DAYS"  => $d["product_download_days"],
			"QUANTITY_TEXT_DISPLAY"  => $d["quantity_text_display"],

			"DISCOUNT_MAIL_SEND" => $d["discount_mail_send"],
			"DAYS_MAIL1"         => $d["days_mail1"],
			"DAYS_MAIL2"         => $d["days_mail2"],
			"DAYS_MAIL3"         => $d["days_mail3"],

			"DISCOUPON_DURATION"         => $d["discoupon_duration"],
			"DISCOUPON_PERCENT_OR_TOTAL" => $d["discoupon_percent_or_total"],
			"DISCOUPON_VALUE"            => $d["discoupon_value"],
			"USE_STOCKROOM"              => $d["use_stockroom"],
			"ALLOW_PRE_ORDER"            => $d["allow_pre_order"],
			"ALLOW_PRE_ORDER_MESSAGE"    => $d["allow_pre_order_message"],

			"DEFAULT_VAT_COUNTRY" => $d["default_vat_country"],
			"DEFAULT_VAT_STATE"   => $d["default_vat_state"],
			"DEFAULT_VAT_GROUP"   => $d["default_vat_group"],
			"VAT_BASED_ON"        => $d["vat_based_on"],

			"PRODUCT_TEMPLATE"               => $d["default_product_template"],
			"CATEGORY_TEMPLATE"              => $d["default_category_template"],
			"DEFAULT_CATEGORYLIST_TEMPLATE"  => $d["default_categorylist_template"],
			"MANUFACTURER_TEMPLATE"          => $d["default_manufacturer_template"],
			"COUNTRY_LIST"                   => $d["country_list"],
			"PRODUCT_DEFAULT_IMAGE"          => $d["product_default_image"],
			"PRODUCT_OUTOFSTOCK_IMAGE"       => $d["product_outofstock_image"],
			"CATEGORY_DEFAULT_IMAGE"         => $d["category_default_image"],
			"ADDTOCART_IMAGE"                => $d["addtocart_image"],
			"REQUESTQUOTE_IMAGE"             => $d["requestquote_image"],
			"REQUESTQUOTE_BACKGROUND"        => $d["requestquote_background"],
			"PRE_ORDER_IMAGE"                => $d["pre_order_image"],
			"CATEGORY_SHORT_DESC_MAX_CHARS"  => $d["category_short_desc_max_chars"],
			"CATEGORY_SHORT_DESC_END_SUFFIX" => $d["category_short_desc_end_suffix"],
			"CATEGORY_DESC_MAX_CHARS"        => $d["category_desc_max_chars"],
			"CATEGORY_DESC_END_SUFFIX"       => $d["category_desc_end_suffix"],

			"CATEGORY_TITLE_MAX_CHARS"           => $d["category_title_max_chars"],
			"CATEGORY_TITLE_END_SUFFIX"          => $d["category_title_end_suffix"],
			"CATEGORY_PRODUCT_TITLE_MAX_CHARS"   => $d["category_product_title_max_chars"],
			"CATEGORY_PRODUCT_TITLE_END_SUFFIX"  => $d["category_product_title_end_suffix"],
			"CATEGORY_PRODUCT_DESC_MAX_CHARS"    => $d["category_product_desc_max_chars"],
			"CATEGORY_PRODUCT_DESC_END_SUFFIX"   => $d["category_product_desc_end_suffix"],
			"RELATED_PRODUCT_DESC_MAX_CHARS"     => $d["related_product_desc_max_chars"],
			"RELATED_PRODUCT_DESC_END_SUFFIX"    => $d["related_product_desc_end_suffix"],
			"RELATED_PRODUCT_TITLE_MAX_CHARS"    => $d["related_product_title_max_chars"],
			"RELATED_PRODUCT_TITLE_END_SUFFIX"   => $d["related_product_title_end_suffix"],
			"ACCESSORY_PRODUCT_DESC_MAX_CHARS"   => $d["accessory_product_desc_max_chars"],
			"ACCESSORY_PRODUCT_DESC_END_SUFFIX"  => $d["accessory_product_desc_end_suffix"],
			"ACCESSORY_PRODUCT_TITLE_MAX_CHARS"  => $d["accessory_product_title_max_chars"],
			"ACCESSORY_PRODUCT_TITLE_END_SUFFIX" => $d["accessory_product_title_end_suffix"],
			"ADDTOCART_BACKGROUND"               => $d["addtocart_background"],
			"SPLIT_DELIVERY_COST"                => $d["split_delivery_cost"],
			"TIME_DIFF_SPLIT_DELIVERY"           => $d["time_diff_split_delivery"],
			"NEWS_MAIL_FROM"                     => $d["news_mail_from"],
			"NEWS_FROM_NAME"                     => $d["news_from_name"],
			"DEFAULT_NEWSLETTER"                 => $d["default_newsletter"],

			"SHOP_COUNTRY"                          => $d["shop_country"],
			"DEFAULT_SHIPPING_COUNTRY"              => $d["default_shipping_country"],
			"REDCURRENCY_SYMBOL"                    => $d["currency_symbol"],
			"PRICE_SEPERATOR"                       => $d["price_seperator"],
			"THOUSAND_SEPERATOR"                    => $d["thousand_seperator"],
			"CURRENCY_SYMBOL_POSITION"              => $d["currency_symbol_position"],
			"PRICE_DECIMAL"                         => $d["price_decimal"],
			"CALCULATION_PRICE_DECIMAL"             => $d["calculation_price_decimal"],
			"UNIT_DECIMAL"                          => $d["unit_decimal"],
			"DEFAULT_DATEFORMAT"                    => $d["default_dateformat"],
			"CURRENCY_CODE"                         => $d["currency_code"],
			"ECONOMIC_INTEGRATION"                  => $d["economic_integration"],
			"DEFAULT_ECONOMIC_ACCOUNT_GROUP"        => $d["default_economic_account_group"],
			"ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC"      => $d["attribute_as_product_in_economic"],
			"DETAIL_ERROR_MESSAGE_ON"               => $d["detail_error_message_on"],
			"CAT_IS_LIGHTBOX"                       => $d["cat_is_lightbox"],
			"PRODUCT_IS_LIGHTBOX"                   => $d["product_is_lightbox"],
			"PRODUCT_DETAIL_IS_LIGHTBOX"            => $d["product_detail_is_lightbox"],
			"PRODUCT_ADDIMG_IS_LIGHTBOX"            => $d["product_addimg_is_lightbox"],
			"USE_PRODUCT_OUTOFSTOCK_IMAGE"          => $d["use_product_outofstock_image"],
			"WELCOME_MSG"                           => $d["welcome_msg"],
			"SHOP_NAME"                             => $d["shop_name"],
			"COUPONS_ENABLE"                        => $d["coupons_enable"],
			"VOUCHERS_ENABLE"                       => $d["vouchers_enable"],
			"APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT" => $d["apply_voucher_coupon_already_discount"],
			"SHOW_EMAIL_VERIFICATION"               => $d["show_email_verification"],

			"RATING_MSG"                     => $d["rating_msg"],
			"DISCOUNT_DURATION"              => $d["discount_duration"],
			"SPECIAL_DISCOUNT_MAIL_SEND"     => $d["special_discount_mail_send"],
			"DISCOUNT_PERCENTAGE"            => $d["discount_percentage"],
			"CATALOG_DAYS"                   => $d["catalog_days"],
			"CATALOG_REMINDER_1"             => $d["catalog_reminder_1"],
			"CATALOG_REMINDER_2"             => $d["catalog_reminder_2"],
			"FAVOURED_REVIEWS"               => $d["favoured_reviews"],
			"COLOUR_SAMPLE_REMAINDER_1"      => $d["colour_sample_remainder_1"],
			"COLOUR_SAMPLE_REMAINDER_2"      => $d["colour_sample_remainder_2"],
			"COLOUR_SAMPLE_REMAINDER_3"      => $d["colour_sample_remainder_3"],
			"COLOUR_COUPON_DURATION"         => $d["colour_coupon_duration"],
			"COLOUR_DISCOUNT_PERCENTAGE"     => $d["colour_discount_percentage"],
			"COLOUR_SAMPLE_DAYS"             => $d["colour_sample_days"],
			"CATEGORY_FRONTPAGE_INTROTEXT"   => $d["category_frontpage_introtext"],
			"REGISTRATION_INTROTEXT"         => $d["registration_introtext"],
			"REGISTRATION_COMPANY_INTROTEXT" => $d["registration_comp_introtext"],
			"VAT_INTROTEXT"                  => $d["vat_introtext"],
			"DELIVERY_RULE"                  => $d["delivery_rule"],
			"GOOGLE_ANA_TRACKER_KEY"         => $d["google_ana_tracker"],
			"AUTOGENERATED_SEO"              => $d["autogenerated_seo"],
			"ENABLE_SEF_PRODUCT_NUMBER"      => $d["enable_sef_product_number"],
			"ENABLE_SEF_NUMBER_NAME"         => $d["enable_sef_number_name"],

			"DEFAULT_CUSTOMER_REGISTER_TYPE"      => $d["default_customer_register_type"],
			"CHECKOUT_LOGIN_REGISTER_SWITCHER"    => $d["checkout_login_register_switcher"],
			"ADDTOCART_BEHAVIOUR"                 => $d["addtocart_behaviour"],
			"WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART" => $d["wanttoshowattributeimage"],
			"SHOW_PRODUCT_DETAIL"                 => $d["show_product_detail"],

			"ALLOW_CUSTOMER_REGISTER_TYPE" => $d["allow_customer_register_type"],
			"REQUIRED_VAT_NUMBER"          => $d["required_vat_number"],

			"OPTIONAL_SHIPPING_ADDRESS" => $d["optional_shipping_address"],
			"SHIPPING_METHOD_ENABLE"    => $d["shipping_method_enable"],

			"SEO_PAGE_TITLE"                      => $d["seo_page_title"],
			"SEO_PAGE_HEADING"                    => $d["seo_page_heading"],
			"SEO_PAGE_SHORT_DESCRIPTION"          => $d["seo_page_short_description"],
			"SEO_PAGE_DESCRIPTION"                => $d["seo_page_description"],
			"SEO_PAGE_KEYWORDS"                   => $d["seo_page_keywords"],
			"SEO_PAGE_LANGAUGE"                   => $d["seo_page_language"],
			"SEO_PAGE_TITLE_CATEGORY"             => $d["seo_page_title_category"],
			"SEO_PAGE_HEADING_CATEGORY"           => $d["seo_page_heading_category"],
			"SEO_PAGE_SHORT_DESCRIPTION_CATEGORY" => $d["seo_page_short_description_category"],
			"SEO_PAGE_DESCRIPTION_CATEGORY"       => $d["seo_page_description_category"],
			"SEO_PAGE_KEYWORDS_CATEGORY"          => $d["seo_page_keywords_category"],
			"SEO_PAGE_TITLE_MANUFACTUR"           => $d["seo_page_title_manufactur"],
			"SEO_PAGE_HEADING_MANUFACTUR"         => $d["seo_page_heading_manufactur"],
			"SEO_PAGE_DESCRIPTION_MANUFACTUR"     => $d["seo_page_description_manufactur"],
			"SEO_PAGE_KEYWORDS_MANUFACTUR"        => $d["seo_page_keywords_manufactur"],
			"SEO_PAGE_CANONICAL_MANUFACTUR"       => $d["seo_page_canonical_manufactur"],

			"USE_TAX_EXEMPT"       => $d["use_tax_exempt"],
			"TAX_EXEMPT_APPLY_VAT" => $d["tax_exempt_apply_vat"],

			"COUPONINFO"       => $d["couponinfo"],
			"MY_TAGS"          => $d["my_tags"],
			"MY_WISHLIST"      => $d["my_wishlist"],
			"COMPARE_PRODUCTS" => $d["compare_products"],

			"REGISTER_METHOD"                    => $d["register_method"],
			"ZERO_PRICE_REPLACE"                 => $d["zero_price_replacement"],
			"ZERO_PRICE_REPLACE_URL"             => $d["zero_price_replacement_url"],
			"PRICE_REPLACE"                      => $d["price_replacement"],
			"PRICE_REPLACE_URL"                  => $d["price_replacement_url"],
			"PAYMENT_CALCULATION_ON"             => $d["payment_calculation_on"],
			"PORTAL_SHOP"                        => $d["portal_shop"],
			"DEFAULT_PORTAL_NAME"                => $d["default_portal_name"],
			"DEFAULT_PORTAL_LOGO"                => $d["default_portal_logo"],
			"SHOPPER_GROUP_DEFAULT_PRIVATE"      => $d["shopper_group_default_private"],
			"SHOPPER_GROUP_DEFAULT_COMPANY"      => $d["shopper_group_default_company"],
			"NEW_SHOPPER_GROUP_GET_VALUE_FROM"   => $d["new_shopper_group_get_value_from"],
			"SHOPPER_GROUP_DEFAULT_UNREGISTERED" => $d["shopper_group_default_unregistered"],

			"PRODUCT_EXPIRE_TEXT" => $d["product_expire_text"],
			"TERMS_ARTICLE_ID"    => $d["terms_article_id"],

			"INVOICE_NUMBER_TEMPLATE"                      => $d["invoice_number_template"],
			"REAL_INVOICE_NUMBER_TEMPLATE"                 => $d["real_invoice_number_template"],
			"FIRST_INVOICE_NUMBER"                         => $d["first_invoice_number"],
			"INVOICE_NUMBER_FOR_FREE_ORDER"                => $d["invoice_number_for_free_order"],
			"DEFAULT_CATEGORY_ORDERING_METHOD"             => $d["default_category_ordering_method"],
			"DEFAULT_PRODUCT_ORDERING_METHOD"              => $d["default_product_ordering_method"],
			"DEFAULT_RELATED_ORDERING_METHOD"              => $d["default_related_ordering_method"],
			"DEFAULT_ACCESSORY_ORDERING_METHOD"            => $d["default_accessory_ordering_method"],
			"DEFAULT_MANUFACTURER_ORDERING_METHOD"         => $d["default_manufacturer_ordering_method"],
			"DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD" => $d["default_manufacturer_product_ordering_method"],
			"WELCOMEPAGE_INTROTEXT"                        => $d["welcomepage_introtext"],
			"NEW_CUSTOMER_SELECTION"                       => $d["new_customer_selection"],
			"AJAX_CART_BOX"                                => $d["ajax_cart_box"],
			"IS_PRODUCT_RESERVE"                           => $d["is_product_reserve"],
			"CART_RESERVATION_MESSAGE"                     => $d["cart_reservation_message"],
			"WITHOUT_VAT_TEXT_INFO"                        => $d["without_vat_text_info"],
			"WITH_VAT_TEXT_INFO"                           => $d["with_vat_text_info"],
			"DEFAULT_STOCKROOM"                            => $d["default_stockroom"],
			"DEFAULT_CART_CHECKOUT_ITEMID"                 => $d["default_cart_checkout_itemid"],
			"USE_IMAGE_SIZE_SWAPPING"                      => $d["use_image_size_swapping"],
			"DEFAULT_WRAPPER_THUMB_WIDTH"                  => $d["default_wrapper_thumb_width"],
			"DEFAULT_WRAPPER_THUMB_HEIGHT"                 => $d["default_wrapper_thumb_height"],
			"DEFAULT_QUANTITY"                             => $d["default_quantity"],
			"DEFAULT_QUANTITY_SELECTBOX_VALUE"             => $d["default_quantity_selectbox_value"],
			"AUTO_SCROLL_WRAPPER"                          => $d["auto_scroll_wrapper"],
			"MAXCATEGORY"                                  => $d["maxcategory"],

			"ECONOMIC_INVOICE_DRAFT"       => $d["economic_invoice_draft"],
			"BOOKING_ORDER_STATUS"         => $d["booking_order_status"],
			"ECONOMIC_BOOK_INVOICE_NUMBER" => $d["economic_book_invoice_number"],

			"PORTAL_LOGIN_ITEMID"    => $d["portal_login_itemid"],
			"PORTAL_LOGOUT_ITEMID"   => $d["portal_logout_itemid"],
			"APPLY_VAT_ON_DISCOUNT"  => $d["apply_vat_on_discount"],
			"CONTINUE_REDIRECT_LINK" => $d["continue_redirect_link"],

			"DEFAULT_LINK_FIND"                          => $d["next_previous_link"],
			"IMAGE_PREVIOUS_LINK_FIND"                   => $d["image_previous_link"],
			"PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE" => $d["product_detail_lighbox_close_button_image"],
			"IMAGE_NEXT_LINK_FIND"                       => $d["image_next_link"],
			"CUSTOM_PREVIOUS_LINK_FIND"                  => $d["custom_previous_link"],
			"CUSTOM_NEXT_LINK_FIND"                      => $d["custom_next_link"],
			"DAFULT_NEXT_LINK_SUFFIX"                    => $d["default_next_suffix"],
			"DAFULT_PREVIOUS_LINK_PREFIX"                => $d["default_previous_prefix"],
			"DAFULT_RETURN_TO_CATEGORY_PREFIX"           => $d["return_to_category_prefix"],
			"ALLOW_MULTIPLE_DISCOUNT"                    => $d["allow_multiple_discount"],

			"DISCOUNT_ENABLE"         => $d["discount_enable"],
			"DISCOUNT_TYPE"           => $d["discount_type"],
			"INVOICE_MAIL_ENABLE"     => $d["invoice_mail_enable"],
			"WISHLIST_LOGIN_REQUIRED" => $d["wishlist_login_required"],

			"INVOICE_MAIL_SEND_OPTION"      => $d["invoice_mail_send_option"],
			"ORDER_MAIL_AFTER"              => $d["order_mail_after"],
			"ACCESSORY_PRODUCT_IN_LIGHTBOX" => $d["accessory_product_in_lightbox"],
			"MINIMUM_ORDER_TOTAL"           => $d["minimum_order_total"],
			"MANUFACTURER_TITLE_MAX_CHARS"  => $d["manufacturer_title_max_chars"],
			"MANUFACTURER_TITLE_END_SUFFIX" => $d["manufacturer_title_end_suffix"],

			"DEFAULT_VOLUME_UNIT"          => $d["default_volume_unit"],
			"DEFAULT_WEIGHT_UNIT"          => $d["default_weight_unit"],
			"RATING_REVIEW_LOGIN_REQUIRED" => $d["rating_review_login_required"],
			"WEBPACK_ENABLE_SMS"           => $d["webpack_enable_sms"],
			"WEBPACK_ENABLE_EMAIL_TRACK"   => $d["webpack_enable_email_track"],

			"STATISTICS_ENABLE"       => $d["statistics_enable"],
			"NEWSLETTER_ENABLE"       => $d["newsletter_enable"],
			"NEWSLETTER_CONFIRMATION" => $d["newsletter_confirmation"],
			"WATERMARK_IMAGE"         => $d["watermark_image"],

			"WATERMARK_CATEGORY_THUMB_IMAGE"     => $d["watermark_category_thumb_image"],
			"WATERMARK_CATEGORY_IMAGE"           => $d["watermark_category_image"],
			"WATERMARK_PRODUCT_IMAGE"            => $d["watermark_product_image"],
			"WATERMARK_PRODUCT_THUMB_IMAGE"      => $d["watermark_product_thumb_image"],
			"WATERMARK_PRODUCT_ADDITIONAL_IMAGE" => $d["watermark_product_additional_image"],
			"WATERMARK_CART_THUMB_IMAGE"         => $d["watermark_cart_thumb_image"],
			"WATERMARK_GIFTCART_IMAGE"           => $d["watermark_giftcart_image"],
			"WATERMARK_GIFTCART_THUMB_IMAGE"     => $d["watermark_giftcart_thumb_image"],
			"WATERMARK_MANUFACTURER_THUMB_IMAGE" => $d["watermark_manufacturer_thumb_image"],
			"WATERMARK_MANUFACTURER_IMAGE"       => $d["watermark_manufacturer_image"],

			'GLS_CUSTOMER_ID'          => $d["gls_customer_id"],
			'CLICKATELL_USERNAME'      => $d["clickatell_username"],
			'CLICKATELL_PASSWORD'      => $d["clickatell_password"],
			'CLICKATELL_API_ID'        => $d["clickatell_api_id"],
			'CLICKATELL_ENABLE'        => $d["clickatell_enable"],
			'CLICKATELL_ORDER_STATUS'  => $d["clickatell_order_status"],
			'PRE_USE_AS_CATALOG'       => $d["use_as_catalog"],
			'SHOW_SHIPPING_IN_CART'    => $d["show_shipping_in_cart"],
			'MANUFACTURER_MAIL_ENABLE' => $d["manufacturer_mail_enable"],
			'SUPPLIER_MAIL_ENABLE'     => $d["supplier_mail_enable"],
			'PRODUCT_COMPARISON_TYPE'  => $d["product_comparison_type"],
			'COMPARE_TEMPLATE_ID'      => $d["compare_template_id"],
			'SSL_ENABLE_IN_CHECKOUT'   => $d["ssl_enable_in_checkout"],
			'VAT_RATE_AFTER_DISCOUNT'  => $d["vat_rate_after_discount"],
			'PRODUCT_DOWNLOAD_ROOT'    => $d["product_download_root"],
			'TWOWAY_RELATED_PRODUCT'   => $d["twoway_related_product"],

			'PRODUCT_HOVER_IMAGE_ENABLE'        => $d["product_hover_image_enable"],
			'PRODUCT_HOVER_IMAGE_WIDTH'         => $d["product_hover_image_width"],
			'PRODUCT_HOVER_IMAGE_HEIGHT'        => $d["product_hover_image_height"],
			'ADDITIONAL_HOVER_IMAGE_ENABLE'     => $d["additional_hover_image_enable"],
			'ADDITIONAL_HOVER_IMAGE_WIDTH'      => $d["additional_hover_image_width"],
			'ADDITIONAL_HOVER_IMAGE_HEIGHT'     => $d["additional_hover_image_height"],
			'SSL_ENABLE_IN_BACKEND'             => $d["ssl_enable_in_backend"],
			"SHOW_PRICE_SHOPPER_GROUP_LIST"     => $d["show_price_shopper_group_list"],
			"SHOW_PRICE_USER_GROUP_LIST"        => $d["show_price_user_group_list"],
			"SHIPPING_AFTER"                    => $d["shipping_after"],
			"ENABLE_ADDRESS_DETAIL_IN_SHIPPING" => $d["enable_address_detail_in_shipping"],

			"CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS"  => $d['category_product_short_desc_max_chars'],
			"CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX" => $d['category_product_short_desc_end_suffix'],
			"RELATED_PRODUCT_SHORT_DESC_MAX_CHARS"   => $d['related_product_short_desc_max_chars'],
			"RELATED_PRODUCT_SHORT_DESC_END_SUFFIX"  => $d['related_product_short_desc_end_suffix'],
			"CALCULATE_VAT_ON"                       => $d['calculate_vat_on'],
			"REMOTE_UPDATE_DOMAIN_URL"               => 'http://dev.redcomponent.com/',
			"ONESTEP_CHECKOUT_ENABLE"                => $d["onestep_checkout_enable"],
			"SHOW_TAX_EXEMPT_INFRONT"                => $d["show_tax_exempt_infront"],
			"NOOF_THUMB_FOR_SCROLLER"                => $d["noof_thumb_for_scroller"],
			"NOOF_SUBATTRIB_THUMB_FOR_SCROLLER"      => $d["noof_subattrib_thumb_for_scroller"],

			"INDIVIDUAL_ADD_TO_CART_ENABLE"       => $d["individual_add_to_cart_enable"],
			"ACCESSORY_AS_PRODUCT_IN_CART_ENABLE" => $d["accessory_as_product_in_cart_enable"],
			"POSTDK_CUSTOMER_NO"                  => $d["postdk_customer_no"],
			"POSTDK_CUSTOMER_PASSWORD"            => $d["postdk_customer_password"],
			"POSTDK_INTEGRATION"                  => $d["postdk_integration"],
			"POSTDANMARK_ADDRESS"                 => $d["postdk_address"],
			"POSTDANMARK_POSTALCODE"              => $d["postdk_postalcode"],
			"AUTO_GENERATE_LABEL"                 => $d["auto_generate_label"],
			"GENERATE_LABEL_ON_STATUS"            => $d["generate_label_on_status"],

			"MENUHIDE"                   => $d["menuhide"],
			"AJAX_CART_DISPLAY_TIME"     => $d['ajax_cart_display_time'],
			"MEDIA_ALLOWED_MIME_TYPE"    => $d['media_allowed_mime_type'],
			"IMAGE_QUALITY_OUTPUT"       => $d['image_quality_output'],
			"SEND_CATALOG_REMINDER_MAIL" => $d['send_catalog_reminder_mail'],
			"CATEGORY_IN_SEF_URL"        => $d['category_in_sef_url'],
			"CATEGORY_TREE_IN_SEF_URL"   => $d['category_tree_in_sef_url'],
			"USE_BLANK_AS_INFINITE"      => $d['use_blank_as_infinite'],
			"USE_ENCODING"               => $d['use_encoding'],
			"CREATE_ACCOUNT_CHECKBOX"    => $d['create_account_checkbox'],

			"SHOW_QUOTATION_PRICE"                  => $d['show_quotation_price'],
			"CHILDPRODUCT_DROPDOWN"                 => $d['childproduct_dropdown'],
			"PURCHASE_PARENT_WITH_CHILD"            => $d['purchase_parent_with_child'],
			"ADDTOCART_DELETE"                      => $d["addtocart_delete"],
			"ADDTOCART_UPDATE"                      => $d["addtocart_update"],
			"DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA"   => $d["display_out_of_stock_attribute_data"],
			"SEND_MAIL_TO_CUSTOMER"                 => $d["send_mail_to_customer"],
			"AJAX_DETAIL_BOX_WIDTH"                 => $d["ajax_detail_box_width"],
			"AJAX_DETAIL_BOX_HEIGHT"                => $d["ajax_detail_box_height"],
			"AJAX_BOX_WIDTH"                        => $d["ajax_box_width"],
			"AJAX_BOX_HEIGHT"                       => $d["ajax_box_height"],
			"DEFAULT_STOCKROOM_BELOW_AMOUNT_NUMBER" => $d["default_stockroom_below_amount_number"],
			"LOAD_REDSHOP_STYLE"                    => $d["load_redshop_style"],
			"ENABLE_STOCKROOM_NOTIFICATION"         => $d["enable_stockroom_notification"],
			"BACKWARD_COMPATIBLE_JS"                => $d['backward_compatible_js'],
			"BACKWARD_COMPATIBLE_PHP"               => $d['backward_compatible_php'],
			"IMPORT_MIN_FILE_SIZE"                  => $d['import_min_file_size'],
			"IMPORT_MAX_FILE_SIZE"                  => $d['import_max_file_size'],
			"IMPORT_FILE_MIME"                      => $d['import_file_mime'],
			"IMPORT_FILE_EXTENSION"                 => $d['import_file_extension'],
		);

		if ($d["cart_timeout"] <= 0)
		{
			$config_array["CART_TIMEOUT"] = 20;
		}

		else
		{
			$config_array["CART_TIMEOUT"] = $d["cart_timeout"];
		}

		$config_array["DEFAULT_QUOTATION_MODE_PRE"] = $d["default_quotation_mode"];

		$config_array["SHOW_PRICE_PRE"] = $d["show_price"];

		if ($d["newsletter_mail_chunk"] == 0)
		{
			$d["newsletter_mail_chunk"] = 1;
		}

		if ($d["newsletter_mail_pause_time"] == 0)
		{
			$d["newsletter_mail_pause_time"] = 1;
		}

		$config_array["NEWSLETTER_MAIL_CHUNK"]      = $d["newsletter_mail_chunk"];
		$config_array["NEWSLETTER_MAIL_PAUSE_TIME"] = $d["newsletter_mail_pause_time"];

		return $config_array;
	}

	/**
	 * We are using file for saving configuration variables
	 * We need some variables that can be uses as dynamically
	 * Here is the logic to define that variables
	 *
	 * IMPORTANT: we need to call this function in plugin or module manually to see the effect of this variables
	 *
	 * @return void
	 */
	public function defineDynamicVars()
	{
		$config = Redshop::getConfig();

		$config->set('SHOW_PRICE', $this->showPrice());
		$config->set('USE_AS_CATALOG', $this->getCatalog());

		$quotationModePre = (int) $config->get('DEFAULT_QUOTATION_MODE_PRE');

		$config->set('DEFAULT_QUOTATION_MODE', $quotationModePre);

		if ($quotationModePre == 1)
		{
			$config->set('DEFAULT_QUOTATION_MODE', (int) $this->setQuotationMode());
		}
	}

	public function showPrice()
	{
		$user           = JFactory::getUser();
		$userHelper     = rsUserHelper::getInstance();
		$shopperGroupId = $userHelper->getShopperGroup($user->id);
		$list           = $userHelper->getShopperGroupList($shopperGroupId);

		if ($list)
		{
			$list = $list[0];

			if (($list->show_price == "yes") || ($list->show_price == "global" && Redshop::getConfig()->get('SHOW_PRICE_PRE') == 1)
				|| ($list->show_price == "" && Redshop::getConfig()->get('SHOW_PRICE_PRE') == 1)
			)
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}
		else
		{
			return Redshop::getConfig()->get('SHOW_PRICE_PRE');
		}
	}

	public function getCatalog()
	{
		$user           = JFactory::getUser();
		$userHelper     = rsUserHelper::getInstance();
		$shopperGroupId = $userHelper->getShopperGroup($user->id);
		$list           = $userHelper->getShopperGroupList($shopperGroupId);

		if ($list)
		{
			$list = $list[0];

			if (($list->use_as_catalog == "yes") || ($list->use_as_catalog == "global" && Redshop::getConfig()->get('PRE_USE_AS_CATALOG') == 1)
				|| ($list->use_as_catalog == "" && Redshop::getConfig()->get('PRE_USE_AS_CATALOG') == 1)
			)
			{
				return 1;
			}
			else
			{
				return 0;
			}
		}

		else
		{
			return Redshop::getConfig()->get('PRE_USE_AS_CATALOG');
		}
	}

	public function setQuotationMode()
	{
		$db               = JFactory::getDbo();
		$user             = JFactory::getUser();
		$userhelper       = rsUserHelper::getInstance();
		$shopper_group_id = Redshop::getConfig()->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED');

		if ($user->id)
		{
			$getShopperGroupID = $userhelper->getShopperGroup($user->id);

			if ($getShopperGroupID)
			{
				$shopper_group_id = $getShopperGroupID;
			}
		}

		$qurey = "SELECT * FROM #__redshop_shopper_group "
			. "WHERE shopper_group_id = " . (int) $shopper_group_id;
		$db->setQuery($qurey);
		$list = $db->loadObject();

		if ($list)
		{
			if ($list->shopper_group_quotation_mode)
			{
				return true;
			}

			return false;
		}

		return Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE_PRE');
	}

	public function maxchar($desc = '', $maxchars = 0, $suffix = '')
	{
		$strdesc = '';

		if ((int) $maxchars <= 0)
		{
			$strdesc = $desc;
		}
		else
		{
			$strdesc = $this->substrws($desc, $maxchars, $suffix);
		}

		return $strdesc;
	}

	public function substrws($text, $length = 50, $ending = '...', $exact = false, $considerHtml = true)
	{
		if ($considerHtml)
		{
			if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length)
			{
				return $text;
			}

			$totalLength = strlen(strip_tags($ending));
			$openTags    = array();
			$truncate    = '';

			preg_match_all('/(<\/?([\w+]+)[^>]*>)?([^<>]*)/', $text, $tags, PREG_SET_ORDER);

			foreach ($tags as $tag)
			{
				if (!preg_match('/img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param/s', $tag[2]))
				{
					if (preg_match('/<[\w]+[^>]*>/s', $tag[0]))
					{
						array_unshift($openTags, $tag[2]);
					}

					elseif (preg_match('/<\/([\w]+)[^>]*>/s', $tag[0], $closeTag))
					{
						$pos = array_search($closeTag[1], $openTags);

						if ($pos !== false)
						{
							array_splice($openTags, $pos, 1);
						}
					}
				}

				$truncate .= $tag[1];

				$contentLength = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', ' ', $tag[3]));

				if ($contentLength + $totalLength > $length)
				{
					$left           = $length - $totalLength;
					$entitiesLength = 0;

					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|&#x[0-9a-f]{1,6};/i', $tag[3], $entities, PREG_OFFSET_CAPTURE))
					{
						foreach ($entities[0] as $entity)
						{
							if ($entity[1] + 1 - $entitiesLength <= $left)
							{
								$left--;
								$entitiesLength += strlen($entity[0]);
							}
							else
							{
								break;
							}
						}
					}

					$truncate .= substr($tag[3], 0, $left + $entitiesLength);
					break;
				}
				else
				{
					$truncate    .= $tag[3];
					$totalLength = $contentLength;
				}

				if ($totalLength >= $length)
				{
					break;
				}
			}
		}

		else
		{
			if (strlen($text) <= $length)
			{
				return $text;
			}
			else
			{
				$truncate = substr($text, 0, $length - strlen($ending));
			}
		}

		if (!$exact)
		{
			$spacepos = strrpos($truncate, ' ');

			if ($spacepos > -1)
			{
				if ($considerHtml)
				{
					$bits = substr($truncate, $spacepos);
					preg_match_all('/<\/([a-z])>/', $bits, $droppedTags, PREG_SET_ORDER);

					if (!empty($droppedTags))
					{
						foreach ($droppedTags as $closingTag)
						{
							if (!in_array($closingTag[1], $openTags))
							{
								array_unshift($openTags, $closingTag[1]);
							}
						}
					}
				}

				$truncate = substr($truncate, 0, $spacepos);
			}
		}

		$truncate .= $ending;

		if ($considerHtml)
		{
			foreach ($openTags as $tag)
			{
				$truncate .= '</' . $tag . '>';
			}
		}

		return $truncate;
	}

	/**
	 * Method to get date format
	 *
	 * @access    public
	 *
	 * @return    array
	 *
	 * @since     1.5
	 */
	public function getDateFormat()
	{
		$option = array();
		$mon    = JText::_(strtoupper(date("M")));
		$month  = JText::_(strtoupper(date("F")));
		$wk     = JText::_(strtoupper(date("D")));
		$week   = JText::_(strtoupper(date("l")));

		$option[] = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$option[] = JHTML::_('select.option', 'Y-m-d', date("Y-m-d"));
		$option[] = JHTML::_('select.option', 'd-m-Y', date("d-m-Y"));
		$option[] = JHTML::_('select.option', 'd.m.Y', date("d.m.Y"));
		$option[] = JHTML::_('select.option', 'Y/m/d', date("Y/m/d"));
		$option[] = JHTML::_('select.option', 'd/m/Y', date("d/m/Y"));
		$option[] = JHTML::_('select.option', 'm/d/y', date("m/d/y"));
		$option[] = JHTML::_('select.option', 'm-d-y', date("m-d-y"));
		$option[] = JHTML::_('select.option', 'm.d.y', date("m.d.y"));
		$option[] = JHTML::_('select.option', 'm/d/Y', date("m/d/Y"));
		$option[] = JHTML::_('select.option', 'm-d-Y', date("m-d-Y"));
		$option[] = JHTML::_('select.option', 'm.d.Y', date("m.d.Y"));
		$option[] = JHTML::_('select.option', 'd/M/Y', date("d/") . $mon . date("/Y"));
		$option[] = JHTML::_('select.option', 'M d,Y', $mon . date(" d, Y"));
		$option[] = JHTML::_('select.option', 'd M Y', date("d ") . $mon . date(" Y"));
		$option[] = JHTML::_('select.option', 'd M Y, h:i:s', date("d ") . $mon . date(" Y, h:i:s"));
		$option[] = JHTML::_('select.option', 'd M Y, h:i A', date("d ") . $mon . date(" Y, h:i A"));
		$option[] = JHTML::_('select.option', 'd-m-Y, h:i:A', date("d-m-Y, h:i:A"));
		$option[] = JHTML::_('select.option', 'd.m.Y, h:i:A', date("d.m.Y, h:i:A"));
		$option[] = JHTML::_('select.option', 'd/m/Y, h:i:A', date("d/m/Y, h:i:A"));
		$option[] = JHTML::_('select.option', 'd M Y, H:i:s', date("d ") . $mon . date(" Y, H:i:s"));
		$option[] = JHTML::_('select.option', 'd-m-Y, H:i:s', date("d-m-Y, H:i:s"));
		$option[] = JHTML::_('select.option', 'd.m.Y, H:i:s', date("d.m.Y, H:i:s"));
		$option[] = JHTML::_('select.option', 'd/m/Y, H:i:s', date("d/m/Y, H:i:s"));
		$option[] = JHTML::_('select.option', 'F d, Y', $month . date(" d, Y"));
		$option[] = JHTML::_('select.option', 'D M d, Y', $wk . " " . $mon . date(" d, Y"));
		$option[] = JHTML::_('select.option', 'l F d, Y', $week . " " . $month . date(" d, Y"));

		return $option;
	}

	/**
	 * Method to convert date according to format
	 *
	 * @access    public
	 *
	 * @return    array
	 *
	 * @since     1.5
	 */
	public function convertDateFormat($date)
	{
		if ($date <= 0)
		{
			$date = time();
		}

		if (Redshop::getConfig()->get('DEFAULT_DATEFORMAT'))
		{
			$convertformat = date(Redshop::getConfig()->get('DEFAULT_DATEFORMAT'), $date);

			if (strpos(Redshop::getConfig()->get('DEFAULT_DATEFORMAT'), "M") !== false)
			{
				$convertformat = str_replace("Jan", JText::_('COM_REDSHOP_JAN'), $convertformat);
				$convertformat = str_replace("Feb", JText::_('COM_REDSHOP_FEB'), $convertformat);
				$convertformat = str_replace("Mar", JText::_('COM_REDSHOP_MAR'), $convertformat);
				$convertformat = str_replace("Apr", JText::_('COM_REDSHOP_APR'), $convertformat);
				$convertformat = str_replace("May", JText::_('COM_REDSHOP_MAY'), $convertformat);
				$convertformat = str_replace("Jun", JText::_('COM_REDSHOP_JUN'), $convertformat);
				$convertformat = str_replace("Jul", JText::_('COM_REDSHOP_JUL'), $convertformat);
				$convertformat = str_replace("Aug", JText::_('COM_REDSHOP_AUG'), $convertformat);
				$convertformat = str_replace("Sep", JText::_('COM_REDSHOP_SEP'), $convertformat);
				$convertformat = str_replace("Oct", JText::_('COM_REDSHOP_OCT'), $convertformat);
				$convertformat = str_replace("Nov", JText::_('COM_REDSHOP_NOV'), $convertformat);
				$convertformat = str_replace("Dec", JText::_('COM_REDSHOP_DEC'), $convertformat);
			}

			if (strpos(Redshop::getConfig()->get('DEFAULT_DATEFORMAT'), "F") !== false)
			{
				$convertformat = str_replace("January", JText::_('COM_REDSHOP_JANUARY'), $convertformat);
				$convertformat = str_replace("February", JText::_('COM_REDSHOP_FEBRUARY'), $convertformat);
				$convertformat = str_replace("March", JText::_('COM_REDSHOP_MARCH'), $convertformat);
				$convertformat = str_replace("April", JText::_('COM_REDSHOP_APRIL'), $convertformat);
				$convertformat = str_replace("May", JText::_('COM_REDSHOP_MAY'), $convertformat);
				$convertformat = str_replace("June", JText::_('COM_REDSHOP_JUNE'), $convertformat);
				$convertformat = str_replace("July", JText::_('COM_REDSHOP_JULY'), $convertformat);
				$convertformat = str_replace("August", JText::_('COM_REDSHOP_AUGUST'), $convertformat);
				$convertformat = str_replace("September", JText::_('COM_REDSHOP_SEPTEMBER'), $convertformat);
				$convertformat = str_replace("October", JText::_('COM_REDSHOP_OCTOBER'), $convertformat);
				$convertformat = str_replace("November", JText::_('COM_REDSHOP_NOVEMBER'), $convertformat);
				$convertformat = str_replace("December", JText::_('COM_REDSHOP_DECEMBER'), $convertformat);
			}

			if (strpos(Redshop::getConfig()->get('DEFAULT_DATEFORMAT'), "D") !== false)
			{
				$convertformat = str_replace("Mon", JText::_('COM_REDSHOP_MON'), $convertformat);
				$convertformat = str_replace("Tue", JText::_('COM_REDSHOP_TUE'), $convertformat);
				$convertformat = str_replace("Wed", JText::_('COM_REDSHOP_WED'), $convertformat);
				$convertformat = str_replace("Thu", JText::_('COM_REDSHOP_THU'), $convertformat);
				$convertformat = str_replace("Fri", JText::_('COM_REDSHOP_FRI'), $convertformat);
				$convertformat = str_replace("Sat", JText::_('COM_REDSHOP_SAT'), $convertformat);
				$convertformat = str_replace("Sun", JText::_('COM_REDSHOP_SUN'), $convertformat);
			}

			if (strpos(Redshop::getConfig()->get('DEFAULT_DATEFORMAT'), "l") !== false)
			{
				$convertformat = str_replace("Monday", JText::_('COM_REDSHOP_MONDAY'), $convertformat);
				$convertformat = str_replace("Tuesday", JText::_('COM_REDSHOP_TUESDAY'), $convertformat);
				$convertformat = str_replace("Wednesday", JText::_('COM_REDSHOP_WEDNESDAY'), $convertformat);
				$convertformat = str_replace("Thursday", JText::_('COM_REDSHOP_THURSDAY'), $convertformat);
				$convertformat = str_replace("Friday", JText::_('COM_REDSHOP_FRIDAY'), $convertformat);
				$convertformat = str_replace("Saturday", JText::_('COM_REDSHOP_SATURDAY'), $convertformat);
				$convertformat = str_replace("Sunday", JText::_('COM_REDSHOP_SUNDAY'), $convertformat);
			}
		}

		else
		{
			$convertformat = date("Y-m-d", $date);
		}

		return $convertformat;
	}

	/**
	 * Method to get Country by ID
	 *
	 * @param   int $conid country id
	 *
	 * @return  country
	 */
	public function getCountryId($conid)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('id'))
			->from($db->qn('#__redshop_country'))
			->where($db->qn('country_3_code') . ' LIKE ' . $db->q($conid));

		$db->setQuery($query);

		return $db->loadResult();
	}

	/**
	 * Method to get Country by ID
	 *
	 * @param   int $conid country id
	 *
	 * @return  country
	 */
	public function getCountryCode2($conid)
	{
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->qn('country_2_code'))
			->from($db->qn('#__redshop_country'))
			->where($db->qn('country_3_code') . ' LIKE ' . $db->q($conid));
		$db->setQuery($query);

		return $db->loadResult();
	}

	public function getStateCode2($conid)
	{
		$db    = JFactory::getDbo();
		$query = 'SELECT state_2_code FROM #__redshop_state '
			. 'WHERE state_3_code LIKE ' . $db->quote($conid);
		$db->setQuery($query);

		return $db->loadResult();
	}

	public function getStateCode($conid, $tax_code)
	{
		if (empty($tax_code))
		{
			return null;
		}

		$db    = JFactory::getDbo();
		$query = 'SELECT  state_3_code , show_state FROM #__redshop_state '
			. 'WHERE state_2_code LIKE ' . $db->quote($tax_code)
			. ' AND id = ' . (int) $conid;
		$db->setQuery($query);
		$rslt_data = $db->loadObjectList();

		if ($rslt_data[0]->show_state == 3)
		{
			$state_code = $rslt_data[0]->state_3_code;

			return $state_code;
		}

		$state_code = $tax_code;

		return $state_code;
	}
}
