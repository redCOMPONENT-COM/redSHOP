<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
if (! defined ( '_VALID_MOS' ) && ! defined ( '_JEXEC' ))
	die ( 'Direct Access to ' . basename ( __FILE__ ) . ' is not allowed.' );
require_once(JPATH_SITE.DS.'components'.DS.'com_redshop'.DS.'helpers'.DS.'user.php');

class Redconfiguration {

	var $_def_array = null;
	var $_configpath = null;
	var $_config_dist_path = null;
	var $_config_bkp_path = null;
	var $_config_tmp_path = null;
	var $_cfgdata = null;
	var $_table_prefix = "redshop";

	/**
	 * define default path
	 */
	function __construct() {

		$this->_configpath = JPATH_SITE . DS . "administrator" . DS . "components" . DS . "com_redshop" . DS . "helpers" . DS . "redshop.cfg.php";
		$this->_config_dist_path = JPATH_SITE . DS . "administrator" . DS . "components" . DS . "com_redshop" . DS . "helpers" . DS . "wizard" . DS . "redshop.cfg.dist.php";
		$this->_config_bkp_path = JPATH_SITE . DS . "administrator" . DS . "components" . DS . "com_redshop" . DS . "helpers" . DS . "wizard" . DS . "redshop.cfg.bkp.php";
		$this->_config_tmp_path = JPATH_SITE . DS . "administrator" . DS . "components" . DS . "com_redshop" . DS . "helpers" . DS . "wizard" . DS . "redshop.cfg.tmp.php";
		$this->_config_def_path = JPATH_SITE . DS . "administrator" . DS . "components" . DS . "com_redshop" . DS . "helpers" . DS . "wizard" . DS . "redshop.cfg.def.php";

		if (! defined ( 'JSYSTEM_IMAGES_PATH' ))
		{
			define ( 'JSYSTEM_IMAGES_PATH', JURI::root().'media/system/images/' );
		}
		if (! defined ( 'REDSHOP_ADMIN_IMAGES_ABSPATH' ))
		{
			define ( 'REDSHOP_ADMIN_IMAGES_ABSPATH', JURI::root().'administrator/components/com_redshop/assets/images/' );
		}
		if (! defined ( 'REDSHOP_FRONT_IMAGES_ABSPATH' ))
		{
			define ( 'REDSHOP_FRONT_IMAGES_ABSPATH', JURI::root().'components/com_redshop/assets/images/' );
		}
		if (! defined ( 'REDSHOP_FRONT_IMAGES_RELPATH' ))
		{
			define ( 'REDSHOP_FRONT_IMAGES_RELPATH', JPATH_ROOT.DS.'components/com_redshop/assets/images/' );
		}
	}

	/**
	 * check configuration file exist or not
	 *
	 * @return boolean
	 */
	function isCFGFile() {

		if (! file_exists ( $this->_configpath )) {
			return false;
		}

		require_once ($this->_configpath);

		return true;
	}

	/**
	 * check table exist
	 *
	 * @return boolean
	 */
	function isCFGTable() {

		$db = & JFactory::getDBO ();

		$query = 'show tables like "' . $db->getPrefix () . 'redshop_configuration"';
		$db->setQuery ( $query );
		$result = $db->loadResult ();

		if (count ( $result ) <= 0) {
			return false;
		}

		return true;
	}

	/**
	 * write configuration table data to file
	 *
	 * TODO: only use when upgrade to 1.1 from 1.0
	 *
	 * @param array $org
	 */
	function setCFGTableData($org = array()) {

		$db = & JFactory::getDBO ();

		// getData From table
		$query = "SELECT * FROM #__redshop_configuration WHERE id = 1";
		$db->setQuery ( $query );
		$cfgdata = $db->loadAssoc ();

		// prepare data from table
		$data = $this->redshopCFGData ( $cfgdata );

		if (count ( $org ) > 0) {
			$data = array_merge ( $org, $data );
		}

		$this->defineCFGVars ( $data );
		$this->writeCFGFile ();

	# defination file for wizard
	/*$this->_def_array = $cfgdata;
		$this->WriteDefFile();*/
	}

	/**
	 * load Default configuration file
	 *
	 * @return boolean
	 */
	function loadDefaultCFGFile() {

		if ($this->isCFGFile ()) {

			if (copy ( $this->_configpath, $this->_config_bkp_path ))
				if (! copy ( $this->_config_dist_path, $this->_configpath ))
					return false;
		} else {
			if (! copy ( $this->_config_dist_path, $this->_configpath ))
				return false;
		}

		return true;
	}

	/**
	 * manage configuration file during installation
	 *
	 * @param array $org
	 * @return boolean
	 */
	function manageCFGFile($org = array()) {

		if ($this->isCFGFile ()) {

			if (count ( $org ) > 0) {

				$this->defineCFGVars ( $org );
				$this->updateCFGFile ();
			}

		} else {

			if ($this->isCFGTable ()) {

				$this->setCFGTableData ( $org );

			} else {
				$this->loadDefaultCFGFile ();
			}
		}
		return true;
	}

	function WriteDefFile($def = array()) {

		if (count ( $def ) <= 0)
			$def = $this->_def_array;

		$html = "<?php \n";

		$html .= 'global $defaultarray;' . "\n" . '$defaultarray = array();' . "\n";

		foreach ( $def as $key => $val ) {
			$html .= '$defaultarray["' . $key . '"] = \'' . addslashes ( $val ) . "';\n";
		}
		$html .= "?>";

		if (! $this->isDEFFile ())
			return false;

		if ($fp = fopen ( $this->_config_def_path, "w" )) {
			fwrite ( $fp, $html, strlen ( $html ) );
			fclose ( $fp );
			return true;
		} else {
			return false;
		}
	}

	function defineCFGVars($data, $bypass = false) {

		$this->_cfgdata = "";

		$this->_cfgdata .= "<?php\n";

		foreach ( $data as $key => $value ) {

			if (! defined ( $key ) || $bypass)
				$this->_cfgdata .= "define ('" . $key . "', '" . addslashes ( $value ) . "');\n";
		}
		$this->_cfgdata .= '?>';

		return;
	}

	function writeCFGFile() {

		if ($fp = fopen ( $this->_configpath, "w" )) {
			fputs ( $fp, $this->_cfgdata, strlen ( $this->_cfgdata ) );
			fclose ( $fp );
			return true;
		} else {
			return false;
		}
	}

	function updateCFGFile() {

		if ($fp = fopen ( $this->_configpath, "a" )) {
			fputs ( $fp, $this->_cfgdata, strlen ( $this->_cfgdata ) );
			fclose ( $fp );
			return true;
		} else {
			return false;
		}
	}

	function backupCFGFile() {

		if ($this->isCFGFile ()) {

			if (! copy ( $this->_configpath, $this->_config_bkp_path ))
				return false;

		} else {
			if (! copy ( $this->_config_dist_path, $this->_configpath ))
				return false;
		}

		return true;
	}

	function isTmpFile() {

		if (file_exists ( $this->_config_tmp_path )) {

			if ($this->isTMPFileWritable ()) {
				require_once ($this->_config_tmp_path);
				return true;
			}
		} else {
			JError::raiseWarning ( 21, JText::_('COM_REDSHOP_REDSHOP_TMP_FILE_NOT_FOUND' ) );
		}

		return false;
	}

	function isTMPFileWritable() {

		if (! is_writable ( $this->_config_tmp_path )) {

			JError::raiseWarning ( 21, JText::_('COM_REDSHOP_REDSHOP_TMP_FILE_NOT_WRITABLE' ) );
			return false;
		}
		return true;
	}

	function isDEFFile() {

		if (file_exists ( $this->_config_def_path )) {

			if ($this->isDEFFileWritable ()) {
				require_once ($this->_config_def_path);
				return true;
			}
		} else {
			JError::raiseWarning ( 21, JText::_('COM_REDSHOP_REDSHOP_DEF_FILE_NOT_FOUND' ) );
		}

		return false;
	}

	function isDEFFileWritable() {

		if (! is_writable ( $this->_config_def_path )) {

			JError::raiseWarning ( 21, JText::_('COM_REDSHOP_REDSHOP_DEF_FILE_NOT_WRITABLE' ) );
			return false;
		}
		return true;
	}

	function storeFromTMPFile() {

		global $temparray;

		global $defaultarray;

		if ($this->isTmpFile () && $this->isDEFFile ()) {

			$ncfgdata = array_merge ( $defaultarray, $temparray );

			$config_array = $this->redshopCFGData ( $ncfgdata );

			$this->defineCFGVars ( $config_array, true );

			$this->backupCFGFile ();

			if (! $this->writeCFGFile ()) {
				return false;
			}
		}

		return true;
	}

