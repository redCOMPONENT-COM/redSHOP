<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 *
 * @since       2.0.0.3
 */

defined('_JEXEC') or die;

/**
 * Constants will be defined when this file autoload to application.
 * Because *RedshopAppConfiguration* will be used as static library,
 * Constructor will not neccessary anymore.
 */
/**
 * Define Site's Image Path
 *
 * @since       2.0.0.3
 */
if (!defined('JSYSTEM_IMAGES_PATH'))
{
	define('JSYSTEM_IMAGES_PATH', JURI::root() . 'media/system/images/');
}

/**
 * Define Admin's Image Path
 *
 * @since       2.0.0.3
 */
if (!defined('REDSHOP_ADMIN_IMAGES_ABSPATH'))
{
	define('REDSHOP_ADMIN_IMAGES_ABSPATH', JURI::root() . 'administrator/components/com_redshop/assets/images/');
}

/**
 * Define Front Store's Image Path
 *
 * @since       2.0.0.3
 */
if (!defined('REDSHOP_FRONT_IMAGES_ABSPATH'))
{
	define('REDSHOP_FRONT_IMAGES_ABSPATH', JURI::root() . 'components/com_redshop/assets/images/');
}

/**
 * Define Assets's Image Path
 *
 * @since       2.0.0.3
 */
if (!defined('REDSHOP_FRONT_IMAGES_RELPATH'))
{
	define('REDSHOP_FRONT_IMAGES_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/images/');
}

/**
 * Define Front Store's Document Path
 *
 * @since       2.0.0.3
 */
if (!defined('REDSHOP_FRONT_DOCUMENT_ABSPATH'))
{
	define('REDSHOP_FRONT_DOCUMENT_ABSPATH', JURI::root() . 'components/com_redshop/assets/document/');
}

/**
 * Define Assets's Document Path
 *
 * @since       2.0.0.3
 */
if (!defined('REDSHOP_FRONT_DOCUMENT_RELPATH'))
{
	define('REDSHOP_FRONT_DOCUMENT_RELPATH', JPATH_ROOT . '/components/com_redshop/assets/document/');
}

/**
 * Configuration for application, this library help to store configuration in database,
 * getting and converting date time, support to get country and state information...
 * Using: RedshopAppConfiguration::<method>
 *
 * @since  2.0.0.3
 */
