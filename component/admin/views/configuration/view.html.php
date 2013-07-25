<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');

require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/template.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . '/helpers/extra_field.php';
require_once JPATH_COMPONENT_SITE . '/helpers/helper.php';

class configurationViewconfiguration extends JView
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	public function display($tpl = null)
	{
		$db = JFactory::getDBO();

		$option   = JRequest::getVar('option');
		$document = JFactory::getDocument();
		$layout   = JRequest::getVar('layout');

		if ($layout == "resettemplate")
		{
			$tpl = "resettemplate";
		}

		$document->setTitle(JText::_('COM_REDSHOP_CONFIG'));
		$document->addScript('components/' . $option . '/assets/js/validation.js');
		$document->addScript('components/' . $option . '/assets/js/select_sort.js');
		$document->addStyleSheet('components/' . $option . '/assets/css/search.css');
		$document->addScript('components/' . $option . '/assets/js/search.js');

		$currency_data = JRequest::getVar('currency_data');

		$redhelper   = new redhelper;
		$config      = new Redconfiguration;
		$redTemplate = new Redtemplate;
		$extra_field = new extra_field;
		$userhelper  = new rsUserhelper;
		$lists       = array();

		// Load language file
		$payment_lang_list = $redhelper->getallPlugins("redshop_payment");
		$language          = JFactory::getLanguage();
		$base_dir          = JPATH_ADMINISTRATOR;
		$language_tag      = $language->getTag();

		for ($l = 0; $l < count($payment_lang_list); $l++)
		{
			$extension = 'plg_redshop_payment_' . $payment_lang_list[$l]->element;
			$language->load($extension, $base_dir, $language_tag, true);
		}

		$configpath = JPATH_COMPONENT . '/helpers/redshop.cfg.php';

		if (!is_writable($configpath))
		{
			JError::raiseWarning(21, JText::_('COM_REDSHOP_CONFIGURATION_FILE_IS_NOT_WRITABLE'));
		}

		JToolBarHelper::title(JText::_('COM_REDSHOP_CONFIG'), 'redshop_icon-48-settings');

		if (is_writable($configpath))
		{
			JToolBarHelper::save();
			JToolBarHelper::apply();
		}

		JToolBarHelper::cancel();

		jimport('joomla.html.pane');
		$pane = JPane::getInstance('sliders');
		$this->pane = $pane;

		$uri = JFactory::getURI();
		$this->setLayout('default');

		$model       = $this->getModel('configuration');
		$newsletters = $model->getnewsletters();

		$templatesel                   = array();
		$templatesel[0]                = new stdClass;
		$templatesel[0]->template_id   = 0;
		$templatesel[0]->template_name = JText::_('COM_REDSHOP_SELECT');

		$product_template      = $redTemplate->getTemplate("product");
		$compare_template      = $redTemplate->getTemplate("compare_product");
		$category_template     = $redTemplate->getTemplate("category");
		$categorylist_template = $redTemplate->getTemplate("frontpage_category");
		$manufacturer_template = $redTemplate->getTemplate("manufacturer_products");
		$ajax_detail_template  = $redTemplate->getTemplate("ajax_cart_detail_box");

		$product_template      = array_merge($templatesel, $product_template);
		$compare_template      = array_merge($templatesel, $compare_template);
		$category_template     = array_merge($templatesel, $category_template);
		$categorylist_template = array_merge($templatesel, $categorylist_template);
		$manufacturer_template = array_merge($templatesel, $manufacturer_template);
		$ajax_detail_template  = array_merge($templatesel, $ajax_detail_template);

		$shopper_groups = $userhelper->getShopperGroupList();

		if (count($shopper_groups) <= 0)
		{
			$shopper_groups = array();
		}

		$tmp                              = array();
		$tmp[]                            = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$new_shopper_group_get_value_from = array_merge($tmp, $shopper_groups);
		defined('CALCULATION_PRICE_DECIMAL') ? CALCULATION_PRICE_DECIMAL : define('CALCULATION_PRICE_DECIMAL', '4');
		defined('NEW_SHOPPER_GROUP_GET_VALUE_FROM') ? NEW_SHOPPER_GROUP_GET_VALUE_FROM : define('NEW_SHOPPER_GROUP_GET_VALUE_FROM', '0');
		defined('IMAGE_QUALITY_OUTPUT') ? IMAGE_QUALITY_OUTPUT : define('IMAGE_QUALITY_OUTPUT', '70');
		$lists['new_shopper_group_get_value_from'] = JHTML::_('select.genericlist', $new_shopper_group_get_value_from,
			'new_shopper_group_get_value_from', 'class="inputbox" ', 'value',
			'text', NEW_SHOPPER_GROUP_GET_VALUE_FROM
		);
		defined('MANUFACTURER_TITLE_MAX_CHARS') ? MANUFACTURER_TITLE_MAX_CHARS : define('MANUFACTURER_TITLE_MAX_CHARS', '');
		defined('MANUFACTURER_TITLE_END_SUFFIX') ? MANUFACTURER_TITLE_END_SUFFIX : define('MANUFACTURER_TITLE_END_SUFFIX', '');
		defined('WRITE_REVIEW_IS_LIGHTBOX') ? WRITE_REVIEW_IS_LIGHTBOX : define('WRITE_REVIEW_IS_LIGHTBOX', '0');
		$lists['write_review_is_lightbox'] = JHTML::_('select.booleanlist', 'write_review_is_lightbox', 'class="inputbox" ', WRITE_REVIEW_IS_LIGHTBOX);
		defined('NOOF_SUBATTRIB_THUMB_FOR_SCROLLER') ? NOOF_SUBATTRIB_THUMB_FOR_SCROLLER : define('NOOF_SUBATTRIB_THUMB_FOR_SCROLLER', '3');
		defined('ACCESSORY_PRODUCT_IN_LIGHTBOX') ? ACCESSORY_PRODUCT_IN_LIGHTBOX : define('ACCESSORY_PRODUCT_IN_LIGHTBOX', '0');
		$lists['accessory_product_in_lightbox'] = JHTML::_('select.booleanlist', 'accessory_product_in_lightbox',
			'class="inputbox" ', ACCESSORY_PRODUCT_IN_LIGHTBOX
		);
		$show_price_user_group_list             = explode(',', SHOW_PRICE_USER_GROUP_LIST);

		defined('REQUESTQUOTE_IMAGE') ? REQUESTQUOTE_IMAGE : define('REQUESTQUOTE_IMAGE', 'requestquote.gif');
		defined('REQUESTQUOTE_BACKGROUND') ? REQUESTQUOTE_BACKGROUND : define('REQUESTQUOTE_BACKGROUND', 'requestquotebg.jpg');
		defined('WEBPACK_ENABLE_SMS') ? WEBPACK_ENABLE_SMS : define('WEBPACK_ENABLE_SMS', '1');
		$lists['webpack_enable_sms'] = JHTML::_('select.booleanlist', 'webpack_enable_sms', 'class="inputbox" size="1"', WEBPACK_ENABLE_SMS);
		defined('WEBPACK_ENABLE_EMAIL_TRACK') ? WEBPACK_ENABLE_EMAIL_TRACK : define('WEBPACK_ENABLE_EMAIL_TRACK', '1');
		$lists['webpack_enable_email_track'] = JHTML::_('select.booleanlist', 'webpack_enable_email_track',
			'class="inputbox" size="1"', WEBPACK_ENABLE_EMAIL_TRACK
		);

		defined('DEFAULT_STOCKAMOUNT_THUMB_WIDTH') ? DEFAULT_STOCKAMOUNT_THUMB_WIDTH : define('DEFAULT_STOCKAMOUNT_THUMB_WIDTH', '150');
		defined('DEFAULT_STOCKAMOUNT_THUMB_HEIGHT') ? DEFAULT_STOCKAMOUNT_THUMB_HEIGHT : define('DEFAULT_STOCKAMOUNT_THUMB_HEIGHT', '90');
		defined('AJAX_DETAIL_BOX_WIDTH') ? AJAX_DETAIL_BOX_WIDTH : define('AJAX_DETAIL_BOX_WIDTH', '500');
		defined('AJAX_DETAIL_BOX_HEIGHT') ? AJAX_DETAIL_BOX_HEIGHT : define('AJAX_DETAIL_BOX_HEIGHT', '600');
		defined('AJAX_BOX_WIDTH') ? AJAX_BOX_WIDTH : define('AJAX_BOX_WIDTH', '500');
		defined('AJAX_BOX_HEIGHT') ? AJAX_BOX_HEIGHT : define('AJAX_BOX_HEIGHT', '150');

		$q = "SELECT  country_3_code as value,country_name as text,country_jtext from #__" . TABLE_PREFIX . "_country ORDER BY country_name ASC";
		$db->setQuery($q);
		$countries = $db->loadObjectList();
		$countries = $redhelper->convertLanguageString($countries);

		$q = "SELECT  stockroom_id as value,stockroom_name as text from #__" . TABLE_PREFIX . "_stockroom ORDER BY stockroom_name ASC";
		$db->setQuery($q);
		$stockroom = $db->loadObjectList();

		$country_list = explode(',', COUNTRY_LIST);

		$tmp                                     = array();
		$tmp[]                                   = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$economic_accountgroup                   = $redhelper->getEconomicAccountGroup();
		$economic_accountgroup                   = array_merge($tmp, $economic_accountgroup);
		$lists['default_economic_account_group'] = JHTML::_('select.genericlist', $economic_accountgroup,
			'default_economic_account_group', 'class="inputbox" size="1" ',
			'value', 'text', DEFAULT_ECONOMIC_ACCOUNT_GROUP
		);
		defined('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC') ? ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC : define('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC', '0');
		$tmpoption                                 = array();
		$tmpoption[]                               = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_NO'));
		$tmpoption[]                               = JHTML::_('select.option', 1, JText::_('COM_REDSHOP_ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC_LBL'));
		$tmpoption[]                               = JHTML::_('select.option', 2, JText::_('COM_REDSHOP_ATTRIBUTE_PLUS_PRODUCT_IN_ECONOMIC_LBL'));
		$lists['attribute_as_product_in_economic'] = JHTML::_('select.genericlist', $tmpoption,
			'attribute_as_product_in_economic', 'class="inputbox" size="1" ',
			'value', 'text', ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC
		);

		defined('DETAIL_ERROR_MESSAGE_ON') ? DETAIL_ERROR_MESSAGE_ON : define('DETAIL_ERROR_MESSAGE_ON', '1');
		$lists['detail_error_message_on'] = JHTML::_('select.booleanlist', 'detail_error_message_on', 'class="inputbox" ', DETAIL_ERROR_MESSAGE_ON);

		defined('PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE') ? PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE :
			define('PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE', '');

		$lists['newsletters']   = JHTML::_('select.genericlist', $newsletters, 'default_newsletter',
			'class="inputbox" size="1" ', 'value', 'text', DEFAULT_NEWSLETTER
		);
		$lists['currency_data'] = JHTML::_('select.genericlist', $currency_data, 'currency_code',
			'class="inputbox" size="1" ', 'value', 'text', CURRENCY_CODE
		);

		defined('USE_ENCODING') ? USE_ENCODING : define('USE_ENCODING', '0');
		$lists['use_encoding'] = JHTML::_('select.booleanlist', 'use_encoding', 'class="inputbox" ', USE_ENCODING);
		defined('REQUIRED_VAT_NUMBER') ? REQUIRED_VAT_NUMBER : define('REQUIRED_VAT_NUMBER', '1');
		$lists['required_vat_number'] = JHTML::_('select.booleanlist', 'required_vat_number', 'class="inputbox" ', REQUIRED_VAT_NUMBER);

		$lists['coupons_enable']           = JHTML::_('select.booleanlist', 'coupons_enable', 'class="inputbox" ', COUPONS_ENABLE);
		$lists['vouchers_enable']          = JHTML::_('select.booleanlist', 'vouchers_enable', 'class="inputbox" ', VOUCHERS_ENABLE);
		$lists['manufacturer_mail_enable'] = JHTML::_('select.booleanlist', 'manufacturer_mail_enable', 'class="inputbox" ', MANUFACTURER_MAIL_ENABLE);

		defined('SUPPLIER_MAIL_ENABLE') ? SUPPLIER_MAIL_ENABLE : define('SUPPLIER_MAIL_ENABLE', '0');
		defined('COMPARE_PRODUCT_THUMB_WIDTH') ? COMPARE_PRODUCT_THUMB_WIDTH : define('COMPARE_PRODUCT_THUMB_WIDTH', '70');
		defined('COMPARE_PRODUCT_THUMB_HEIGHT') ? COMPARE_PRODUCT_THUMB_HEIGHT : define('COMPARE_PRODUCT_THUMB_HEIGHT', '70');

		$lists['supplier_mail_enable'] = JHTML::_('select.booleanlist', 'supplier_mail_enable', 'class="inputbox" ', SUPPLIER_MAIL_ENABLE);

		$lists['splitable_payment']         = JHTML::_('select.booleanlist', 'splitable_payment', 'class="inputbox"', SPLITABLE_PAYMENT);
		$lists['show_captcha']              = JHTML::_('select.booleanlist', 'show_captcha', 'class="inputbox"', SHOW_CAPTCHA);
		$lists['create_account_checkbox']   = JHTML::_('select.booleanlist', 'create_account_checkbox', 'class="inputbox"', CREATE_ACCOUNT_CHECKBOX);
		$lists['show_email_verification']   = JHTML::_('select.booleanlist', 'show_email_verification', 'class="inputbox"', SHOW_EMAIL_VERIFICATION);
		$lists['quantity_text_display']     = JHTML::_('select.booleanlist', 'quantity_text_display', 'class="inputbox"', QUANTITY_TEXT_DISPLAY);
		$lists['enable_sef_product_number'] = JHTML::_('select.booleanlist', 'enable_sef_product_number', 'class="inputbox"', ENABLE_SEF_PRODUCT_NUMBER);

		$lists['enable_sef_number_name'] = JHTML::_('select.booleanlist', 'enable_sef_number_name', 'class="inputbox"', ENABLE_SEF_NUMBER_NAME, 'COM_REDSHOP_NAME', 'COM_REDSHOP_ID');
		$lists['category_in_sef_url']    = JHTML::_('select.booleanlist', 'category_in_sef_url', 'class="inputbox"', CATEGORY_IN_SEF_URL);

		$lists['autogenerated_seo']        = JHTML::_('select.booleanlist', 'autogenerated_seo', 'class="inputbox"', AUTOGENERATED_SEO);
		$lists['shop_country']             = JHTML::_('select.genericlist', $countries, 'shop_country', 'class="inputbox" size="1" ', 'value', 'text', SHOP_COUNTRY);
		$lists['default_shipping_country'] = JHTML::_('select.genericlist', $countries, 'default_shipping_country',
			'class="inputbox" size="1" ', 'value', 'text', DEFAULT_SHIPPING_COUNTRY
		);

		// Default_shipping_country
		$lists['show_shipping_in_cart'] = JHTML::_('select.booleanlist', 'show_shipping_in_cart', 'class="inputbox"', SHOW_SHIPPING_IN_CART);
		$lists['discount_mail_send']    = JHTML::_('select.booleanlist', 'discount_mail_send', 'class="inputbox"', DISCOUNT_MAIL_SEND);
		defined('SPECIAL_DISCOUNT_MAIL_SEND') ? SPECIAL_DISCOUNT_MAIL_SEND : define('SPECIAL_DISCOUNT_MAIL_SEND', '1');
		$lists['special_discount_mail_send'] = JHTML::_('select.booleanlist', 'special_discount_mail_send', 'class="inputbox"', SPECIAL_DISCOUNT_MAIL_SEND);
		$lists['economic_integration']       = JHTML::_('select.booleanlist', 'economic_integration', 'class="inputbox"', ECONOMIC_INTEGRATION);
		$discoupon_percent_or_total          = array(JHTML::_('select.option', 0, JText::_('COM_REDSHOP_TOTAL')),
			JHTML::_('select.option', 1, JText::_('COM_REDSHOP_PERCENTAGE'))
		);
		$lists['discoupon_percent_or_total'] = JHTML::_('select.genericlist', $discoupon_percent_or_total,
			'discoupon_percent_or_total', 'class="inputbox" size="1"',
			'value', 'text', DISCOUPON_PERCENT_OR_TOTAL
		);
		$lists['use_container']              = JHTML::_('select.booleanlist', 'use_container', 'class="inputbox" size="1"', USE_CONTAINER);
		$lists['use_stockroom']              = JHTML::_('select.booleanlist', 'use_stockroom', 'class="inputbox" size="1"', USE_STOCKROOM);
		$lists['use_blank_as_infinite']      = JHTML::_('select.booleanlist', 'use_blank_as_infinite', 'class="inputbox" size="1"', USE_BLANK_AS_INFINITE);

		$lists['allow_pre_order']         = JHTML::_('select.booleanlist', 'allow_pre_order', 'class="inputbox" size="1"', ALLOW_PRE_ORDER);
		$lists['onestep_checkout_enable'] = JHTML::_('select.booleanlist', 'onestep_checkout_enable', 'class="inputbox" size="1"', ONESTEP_CHECKOUT_ENABLE);
		$lists['ssl_enable_in_checkout']  = JHTML::_('select.booleanlist', 'ssl_enable_in_checkout', 'class="inputbox" size="1"', SSL_ENABLE_IN_CHECKOUT);
		$lists['twoway_related_product']  = JHTML::_('select.booleanlist', 'twoway_related_product', 'class="inputbox" size="1"', TWOWAY_RELATED_PRODUCT);

		// For child product opttion
		defined('CHILDPRODUCT_DROPDOWN') ? CHILDPRODUCT_DROPDOWN : define('CHILDPRODUCT_DROPDOWN', 'product_name');
		$chilproduct_data               = $redhelper->getChildProductOption();
		$lists['childproduct_dropdown'] = JHTML::_('select.genericlist', $chilproduct_data, 'childproduct_dropdown',
			'class="inputbox" size="1" ', 'value', 'text', CHILDPRODUCT_DROPDOWN
		);
		defined('PURCHASE_PARENT_WITH_CHILD') ? PURCHASE_PARENT_WITH_CHILD : define('PURCHASE_PARENT_WITH_CHILD', '0');
		$lists['purchase_parent_with_child']    = JHTML::_('select.booleanlist', 'purchase_parent_with_child',
			'class="inputbox" size="1"', PURCHASE_PARENT_WITH_CHILD
		);
		$lists['product_hover_image_enable']    = JHTML::_('select.booleanlist', 'product_hover_image_enable',
			'class="inputbox" size="1"', PRODUCT_HOVER_IMAGE_ENABLE
		);
		$lists['additional_hover_image_enable'] = JHTML::_('select.booleanlist', 'additional_hover_image_enable',
			'class="inputbox" size="1"', ADDITIONAL_HOVER_IMAGE_ENABLE
		);
		$lists['ssl_enable_in_backend']         = JHTML::_('select.booleanlist', 'ssl_enable_in_backend', 'class="inputbox" size="1"', SSL_ENABLE_IN_BACKEND);
		$lists['use_tax_exempt']                = JHTML::_('select.booleanlist', 'use_tax_exempt', 'class="inputbox" size="1"', USE_TAX_EXEMPT);
		$lists['tax_exempt_apply_vat']          = JHTML::_('select.booleanlist', 'tax_exempt_apply_vat', 'class="inputbox" size="1"', TAX_EXEMPT_APPLY_VAT);
		$lists['couponinfo']                    = JHTML::_('select.booleanlist', 'couponinfo', 'class="inputbox" size="1"', COUPONINFO);
		$lists['my_tags']                       = JHTML::_('select.booleanlist', 'my_tags', 'class="inputbox" size="1"', MY_TAGS);
		$lists['my_wishlist']                   = JHTML::_('select.booleanlist', 'my_wishlist', 'class="inputbox" size="1"', MY_WISHLIST);
		$lists['compare_products']              = JHTML::_('select.booleanlist', 'compare_products', 'class="inputbox" size="1"', COMARE_PRODUCTS);
		$lists['country_list']                  = JHTML::_('select.genericlist', $countries, 'country_list[]', 'class="inputbox" multiple="multiple"',
			'value', 'text', $country_list
		);
		$lists['product_detail_is_lightbox']    = JHTML::_('select.booleanlist', 'product_detail_is_lightbox',
			'class="inputbox" size="1"', PRODUCT_DETAIL_IS_LIGHTBOX
		);
		$lists['new_customer_selection']        = JHTML::_('select.booleanlist', 'new_customer_selection', 'class="inputbox" size="1"', NEW_CUSTOMER_SELECTION);
		$lists['ajax_cart_box']                 = JHTML::_('select.booleanlist', 'ajax_cart_box', 'class="inputbox" size="1"', AJAX_CART_BOX);
		$lists['is_product_reserve']            = JHTML::_('select.booleanlist', 'is_product_reserve', 'class="inputbox" size="1"', IS_PRODUCT_RESERVE);
		$lists['product_is_lightbox']           = JHTML::_('select.booleanlist', 'product_is_lightbox', 'class="inputbox" size="1"', PRODUCT_IS_LIGHTBOX);
		$lists['product_addimg_is_lightbox']    = JHTML::_('select.booleanlist', 'product_addimg_is_lightbox',
			'class="inputbox" size="1"', PRODUCT_ADDIMG_IS_LIGHTBOX
		);
		$lists['cat_is_lightbox']               = JHTML::_('select.booleanlist', 'cat_is_lightbox', 'class="inputbox" size="1"', CAT_IS_LIGHTBOX);
		$lists['default_stockroom']             = JHTML::_('select.genericlist', $stockroom, 'default_stockroom',
			'class="inputbox" size="1" ', 'value', 'text', DEFAULT_STOCKROOM
		);
		$lists['portalshop']                    = JHTML::_('select.booleanlist', 'portal_shop', 'class="inputbox" size="1"', PORTAL_SHOP);
		$lists['use_image_size_swapping']       = JHTML::_('select.booleanlist', 'use_image_size_swapping', 'class="inputbox" size="1"', USE_IMAGE_SIZE_SWAPPING);
		$lists['apply_vat_on_discount']         = JHTML::_('select.booleanlist', 'apply_vat_on_discount', 'class="inputbox" size="1"', APPLY_VAT_ON_DISCOUNT);
		$lists['auto_scroll_wrapper']           = JHTML::_('select.booleanlist', 'auto_scroll_wrapper', 'class="inputbox" size="1"', AUTO_SCROLL_WRAPPER);
		$lists['allow_multiple_discount']       = JHTML::_('select.booleanlist', 'allow_multiple_discount', 'class="inputbox" size="1"', ALLOW_MULTIPLE_DISCOUNT);
		defined('SHOW_PRODUCT_DETAIL') ? SHOW_PRODUCT_DETAIL : define('SHOW_PRODUCT_DETAIL', '1');
		$lists['show_product_detail'] = JHTML::_('select.booleanlist', 'show_product_detail', 'class="inputbox" size="1"', SHOW_PRODUCT_DETAIL);
		$lists['compare_template_id'] = JHTML::_('select.genericlist', $compare_template, 'compare_template_id',
			'class="inputbox" size="1" ', 'template_id', 'template_name', COMPARE_TEMPLATE_ID
		);

		defined('SHOW_TERMS_AND_CONDITIONS') ? SHOW_TERMS_AND_CONDITIONS : define('SHOW_TERMS_AND_CONDITIONS', '0');
		$lists['show_terms_and_conditions']    = JHTML::_('select.booleanlist', 'show_terms_and_conditions',
			'class="inputbox" size="1"', SHOW_TERMS_AND_CONDITIONS, $yes = JText::_('COM_REDSHOP_SHOW_PER_USER'),
			$no = JText::_('COM_REDSHOP_SHOW_PER_ORDER')
		);
		$lists['rating_review_login_required'] = JHTML::_('select.booleanlist', 'rating_review_login_required',
			'class="inputbox" size="1"', RATING_REVIEW_LOGIN_REQUIRED
		);

		$product_comparison   = array();
		$product_comparison[] = JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT'));
		$product_comparison[] = JHTML::_('select.option', 'category', JText::_('COM_REDSHOP_CATEGORY'));
		$product_comparison[] = JHTML::_('select.option', 'global', JText::_('COM_REDSHOP_GLOBAL'));

		$pagination                       = array(0 => array("value" => 0, "text" => "Joomla"), 1 => array("value" => 1, "text" => "Redshop"));
		$lists['product_comparison_type'] = JHTML::_('select.genericlist', $product_comparison, 'product_comparison_type',
			'class="inputbox" size="1"', 'value', 'text', PRODUCT_COMPARISON_TYPE
		);
		$lists['pagination']              = JHTML::_('select.genericlist', $pagination, 'pagination', 'class="inputbox" size="1" ', 'value', 'text', PAGINATION);
		$lists['newsletter_enable']       = JHTML::_('select.booleanlist', 'newsletter_enable', 'class="inputbox" size="1"', NEWSLETTER_ENABLE);
		$lists['newsletter_confirmation'] = JHTML::_('select.booleanlist', 'newsletter_confirmation', 'class="inputbox" size="1"', NEWSLETTER_CONFIRMATION);

		$lists['watermark_category_image']       = JHTML::_('select.booleanlist', 'watermark_category_image',
			'class="inputbox" size="1"', WATERMARK_CATEGORY_IMAGE
		);
		$lists['watermark_category_thumb_image'] = JHTML::_('select.booleanlist', 'watermark_category_thumb_image',
			'class="inputbox" size="1"', WATERMARK_CATEGORY_THUMB_IMAGE
		);
		$lists['watermark_product_image']        = JHTML::_('select.booleanlist', 'watermark_product_image', 'class="inputbox" size="1"', WATERMARK_PRODUCT_IMAGE);
		$lists['watermark_product_thumb_image']  = JHTML::_('select.booleanlist', 'watermark_product_thumb_image',
			'class="inputbox" size="1"', WATERMARK_PRODUCT_THUMB_IMAGE
		);
		defined('WATERMARK_PRODUCT_ADDITIONAL_IMAGE') ? WATERMARK_PRODUCT_ADDITIONAL_IMAGE : define('WATERMARK_PRODUCT_ADDITIONAL_IMAGE', '0');
		$lists['watermark_product_additional_image']  = JHTML::_('select.booleanlist', 'watermark_product_additional_image',
			'class="inputbox" size="1"', WATERMARK_PRODUCT_ADDITIONAL_IMAGE
		);
		$lists['watermark_cart_thumb_image']          = JHTML::_('select.booleanlist', 'watermark_cart_thumb_image',
			'class="inputbox" size="1"', WATERMARK_CART_THUMB_IMAGE
		);
		$lists['watermark_giftcart_image']            = JHTML::_('select.booleanlist', 'watermark_giftcart_image',
			'class="inputbox" size="1"', WATERMARK_GIFTCART_IMAGE
		);
		$lists['watermark_giftcart_thumb_image']      = JHTML::_('select.booleanlist', 'watermark_giftcart_thumb_image',
			'class="inputbox" size="1"', WATERMARK_GIFTCART_THUMB_IMAGE
		);
		$lists['watermark_manufacturer_thumb_image']  = JHTML::_('select.booleanlist', 'watermark_manufacturer_thumb_image',
			'class="inputbox" size="1"', WATERMARK_MANUFACTURER_THUMB_IMAGE
		);
		$lists['watermark_manufacturer_image']        = JHTML::_('select.booleanlist', 'watermark_manufacturer_image',
			'class="inputbox" size="1"', WATERMARK_MANUFACTURER_IMAGE
		);
		$lists['clickatell_enable']                   = JHTML::_('select.booleanlist', 'clickatell_enable', 'class="inputbox" size="1"', CLICKATELL_ENABLE);
		$lists['quotation_mode']                      = JHTML::_('select.booleanlist', 'default_quotation_mode',
			'onclick="return quote_price(this.value);" class="inputbox" size="1"',
			DEFAULT_QUOTATION_MODE_PRE, $yes = JText::_('COM_REDSHOP_ON'),
			$no = JText::_('COM_REDSHOP_OFF')
		);
		$lists['wanttoshowattributeimage']            = JHTML::_('select.booleanlist', 'wanttoshowattributeimage',
			'class="inputbox" size="1"', WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART
		);
		$lists['show_quotation_price']                = JHTML::_('select.booleanlist', 'show_quotation_price',
			'class="inputbox" size="1"', SHOW_QUOTATION_PRICE
		);
		$lists['display_out_of_stock_attribute_data'] = JHTML::_('select.booleanlist', 'display_out_of_stock_attribute_data',
			'class="inputbox"', DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA
		);
		$orderstatus                                  = $model->getOrderstatus();
		$tmp                                          = array();
		$tmp[]                                        = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$orderstatus                                  = array_merge($tmp, $orderstatus);
		$lists['clickatell_order_status']             = JHTML::_('select.genericlist', $orderstatus, 'clickatell_order_status',
			'class="inputbox" size="1" ', 'value', 'text', CLICKATELL_ORDER_STATUS
		);

		$menuitem           = array();
		$menuitem[0]        = new stdClass;
		$menuitem[0]->value = 0;
		$menuitem[0]->text  = JText::_('COM_REDSHOP_SELECT');
		$q                  = "SELECT m.id,m.title AS name,mt.title FROM #__menu AS m "
			. "LEFT JOIN #__menu_types AS mt ON mt.menutype=m.menutype "
			. "WHERE m.published=1 "
			. "ORDER BY m.menutype,m.ordering";
		$db->setQuery($q);
		$menuitemlist = $db->loadObjectList();

		for ($i = 0; $i < count($menuitemlist); $i++)
		{
			$menuitem[$i + 1]        = new stdClass;
			$menuitem[$i + 1]->value = $menuitemlist[$i]->id;
			$menuitem[$i + 1]->text  = $menuitemlist[$i]->name;
		}

		$lists['url_after_portal_login']  = JHTML::_('select.genericlist', $menuitem, 'portal_login_itemid',
			'class="inputbox" size="1" ', 'value', 'text', PORTAL_LOGIN_ITEMID
		);
		$lists['url_after_portal_logout'] = JHTML::_('select.genericlist', $menuitem, 'portal_logout_itemid',
			'class="inputbox" size="1" ', 'value', 'text', PORTAL_LOGOUT_ITEMID
		);

		$default_vat_group = $model->getVatGroup();
		$tmp               = array();
		$tmp[]             = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$default_vat_group = array_merge($tmp, $default_vat_group);

		$tmp                 = array();
		$tmp[]               = JHTML::_('select.option', '', JText::_('COM_REDSHOP_SELECT'));
		$default_vat_country = array_merge($tmp, $countries);

		$default_customer_register_type          = array();
		$default_customer_register_type[]        = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$default_customer_register_type[]        = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_PRIVATE'));
		$default_customer_register_type[]        = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_COMPANY'));
		$lists['default_customer_register_type'] = JHTML::_('select.genericlist', $default_customer_register_type,
			'default_customer_register_type', 'class="inputbox" ', 'value', 'text', DEFAULT_CUSTOMER_REGISTER_TYPE
		);

		$addtocart_behaviour          = array();
		$addtocart_behaviour[]        = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$addtocart_behaviour[]        = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_DIRECT_TO_CART'));
		$addtocart_behaviour[]        = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_STAY_ON_CURRENT_VIEW'));
		$lists['addtocart_behaviour'] = JHTML::_('select.genericlist', $addtocart_behaviour, 'addtocart_behaviour',
			'class="inputbox" ', 'value', 'text', ADDTOCART_BEHAVIOUR
		);

		$allow_customer_register_type          = array();
		$allow_customer_register_type[]        = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_BOTH'));
		$allow_customer_register_type[]        = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_PRIVATE'));
		$allow_customer_register_type[]        = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_COMPANY'));
		$lists['allow_customer_register_type'] = JHTML::_('select.genericlist', $allow_customer_register_type,
			'allow_customer_register_type', 'class="inputbox" ', 'value', 'text',
			ALLOW_CUSTOMER_REGISTER_TYPE
		);

		// Optional shipping address select box
		$lists['optional_shipping_address'] = JHTML::_('select.booleanlist', 'optional_shipping_address', 'class="inputbox" ', OPTIONAL_SHIPPING_ADDRESS);
		$lists['shipping_method_enable']    = JHTML::_('select.booleanlist', 'shipping_method_enable', 'class="inputbox" ', SHIPPING_METHOD_ENABLE);

		$lists['default_vat_group'] = JHTML::_('select.genericlist', $default_vat_group, 'default_vat_group',
			'class="inputbox" ', 'value', 'text', DEFAULT_VAT_GROUP
		);

		$vat_based_on          = array();
		$vat_based_on[]        = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_WEBSHOP_MODE'));
		$vat_based_on[]        = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_CUSTOMER_MODE'));
		$vat_based_on[]        = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_EU_MODE'));
		$lists['vat_based_on'] = JHTML::_('select.genericlist', $vat_based_on, 'vat_based_on', 'class="inputbox" ', 'value', 'text', VAT_BASED_ON);

		$lists['default_vat_country'] = JHTML::_('select.genericlist', $default_vat_country, 'default_vat_country',
			'class="inputbox" onchange="changeStateList();"', 'value', 'text', DEFAULT_VAT_COUNTRY
		);

		$country_list_name     = 'default_vat_country';
		$state_list_name       = 'default_vat_state';
		$selected_country_code = DEFAULT_VAT_COUNTRY;
		$selected_state_code   = DEFAULT_VAT_STATE;

		if (empty($selected_state_code))
		{
			$selected_state_code = "originalPos";
		}
		else
		{
			$selected_state_code = "'" . $selected_state_code . "'";
		}

		$db->setQuery("SELECT c.country_id, c.country_3_code, s.state_name, s.state_2_code
						FROM #__" . TABLE_PREFIX . "_country c
						LEFT JOIN #__" . TABLE_PREFIX . "_state s
						ON c.country_id=s.country_id OR s.country_id IS NULL
						ORDER BY c.country_id, s.state_name");
		$states = $db->loadObjectList();

		// Build the State lists for each Country
		$script = "<script language=\"javascript\" type=\"text/javascript\">//<![CDATA[\n";
		$script .= "<!--\n";
		$script .= "var originalOrder = '1';\n";
		$script .= "var originalPos = '$selected_country_code';\n";
		$script .= "var states = new Array();	// array in the format [key,value,text]\n";
		$i            = 0;
		$prev_country = '';

		for ($j = 0; $j < count($states); $j++)
		{
			$state          = $states[$j];
			$country_3_code = $state->country_3_code;

			if ($state->state_name)
			{
				if ($prev_country != $country_3_code)
				{
					$script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','',' -= " . JText::_("COM_REDSHOP_SELECT") . " =-' );\n";
				}

				$prev_country = $country_3_code;

				// Array in the format [key,value,text]
				$script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','"
					. $state->state_2_code . "','"
					. addslashes(JText::_($state->state_name))
					. "' );\n";
			}
			else
			{
				$script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','','" . JText::_("COM_REDSHOP_NONE") . "' );\n";
			}
		}

		$script .= "function changeStateList()
					{
						var selected_country = null;
						for (var i=0; i<document.adminForm.default_vat_country.length; i++)
						{
							if (document.adminForm." . $country_list_name . "[i].selected)
							{
								selected_country = document.adminForm." . $country_list_name . "[i].value;
							}
						}
						changeDynaList('" . $state_list_name . "',states,selected_country, originalPos, originalOrder);
				 	}
				 	writeDynaList( 'class=\"inputbox\" name=\"default_vat_state\" size=\"1\" id=\"default_vat_state\"',
				 	states, originalPos, originalPos, $selected_state_code );
					//-->
					//]]></script>";
		$lists['default_vat_state'] = $script;

		$shopper_Group_private = $model->getShopperGroupPrivate();

		$tmp   = array();
		$tmp[] = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$tmp   = array_merge($tmp, $shopper_Group_private);

		$lists['shopper_group_default_private'] = JHTML::_('select.genericlist', $tmp, 'shopper_group_default_private',
			'class="inputbox" ', 'value', 'text', SHOPPER_GROUP_DEFAULT_PRIVATE
		);

		$shopper_Group_company                  = $model->getShopperGroupCompany();
		$tmp                                    = array();
		$tmp[]                                  = JHTML::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$tmp                                    = array_merge($tmp, $shopper_Group_company);
		$lists['shopper_group_default_company'] = JHTML::_('select.genericlist', $tmp, 'shopper_group_default_company',
			'class="inputbox" ', 'value', 'text', SHOPPER_GROUP_DEFAULT_COMPANY
		);

		$tmp   = array();
		$tmp[] = JHTML::_('select.option', 0, JText::_('SELECT'));
		$tmp   = array_merge($tmp, $shopper_Group_private, $shopper_Group_company);
		defined('SHOPPER_GROUP_DEFAULT_UNREGISTERED') ? SHOPPER_GROUP_DEFAULT_UNREGISTERED :
			define('SHOPPER_GROUP_DEFAULT_UNREGISTERED', SHOPPER_GROUP_DEFAULT_PRIVATE);
		$lists['shopper_group_default_unregistered'] = JHTML::_('select.genericlist', $tmp, 'shopper_group_default_unregistered',
			'class="inputbox" ', 'value', 'text', SHOPPER_GROUP_DEFAULT_UNREGISTERED
		);

		$register_methods         = array();
		$register_methods[]       = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_REGISTER_WITH_ACCOUNT_CREATION'));
		$register_methods[]       = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_REGISTER_WITHOUT_ACCOUNT_CREATION'));
		$register_methods[]       = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_REGISTER_ACCOUNT_OPTIONAL'));
		$register_methods[]       = JHTML::_('select.option', '3', JText::_('COM_REDSHOP_REGISTER_ACCOUNT_SILENT'));
		$lists['register_method'] = JHTML::_('select.genericlist', $register_methods, 'register_method',
			'class="inputbox" id="register_method"', 'value', 'text', REGISTER_METHOD
		);

		$lists['product_template']              = JHTML::_('select.genericlist', $product_template, 'default_product_template',
			'class="inputbox" size="1" ', 'template_id', 'template_name', PRODUCT_TEMPLATE
		);
		$lists['ajax_detail_template']          = JHTML::_('select.genericlist', $ajax_detail_template, 'default_ajax_detailbox_template',
			'class="inputbox" size="1" ', 'template_id', 'template_name', DEFAULT_AJAX_DETAILBOX_TEMPLATE
		);
		$lists['category_template']             = JHTML::_('select.genericlist', $category_template, 'default_category_template',
			'class="inputbox" size="1" ', 'template_id', 'template_name', CATEGORY_TEMPLATE
		);
		$lists['default_categorylist_template'] = JHTML::_('select.genericlist', $categorylist_template, 'default_categorylist_template',
			'class="inputbox" size="1" ', 'template_id', 'template_name', DEFAULT_CATEGORYLIST_TEMPLATE
		);
		$lists['manufacturer_template']         = JHTML::_('select.genericlist', $manufacturer_template, 'default_manufacturer_template',
			'class="inputbox" size="1" ', 'template_id', 'template_name', MANUFACTURER_TEMPLATE
		);
		$lists['show_price']                    = JHTML::_('select.booleanlist', 'show_price', 'class="inputbox" size="1"', SHOW_PRICE_PRE);

		$PRE_USE_AS_CATALOG                     = (defined('PRE_USE_AS_CATALOG')) ? PRE_USE_AS_CATALOG : 0;
		$lists['use_as_catalog']                = JHTML::_('select.booleanlist', 'use_as_catalog', 'class="inputbox" size="1"', $PRE_USE_AS_CATALOG);
		$lists['show_tax_exempt_infront']       = JHTML::_('select.booleanlist', 'show_tax_exempt_infront',
			'class="inputbox" size="1"', SHOW_TAX_EXEMPT_INFRONT
		);
		$lists['individual_add_to_cart_enable'] = JHTML::_('select.booleanlist', 'individual_add_to_cart_enable',
			'class="inputbox" size="1"', INDIVIDUAL_ADD_TO_CART_ENABLE,
			JText::_('COM_REDSHOP_INDIVIDUAL_ADD_TO_CART_PER_PROPERTY'),
			JText::_('COM_REDSHOP_ADD_TO_CART_PER_PRODUCT')
		);
		defined('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE') ? ACCESSORY_AS_PRODUCT_IN_CART_ENABLE : define('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE', '0');
		$lists['accessory_as_product_in_cart_enable'] = JHTML::_('select.booleanlist', 'accessory_as_product_in_cart_enable',
			'class="inputbox" size="1"', ACCESSORY_AS_PRODUCT_IN_CART_ENABLE
		);
		$lists['use_product_outofstock_image']        = JHTML::_('select.booleanlist', 'use_product_outofstock_image',
			'class="inputbox" size="1"', USE_PRODUCT_OUTOFSTOCK_IMAGE
		);
		defined('ENABLE_ADDRESS_DETAIL_IN_SHIPPING') ? ENABLE_ADDRESS_DETAIL_IN_SHIPPING : define('ENABLE_ADDRESS_DETAIL_IN_SHIPPING', '0');
		$lists['enable_address_detail_in_shipping'] = JHTML::_('select.booleanlist', 'enable_address_detail_in_shipping',
			'class="inputbox" size="1"', ENABLE_ADDRESS_DETAIL_IN_SHIPPING
		);

		defined('SEND_MAIL_TO_CUSTOMER') ? SEND_MAIL_TO_CUSTOMER : define('SEND_MAIL_TO_CUSTOMER', '1');
		$lists['send_mail_to_customer'] = JHTML::_('select.booleanlist', 'send_mail_to_customer', 'class="inputbox" size="1"', SEND_MAIL_TO_CUSTOMER);

		$bookinvoice                     = array();
		$bookinvoice[]                   = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_DIRECTLY_BOOK'));
		$bookinvoice[]                   = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_MANUALLY_BOOK'));
		$bookinvoice[]                   = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_BOOK_ON_ORDER_STATUS'));
		$lists['economic_invoice_draft'] = JHTML::_('select.genericlist', $bookinvoice, 'economic_invoice_draft',
			'class="inputbox" onchange="javascript:changeBookInvoice(this.value);" ', 'value', 'text',
			ECONOMIC_INVOICE_DRAFT
		);

		defined('ECONOMIC_BOOK_INVOICE_NUMBER') ? ECONOMIC_BOOK_INVOICE_NUMBER : define('ECONOMIC_BOOK_INVOICE_NUMBER', '0');
		$lists['economic_book_invoice_number'] = JHTML::_('select.booleanlist', 'economic_book_invoice_number',
			'class="inputbox" size="1"', ECONOMIC_BOOK_INVOICE_NUMBER,
			JText::_('COM_REDSHOP_SEQUENTIALLY_IN_ECONOMIC_NO_MATCH_UP_WITH_ORDER_NUMBER'),
			JText::_('COM_REDSHOP_SAME_AS_ORDER_NUMBER')
		);

		// NEXT-PREVIOUS LINK
		$link_type                   = array();
		$link_type[]                 = JHTML::_('select.option', '0', JText::_('COM_REDSHOP_DEFAULT_LINK'));
		$link_type[]                 = JHTML::_('select.option', '1', JText::_('COM_REDSHOP_CUSTOM_LINK'));
		$link_type[]                 = JHTML::_('select.option', '2', JText::_('COM_REDSHOP_IMAGE_LINK'));
		$lists['next_previous_link'] = JHTML::_('select.genericlist', $link_type, 'next_previous_link',
			'class="inputbox" ', 'value', 'text', DEFAULT_LINK_FIND
		);

		$order_data                                            = $redhelper->getOrderByList();
		$lists['default_product_ordering_method']              = JHTML::_('select.genericlist', $order_data, 'default_product_ordering_method',
			'class="inputbox" size="1" ', 'value', 'text', DEFAULT_PRODUCT_ORDERING_METHOD
		);
		$lists['default_manufacturer_product_ordering_method'] = JHTML::_('select.genericlist', $order_data,
			'default_manufacturer_product_ordering_method', 'class="inputbox" size="1" ', 'value',
			'text', DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD
		);

		$order_data                                 = $redhelper->getRelatedOrderByList();
		$lists['default_related_ordering_method']   = JHTML::_('select.genericlist', $order_data, 'default_related_ordering_method',
			'class="inputbox" size="1" ', 'value', 'text', DEFAULT_RELATED_ORDERING_METHOD
		);
		$order_data                                 = $redhelper->getAccessoryOrderByList();
		$lists['default_accessory_ordering_method'] = JHTML::_('select.genericlist', $order_data, 'default_accessory_ordering_method',
			'class="inputbox" size="1" ', 'value', 'text', DEFAULT_ACCESSORY_ORDERING_METHOD
		);

		$shipping_after          = defined('SHIPPING_AFTER') ? SHIPPING_AFTER : 'total';
		$lists['shipping_after'] = $extra_field->rs_booleanlist('shipping_after', 'class="inputbox"', $shipping_after,
			$yes = JText::_('COM_REDSHOP_TOTAL'), $no = JText::_('COM_REDSHOP_SUBTOTAL_LBL'), '', 'total', 'subtotal'
		);

		$payment_calculation             = defined('PAYMENT_CALCULATION_ON') ? PAYMENT_CALCULATION_ON : 'total';
		$lists['payment_calculation_on'] = $extra_field->rs_booleanlist('payment_calculation_on',
			'class="inputbox"', $payment_calculation, $yes = JText::_('COM_REDSHOP_TOTAL'),
			$no = JText::_('COM_REDSHOP_SUBTOTAL_LBL'), '', 'total', 'subtotal'
		);

		$calculate_vat_on          = defined('CALCULATE_VAT_ON') ? CALCULATE_VAT_ON : 'BT';
		$lists['calculate_vat_on'] = $extra_field->rs_booleanlist('calculate_vat_on', 'class="inputbox"',
			$calculate_vat_on, $yes = JText::_('COM_REDSHOP_BILLING_ADDRESS_LBL'),
			$no = JText::_('COM_REDSHOP_SHIPPING_ADDRESS_LBL'), '', 'BT', 'ST'
		);

		$order_data           = array();
		$order_data[0]        = new stdClass;
		$order_data[0]->value = "c.category_name ASC";
		$order_data[0]->text  = JText::_('COM_REDSHOP_CATEGORY_NAME');

		$order_data[1]        = new stdClass;
		$order_data[1]->value = "c.category_id DESC";
		$order_data[1]->text  = JText::_('COM_REDSHOP_NEWEST');

		$order_data[2]        = new stdClass;
		$order_data[2]->value = "c.ordering ASC";
		$order_data[2]->text  = JText::_('COM_REDSHOP_ORDERING');

		$lists['default_category_ordering_method'] = JHTML::_('select.genericlist', $order_data, 'default_category_ordering_method',
			'class="inputbox" size="1" ', 'value', 'text', DEFAULT_CATEGORY_ORDERING_METHOD
		);

		$order_data                                    = $redhelper->getManufacturerOrderByList();
		$lists['default_manufacturer_ordering_method'] = JHTML::_('select.genericlist', $order_data, 'default_manufacturer_ordering_method',
			'class="inputbox" size="1" ', 'value', 'text', DEFAULT_MANUFACTURER_ORDERING_METHOD
		);

		$symbol_position           = array();
		$symbol_position[0]        = new stdClass;
		$symbol_position[0]->value = " ";
		$symbol_position[0]->text  = JText::_('COM_REDSHOP_SELECT');

		$symbol_position[1]        = new stdClass;
		$symbol_position[1]->value = "front";
		$symbol_position[1]->text  = JText::_('COM_REDSHOP_FRONT');

		$symbol_position[2]        = new stdClass;
		$symbol_position[2]->value = "behind";
		$symbol_position[2]->text  = JText::_('COM_REDSHOP_BEHIND');

		$symbol_position[3]        = new stdClass;
		$symbol_position[3]->value = "none";
		$symbol_position[3]->text  = JText::_('COM_REDSHOP_NONE');

		$lists['currency_symbol_position'] = JHTML::_('select.genericlist', $symbol_position, 'currency_symbol_position',
			'class="inputbox" ', 'value', 'text', CURRENCY_SYMBOL_POSITION
		);

		$default_dateformat          = $config->getDateFormat();
		$lists['default_dateformat'] = JHTML::_('select.genericlist', $default_dateformat, 'default_dateformat',
			'class="inputbox" ', 'value', 'text', DEFAULT_DATEFORMAT
		);

		$lists['discount_enable']         = JHTML::_('select.booleanlist', 'discount_enable', 'class="inputbox" ', DISCOUNT_ENABLE);
		$lists['invoice_mail_enable']     = JHTML::_('select.booleanlist', 'invoice_mail_enable', 'class="inputbox"', INVOICE_MAIL_ENABLE);
		$lists['enable_backendaccess']    = JHTML::_('select.booleanlist', 'enable_backendaccess', 'class="inputbox"', ENABLE_BACKENDACCESS);
		$lists['wishlist_login_required'] = JHTML::_('select.booleanlist', 'wishlist_login_required', 'class="inputbox"', WISHLIST_LOGIN_REQUIRED);

		$invoice_mail_send_option           = array();
		$invoice_mail_send_option[0]        = new stdClass;
		$invoice_mail_send_option[0]->value = 0;
		$invoice_mail_send_option[0]->text  = JText::_('COM_REDSHOP_SELECT');

		$invoice_mail_send_option[1]        = new stdClass;
		$invoice_mail_send_option[1]->value = 1;
		$invoice_mail_send_option[1]->text  = JText::_('COM_REDSHOP_ADMINISTRATOR');

		$invoice_mail_send_option[2]        = new stdClass;
		$invoice_mail_send_option[2]->value = 2;
		$invoice_mail_send_option[2]->text  = JText::_('COM_REDSHOP_CUSTOMER');

		$invoice_mail_send_option[3]        = new stdClass;
		$invoice_mail_send_option[3]->value = 3;
		$invoice_mail_send_option[3]->text  = JText::_('COM_REDSHOP_BOTH');

		$lists['invoice_mail_send_option'] = JHTML::_('select.genericlist', $invoice_mail_send_option, 'invoice_mail_send_option',
			'class="inputbox" ', 'value', 'text', INVOICE_MAIL_SEND_OPTION
		);

		$order_mail_after           = array();
		$order_mail_after[0]        = new stdClass;
		$order_mail_after[0]->value = 0;
		$order_mail_after[0]->text  = JText::_('COM_REDSHOP_ORDER_MAIL_BEFORE_PAYMENT');

		$order_mail_after[1]        = new stdClass;
		$order_mail_after[1]->value = 1;
		$order_mail_after[1]->text  = JText::_('COM_REDSHOP_ORDER_MAIL_AFTER_PAYMENT');

		$lists['order_mail_after'] = JHTML::_('select.genericlist', $order_mail_after, 'order_mail_after',
			'class="inputbox" ', 'value', 'text', ORDER_MAIL_AFTER
		);

		$discount_type           = array();
		$discount_type[0]        = new stdClass;
		$discount_type[0]->value = 0;
		$discount_type[0]->text  = JText::_('COM_REDSHOP_SELECT');

		$discount_type[1]        = new stdClass;
		$discount_type[1]->value = 1;
		$discount_type[1]->text  = JText::_('COM_REDSHOP_DISCOUNT_OR_VOUCHER_OR_COUPON');

		$discount_type[2]        = new stdClass;
		$discount_type[2]->value = 2;
		$discount_type[2]->text  = JText::_('COM_REDSHOP_DISCOUNT_VOUCHER_OR_COUPON');

		$discount_type[3]        = new stdClass;
		$discount_type[3]->value = 3;
		$discount_type[3]->text  = JText::_('COM_REDSHOP_DISCOUNT_VOUCHER_COUPON');

		$discount_type[4]        = new stdClass;
		$discount_type[4]->value = 4;
		$discount_type[4]->text  = JText::_('COM_REDSHOP_DISCOUNT_VOUCHER_COUPON_MULTIPLE');

		$lists['discount_type'] = JHTML::_('select.genericlist', $discount_type, 'discount_type',
			'class="inputbox" ', 'value', 'text', DISCOUNT_TYPE
		);

		/*
		 * Measurement select boxes
		 */
		$option           = array();
		$option[0]        = new stdClass;
		$option[0]->value = 0;
		$option[0]->text  = JText::_('COM_REDSHOP_SELECT');

		$option[1]        = new stdClass;
		$option[1]->value = 'mm';
		$option[1]->text  = JText::_('COM_REDSHOP_MILLIMETER');

		$option[2]        = new stdClass;
		$option[2]->value = 'cm';
		$option[2]->text  = JText::_('COM_REDSHOP_CENTIMETERS');

		$option[3]        = new stdClass;
		$option[3]->value = 'inch';
		$option[3]->text  = JText::_('COM_REDSHOP_INCHES');

		$option[4]        = new stdClass;
		$option[4]->value = 'feet';
		$option[4]->text  = JText::_('COM_REDSHOP_FEET');

		$option[5]        = new stdClass;
		$option[5]->value = 'm';
		$option[5]->text  = JText::_('COM_REDSHOP_METER');

		$lists['default_volume_unit'] = JHTML::_('select.genericlist', $option, 'default_volume_unit',
			'class="inputbox" ', 'value', 'text', DEFAULT_VOLUME_UNIT
		);
		unset($option);

		$option           = array();
		$option[0]        = new stdClass;
		$option[0]->value = 0;
		$option[0]->text  = JText::_('COM_REDSHOP_SELECT');

		$option[1]        = new stdClass;
		$option[1]->value = 'gram';
		$option[1]->text  = JText::_('COM_REDSHOP_GRAM');

		$option[2]        = new stdClass;
		$option[2]->value = 'pounds';
		$option[2]->text  = JText::_('COM_REDSHOP_POUNDS');

		$option[3]        = new stdClass;
		$option[3]->value = 'kg';
		$option[3]->text  = JText::_('COM_REDSHOP_KG');

		$lists['default_weight_unit'] = JHTML::_('select.genericlist', $option, 'default_weight_unit',
			'class="inputbox" ', 'value', 'text', DEFAULT_WEIGHT_UNIT
		);
		unset($option);

		$lists['postdk_integration']         = JHTML::_('select.booleanlist', 'postdk_integration', 'class="inputbox" size="1"', POSTDK_INTEGRATION);
		$lists['display_new_orders']         = JHTML::_('select.booleanlist', 'display_new_orders', 'class="inputbox" size="1"', DISPLAY_NEW_ORDERS);
		$lists['display_new_customers']      = JHTML::_('select.booleanlist', 'display_new_customers', 'class="inputbox" size="1"', DISPLAY_NEW_CUSTOMERS);
		$lists['display_statistic']          = JHTML::_('select.booleanlist', 'display_statistic', 'class="inputbox" size="1"', DISPLAY_STATISTIC);
		$lists['expand_all']                 = JHTML::_('select.booleanlist', 'expand_all', 'class="inputbox" size="1"', EXPAND_ALL);
		$lists['send_catalog_reminder_mail'] = JHTML::_('select.booleanlist', 'send_catalog_reminder_mail', 'class="inputbox" size="1"', SEND_CATALOG_REMINDER_MAIL);

		$current_version      = $model->getcurrentversion();
		$getinstalledmodule   = $model->getinstalledmodule();
		$getinstalledplugins  = $model->getinstalledplugins();
		$getinstalledshipping = $model->getinstalledplugins('redshop_shipping');

		$db_version  = $db->getVersion();
		$php_version = phpversion();
		$server      = $this->get_server_software();
		$gd_check    = extension_loaded('gd');
		$mb_check    = extension_loaded('mbstring');

		$this->server               = $server;
		$this->php_version          = $php_version;
		$this->db_version           = $db_version;
		$this->gd_check             = $gd_check;
		$this->mb_check             = $mb_check;
		$this->getinstalledmodule   = $getinstalledmodule;
		$this->getinstalledplugins  = $getinstalledplugins;
		$this->getinstalledshipping = $getinstalledshipping;
		$this->current_version      = $current_version;
		$this->lists                = $lists;
		$this->request_url          = $uri->toString();

		parent::display($tpl);
	}

	public function get_server_software()
	{
		if (isset($_SERVER['SERVER_SOFTWARE']))
		{
			return $_SERVER['SERVER_SOFTWARE'];
		}
		elseif ($sf = getenv('SERVER_SOFTWARE'))
		{
			return $sf;
		}
		else
		{
			return JText::_('COM_REDSHOP_N_A');
		}
	}
}