	function redshopCFGData($d)
	{
		$d['booking_order_status'] = (isset($d['booking_order_status'])) ? $d['booking_order_status'] : 0;

		$config_array = array ("PI" => 3.14, "ADMINISTRATOR_EMAIL" => $d ["administrator_email"], "THUMB_WIDTH" => $d ["thumb_width"], "THUMB_HEIGHT" => $d ["thumb_height"], "THUMB_WIDTH_2" => $d ["thumb_width_2"], "THUMB_HEIGHT_2" => $d ["thumb_height_2"], "THUMB_WIDTH_3" => $d ["thumb_width_3"], "THUMB_HEIGHT_3" => $d ["thumb_height_3"], "CATEGORY_PRODUCT_THUMB_WIDTH" => $d ["category_product_thumb_width"], "CATEGORY_PRODUCT_THUMB_HEIGHT" => $d ["category_product_thumb_height"], "CATEGORY_PRODUCT_THUMB_WIDTH_2" => $d ["category_product_thumb_width_2"], "CATEGORY_PRODUCT_THUMB_HEIGHT_2" => $d ["category_product_thumb_height_2"], "CATEGORY_PRODUCT_THUMB_WIDTH_3" => $d ["category_product_thumb_width_3"], "CATEGORY_PRODUCT_THUMB_HEIGHT_3" => $d ["category_product_thumb_height_3"], "RELATED_PRODUCT_THUMB_WIDTH" => $d ["related_product_thumb_width"], "RELATED_PRODUCT_THUMB_HEIGHT" => $d ["related_product_thumb_height"], "RELATED_PRODUCT_THUMB_WIDTH_2" => $d ["related_product_thumb_width_2"], "RELATED_PRODUCT_THUMB_HEIGHT_2" => $d ["related_product_thumb_height_2"], "RELATED_PRODUCT_THUMB_WIDTH_3" => $d ["related_product_thumb_width_3"], "RELATED_PRODUCT_THUMB_HEIGHT_3" => $d ["related_product_thumb_height_3"], "ATTRIBUTE_SCROLLER_THUMB_WIDTH" => $d ["attribute_scroller_thumb_width"], "ATTRIBUTE_SCROLLER_THUMB_HEIGHT" => $d ["attribute_scroller_thumb_height"], "ACCESSORY_PRODUCT_IN_LIGHTBOX" => $d ["accessory_product_in_lightbox"],"COMPARE_PRODUCT_THUMB_WIDTH" => $d ["compare_product_thumb_width"], "COMPARE_PRODUCT_THUMB_HEIGHT" => $d ["compare_product_thumb_height"],

		"ACCESSORY_THUMB_HEIGHT" => $d ["accessory_thumb_height"], "ACCESSORY_THUMB_WIDTH" => $d ["accessory_thumb_width"], "ACCESSORY_THUMB_HEIGHT_2" => $d ["accessory_thumb_height_2"], "ACCESSORY_THUMB_WIDTH_2" => $d ["accessory_thumb_width_2"], "ACCESSORY_THUMB_HEIGHT_3" => $d ["accessory_thumb_height_3"], "ACCESSORY_THUMB_WIDTH_3" => $d ["accessory_thumb_width_3"],

		"DEFAULT_AJAX_DETAILBOX_TEMPLATE" => $d ["default_ajax_detailbox_template"], "ASTERISK_POSITION" => 0, "MANUFACTURER_THUMB_WIDTH" => $d ["manufacturer_thumb_width"], "MANUFACTURER_THUMB_HEIGHT" => $d ["manufacturer_thumb_height"], "MANUFACTURER_PRODUCT_THUMB_WIDTH" => $d ["manufacturer_product_thumb_width"], "MANUFACTURER_PRODUCT_THUMB_HEIGHT" => $d ["manufacturer_product_thumb_height"], "MANUFACTURER_PRODUCT_THUMB_WIDTH_2" => $d ["manufacturer_product_thumb_width_2"], "MANUFACTURER_PRODUCT_THUMB_HEIGHT_2" => $d ["manufacturer_product_thumb_height_2"], "MANUFACTURER_PRODUCT_THUMB_WIDTH_3" => $d ["manufacturer_product_thumb_width_3"], "MANUFACTURER_PRODUCT_THUMB_HEIGHT_3" => $d ["manufacturer_product_thumb_height_3"],

		"CART_THUMB_WIDTH" => $d ["cart_thumb_width"], "CART_THUMB_HEIGHT" => $d ["cart_thumb_height"],

		"GIFTCARD_THUMB_WIDTH" => $d ["giftcard_thumb_width"], "GIFTCARD_THUMB_HEIGHT" => $d ["giftcard_thumb_height"], "GIFTCARD_LIST_THUMB_WIDTH" => $d ["giftcard_list_thumb_width"], "GIFTCARD_LIST_THUMB_HEIGHT" => $d ["giftcard_list_thumb_height"],

		"PRODUCT_MAIN_IMAGE" => $d ["product_main_image"], "PRODUCT_MAIN_IMAGE_HEIGHT" => $d ["product_main_image_height"], "PRODUCT_MAIN_IMAGE_2" => $d ["product_main_image_2"], "PRODUCT_MAIN_IMAGE_HEIGHT_2" => $d ["product_main_image_height_2"], "PRODUCT_MAIN_IMAGE_3" => $d ["product_main_image_3"], "PRODUCT_MAIN_IMAGE_HEIGHT_3" => $d ["product_main_image_height_3"],

		"PRODUCT_ADDITIONAL_IMAGE" => $d ["product_additional_image"], "PRODUCT_ADDITIONAL_IMAGE_HEIGHT" => $d ["product_additional_image_height"], "PRODUCT_ADDITIONAL_IMAGE_2" => $d ["product_additional_image_2"], "PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2" => $d ["product_additional_image_height_2"], "PRODUCT_ADDITIONAL_IMAGE_3" => $d ["product_additional_image_3"], "PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3" => $d ["product_additional_image_height_3"],

		"PRODUCT_PREVIEW_IMAGE_WIDTH" => $d ["product_preview_image_width"], "PRODUCT_PREVIEW_IMAGE_HEIGHT" => $d ["product_preview_image_height"],

		"CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH" => $d ["category_product_preview_image_width"], "CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT" => $d ["category_product_preview_image_height"],

		"PRODUCT_COMPARE_LIMIT" => $d ["product_compare_limit"], "PRODUCT_DOWNLOAD_LIMIT" => $d ["product_download_limit"], "PRODUCT_DOWNLOAD_DAYS" => $d ["product_download_days"], "QUANTITY_TEXT_DISPLAY" => $d ["quantity_text_display"],

		"DISCOUNT_MAIL_SEND" => $d ["discount_mail_send"], "DAYS_MAIL1" => $d ["days_mail1"], "DAYS_MAIL2" => $d ["days_mail2"], "DAYS_MAIL3" => $d ["days_mail3"],

		"DISCOUPON_DURATION" => $d ["discoupon_duration"], "DISCOUPON_PERCENT_OR_TOTAL" => $d ["discoupon_percent_or_total"], "DISCOUPON_VALUE" => $d ["discoupon_value"], "USE_CONTAINER" => $d ["use_container"], "USE_STOCKROOM" => $d ["use_stockroom"], "ALLOW_PRE_ORDER" => $d ["allow_pre_order"], "ALLOW_PRE_ORDER_MESSAGE" => $d ["allow_pre_order_message"],

		"DEFAULT_VAT_COUNTRY" => $d ["default_vat_country"], "DEFAULT_VAT_STATE" => $d ["default_vat_state"], "DEFAULT_VAT_GROUP" => $d ["default_vat_group"], "VAT_BASED_ON" => $d ["vat_based_on"],

		"PRODUCT_TEMPLATE" => $d ["default_product_template"], "CATEGORY_TEMPLATE" => $d ["default_category_template"], "DEFAULT_CATEGORYLIST_TEMPLATE" => $d ["default_categorylist_template"], "MANUFACTURER_TEMPLATE" => $d ["default_manufacturer_template"], "WRITE_REVIEW_IS_LIGHTBOX" => $d ['write_review_is_lightbox'], "COUNTRY_LIST" => $d ["country_list"], "PRODUCT_DEFAULT_IMAGE" => $d ["product_default_image"], "PRODUCT_OUTOFSTOCK_IMAGE" => $d ["product_outofstock_image"], "CATEGORY_DEFAULT_IMAGE" => $d ["category_default_image"], "ADDTOCART_IMAGE" => $d ["addtocart_image"], "REQUESTQUOTE_IMAGE" => $d ["requestquote_image"], "REQUESTQUOTE_BACKGROUND" => $d ["requestquote_background"], "PRE_ORDER_IMAGE" => $d ["pre_order_image"], "CATEGORY_SHORT_DESC_MAX_CHARS" => $d ["category_short_desc_max_chars"], "CATEGORY_SHORT_DESC_END_SUFFIX" => $d ["category_short_desc_end_suffix"], "CATEGORY_DESC_MAX_CHARS" => $d ["category_desc_max_chars"], "CATEGORY_DESC_END_SUFFIX" => $d ["category_desc_end_suffix"],

		"CATEGORY_TITLE_MAX_CHARS" => $d ["category_title_max_chars"], "CATEGORY_TITLE_END_SUFFIX" => $d ["category_title_end_suffix"], "CATEGORY_PRODUCT_TITLE_MAX_CHARS" => $d ["category_product_title_max_chars"], "CATEGORY_PRODUCT_TITLE_END_SUFFIX" => $d ["category_product_title_end_suffix"], "CATEGORY_PRODUCT_DESC_MAX_CHARS" => $d ["category_product_desc_max_chars"], "CATEGORY_PRODUCT_DESC_END_SUFFIX" => $d ["category_product_desc_end_suffix"], "RELATED_PRODUCT_DESC_MAX_CHARS" => $d ["related_product_desc_max_chars"], "RELATED_PRODUCT_DESC_END_SUFFIX" => $d ["related_product_desc_end_suffix"], "RELATED_PRODUCT_TITLE_MAX_CHARS" => $d ["related_product_title_max_chars"], "RELATED_PRODUCT_TITLE_END_SUFFIX" => $d ["related_product_title_end_suffix"], "ACCESSORY_PRODUCT_DESC_MAX_CHARS" => $d ["accessory_product_desc_max_chars"], "ACCESSORY_PRODUCT_DESC_END_SUFFIX" => $d ["accessory_product_desc_end_suffix"], "ACCESSORY_PRODUCT_TITLE_MAX_CHARS" => $d ["accessory_product_title_max_chars"], "ACCESSORY_PRODUCT_TITLE_END_SUFFIX" => $d ["accessory_product_title_end_suffix"], "ADDTOCART_BACKGROUND" => $d ["addtocart_background"], "TABLE_PREFIX" => $d ["table_prefix"], "SPLIT_DELIVERY_COST" => $d ["split_delivery_cost"], "TIME_DIFF_SPLIT_DELIVERY" => $d ["time_diff_split_delivery"], "NEWS_MAIL_FROM" => $d ["news_mail_from"], "NEWS_FROM_NAME" => $d ["news_from_name"], "DEFAULT_NEWSLETTER" => $d ["default_newsletter"],

		"SHOP_COUNTRY" => $d ["shop_country"], "DEFAULT_SHIPPING_COUNTRY" => $d ["default_shipping_country"], "REDCURRENCY_SYMBOL" => $d ["currency_symbol"], "PRICE_SEPERATOR" => $d ["price_seperator"], "THOUSAND_SEPERATOR" => $d ["thousand_seperator"], "CURRENCY_SYMBOL_POSITION" => $d ["currency_symbol_position"], "PRICE_DECIMAL" => $d ["price_decimal"],  "CALCULATION_PRICE_DECIMAL" => $d ["calculation_price_decimal"], "UNIT_DECIMAL" => $d ["unit_decimal"], "DEFAULT_DATEFORMAT" => $d ["default_dateformat"], "CURRENCY_CODE" => $d ["currency_code"], "ECONOMIC_INTEGRATION" => $d ["economic_integration"], "DEFAULT_ECONOMIC_ACCOUNT_GROUP" => $d ["default_economic_account_group"], "ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC" => $d ["attribute_as_product_in_economic"], "DETAIL_ERROR_MESSAGE_ON" => $d ["detail_error_message_on"], "CAT_IS_LIGHTBOX" => $d ["cat_is_lightbox"], "PRODUCT_IS_LIGHTBOX" => $d ["product_is_lightbox"], "PRODUCT_DETAIL_IS_LIGHTBOX" => $d ["product_detail_is_lightbox"], "PRODUCT_ADDIMG_IS_LIGHTBOX" => $d ["product_addimg_is_lightbox"], "USE_PRODUCT_OUTOFSTOCK_IMAGE" => $d ["use_product_outofstock_image"], "WELCOME_MSG" => $d ["welcome_msg"], "SHOP_NAME" => $d ["shop_name"], "COUPONS_ENABLE" => $d ["coupons_enable"], "VOUCHERS_ENABLE" => $d ["vouchers_enable"], "SPLITABLE_PAYMENT" => $d ["splitable_payment"], "SHOW_CAPTCHA" => $d ["show_captcha"], "SHOW_EMAIL_VERIFICATION" => $d ["show_email_verification"],

		"RATING_MSG" => $d ["rating_msg"], "DISCOUNT_DURATION" => $d ["discount_duration"], "SPECIAL_DISCOUNT_MAIL_SEND" => $d ["special_discount_mail_send"], "DISCOUNT_PERCENTAGE" => $d ["discount_percentage"], "CATALOG_DAYS" => $d ["catalog_days"], "CATALOG_REMINDER_1" => $d ["catalog_reminder_1"], "CATALOG_REMINDER_2" => $d ["catalog_reminder_2"], "FAVOURED_REVIEWS" => $d ["favoured_reviews"], "COLOUR_SAMPLE_REMAINDER_1" => $d ["colour_sample_remainder_1"], "COLOUR_SAMPLE_REMAINDER_2" => $d ["colour_sample_remainder_2"], "COLOUR_SAMPLE_REMAINDER_3" => $d ["colour_sample_remainder_3"], "COLOUR_COUPON_DURATION" => $d ["colour_coupon_duration"], "COLOUR_DISCOUNT_PERCENTAGE" => $d ["colour_discount_percentage"], "COLOUR_SAMPLE_DAYS" => $d ["colour_sample_days"], "CATEGORY_FRONTPAGE_INTROTEXT" => $d ["category_frontpage_introtext"], "REGISTRATION_INTROTEXT" => $d ["registration_introtext"], "REGISTRATION_COMPANY_INTROTEXT" => $d ["registration_comp_introtext"], "VAT_INTROTEXT" => $d ["vat_introtext"], "ORDER_LIST_INTROTEXT" => $d ["order_lists_introtext"], "ORDER_DETAIL_INTROTEXT" => $d ["order_detail_introtext"], "ORDER_RECEIPT_INTROTEXT" => $d ["order_receipt_introtext"], "DELIVERY_RULE" => $d ["delivery_rule"], "GOOGLE_ANA_TRACKER_KEY" => $d ["google_ana_tracker"], "AUTOGENERATED_SEO" => $d ["autogenerated_seo"], "ENABLE_SEF_PRODUCT_NUMBER" => $d ["enable_sef_product_number"], "ENABLE_SEF_NUMBER_NAME" => $d ["enable_sef_number_name"],

		"DEFAULT_CUSTOMER_REGISTER_TYPE" => $d ["default_customer_register_type"], "ADDTOCART_BEHAVIOUR" => $d ["addtocart_behaviour"], "WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART" => $d ["wanttoshowattributeimage"],

		"ALLOW_CUSTOMER_REGISTER_TYPE" => $d ["allow_customer_register_type"],

		"OPTIONAL_SHIPPING_ADDRESS" => $d ["optional_shipping_address"], "SHIPPING_METHOD_ENABLE" => $d ["shipping_method_enable"],

		"SEO_PAGE_TITLE" => $d ["seo_page_title"], "SEO_PAGE_HEADING" => $d ["seo_page_heading"], "SEO_PAGE_SHORT_DESCRIPTION" => $d ["seo_page_short_description"], "SEO_PAGE_DESCRIPTION" => $d ["seo_page_description"], "SEO_PAGE_KEYWORDS" => $d ["seo_page_keywords"], "SEO_PAGE_LANGAUGE" => $d ["seo_page_language"], "SEO_PAGE_ROBOTS" => $d ["seo_page_robots"], "SEO_PAGE_TITLE_CATEGORY" => $d ["seo_page_title_category"], "SEO_PAGE_HEADING_CATEGORY" => $d ["seo_page_heading_category"], "SEO_PAGE_SHORT_DESCRIPTION_CATEGORY" => $d ["seo_page_short_description_category"], "SEO_PAGE_DESCRIPTION_CATEGORY" => $d ["seo_page_description_category"], "SEO_PAGE_KEYWORDS_CATEGORY" => $d ["seo_page_keywords_category"], "SEO_PAGE_TITLE_MANUFACTUR" => $d ["seo_page_title_manufactur"], "SEO_PAGE_HEADING_MANUFACTUR" => $d ["seo_page_heading_manufactur"], "SEO_PAGE_DESCRIPTION_MANUFACTUR" => $d ["seo_page_description_manufactur"], "SEO_PAGE_KEYWORDS_MANUFACTUR" => $d ["seo_page_keywords_manufactur"],

		"USE_TAX_EXEMPT" => $d ["use_tax_exempt"], "TAX_EXEMPT_APPLY_VAT" => $d ["tax_exempt_apply_vat"],

		"COUPONINFO" => $d ["couponinfo"], "MY_TAGS" => $d ["my_tags"], "MY_WISHLIST" => $d ["my_wishlist"], "COMARE_PRODUCTS" => $d ["compare_products"],

		"REGISTER_METHOD" => $d ["register_method"], "ZERO_PRICE_REPLACE" => $d ["zero_price_replacement"], "ZERO_PRICE_REPLACE_URL" => $d ["zero_price_replacement_url"], "PRICE_REPLACE" => $d ["price_replacement"], "PRICE_REPLACE_URL" => $d ["price_replacement_url"], "PAYMENT_CALCULATION_ON" => $d ["payment_calculation_on"], "PORTAL_SHOP" => $d ["portal_shop"], "DEFAULT_PORTAL_NAME" => $d ["default_portal_name"], "DEFAULT_PORTAL_LOGO" => $d ["default_portal_logo"], "SHOPPER_GROUP_DEFAULT_PRIVATE" => $d ["shopper_group_default_private"], "SHOPPER_GROUP_DEFAULT_COMPANY" => $d ["shopper_group_default_company"], "NEW_SHOPPER_GROUP_GET_VALUE_FROM" => $d ["new_shopper_group_get_value_from"], //			"SHOPPER_GROUP_DEFAULT_TAX_EXEMPT" => $d["shopper_group_default_tax_exempt"],


		"PRODUCT_EXPIRE_TEXT" => $d ["product_expire_text"], "TERMS_ARTICLE_ID" => $d ["terms_article_id"],

		"INVOICE_NUMBER_TEMPLATE" => $d ["invoice_number_template"], "FIRST_INVOICE_NUMBER" => $d ["first_invoice_number"], "DEFAULT_CATEGORY_ORDERING_METHOD" => $d ["default_category_ordering_method"], "DEFAULT_PRODUCT_ORDERING_METHOD" => $d ["default_product_ordering_method"], "DEFAULT_RELATED_ORDERING_METHOD" => $d ["default_related_ordering_method"], "DEFAULT_ACCESSORY_ORDERING_METHOD" => $d ["default_accessory_ordering_method"], "DEFAULT_MANUFACTURER_ORDERING_METHOD" => $d ["default_manufacturer_ordering_method"], "DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD" => $d ["default_manufacturer_product_ordering_method"], "WELCOMEPAGE_INTROTEXT" => $d ["welcomepage_introtext"], "NEW_CUSTOMER_SELECTION" => $d ["new_customer_selection"], "AJAX_CART_BOX" => $d ["ajax_cart_box"], "IS_PRODUCT_RESERVE" => $d ["is_product_reserve"], "CART_RESERVATION_MESSAGE" => $d ["cart_reservation_message"], "WITHOUT_VAT_TEXT_INFO" => $d ["without_vat_text_info"], "WITH_VAT_TEXT_INFO" => $d ["with_vat_text_info"], "DEFAULT_STOCKROOM" => $d ["default_stockroom"], "DEFAULT_CART_CHECKOUT_ITEMID" => $d ["default_cart_checkout_itemid"], "USE_IMAGE_SIZE_SWAPPING" => $d ["use_image_size_swapping"], "DEFAULT_WRAPPER_THUMB_WIDTH" => $d ["default_wrapper_thumb_width"], "DEFAULT_WRAPPER_THUMB_HEIGHT" => $d ["default_wrapper_thumb_height"], "DEFAULT_QUANTITY" => $d ["default_quantity"], "DEFAULT_QUANTITY_SELECTBOX_VALUE" => $d ["default_quantity_selectbox_value"], "AUTO_SCROLL_WRAPPER" => $d ["auto_scroll_wrapper"], "MAXCATEGORY" => $d ["maxcategory"], "ECONOMIC_INVOICE_DRAFT" => $d ["economic_invoice_draft"], "BOOKING_ORDER_STATUS" => $d ["booking_order_status"],

		"PORTAL_LOGIN_ITEMID" => $d ["portal_login_itemid"], "PORTAL_LOGOUT_ITEMID" => $d ["portal_logout_itemid"], "APPLY_VAT_ON_DISCOUNT" => $d ["apply_vat_on_discount"], "CONTINUE_REDIRECT_LINK" => $d ["continue_redirect_link"],

		"DEFAULT_LINK_FIND" => $d ["next_previous_link"], "IMAGE_PREVIOUS_LINK_FIND" => $d ["image_previous_link"], "PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE" => $d ["product_detail_lighbox_close_button_image"], "IMAGE_NEXT_LINK_FIND" => $d ["image_next_link"], "CUSTOM_PREVIOUS_LINK_FIND" => $d ["custom_previous_link"], "CUSTOM_NEXT_LINK_FIND" => $d ["custom_next_link"], "DAFULT_NEXT_LINK_SUFFIX" => $d ["default_next_suffix"], "DAFULT_PREVIOUS_LINK_PREFIX" => $d ["default_previous_prefix"], "DAFULT_RETURN_TO_CATEGORY_PREFIX" => $d ["return_to_category_prefix"], "ALLOW_MULTIPLE_DISCOUNT" => $d ["allow_multiple_discount"],

		"DISCOUNT_ENABLE" => $d ["discount_enable"], "DISCOUNT_TYPE" => $d ["discount_type"], "INVOICE_MAIL_ENABLE" => $d ["invoice_mail_enable"], "ENABLE_BACKENDACCESS" => $d ["enable_backendaccess"], "WISHLIST_LOGIN_REQUIRED" => $d ["wishlist_login_required"],

		"INVOICE_MAIL_SEND_OPTION" => $d ["invoice_mail_send_option"], "MINIMUM_ORDER_TOTAL" => $d ["minimum_order_total"], "MANUFACTURER_TITLE_MAX_CHARS" => $d ["manufacturer_title_max_chars"], "MANUFACTURER_TITLE_END_SUFFIX" => $d ["manufacturer_title_end_suffix"],

		"DEFAULT_VOLUME_UNIT" => $d ["default_volume_unit"], "DEFAULT_WEIGHT_UNIT" => $d ["default_weight_unit"],

		"NEWSLETTER_ENABLE" => $d ["newsletter_enable"], "NEWSLETTER_CONFIRMATION" => $d ["newsletter_confirmation"], "WATERMARK_IMAGE" => $d ["watermark_image"],

		"WATERMARK_CATEGORY_THUMB_IMAGE" => $d ["watermark_category_thumb_image"], "WATERMARK_CATEGORY_IMAGE" => $d ["watermark_category_image"], "WATERMARK_PRODUCT_IMAGE" => $d ["watermark_product_image"], "WATERMARK_PRODUCT_THUMB_IMAGE" => $d ["watermark_product_thumb_image"], "WATERMARK_PRODUCT_ADDITIONAL_IMAGE" => $d ["watermark_product_additional_image"], "WATERMARK_CART_THUMB_IMAGE" => $d ["watermark_cart_thumb_image"], "WATERMARK_GIFTCART_IMAGE" => $d ["watermark_giftcart_image"], "WATERMARK_GIFTCART_THUMB_IMAGE" => $d ["watermark_giftcart_thumb_image"], "WATERMARK_MANUFACTURER_THUMB_IMAGE" => $d ["watermark_manufacturer_thumb_image"], "WATERMARK_MANUFACTURER_IMAGE" => $d ["watermark_manufacturer_image"],

		'CLICKATELL_USERNAME' => $d ["clickatell_username"], 'CLICKATELL_PASSWORD' => $d ["clickatell_password"], 'CLICKATELL_API_ID' => $d ["clickatell_api_id"], 'CLICKATELL_ENABLE' => $d ["clickatell_enable"], 'CLICKATELL_ORDER_STATUS' => $d ["clickatell_order_status"], 'PRE_USE_AS_CATALOG' => $d ["use_as_catalog"], 'SHOW_SHIPPING_IN_CART' => $d ["show_shipping_in_cart"], 'MANUFACTURER_MAIL_ENABLE' => $d ["manufacturer_mail_enable"], 'SUPPLIER_MAIL_ENABLE' => $d ["supplier_mail_enable"], 'PRODUCT_COMPARISON_TYPE' => $d ["product_comparison_type"], 'COMPARE_TEMPLATE_ID' => $d ["compare_template_id"], 'SSL_ENABLE_IN_CHECKOUT' => $d ["ssl_enable_in_checkout"], 'PAGINATION' => $d ["pagination"], 'VAT_RATE_AFTER_DISCOUNT' => $d ["vat_rate_after_discount"], 'PRODUCT_DOWNLOAD_ROOT' => $d ["product_download_root"], 'TWOWAY_RELATED_PRODUCT' => $d ["twoway_related_product"],

		'PRODUCT_HOVER_IMAGE_ENABLE' => $d ["product_hover_image_enable"], 'PRODUCT_HOVER_IMAGE_WIDTH' => $d ["product_hover_image_width"], 'PRODUCT_HOVER_IMAGE_HEIGHT' => $d ["product_hover_image_height"], 'ADDITIONAL_HOVER_IMAGE_ENABLE' => $d ["additional_hover_image_enable"], 'ADDITIONAL_HOVER_IMAGE_WIDTH' => $d ["additional_hover_image_width"], 'ADDITIONAL_HOVER_IMAGE_HEIGHT' => $d ["additional_hover_image_height"], 'SSL_ENABLE_IN_BACKEND' => $d ["ssl_enable_in_backend"], "SHOW_PRICE_SHOPPER_GROUP_LIST" => $d ["show_price_shopper_group_list"], "SHOW_PRICE_USER_GROUP_LIST" => $d ["show_price_user_group_list"], "SHIPPING_AFTER" => $d ["shipping_after"], "ENABLE_ADDRESS_DETAIL_IN_SHIPPING" => $d ["enable_address_detail_in_shipping"],

		"CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS" => $d ['category_product_short_desc_max_chars'], "CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX" => $d ['category_product_short_desc_end_suffix'], "RELATED_PRODUCT_SHORT_DESC_MAX_CHARS" => $d ['related_product_short_desc_max_chars'], "RELATED_PRODUCT_SHORT_DESC_END_SUFFIX" => $d ['related_product_short_desc_end_suffix'], "CALCULATE_VAT_ON" => $d ['calculate_vat_on'], "REMOTE_UPDATE_DOMAIN_URL" => 'http://dev.redcomponent.com/', "ONESTEP_CHECKOUT_ENABLE" => $d ["onestep_checkout_enable"], "SHOW_TAX_EXEMPT_INFRONT" => $d ["show_tax_exempt_infront"], "NOOF_THUMB_FOR_SCROLLER" => $d ["noof_thumb_for_scroller"], "NOOF_SUBATTRIB_THUMB_FOR_SCROLLER" => $d ["noof_subattrib_thumb_for_scroller"], "INDIVIDUAL_ADD_TO_CART_ENABLE" => $d ["individual_add_to_cart_enable"], "ACCESSORY_AS_PRODUCT_IN_CART_ENABLE" => $d ["accessory_as_product_in_cart_enable"], "POSTDK_CUSTOMER_NO" => $d ["postdk_customer_no"], "POSTDK_CUSTOMER_PASSWORD" => $d ["postdk_customer_password"], "POSTDK_INTEGRATION" => $d ["postdk_integration"], "POSTDANMARK_MODE" => $d ["postdk_testmode"], "POSTDANMARK_ADDRESS" => $d ["postdk_address"], "POSTDANMARK_POSTALCODE" => $d ["postdk_postalcode"], "POSTDK_LABEL_REMARK" => $d["postdk_label_remark"], "QUICKLINK_ICON" => $d ["quicklink_icon"], "DISPLAY_NEW_ORDERS" => $d ["display_new_orders"], "DISPLAY_NEW_CUSTOMERS" => $d ["display_new_customers"], "DISPLAY_STATISTIC" => $d ['display_statistic'], "EXPAND_ALL" => $d ['expand_all'], "AJAX_CART_DISPLAY_TIME" => $d ['ajax_cart_display_time'], "IMAGE_QUALITY_OUTPUT" => $d ['image_quality_output'], "SEND_CATALOG_REMINDER_MAIL" => $d ['send_catalog_reminder_mail'], "CATEGORY_IN_SEF_URL" => $d ['category_in_sef_url'], "USE_BLANK_AS_INFINITE" => $d ['use_blank_as_infinite'], "USE_ENCODING" => $d ['use_encoding'], "CREATE_ACCOUNT_CHECKBOX" => $d ['create_account_checkbox'], "SHOW_QUOTATION_PRICE" => $d ['show_quotation_price'], "CHILDPRODUCT_DROPDOWN" => $d ['childproduct_dropdown'], "PURCHASE_PARENT_WITH_CHILD" => $d ['purchase_parent_with_child'],"ADDTOCART_DELETE" => $d["addtocart_delete"],
			"ADDTOCART_UPDATE" => $d["addtocart_update"], "DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA" => $d['display_out_of_stock_attribute_data'] )

		;

		if ($d ["cart_timeout"] <= 0) {
			$config_array ["CART_TIMEOUT"] = 20;
		} else {
			$config_array ["CART_TIMEOUT"] = $d ["cart_timeout"];
		}

		/*if($d["default_quotation_mode"]==1)
		{
			$config_array["DEFAULT_QUOTATION_MODE"] = $this->setQuotationMode($d);
		} else {
			$config_array["DEFAULT_QUOTATION_MODE"] = $d["default_quotation_mode"];
		}*/

		/*if($d["show_price"] == 1){
			$config_array["SHOW_PRICE"] = $this->showPrice($d);
		}else{
			$config_array["SHOW_PRICE"] = $d["show_price"];
		}*/

		$config_array ["DEFAULT_QUOTATION_MODE_PRE"] = $d ["default_quotation_mode"];

		$config_array ["SHOW_PRICE_PRE"] = $d ["show_price"];

		$config_array ["MAGIC_MAGNIFYPLUS_PRE"] = 0;

		if ($d ["newsletter_mail_chunk"] == 0) {
			$d ["newsletter_mail_chunk"] = 1;
		}
		if ($d ["newsletter_mail_pause_time"] == 0) {
			$d ["newsletter_mail_pause_time"] = 1;
		}
		$config_array ["NEWSLETTER_MAIL_CHUNK"] = $d ["newsletter_mail_chunk"];
		$config_array ["NEWSLETTER_MAIL_PAUSE_TIME"] = $d ["newsletter_mail_pause_time"];

		return $config_array;
	}