class RedshopAppConfiguration
{
	/**
	 * Load redshop.cfg.php in /administrator/components/com_redshop/helpers/
	 * If success return true, else return false.
	 *
	 * @return  boolean
	 *
	 * @since  2.0.0.3
	 */
	public static function loadConfigFile()
	{
		if (!file_exists(JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php'))
		{
			return false;
		}

		require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';

		return true;
	}

	/**
	 * load default configuration file, if success return true, else return false.
	 *
	 * @return boolean
	 *
	 * @since  2.0.0.3
	 */
	public static function loadDefaultConfigFile()
	{
		$basePath = JPATH_SITE . '/administrator/components/com_redshop/helpers/';

		if (self::loadConfigFile())
		{
			if (copy($basePath . 'redshop.cfg.php', $basePath . 'redshop.cfg.bkp.php'))
			{
				if (!copy($basepath . 'wizard/redshop.cfg.dist.php', $basepath . 'redshop.cfg.php'))
				{
					return false;
				}
			}
		}
		else
		{
			if (!copy($basepath . 'wizard/redshop.cfg.dist.php', $basepath . 'redshop.cfg.php'))
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
	 *
	 * @since  2.0.0.3
	 */
	public static function loadTempConfigFile()
	{
		$basePath = JPATH_SITE . '/administrator/components/com_redshop/helpers/';

		if (file_exists($basepath . 'wizard/redshop.cfg.tmp.php'))
		{
			if (self::checkTemConfigFileIsWritable())
			{
				require_once $basepath . 'wizard/redshop.cfg.tmp.php';

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
	 * Try to find if def configuration file is available. This function is for wizard.
	 *
	 * @return  boolean  True when file is exist.
	 *
	 * @since  2.0.0.3
	 */
	public static function loadDefConfigFile()
	{
		$basePath = JPATH_SITE . '/administrator/components/com_redshop/helpers/';

		if (file_exists($basepath . 'wizard/redshop.cfg.def.php'))
		{
			if (self::checkDefConfigFileIsWritable())
			{
				require_once $basepath . 'wizard/redshop.cfg.def.php';

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
	 * Check table: redshop_configuration is existed in database or not,
	 * return true if it is existed, else return false.
	 *
	 * @return  boolean
	 *
	 * @since  2.0.0.3
	 */
	public static function checkConfigTableExist()
	{
		$db    = JFactory::getDbo();
		$query = 'SHOW TABLES LIKE ' . $db->qn('#__redshop_configuration');
		$db->setQuery($query);

		if (count($db->loadResult()) <= 0)
		{
			return false;
		}

		return true;
	}

	/**
	 * Check if temp file is writeable or not.
	 *
	 * @return  boolean  True if file is writeable.
	 *
	 * @since  2.0.0.3
	 */
	public static function checkTemConfigFileIsWritable()
	{
		$basePath = JPATH_SITE . '/administrator/components/com_redshop/helpers/';

		return is_writable($basepath . 'wizard/redshop.cfg.tmp.php');
	}

	/**
	 * Check for def file is writeable or not.
	 *
	 * @return  boolean  True if file is writeable.
	 */
	public static function checkDefConfigFileIsWritable()
	{
		$basePath = JPATH_SITE . '/administrator/components/com_redshop/helpers/';

		return is_writable($basepath . 'wizard/redshop.cfg.def.php');
	}

	/**
	 * This function will define relation between keys and define variables.
	 * This needs to be updated when you want new variable in configuration.
	 *
	 * @param   array  $data  Associative array of values. Typically a from $_POST.
	 *
	 * @return  array         Associative array of configuration variables which are ready to write.
	 *
	 * @since  2.0.0.3
	 */
	public static function prepareData($data)
	{
		if (!isset($data['booking_order_status']))
		{
			$data['booking_order_status'] = 0;
		}

		$config = array(
			"PI"                                           => 3.14,
			"ADMINISTRATOR_EMAIL"                          => $data["administrator_email"],
			"THUMB_WIDTH"                                  => $data["thumb_width"],
			"THUMB_HEIGHT"                                 => $data["thumb_height"],
			"THUMB_WIDTH_2"                                => $data["thumb_width_2"],
			"THUMB_HEIGHT_2"                               => $data["thumb_height_2"],
			"THUMB_WIDTH_3"                                => $data["thumb_width_3"],
			"THUMB_HEIGHT_3"                               => $data["thumb_height_3"],
			"CATEGORY_PRODUCT_THUMB_WIDTH"                 => $data["category_product_thumb_width"],
			"CATEGORY_PRODUCT_THUMB_HEIGHT"                => $data["category_product_thumb_height"],
			"CATEGORY_PRODUCT_THUMB_WIDTH_2"               => $data["category_product_thumb_width_2"],
			"CATEGORY_PRODUCT_THUMB_HEIGHT_2"              => $data["category_product_thumb_height_2"],
			"CATEGORY_PRODUCT_THUMB_WIDTH_3"               => $data["category_product_thumb_width_3"],
			"CATEGORY_PRODUCT_THUMB_HEIGHT_3"              => $data["category_product_thumb_height_3"],
			"RELATED_PRODUCT_THUMB_WIDTH"                  => $data["related_product_thumb_width"],
			"RELATED_PRODUCT_THUMB_HEIGHT"                 => $data["related_product_thumb_height"],
			"RELATED_PRODUCT_THUMB_WIDTH_2"                => $data["related_product_thumb_width_2"],
			"RELATED_PRODUCT_THUMB_HEIGHT_2"               => $data["related_product_thumb_height_2"],
			"RELATED_PRODUCT_THUMB_WIDTH_3"                => $data["related_product_thumb_width_3"],
			"RELATED_PRODUCT_THUMB_HEIGHT_3"               => $data["related_product_thumb_height_3"],
			"ATTRIBUTE_SCROLLER_THUMB_WIDTH"               => $data["attribute_scroller_thumb_width"],
			"ATTRIBUTE_SCROLLER_THUMB_HEIGHT"              => $data["attribute_scroller_thumb_height"],
			"COMPARE_PRODUCT_THUMB_WIDTH"                  => $data["compare_product_thumb_width"],
			"COMPARE_PRODUCT_THUMB_HEIGHT"                 => $data["compare_product_thumb_height"],
			"ACCESSORY_THUMB_HEIGHT"                       => $data["accessory_thumb_height"],
			"ACCESSORY_THUMB_WIDTH"                        => $data["accessory_thumb_width"],
			"ACCESSORY_THUMB_HEIGHT_2"                     => $data["accessory_thumb_height_2"],
			"ACCESSORY_THUMB_WIDTH_2"                      => $data["accessory_thumb_width_2"],
			"ACCESSORY_THUMB_HEIGHT_3"                     => $data["accessory_thumb_height_3"],
			"ACCESSORY_THUMB_WIDTH_3"                      => $data["accessory_thumb_width_3"],

			"DEFAULT_AJAX_DETAILBOX_TEMPLATE"              => $data["default_ajax_detailbox_template"],
			"ASTERISK_POSITION"                            => 0,
			"MANUFACTURER_THUMB_WIDTH"                     => $data["manufacturer_thumb_width"],
			"MANUFACTURER_THUMB_HEIGHT"                    => $data["manufacturer_thumb_height"],
			"MANUFACTURER_PRODUCT_THUMB_WIDTH"             => $data["manufacturer_product_thumb_width"],
			"MANUFACTURER_PRODUCT_THUMB_HEIGHT"            => $data["manufacturer_product_thumb_height"],
			"MANUFACTURER_PRODUCT_THUMB_WIDTH_2"           => $data["manufacturer_product_thumb_width_2"],
			"MANUFACTURER_PRODUCT_THUMB_HEIGHT_2"          => $data["manufacturer_product_thumb_height_2"],
			"MANUFACTURER_PRODUCT_THUMB_WIDTH_3"           => $data["manufacturer_product_thumb_width_3"],
			"MANUFACTURER_PRODUCT_THUMB_HEIGHT_3"          => $data["manufacturer_product_thumb_height_3"],

			"CART_THUMB_WIDTH"                             => $data["cart_thumb_width"],
			"CART_THUMB_HEIGHT"                            => $data["cart_thumb_height"],
			"SHOW_TERMS_AND_CONDITIONS"                    => $data["show_terms_and_conditions"],

			"GIFTCARD_THUMB_WIDTH"                         => $data["giftcard_thumb_width"],
			"GIFTCARD_THUMB_HEIGHT"                        => $data["giftcard_thumb_height"],
			"GIFTCARD_LIST_THUMB_WIDTH"                    => $data["giftcard_list_thumb_width"],
			"GIFTCARD_LIST_THUMB_HEIGHT"                   => $data["giftcard_list_thumb_height"],

			"PRODUCT_MAIN_IMAGE"                           => $data["product_main_image"],
			"PRODUCT_MAIN_IMAGE_HEIGHT"                    => $data["product_main_image_height"],
			"PRODUCT_MAIN_IMAGE_2"                         => $data["product_main_image_2"],
			"PRODUCT_MAIN_IMAGE_HEIGHT_2"                  => $data["product_main_image_height_2"],
			"PRODUCT_MAIN_IMAGE_3"                         => $data["product_main_image_3"],
			"PRODUCT_MAIN_IMAGE_HEIGHT_3"                  => $data["product_main_image_height_3"],

			"PRODUCT_ADDITIONAL_IMAGE"                     => $data["product_additional_image"],
			"PRODUCT_ADDITIONAL_IMAGE_HEIGHT"              => $data["product_additional_image_height"],
			"PRODUCT_ADDITIONAL_IMAGE_2"                   => $data["product_additional_image_2"],
			"PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2"            => $data["product_additional_image_height_2"],
			"PRODUCT_ADDITIONAL_IMAGE_3"                   => $data["product_additional_image_3"],
			"PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3"            => $data["product_additional_image_height_3"],

			"PRODUCT_PREVIEW_IMAGE_WIDTH"                  => $data["product_preview_image_width"],
			"PRODUCT_PREVIEW_IMAGE_HEIGHT"                 => $data["product_preview_image_height"],
			"DEFAULT_STOCKAMOUNT_THUMB_WIDTH"              => $data['default_stockamount_thumb_width'],
			"DEFAULT_STOCKAMOUNT_THUMB_HEIGHT"             => $data['default_stockamount_thumb_height'],

			"CATEGORY_PRODUCT_PREVIEW_IMAGE_WIDTH"         => $data["category_product_preview_image_width"],
			"CATEGORY_PRODUCT_PREVIEW_IMAGE_HEIGHT"        => $data["category_product_preview_image_height"],

			"PRODUCT_COMPARE_LIMIT"                        => $data["product_compare_limit"],
			"PRODUCT_DOWNLOAD_LIMIT"                       => $data["product_download_limit"],
			"PRODUCT_DOWNLOAD_DAYS"                        => $data["product_download_days"],
			"QUANTITY_TEXT_DISPLAY"                        => $data["quantity_text_display"],

			"DISCOUNT_MAIL_SEND"                           => $data["discount_mail_send"],
			"DAYS_MAIL1"                                   => $data["days_mail1"],
			"DAYS_MAIL2"                                   => $data["days_mail2"],
			"DAYS_MAIL3"                                   => $data["days_mail3"],

			"DISCOUPON_DURATION"                           => $data["discoupon_duration"],
			"DISCOUPON_PERCENT_OR_TOTAL"                   => $data["discoupon_percent_or_total"],
			"DISCOUPON_VALUE"                              => $data["discoupon_value"],
			"USE_STOCKROOM"                                => $data["use_stockroom"],
			"ALLOW_PRE_ORDER"                              => $data["allow_pre_order"],
			"ALLOW_PRE_ORDER_MESSAGE"                      => $data["allow_pre_order_message"],

			"DEFAULT_VAT_COUNTRY"                          => $data["default_vat_country"],
			"DEFAULT_VAT_STATE"                            => $data["default_vat_state"],
			"DEFAULT_VAT_GROUP"                            => $data["default_vat_group"],
			"VAT_BASED_ON"                                 => $data["vat_based_on"],

			"PRODUCT_TEMPLATE"                             => $data["default_product_template"],
			"CATEGORY_TEMPLATE"                            => $data["default_category_template"],
			"DEFAULT_CATEGORYLIST_TEMPLATE"                => $data["default_categorylist_template"],
			"MANUFACTURER_TEMPLATE"                        => $data["default_manufacturer_template"],
			"COUNTRY_LIST"                                 => $data["country_list"],
			"PRODUCT_DEFAULT_IMAGE"                        => $data["product_default_image"],
			"PRODUCT_OUTOFSTOCK_IMAGE"                     => $data["product_outofstock_image"],
			"CATEGORY_DEFAULT_IMAGE"                       => $data["category_default_image"],
			"ADDTOCART_IMAGE"                              => $data["addtocart_image"],
			"REQUESTQUOTE_IMAGE"                           => $data["requestquote_image"],
			"REQUESTQUOTE_BACKGROUND"                      => $data["requestquote_background"],
			"PRE_ORDER_IMAGE"                              => $data["pre_order_image"],
			"CATEGORY_SHORT_DESC_MAX_CHARS"                => $data["category_short_desc_max_chars"],
			"CATEGORY_SHORT_DESC_END_SUFFIX"               => $data["category_short_desc_end_suffix"],
			"CATEGORY_DESC_MAX_CHARS"                      => $data["category_desc_max_chars"],
			"CATEGORY_DESC_END_SUFFIX"                     => $data["category_desc_end_suffix"],

			"CATEGORY_TITLE_MAX_CHARS"                     => $data["category_title_max_chars"],
			"CATEGORY_TITLE_END_SUFFIX"                    => $data["category_title_end_suffix"],
			"CATEGORY_PRODUCT_TITLE_MAX_CHARS"             => $data["category_product_title_max_chars"],
			"CATEGORY_PRODUCT_TITLE_END_SUFFIX"            => $data["category_product_title_end_suffix"],
			"CATEGORY_PRODUCT_DESC_MAX_CHARS"              => $data["category_product_desc_max_chars"],
			"CATEGORY_PRODUCT_DESC_END_SUFFIX"             => $data["category_product_desc_end_suffix"],
			"RELATED_PRODUCT_DESC_MAX_CHARS"               => $data["related_product_desc_max_chars"],
			"RELATED_PRODUCT_DESC_END_SUFFIX"              => $data["related_product_desc_end_suffix"],
			"RELATED_PRODUCT_TITLE_MAX_CHARS"              => $data["related_product_title_max_chars"],
			"RELATED_PRODUCT_TITLE_END_SUFFIX"             => $data["related_product_title_end_suffix"],
			"ACCESSORY_PRODUCT_DESC_MAX_CHARS"             => $data["accessory_product_desc_max_chars"],
			"ACCESSORY_PRODUCT_DESC_END_SUFFIX"            => $data["accessory_product_desc_end_suffix"],
			"ACCESSORY_PRODUCT_TITLE_MAX_CHARS"            => $data["accessory_product_title_max_chars"],
			"ACCESSORY_PRODUCT_TITLE_END_SUFFIX"           => $data["accessory_product_title_end_suffix"],
			"ADDTOCART_BACKGROUND"                         => $data["addtocart_background"],
			"SPLIT_DELIVERY_COST"                          => $data["split_delivery_cost"],
			"TIME_DIFF_SPLIT_DELIVERY"                     => $data["time_diff_split_delivery"],
			"NEWS_MAIL_FROM"                               => $data["news_mail_from"],
			"NEWS_FROM_NAME"                               => $data["news_from_name"],
			"DEFAULT_NEWSLETTER"                           => $data["default_newsletter"],

			"SHOP_COUNTRY"                                 => $data["shop_country"],
			"DEFAULT_SHIPPING_COUNTRY"                     => $data["default_shipping_country"],
			"REDCURRENCY_SYMBOL"                           => $data["currency_symbol"],
			"PRICE_SEPERATOR"                              => $data["price_seperator"],
			"THOUSAND_SEPERATOR"                           => $data["thousand_seperator"],
			"CURRENCY_SYMBOL_POSITION"                     => $data["currency_symbol_position"],
			"PRICE_DECIMAL"                                => $data["price_decimal"],
			"CALCULATION_PRICE_DECIMAL"                    => $data["calculation_price_decimal"],
			"UNIT_DECIMAL"                                 => $data["unit_decimal"],
			"DEFAULT_DATEFORMAT"                           => $data["default_dateformat"],
			"CURRENCY_CODE"                                => $data["currency_code"],
			"ECONOMIC_INTEGRATION"                         => $data["economic_integration"],
			"DEFAULT_ECONOMIC_ACCOUNT_GROUP"               => $data["default_economic_account_group"],
			"ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC"             => $data["attribute_as_product_in_economic"],
			"DETAIL_ERROR_MESSAGE_ON"                      => $data["detail_error_message_on"],
			"CAT_IS_LIGHTBOX"                              => $data["cat_is_lightbox"],
			"PRODUCT_IS_LIGHTBOX"                          => $data["product_is_lightbox"],
			"PRODUCT_DETAIL_IS_LIGHTBOX"                   => $data["product_detail_is_lightbox"],
			"PRODUCT_ADDIMG_IS_LIGHTBOX"                   => $data["product_addimg_is_lightbox"],
			"USE_PRODUCT_OUTOFSTOCK_IMAGE"                 => $data["use_product_outofstock_image"],
			"WELCOME_MSG"                                  => $data["welcome_msg"],
			"SHOP_NAME"                                    => $data["shop_name"],
			"COUPONS_ENABLE"                               => $data["coupons_enable"],
			"VOUCHERS_ENABLE"                              => $data["vouchers_enable"],
			"APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT"        => $data["apply_voucher_coupon_already_discount"],
			"SHOW_EMAIL_VERIFICATION"                      => $data["show_email_verification"],

			"RATING_MSG"                                   => $data["rating_msg"],
			"DISCOUNT_DURATION"                            => $data["discount_duration"],
			"SPECIAL_DISCOUNT_MAIL_SEND"                   => $data["special_discount_mail_send"],
			"DISCOUNT_PERCENTAGE"                          => $data["discount_percentage"],
			"CATALOG_DAYS"                                 => $data["catalog_days"],
			"CATALOG_REMINDER_1"                           => $data["catalog_reminder_1"],
			"CATALOG_REMINDER_2"                           => $data["catalog_reminder_2"],
			"FAVOURED_REVIEWS"                             => $data["favoured_reviews"],
			"COLOUR_SAMPLE_REMAINDER_1"                    => $data["colour_sample_remainder_1"],
			"COLOUR_SAMPLE_REMAINDER_2"                    => $data["colour_sample_remainder_2"],
			"COLOUR_SAMPLE_REMAINDER_3"                    => $data["colour_sample_remainder_3"],
			"COLOUR_COUPON_DURATION"                       => $data["colour_coupon_duration"],
			"COLOUR_DISCOUNT_PERCENTAGE"                   => $data["colour_discount_percentage"],
			"COLOUR_SAMPLE_DAYS"                           => $data["colour_sample_days"],
			"CATEGORY_FRONTPAGE_INTROTEXT"                 => $data["category_frontpage_introtext"],
			"REGISTRATION_INTROTEXT"                       => $data["registration_introtext"],
			"REGISTRATION_COMPANY_INTROTEXT"               => $data["registration_comp_introtext"],
			"VAT_INTROTEXT"                                => $data["vat_introtext"],
			"DELIVERY_RULE"                                => $data["delivery_rule"],
			"GOOGLE_ANA_TRACKER_KEY"                       => $data["google_ana_tracker"],
			"AUTOGENERATED_SEO"                            => $data["autogenerated_seo"],
			"ENABLE_SEF_PRODUCT_NUMBER"                    => $data["enable_sef_product_number"],
			"ENABLE_SEF_NUMBER_NAME"                       => $data["enable_sef_number_name"],

			"DEFAULT_CUSTOMER_REGISTER_TYPE"               => $data["default_customer_register_type"],
			"CHECKOUT_LOGIN_REGISTER_SWITCHER"             => $data["checkout_login_register_switcher"],
			"ADDTOCART_BEHAVIOUR"                          => $data["addtocart_behaviour"],
			"WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART"          => $data["wanttoshowattributeimage"],
			"SHOW_PRODUCT_DETAIL"                          => $data["show_product_detail"],

			"ALLOW_CUSTOMER_REGISTER_TYPE"                 => $data["allow_customer_register_type"],
			"REQUIRED_VAT_NUMBER"                          => $data["required_vat_number"],

			"OPTIONAL_SHIPPING_ADDRESS"                    => $data["optional_shipping_address"],
			"SHIPPING_METHOD_ENABLE"                       => $data["shipping_method_enable"],

			"SEO_PAGE_TITLE"                               => $data["seo_page_title"],
			"SEO_PAGE_HEADING"                             => $data["seo_page_heading"],
			"SEO_PAGE_SHORT_DESCRIPTION"                   => $data["seo_page_short_description"],
			"SEO_PAGE_DESCRIPTION"                         => $data["seo_page_description"],
			"SEO_PAGE_KEYWORDS"                            => $data["seo_page_keywords"],
			"SEO_PAGE_LANGAUGE"                            => $data["seo_page_language"],
			"SEO_PAGE_TITLE_CATEGORY"                      => $data["seo_page_title_category"],
			"SEO_PAGE_HEADING_CATEGORY"                    => $data["seo_page_heading_category"],
			"SEO_PAGE_SHORT_DESCRIPTION_CATEGORY"          => $data["seo_page_short_description_category"],
			"SEO_PAGE_DESCRIPTION_CATEGORY"                => $data["seo_page_description_category"],
			"SEO_PAGE_KEYWORDS_CATEGORY"                   => $data["seo_page_keywords_category"],
			"SEO_PAGE_TITLE_MANUFACTUR"                    => $data["seo_page_title_manufactur"],
			"SEO_PAGE_HEADING_MANUFACTUR"                  => $data["seo_page_heading_manufactur"],
			"SEO_PAGE_DESCRIPTION_MANUFACTUR"              => $data["seo_page_description_manufactur"],
			"SEO_PAGE_KEYWORDS_MANUFACTUR"                 => $data["seo_page_keywords_manufactur"],
			"SEO_PAGE_CANONICAL_MANUFACTUR"                => $data["seo_page_canonical_manufactur"],

			"USE_TAX_EXEMPT"                               => $data["use_tax_exempt"],
			"TAX_EXEMPT_APPLY_VAT"                         => $data["tax_exempt_apply_vat"],

			"COUPONINFO"                                   => $data["couponinfo"],
			"MY_TAGS"                                      => $data["my_tags"],
			"MY_WISHLIST"                                  => $data["my_wishlist"],
			"COMPARE_PRODUCTS"                             => $data["compare_products"],

			"REGISTER_METHOD"                              => $data["register_method"],
			"ZERO_PRICE_REPLACE"                           => $data["zero_price_replacement"],
			"ZERO_PRICE_REPLACE_URL"                       => $data["zero_price_replacement_url"],
			"PRICE_REPLACE"                                => $data["price_replacement"],
			"PRICE_REPLACE_URL"                            => $data["price_replacement_url"],
			"PAYMENT_CALCULATION_ON"                       => $data["payment_calculation_on"],
			"PORTAL_SHOP"                                  => $data["portal_shop"],
			"DEFAULT_PORTAL_NAME"                          => $data["default_portal_name"],
			"DEFAULT_PORTAL_LOGO"                          => $data["default_portal_logo"],
			"SHOPPER_GROUP_DEFAULT_PRIVATE"                => $data["shopper_group_default_private"],
			"SHOPPER_GROUP_DEFAULT_COMPANY"                => $data["shopper_group_default_company"],
			"NEW_SHOPPER_GROUP_GET_VALUE_FROM"             => $data["new_shopper_group_get_value_from"],
			"SHOPPER_GROUP_DEFAULT_UNREGISTERED"           => $data["shopper_group_default_unregistered"],

			"PRODUCT_EXPIRE_TEXT"                          => $data["product_expire_text"],
			"TERMS_ARTICLE_ID"                             => $data["terms_article_id"],

			"INVOICE_NUMBER_TEMPLATE"                      => $data["invoice_number_template"],
			"REAL_INVOICE_NUMBER_TEMPLATE"                 => $data["real_invoice_number_template"],
			"FIRST_INVOICE_NUMBER"                         => $data["first_invoice_number"],
			"INVOICE_NUMBER_FOR_FREE_ORDER"                => $data["invoice_number_for_free_order"],
			"DEFAULT_CATEGORY_ORDERING_METHOD"             => $data["default_category_ordering_method"],
			"DEFAULT_PRODUCT_ORDERING_METHOD"              => $data["default_product_ordering_method"],
			"DEFAULT_RELATED_ORDERING_METHOD"              => $data["default_related_ordering_method"],
			"DEFAULT_ACCESSORY_ORDERING_METHOD"            => $data["default_accessory_ordering_method"],
			"DEFAULT_MANUFACTURER_ORDERING_METHOD"         => $data["default_manufacturer_ordering_method"],
			"DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD" => $data["default_manufacturer_product_ordering_method"],
			"WELCOMEPAGE_INTROTEXT"                        => $data["welcomepage_introtext"],
			"NEW_CUSTOMER_SELECTION"                       => $data["new_customer_selection"],
			"AJAX_CART_BOX"                                => $data["ajax_cart_box"],
			"IS_PRODUCT_RESERVE"                           => $data["is_product_reserve"],
			"CART_RESERVATION_MESSAGE"                     => $data["cart_reservation_message"],
			"WITHOUT_VAT_TEXT_INFO"                        => $data["without_vat_text_info"],
			"WITH_VAT_TEXT_INFO"                           => $data["with_vat_text_info"],
			"DEFAULT_STOCKROOM"                            => $data["default_stockroom"],
			"DEFAULT_CART_CHECKOUT_ITEMID"                 => $data["default_cart_checkout_itemid"],
			"USE_IMAGE_SIZE_SWAPPING"                      => $data["use_image_size_swapping"],
			"DEFAULT_WRAPPER_THUMB_WIDTH"                  => $data["default_wrapper_thumb_width"],
			"DEFAULT_WRAPPER_THUMB_HEIGHT"                 => $data["default_wrapper_thumb_height"],
			"DEFAULT_QUANTITY"                             => $data["default_quantity"],
			"DEFAULT_QUANTITY_SELECTBOX_VALUE"             => $data["default_quantity_selectbox_value"],
			"AUTO_SCROLL_WRAPPER"                          => $data["auto_scroll_wrapper"],
			"MAXCATEGORY"                                  => $data["maxcategory"],

			"ECONOMIC_INVOICE_DRAFT"                       => $data["economic_invoice_draft"],
			"BOOKING_ORDER_STATUS"                         => $data["booking_order_status"],
			"ECONOMIC_BOOK_INVOICE_NUMBER"                 => $data["economic_book_invoice_number"],

			"PORTAL_LOGIN_ITEMID"                          => $data["portal_login_itemid"],
			"PORTAL_LOGOUT_ITEMID"                         => $data["portal_logout_itemid"],
			"APPLY_VAT_ON_DISCOUNT"                        => $data["apply_vat_on_discount"],
			"CONTINUE_REDIRECT_LINK"                       => $data["continue_redirect_link"],

			"DEFAULT_LINK_FIND"                            => $data["next_previous_link"],
			"IMAGE_PREVIOUS_LINK_FIND"                     => $data["image_previous_link"],
			"PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE"   => $data["product_detail_lighbox_close_button_image"],
			"IMAGE_NEXT_LINK_FIND"                         => $data["image_next_link"],
			"CUSTOM_PREVIOUS_LINK_FIND"                    => $data["custom_previous_link"],
			"CUSTOM_NEXT_LINK_FIND"                        => $data["custom_next_link"],
			"DAFULT_NEXT_LINK_SUFFIX"                      => $data["default_next_suffix"],
			"DAFULT_PREVIOUS_LINK_PREFIX"                  => $data["default_previous_prefix"],
			"DAFULT_RETURN_TO_CATEGORY_PREFIX"             => $data["return_to_category_prefix"],
			"ALLOW_MULTIPLE_DISCOUNT"                      => $data["allow_multiple_discount"],

			"DISCOUNT_ENABLE"                              => $data["discount_enable"],
			"DISCOUNT_TYPE"                                => $data["discount_type"],
			"INVOICE_MAIL_ENABLE"                          => $data["invoice_mail_enable"],
			"WISHLIST_LOGIN_REQUIRED"                      => $data["wishlist_login_required"],

			"INVOICE_MAIL_SEND_OPTION"                     => $data["invoice_mail_send_option"],
			"ORDER_MAIL_AFTER"                             => $data["order_mail_after"],
			"ACCESSORY_PRODUCT_IN_LIGHTBOX"                => $data["accessory_product_in_lightbox"],
			"MINIMUM_ORDER_TOTAL"                          => $data["minimum_order_total"],
			"MANUFACTURER_TITLE_MAX_CHARS"                 => $data["manufacturer_title_max_chars"],
			"MANUFACTURER_TITLE_END_SUFFIX"                => $data["manufacturer_title_end_suffix"],

			"DEFAULT_VOLUME_UNIT"                          => $data["default_volume_unit"],
			"DEFAULT_WEIGHT_UNIT"                          => $data["default_weight_unit"],
			"RATING_REVIEW_LOGIN_REQUIRED"                 => $data["rating_review_login_required"],
			"WEBPACK_ENABLE_SMS"                           => $data["webpack_enable_sms"],
			"WEBPACK_ENABLE_EMAIL_TRACK"                   => $data["webpack_enable_email_track"],

			"STATISTICS_ENABLE"                            => $data["statistics_enable"],
			"NEWSLETTER_ENABLE"                            => $data["newsletter_enable"],
			"NEWSLETTER_CONFIRMATION"                      => $data["newsletter_confirmation"],
			"WATERMARK_IMAGE"                              => $data["watermark_image"],

			"WATERMARK_CATEGORY_THUMB_IMAGE"               => $data["watermark_category_thumb_image"],
			"WATERMARK_CATEGORY_IMAGE"                     => $data["watermark_category_image"],
			"WATERMARK_PRODUCT_IMAGE"                      => $data["watermark_product_image"],
			"WATERMARK_PRODUCT_THUMB_IMAGE"                => $data["watermark_product_thumb_image"],
			"WATERMARK_PRODUCT_ADDITIONAL_IMAGE"           => $data["watermark_product_additional_image"],
			"WATERMARK_CART_THUMB_IMAGE"                   => $data["watermark_cart_thumb_image"],
			"WATERMARK_GIFTCART_IMAGE"                     => $data["watermark_giftcart_image"],
			"WATERMARK_GIFTCART_THUMB_IMAGE"               => $data["watermark_giftcart_thumb_image"],
			"WATERMARK_MANUFACTURER_THUMB_IMAGE"           => $data["watermark_manufacturer_thumb_image"],
			"WATERMARK_MANUFACTURER_IMAGE"                 => $data["watermark_manufacturer_image"],

			'GLS_CUSTOMER_ID'                              => $data["gls_customer_id"],
			'CLICKATELL_USERNAME'                          => $data["clickatell_username"],
			'CLICKATELL_PASSWORD'                          => $data["clickatell_password"],
			'CLICKATELL_API_ID'                            => $data["clickatell_api_id"],
			'CLICKATELL_ENABLE'                            => $data["clickatell_enable"],
			'CLICKATELL_ORDER_STATUS'                      => $data["clickatell_order_status"],
			'PRE_USE_AS_CATALOG'                           => $data["use_as_catalog"],
			'SHOW_SHIPPING_IN_CART'                        => $data["show_shipping_in_cart"],
			'MANUFACTURER_MAIL_ENABLE'                     => $data["manufacturer_mail_enable"],
			'SUPPLIER_MAIL_ENABLE'                         => $data["supplier_mail_enable"],
			'PRODUCT_COMPARISON_TYPE'                      => $data["product_comparison_type"],
			'COMPARE_TEMPLATE_ID'                          => $data["compare_template_id"],
			'SSL_ENABLE_IN_CHECKOUT'                       => $data["ssl_enable_in_checkout"],
			'VAT_RATE_AFTER_DISCOUNT'                      => $data["vat_rate_after_discount"],
			'PRODUCT_DOWNLOAD_ROOT'                        => $data["product_download_root"],
			'TWOWAY_RELATED_PRODUCT'                       => $data["twoway_related_product"],

			'PRODUCT_HOVER_IMAGE_ENABLE'                   => $data["product_hover_image_enable"],
			'PRODUCT_HOVER_IMAGE_WIDTH'                    => $data["product_hover_image_width"],
			'PRODUCT_HOVER_IMAGE_HEIGHT'                   => $data["product_hover_image_height"],
			'ADDITIONAL_HOVER_IMAGE_ENABLE'                => $data["additional_hover_image_enable"],
			'ADDITIONAL_HOVER_IMAGE_WIDTH'                 => $data["additional_hover_image_width"],
			'ADDITIONAL_HOVER_IMAGE_HEIGHT'                => $data["additional_hover_image_height"],
			'SSL_ENABLE_IN_BACKEND'                        => $data["ssl_enable_in_backend"],
			"SHOW_PRICE_SHOPPER_GROUP_LIST"                => $data["show_price_shopper_group_list"],
			"SHOW_PRICE_USER_GROUP_LIST"                   => $data["show_price_user_group_list"],
			"SHIPPING_AFTER"                               => $data["shipping_after"],
			"ENABLE_ADDRESS_DETAIL_IN_SHIPPING"            => $data["enable_address_detail_in_shipping"],

			"CATEGORY_PRODUCT_SHORT_DESC_MAX_CHARS"        => $data['category_product_short_desc_max_chars'],
			"CATEGORY_PRODUCT_SHORT_DESC_END_SUFFIX"       => $data['category_product_short_desc_end_suffix'],
			"RELATED_PRODUCT_SHORT_DESC_MAX_CHARS"         => $data['related_product_short_desc_max_chars'],
			"RELATED_PRODUCT_SHORT_DESC_END_SUFFIX"        => $data['related_product_short_desc_end_suffix'],
			"CALCULATE_VAT_ON"                             => $data['calculate_vat_on'],
			"REMOTE_UPDATE_DOMAIN_URL"                     => 'http://dev.redcomponent.com/',
			"ONESTEP_CHECKOUT_ENABLE"                      => $data["onestep_checkout_enable"],
			"SHOW_TAX_EXEMPT_INFRONT"                      => $data["show_tax_exempt_infront"],
			"NOOF_THUMB_FOR_SCROLLER"                      => $data["noof_thumb_for_scroller"],
			"NOOF_SUBATTRIB_THUMB_FOR_SCROLLER"            => $data["noof_subattrib_thumb_for_scroller"],

			"INDIVIDUAL_ADD_TO_CART_ENABLE"                => $data["individual_add_to_cart_enable"],
			"ACCESSORY_AS_PRODUCT_IN_CART_ENABLE"          => $data["accessory_as_product_in_cart_enable"],
			"POSTDK_CUSTOMER_NO"                           => $data["postdk_customer_no"],
			"POSTDK_CUSTOMER_PASSWORD"                     => $data["postdk_customer_password"],
			"POSTDK_INTEGRATION"                           => $data["postdk_integration"],
			"POSTDANMARK_ADDRESS"                          => $data["postdk_address"],
			"POSTDANMARK_POSTALCODE"                       => $data["postdk_postalcode"],
			"AUTO_GENERATE_LABEL"                          => $data["auto_generate_label"],
			"GENERATE_LABEL_ON_STATUS"                     => $data["generate_label_on_status"],

			"MENUHIDE"                                     => $data["menuhide"],
			"AJAX_CART_DISPLAY_TIME"                       => $data['ajax_cart_display_time'],
			"MEDIA_ALLOWED_MIME_TYPE"                      => $data['media_allowed_mime_type'],
			"IMAGE_QUALITY_OUTPUT"                         => $data['image_quality_output'],
			"SEND_CATALOG_REMINDER_MAIL"                   => $data['send_catalog_reminder_mail'],
			"CATEGORY_IN_SEF_URL"                          => $data['category_in_sef_url'],
			"CATEGORY_TREE_IN_SEF_URL"                     => $data['category_tree_in_sef_url'],
			"USE_BLANK_AS_INFINITE"                        => $data['use_blank_as_infinite'],
			"USE_ENCODING"                                 => $data['use_encoding'],
			"CREATE_ACCOUNT_CHECKBOX"                      => $data['create_account_checkbox'],

			"SHOW_QUOTATION_PRICE"                         => $data['show_quotation_price'],
			"CHILDPRODUCT_DROPDOWN"                        => $data['childproduct_dropdown'],
			"PURCHASE_PARENT_WITH_CHILD"                   => $data['purchase_parent_with_child'],
			"ADDTOCART_DELETE"                             => $data["addtocart_delete"],
			"ADDTOCART_UPDATE"                             => $data["addtocart_update"],
			"DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA"          => $data["display_out_of_stock_attribute_data"],
			"SEND_MAIL_TO_CUSTOMER"                        => $data["send_mail_to_customer"],
			"AJAX_DETAIL_BOX_WIDTH"                        => $data["ajax_detail_box_width"],
			"AJAX_DETAIL_BOX_HEIGHT"                       => $data["ajax_detail_box_height"],
			"AJAX_BOX_WIDTH"                               => $data["ajax_box_width"],
			"AJAX_BOX_HEIGHT"                              => $data["ajax_box_height"],
			"DEFAULT_STOCKROOM_BELOW_AMOUNT_NUMBER"        => $data["default_stockroom_below_amount_number"],
			"LOAD_REDSHOP_STYLE"                           => $data["load_redshop_style"],
			"ENABLE_STOCKROOM_NOTIFICATION"                => $data["enable_stockroom_notification"],

			"BACKWARD_COMPATIBLE_JS"                       => $data['backward_compatible_js'],
			"BACKWARD_COMPATIBLE_PHP"                      => $data['backward_compatible_php']
		);

		if ($data["cart_timeout"] <= 0)
		{
			$config["CART_TIMEOUT"] = 20;
		}
		else
		{
			$config["CART_TIMEOUT"] = $data["cart_timeout"];
		}

		$config["DEFAULT_QUOTATION_MODE_PRE"] = $data["default_quotation_mode"];

		$config["SHOW_PRICE_PRE"] = $data["show_price"];

		if ($data["newsletter_mail_chunk"] == 0)
		{
			$data["newsletter_mail_chunk"] = 1;
		}

		if ($data["newsletter_mail_pause_time"] == 0)
		{
			$data["newsletter_mail_pause_time"] = 1;
		}

		$config["NEWSLETTER_MAIL_CHUNK"]      = $data["newsletter_mail_chunk"];
		$config["NEWSLETTER_MAIL_PAUSE_TIME"] = $data["newsletter_mail_pause_time"];

		return $config;
	}

	/**
	 * Prepare a text to define Config contants from provided $data.
	 *
	 * @param   array    $data    Configuration Data associative array
	 * @param   boolean  $bypass  Don't write anything and simply bypass if it is set to true.
	 *
	 * @return  string
	 *
	 * @since  2.0.0.3
	 */
	public static function defineConfigFromData($data, $bypass = false)
	{
		$defineText = "";

		foreach ($data as $key => $value)
		{
			if (!defined($key) || $bypass)
			{
				$defineText .= "define('" . $key . "', '" . addslashes($value) . "');\n";
			}
		}

		return $defineText;
	}

	/**
	 * Write prepared data into a file.
	 * $defineText = self::defineConfigFromData($data, $bypass)
	 *
	 * @param   string  $defineText  Text was defined as command to define configuration
	 *
	 * @return  boolean              True when file successfully saved.
	 *
	 * @since  2.0.0.3
	 */
	public static function writeConfigToFile($defineText = "")
	{
		if ($fp = fopen(JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php', "w"))
		{
			// Cleaning <?php and ?\> tag from the code
			$defineText = str_replace(array('<?php', '?>', "\n"), '', $defineText);

			// Now, adding <?php tag at the top of the file.
			$defineText = "<?php \n" . $defineText;

			fwrite($fp, $defineText, strlen($defineText));
			fclose($fp);

			return true;
		}

		return false;
	}

	/**
	 * Update Configuration file with new parameter.
	 * This function is specially use during upgrading redSHOP and need to put new configuration params.
	 *
	 * @param   string  $defineText  Text was defined as command to define configuration
	 *
	 * @return  boolean  True when file successfully updated.
	 *
	 * @since  2.0.0.3
	 */
	public static function updateConfigFile($defineText = "")
	{
		if ($fp = fopen(JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php', "a"))
		{
			fputs($fp, $defineText, strlen($defineText));
			fclose($fp);

			return true;
		}

		return false;
	}

	/**
	 * Backup Configuration file before running wizard.
	 *
	 * @return  boolean  True on successfully backed up.
	 */
	public static function backupConfigFile()
	{
		$basePath = JPATH_SITE . '/administrator/components/com_redshop/helpers/';

		if (self::loadConfigFile())
		{
			if (!copy($basepath . 'redshop.cfg.php', $basepath . 'wizard/redshop.cfg.bkp.php'))
			{
				return false;
			}
		}

		else
		{
			if (!copy($basepath . 'wizard/redshop.cfg.dist.php', $basepath . 'redshop.cfg.php'))
			{
				return false;
			}
		}

		return true;
	}

	/**
	 * Write configuration from data in #__red_configuration to file
	 *
	 * @param   array  $org  Config additional variables to merge
	 *
	 * @return void
	 *
	 * @since  2.0.0.3
	 */
	public static function fetchConfigWriteToFile($org = array())
	{
		$db = JFactory::getDbo();

		// Get aata from table
		$query = $db->getQuery(true)
					->select('*')
					->from($db->qn('#__redshop_configuration'))
					->where($db->qn('id') . ' = 1');
		$db->setQuery($query);
		$configData = $db->loadAssoc();

		// Prepare data from table
		$data = self::prepareData($configData);

		if (count($org) > 0)
		{
			$data = array_merge($org, $data);
		}

		$defineText = self::defineConfigFromData($data);
		self::writeConfigToFile($defineText);
	}

	/**
	 * Load or Update configuration file
	 *
	 * @param   array  $org  Config additional variables to merge
	 *
	 * @return boolean
	 *
	 * @since  2.0.0.3
	 */
	public static function loadOrUpdateConfigFile($org = array())
	{
		if (self::loadConfigFile())
		{
			if (count($org) > 0)
			{
				/* Set last param as false to ensure the last line is empty
				and not containing '?>' at the end of the file*/
				$additionalDefine = self::defineConfigFromData($org, $false);
				self::updateConfigFile($additionalDefine);
			}
		}
		else
		{
			if (self::checkConfigTableExist())
			{
				self::fetchConfigWriteToFile($org);
			}
			else
			{
				self::loadDefaultConfigFile();
			}
		}

		return true;
	}

	/**
	 * Restore configuration file from temp file.
	 *
	 * @return  boolean  True if file is restored.
	 *
	 * @since  2.0.0.3
	 */
	public static function restoreFromTempConfigFile()
	{
		/**
		 * @todo  Need to remove these global variables
		 */
		global $temparray;
		global $defaultarray;

		if (self::loadTempConfigFile() && self::loadDefConfigFile())
		{
			$data       = array_merge($defaultarray, $temparray);
			$config     = self::prepareData($data);
			$defineText = self::defineConfigFromData($config, true);

			self::backupConfigFile();

			if (!self::writeConfigToFile($defineText))
			{
				return false;
			}
		}

		return true;
	}
}