	/**
	 * We are using file for saving configuration variables
	 * We need some variables that can be uses as dynamically
	 * Here is the logic to define that variables
	 *
	 * IMPORTANT: we need to call this function in plugin or module manually to see the effect of this variables
	 */
	function defineDynamicVars() {

		/*if (!defined('SHOW_PRICE')){
			if(SHOW_PRICE_PRE == 1){
				define ('SHOW_PRICE', $this->showPrice());
			}else{
				define ('SHOW_PRICE', SHOW_PRICE_PRE);
			}
		}*/

		if (! defined ( 'SHOW_PRICE' )) {

			define ( 'SHOW_PRICE', $this->showPrice () );
			define ( 'USE_AS_CATALOG', $this->getCatalog () );
			//	echo SHOW_PRICE;die();


		}

		if (! defined ( 'DEFAULT_QUOTATION_MODE' )) {
			if (DEFAULT_QUOTATION_MODE_PRE == 1) {
				define ( 'DEFAULT_QUOTATION_MODE', $this->setQuotationMode () );
			} else {
				define ( 'DEFAULT_QUOTATION_MODE', DEFAULT_QUOTATION_MODE_PRE );
			}
		}

		if (! defined ( 'MAGIC_MAGNIFYPLUS' )) {
			if (MAGIC_MAGNIFYPLUS_PRE == 0) {
				define ( 'MAGIC_MAGNIFYPLUS', $this->checkMagicMagnity () );
			} else {
				define ( 'MAGIC_MAGNIFYPLUS', MAGIC_MAGNIFYPLUS_PRE );
			}
		}
	}

	function checkMagicMagnity() {
		jimport ( 'joomla.application.module.helper' );
		return JModuleHelper::isEnabled ( 'redmagicmagnifyplus' );
	}

	function showPrice() {

		$user = & JFactory::getUser ();
		$db = & JFactory::getDBO ();
		$userhelper = new rsUserhelper();

		if (! $user->id) {

			$q_shopper_grp = "select show_price,is_logged_in FROM  #__" . $this->_table_prefix . "_shopper_group where shopper_group_id ='" . SHOPPER_GROUP_DEFAULT_PRIVATE . "'";
			$db->setQuery ( $q_shopper_grp );
			$r_shopper_grp = $db->loadObject ();
			if ($r_shopper_grp) {
				if ($r_shopper_grp->is_logged_in == 0) {
					return 0;
				}

				if (($r_shopper_grp->show_price == "yes") || ($r_shopper_grp->show_price == "global" && SHOW_PRICE_PRE == 1) || ($r_shopper_grp->show_price == "" && SHOW_PRICE_PRE == 1)) {
					return 1;
				} else {
					return 0;
				}
			} else {

				return SHOW_PRICE_PRE;
			}

		} else {

		     $getShopperGroupID= $userhelper->getShopperGroup($user->id);



			//$sql = "SELECT shopper_group_id FROM  #__" . $this->_table_prefix . "_users_info AS ui   WHERE user_id = " . $user->id . " AND address_type='BT' ";
			//die();
			//$db->setQuery ( $sql );
			//$shopper_group = $db->loadObject ();
;
			if($getShopperGroupID)
			{
				$q_shopper_grp = "select show_price FROM  #__" . $this->_table_prefix . "_shopper_group where shopper_group_id ='" . $getShopperGroupID . "'";
				$db->setQuery ( $q_shopper_grp );
				$r_shopper_grp = $db->loadObject ();
				if ($r_shopper_grp)
				{
					if (($r_shopper_grp->show_price == "yes") || ($r_shopper_grp->show_price == "global" && SHOW_PRICE_PRE == 1) || ($r_shopper_grp->show_price == "" && SHOW_PRICE_PRE == 1)) {
						return 1;
					} else {
						return 0;
					}
				} else {
					return SHOW_PRICE_PRE;
				}
			}
			else
			{
				return SHOW_PRICE_PRE;
			}
		}
	}

	function getCatalog() {

		$user = & JFactory::getUser ();
		$db = & JFactory::getDBO ();
		$userhelper = new rsUserhelper();

		if (! $user->id) {

			$q_catalog = "select use_as_catalog, is_logged_in FROM  #__" . $this->_table_prefix . "_shopper_group where shopper_group_id ='" . SHOPPER_GROUP_DEFAULT_PRIVATE . "'";
			$db->setQuery ( $q_catalog );
			$r_catalog = $db->loadObject ();
			if ($r_catalog) {

				if ($r_catalog->is_logged_in == 0) {
					return 0;
				}

				if (($r_catalog->use_as_catalog == "yes") || ($r_catalog->use_as_catalog == "global" && PRE_USE_AS_CATALOG == 1) || ($r_catalog->use_as_catalog == "" && PRE_USE_AS_CATALOG == 1)) {
					return 1;
				} else {
					return 0;
				}
			} else {
				return PRE_USE_AS_CATALOG;
			}

		} else {

  			$getShopperGroupID= $userhelper->getShopperGroup($user->id);
			//$sql = "SELECT shopper_group_id FROM  #__" . $this->_table_prefix . "_users_info AS ui   WHERE user_id = " . $user->id . " AND address_type='BT' ";

			//$db->setQuery ( $sql );
			//$shopper_group = $db->loadObject ();
			if($getShopperGroupID)
			{
				$q_catalog = "select use_as_catalog FROM  #__" . $this->_table_prefix . "_shopper_group where shopper_group_id ='" . $getShopperGroupID . "'";
				$db->setQuery ( $q_catalog );
				$r_catalog = $db->loadObject ();
				if ($r_catalog) {

					if (($r_catalog->use_as_catalog == "yes") || ($r_catalog->use_as_catalog == "global" && PRE_USE_AS_CATALOG == 1) || ($r_catalog->use_as_catalog == "" && PRE_USE_AS_CATALOG == 1)) {
						return 1;
					} else {
						return 0;
					}
				} else {
					return PRE_USE_AS_CATALOG;
				}
			} else {
				return PRE_USE_AS_CATALOG;
			}
		}

	}

	/*function showPrice(){

		$user	=& JFactory::getUser();
		$db = & JFactory :: getDBO();
		$user_group=array();
		$filter_user_group=array();
	    $show_price_shopper_group_list = SHOW_PRICE_SHOPPER_GROUP_LIST ? SHOW_PRICE_SHOPPER_GROUP_LIST:0;

		if( SHOW_PRICE_USER_GROUP_LIST =='' && SHOW_PRICE_SHOPPER_GROUP_LIST =='' ){

	    	return 1;

	    }else{

	    	if(!$user->id){

	    		$sql = " SELECT FIND_IN_SET('".SHOPPER_GROUP_DEFAULT_PRIVATE."', '".SHOW_PRICE_SHOPPER_GROUP_LIST."') as chk ";

			    $db->setQuery($sql);
				$user_group = $db->loadObject();

			   	if($user_group->chk > 0){
			   		return 1;
			   	}else{
			   		return 0;
			   	}
	    	}

		    $sql = "SELECT shopper_group_id FROM  #__".$this->_table_prefix."_users_info AS ui   WHERE user_id = ".$user->id. " AND address_type='BT' ";

			$db->setQuery($sql);
			$shopper_group = $db->loadObject();


			$sql = "SELECT FIND_IN_SET('".$user->gid."', '".SHOW_PRICE_USER_GROUP_LIST."') as user_group UNION "
		    			. " SELECT FIND_IN_SET('".$shopper_group->shopper_group_id."', '".SHOW_PRICE_SHOPPER_GROUP_LIST."') ";

		    $db->setQuery($sql);
			$filter_user_group = $db->loadAssocList();

		   if(array_key_exists("user_group",$filter_user_group[0]))
			{
				if(count($filter_user_group)==1 && @$filter_user_group[0]->user_group == 0){
		   			return 0;
		   		}else{
		   			return 1;
		   		}
			}
		   	else{
		   		return 1;
		   	}
	    }
	}*/

	function setQuotationMode() {
		$user = & JFactory::getUser ();
		$db = & JFactory::getDBO ();

		$shopper_group_quotation = 0;
		if (! $user->id) {
			$sql = "SELECT * FROM #__" . $this->_table_prefix . "_shopper_group AS sg " . "WHERE shopper_group_id=" . SHOPPER_GROUP_DEFAULT_PRIVATE;
			$db->setQuery ( $sql );
			$shopper_group = $db->loadObject ();
			if (count ( $shopper_group ) > 0) {
				$shopper_group_quotation = $shopper_group->shopper_group_quotation_mode;
			}
			if ($shopper_group_quotation == 1) {
				return 1;
			} else {

				return 0;
			}
		}
		$sql = "SELECT * FROM #__" . $this->_table_prefix . "_users_info AS ui " . "LEFT JOIN #__" . $this->_table_prefix . "_shopper_group AS sg ON sg.shopper_group_id=ui.shopper_group_id " . "WHERE user_id=" . $user->id . " AND address_type='BT' ";
		$db->setQuery ( $sql );
		$shopper_group = $db->loadObject ();
		if (count ( $shopper_group ) > 0) {
			$shopper_group_quotation = $shopper_group->shopper_group_quotation_mode;
		} else {

			return DEFAULT_QUOTATION_MODE_PRE;
		}
		if ($shopper_group_quotation == 1) {
			return 1;
		} else {

			return 0;
		}
	}

	function countryList($countryList) {
		$country_list = explode ( ',', $countryList );
		$tmp = new stdClass ();
		$tmp = @array_merge ( $tmp, $country_list );
		$country_listCode = '';
		$i = '';
		if ($country_list) {
			foreach ( $country_list as $key => $value ) {
				$country_listCode .= "'" . $value . "'";
				$i ++;
				if ($i < count ( $country_list )) {
					$country_listCode .= ',';
				}

			}
			return $country_listCode;
		}
	}
	function maxchar($desc = '', $maxchars = 0, $suffix = '') {

		$strdesc = '';

		if (( int ) $maxchars <= 0) {

			$strdesc = $desc;

		} else {
			$strdesc = $this->substrws ( $desc, $maxchars );
			//$strdesc = substr($desc,0,$maxchars) ;
			if (strlen ( $desc ) >= $maxchars) {

				$strdesc .= $suffix;
			}

		}
		return $strdesc;
	}

	function substrws($text, $len = 50) {

		if ((strlen ( $text ) > $len)) {

			$whitespaceposition = strpos ( $text, " ", $len ) - 1;

			if ($whitespaceposition > 0)
				$text = substr ( $text, 0, ($whitespaceposition + 1) );

			// close unclosed html tags
			if (preg_match_all ( "|<([a-zA-Z]+)>|", $text, $aBuffer )) {

				if (! empty ( $aBuffer [1] )) {

					preg_match_all ( "|</([a-zA-Z]+)>|", $text, $aBuffer2 );

					if (count ( $aBuffer [1] ) != count ( $aBuffer2 [1] )) {

						foreach ( $aBuffer [1] as $index => $tag ) {

							if (empty ( $aBuffer2 [1] [$index] ) || $aBuffer2 [1] [$index] != $tag)
								$text .= '</' . $tag . '>';
						}
					}
				}
			}
		}

		return $text;
	}
	/**
	 * Method to get date format
	 *
	 * @access	public
	 * @return	array
	 * @since	1.5
	 */
	function getDateFormat()
	{
		$option = array ();
		$mon = JText::_ ( strtoupper ( date ( "M" ) ) );
		$month = JText::_ ( strtoupper ( date ( "F" ) ) );
		$wk = JText::_ ( strtoupper ( date ( "D" ) ) );
		$week = JText::_ ( strtoupper ( date ( "l" ) ) );

		$option [] = JHTML::_ ( 'select.option', '0', JText::_ ( 'SELECT' ) );
		$option [] = JHTML::_ ( 'select.option', 'Y-m-d', date ( "Y-m-d" ) );
		$option [] = JHTML::_ ( 'select.option', 'd-m-Y', date ( "d-m-Y" ) );
		$option [] = JHTML::_ ( 'select.option', 'd.m.Y', date ( "d.m.Y" ) );
		$option [] = JHTML::_ ( 'select.option', 'Y/m/d', date ( "Y/m/d" ) );
		$option [] = JHTML::_ ( 'select.option', 'd/m/Y', date ( "d/m/Y" ) );
		$option [] = JHTML::_ ( 'select.option', 'm/d/y', date ( "m/d/y" ) );
		$option [] = JHTML::_ ( 'select.option', 'm-d-y', date ( "m-d-y" ) );
		$option [] = JHTML::_ ( 'select.option', 'm.d.y', date ( "m.d.y" ) );
		$option [] = JHTML::_ ( 'select.option', 'm/d/Y', date ( "m/d/Y" ) );
		$option [] = JHTML::_ ( 'select.option', 'm-d-Y', date ( "m-d-Y" ) );
		$option [] = JHTML::_ ( 'select.option', 'm.d.Y', date ( "m.d.Y" ) );
		$option [] = JHTML::_ ( 'select.option', 'd/M/Y', date ( "d/" ) . $mon . date ( "/Y" ) );
		$option [] = JHTML::_ ( 'select.option', 'M d,Y', $mon . date ( " d, Y" ) );
		$option [] = JHTML::_ ( 'select.option', 'd M Y', date ( "d " ) . $mon . date ( " Y" ) );
		$option [] = JHTML::_ ( 'select.option', 'd M Y, h:i:s', date ( "d " ) . $mon . date ( " Y, h:i:s" ) );
		$option [] = JHTML::_ ( 'select.option', 'd M Y, h:i A', date ( "d " ) . $mon . date ( " Y, h:i A" ) );
		$option [] = JHTML::_ ( 'select.option', 'd-m-Y, h:i:A', date ( "d-m-Y, h:i:A" ) );
		$option [] = JHTML::_ ( 'select.option', 'd.m.Y, h:i:A', date ( "d.m.Y, h:i:A" ) );
		$option [] = JHTML::_ ( 'select.option', 'd/m/Y, h:i:A', date ( "d/m/Y, h:i:A" ) );
		$option [] = JHTML::_ ( 'select.option', 'd M Y, H:i:s', date ( "d " ) . $mon . date ( " Y, H:i:s" ) );
		$option [] = JHTML::_ ( 'select.option', 'd-m-Y, H:i:s', date ( "d-m-Y, H:i:s" ) );
		$option [] = JHTML::_ ( 'select.option', 'd.m.Y, H:i:s', date ( "d.m.Y, H:i:s" ) );
		$option [] = JHTML::_ ( 'select.option', 'd/m/Y, H:i:s', date ( "d/m/Y, H:i:s" ) );
		$option [] = JHTML::_ ( 'select.option', 'F d, Y', $month . date ( " d, Y" ) );
		$option [] = JHTML::_ ( 'select.option', 'D M d, Y', $wk . " " . $mon . date ( " d, Y" ) );
		$option [] = JHTML::_ ( 'select.option', 'l F d, Y', $week . " " . $month . date ( " d, Y" ) );

		return $option;
	}

	/**
	 * Method to convert date according to format
	 *
	 * @access	public
	 * @return	array
	 * @since	1.5
	 */
	function convertDateFormat($date)
	{
		$JApp =& JFactory::getApplication();
		#$dateobj=& JFactory::getDate($date);
		#$dateobj->setOffset($JApp->getCfg('offset'));
		#$date = strtotime($dateobj->toFormat());
		if (DEFAULT_DATEFORMAT)
		{
			$convertformat = date ( DEFAULT_DATEFORMAT, $date);
			if (strstr ( DEFAULT_DATEFORMAT, "M" ))
			{
				$convertformat = str_replace ( "Jan", JText::_('COM_REDSHOP_JAN' ), $convertformat );
				$convertformat = str_replace ( "Feb", JText::_('COM_REDSHOP_FEB' ), $convertformat );
				$convertformat = str_replace ( "Mar", JText::_('COM_REDSHOP_MAR' ), $convertformat );
				$convertformat = str_replace ( "Apr", JText::_('COM_REDSHOP_APR' ), $convertformat );
				$convertformat = str_replace ( "May", JText::_('COM_REDSHOP_MAY' ), $convertformat );
				$convertformat = str_replace ( "Jun", JText::_('COM_REDSHOP_JUN' ), $convertformat );
				$convertformat = str_replace ( "Jul", JText::_('COM_REDSHOP_JUL' ), $convertformat );
				$convertformat = str_replace ( "Aug", JText::_('COM_REDSHOP_AUG' ), $convertformat );
				$convertformat = str_replace ( "Sep", JText::_('COM_REDSHOP_SEP' ), $convertformat );
				$convertformat = str_replace ( "Oct", JText::_('COM_REDSHOP_OCT' ), $convertformat );
				$convertformat = str_replace ( "Nov", JText::_('COM_REDSHOP_NOV' ), $convertformat );
				$convertformat = str_replace ( "Dec", JText::_('COM_REDSHOP_DEC' ), $convertformat );
			}
			if (strstr ( DEFAULT_DATEFORMAT, "F" )) {
				$convertformat = str_replace ( "January", JText::_('COM_REDSHOP_JANUARY' ), $convertformat );
				$convertformat = str_replace ( "February", JText::_('COM_REDSHOP_FEBRUARY' ), $convertformat );
				$convertformat = str_replace ( "March", JText::_('COM_REDSHOP_MARCH' ), $convertformat );
				$convertformat = str_replace ( "April", JText::_('COM_REDSHOP_APRIL' ), $convertformat );
				$convertformat = str_replace ( "May", JText::_('COM_REDSHOP_MAY' ), $convertformat );
				$convertformat = str_replace ( "June", JText::_('COM_REDSHOP_JUNE' ), $convertformat );
				$convertformat = str_replace ( "July", JText::_('COM_REDSHOP_JULY' ), $convertformat );
				$convertformat = str_replace ( "August", JText::_('COM_REDSHOP_AUGUST' ), $convertformat );
				$convertformat = str_replace ( "September", JText::_('COM_REDSHOP_SEPTEMBER' ), $convertformat );
				$convertformat = str_replace ( "October", JText::_('COM_REDSHOP_OCTOBER' ), $convertformat );
				$convertformat = str_replace ( "November", JText::_('COM_REDSHOP_NOVEMBER' ), $convertformat );
				$convertformat = str_replace ( "December", JText::_('COM_REDSHOP_DECEMBER' ), $convertformat );
			}
			if (strstr ( DEFAULT_DATEFORMAT, "D" )) {
				$convertformat = str_replace ( "Mon", JText::_('COM_REDSHOP_MON' ), $convertformat );
				$convertformat = str_replace ( "Tue", JText::_('COM_REDSHOP_TUE' ), $convertformat );
				$convertformat = str_replace ( "Wed", JText::_('COM_REDSHOP_WED' ), $convertformat );
				$convertformat = str_replace ( "Thu", JText::_('COM_REDSHOP_THU' ), $convertformat );
				$convertformat = str_replace ( "Fri", JText::_('COM_REDSHOP_FRI' ), $convertformat );
				$convertformat = str_replace ( "Sat", JText::_('COM_REDSHOP_SAT' ), $convertformat );
				$convertformat = str_replace ( "Sun", JText::_('COM_REDSHOP_SUN' ), $convertformat );
			}
			if (strstr ( DEFAULT_DATEFORMAT, "l" )) {
				$convertformat = str_replace ( "Monday", JText::_('COM_REDSHOP_MONDAY' ), $convertformat );
				$convertformat = str_replace ( "Tuesday", JText::_('COM_REDSHOP_TUESDAY' ), $convertformat );
				$convertformat = str_replace ( "Wednesday", JText::_('COM_REDSHOP_WEDNESDAY' ), $convertformat );
				$convertformat = str_replace ( "Thursday", JText::_('COM_REDSHOP_THURSDAY' ), $convertformat );
				$convertformat = str_replace ( "Friday", JText::_('COM_REDSHOP_FRIDAY' ), $convertformat );
				$convertformat = str_replace ( "Saturday", JText::_('COM_REDSHOP_SATURDAY' ), $convertformat );
				$convertformat = str_replace ( "Sunday", JText::_('COM_REDSHOP_SUNDAY' ), $convertformat );
			}
		}
		else
		{
			$convertformat = date ( "Y-m-d", $date);
		}
		return $convertformat;
	}

	function getCountryId($conid) {
		$db = & JFactory::getDBO ();
		$query = 'SELECT country_id FROM #__' . TABLE_PREFIX . '_country ' . 'WHERE country_3_code LIKE "' . $conid . '"';
		$db->setQuery ( $query );
		return $db->loadResult ();
	}

	function getCountryCode2($conid) {
		$db = & JFactory::getDBO ();
		$query = 'SELECT country_2_code FROM #__' . TABLE_PREFIX . '_country ' . 'WHERE country_3_code LIKE "' . $conid . '"';
		$db->setQuery ( $query );
		return $db->loadResult ();
	}

	function getStateCode2($conid) {
		$db = & JFactory::getDBO ();
		$query = 'SELECT state_2_code FROM #__' . TABLE_PREFIX . '_state ' . 'WHERE state_3_code LIKE "' . $conid . '"';
		$db->setQuery ( $query );
		return $db->loadResult ();
	}

	function getStateCode($conid, $tax_code) {
		$db = & JFactory::getDBO ();
		$query = 'SELECT  state_3_code , show_state FROM #__' . TABLE_PREFIX . '_state ' . 'WHERE state_2_code LIKE "' . $tax_code . '" and country_id="' . $conid . '"';
		$db->setQuery ( $query );
		$rslt_data = $db->loadObjectList ();

		if ($rslt_data [0]->show_state == 3) {
			$state_code = $rslt_data [0]->state_3_code;
			return $state_code;
		}
		$state_code = $tax_code;
		return $state_code;
	}

	function getCountryList($setcountry_code = 0, $country_codename = "country_code") {
		require_once (JPATH_SITE . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'helper.php');

		$redhelper = new redhelper ();
		$db = jFactory::getDBO ();
		$country_listCode = $this->countryList ( COUNTRY_LIST );

		$q = 'SELECT country_3_code AS value,country_name AS text,country_jtext ' . 'FROM #__' . TABLE_PREFIX . '_country ' . 'WHERE country_3_code IN (' . $country_listCode . ') ' . 'ORDER BY country_name ASC';
		$db->setQuery ( $q );
		$countries = $db->loadObjectList ();
		$countries = $redhelper->convertLanguageString ( $countries );

		$stylecountry = '';
		$totalcountries = count ( $countries );
		if (count ( $countries ) == 1) {

			$setcountry_code = $countries [0]->value;
			$totalcountries = 1;
		}
		$temps = array ();
		$temps [0]->value = "0";
		$temps [0]->text = JText::_('COM_REDSHOP_SELECT' );
		$countries = @array_merge ( $temps, $countries );
		return JHTML::_ ( 'select.genericlist', $countries, $country_codename, 'class="inputbox" onchange="changeStateList' . $country_codename . '();"', 'value', 'text', $setcountry_code );
	}

	function getStateList($setstate_code = 0, $setcountry_code = 0, $state_codename = "state_code", $country_codename = "country_code") {
		require_once (JPATH_SITE . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'helper.php');

		$redhelper = new redhelper ();
		$db = jFactory::getDBO ();

		$country_listCode = $this->countryList ( COUNTRY_LIST );
		$varState = array ();

		$q = 'SELECT country_3_code AS value,country_name AS text,country_jtext ' . 'FROM #__' . TABLE_PREFIX . '_country ' . 'WHERE country_3_code IN (' . $country_listCode . ') ' . 'ORDER BY country_name ASC';
		$db->setQuery ( $q );
		$countries = $db->loadObjectList ();
		$totalcountries = count ( $countries );
		if ($totalcountries == 1 && $setcountry_code == 0) {
			$setcountry_code = $countries [0]->value;
		}

		$db->setQuery ( "SELECT c.country_id, c.country_3_code, s.state_name, s.state_2_code
						FROM #__" . TABLE_PREFIX . "_country c,#__" . TABLE_PREFIX . "_state s
						WHERE (c.country_id=s.country_id OR s.country_id IS NULL)
						AND (c.country_3_code in (" . $country_listCode . "))
						ORDER BY c.country_id, s.state_name" );
		$states = $db->loadObjectList ();
		// Build the State lists for each Country
		$script = "<script language=\"javascript\" type=\"text/javascript\">";
		$script .= "var originalPos = '$setcountry_code';\n";
		$script .= "var states = new Array();\n";
		$i = 0;
		$prev_country = '';

		for($j = 0; $j < count ( $states ); $j ++) {
			$state = $states [$j];
			$country_3_code = $state->country_3_code;
			if ($state->state_name) {
				if ($prev_country != $country_3_code) {
					$script .= "states[" . $i ++ . "] = new Array( '" . $country_3_code . "','',' -= " . JText::_ ( "SELECT" ) . " =-' );\n";
					$varState [0]->value = 0;
					$varState [0]->text = JText::_ ( "SELECT" );
				}
				$prev_country = $country_3_code;
				// array in the format [key,value,text]
				$script .= "states[" . $i ++ . "] = new Array( '" . $country_3_code . "','" . $state->state_2_code . "','" . addslashes ( JText::_ ( $state->state_name ) ) . "' );\n";
				if ($country_3_code == $setcountry_code) {
					$varState [$i]->value = $state->state_2_code;
					$varState [$i]->text = JText::_ ( $state->state_name );
				}
			} else {
				$script .= "states[" . $i ++ . "] = new Array( '" . $country_3_code . "','','" . JText::_ ( "NONE" ) . "' );\n";
			}
		}
		$j = 0;
		$script .= "\nvar stated = new Array();\n";
		foreach($states as $maybe)
		{
			if(!$stated[$maybe->country_3_code] == $maybe->country_3_code){
			$script .= "stated[".$j++."] = new Array( '".$maybe->country_3_code."','','" . JText::_ ( "NONE" ) . "'  );\n";
			}
			$stated[$maybe->country_3_code] = $maybe->country_3_code;
		}

		$script .= "
		function changeStateList" . $country_codename . "() {
		  var selected_country = null;
		  for (var i=0; i<document.adminForm." . $country_codename . ".length; i++)
		  {
		  	if (document.adminForm." . $country_codename . "[i].selected)
		  	{
				selected_country = document.adminForm." . $country_codename . "[i].value;
			}
		  }
		  eval(changeDynaList('" . $state_codename . "',states,selected_country, originalPos, 1));
	 	}
		</script>";
		$script .= JHTML::_ ( 'select.genericlist', $varState, $state_codename, 'class="inputbox" ', 'value', 'text', $setstate_code );
		return $script;
	}

}
?>
