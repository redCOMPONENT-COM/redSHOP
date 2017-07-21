<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class RedshopViewConfiguration extends RedshopViewAdmin
{
	/**
	 * The request url.
	 *
	 * @var  string
	 */
	public $request_url;

	/**
	 * Do we have to display a sidebar ?
	 *
	 * @var  boolean
	 */
	protected $displaySidebar = false;

	public function display($tpl = null)
	{
		$db = JFactory::getDbo();

		$document = JFactory::getDocument();
		$layout   = JRequest::getVar('layout');

		if ($layout == "resettemplate")
		{
			$tpl = "resettemplate";
		}

		$document->setTitle(JText::_('COM_REDSHOP_CONFIG'));
		$document->addScript('components/com_redshop/assets/js/validation.js');

		$model         = $this->getModel('configuration');
		$currency_data = $model->getCurrencies();

		$this->config = $model->getData();

		$redhelper   = redhelper::getInstance();
		$config      = Redconfiguration::getInstance();
		$extra_field = extra_field::getInstance();
		$userhelper  = rsUserHelper::getInstance();
		$lists       = array();

		// Load payment languages
		RedshopHelperPayment::loadLanguages(true);
		RedshopHelperShipping::loadLanguages(true);
		RedshopHelperModule::loadLanguages();

		JToolbarHelper::title(JText::_('COM_REDSHOP_CONFIG'), 'equalizer redshop_icon-48-settings');
		JToolbarHelper::save();
		JToolbarHelper::apply();
		JToolbarHelper::cancel();

		$this->setLayout('default');

		$newsletters = $model->getnewsletters();

		$templatesel                   = array();
		$templatesel[0]                = new stdClass;
		$templatesel[0]->template_id   = 0;
		$templatesel[0]->template_name = JText::_('COM_REDSHOP_SELECT');

		$product_template      = RedshopHelperTemplate::getTemplate("product");
		$compare_template      = RedshopHelperTemplate::getTemplate("compare_product");
		$category_template     = RedshopHelperTemplate::getTemplate("category");
		$categorylist_template = RedshopHelperTemplate::getTemplate("frontpage_category");
		$manufacturer_template = RedshopHelperTemplate::getTemplate("manufacturer_products");
		$ajax_detail_template  = RedshopHelperTemplate::getTemplate("ajax_cart_detail_box");

		$product_template      = array_merge($templatesel, $product_template);
		$compare_template      = array_merge($templatesel, $compare_template);
		$category_template     = array_merge($templatesel, $category_template);
		$categorylist_template = array_merge($templatesel, $categorylist_template);
		$manufacturer_template = array_merge($templatesel, $manufacturer_template);
		$ajax_detail_template  = array_merge($templatesel, $ajax_detail_template);

		$shopper_groups = Redshop\Helper\ShopperGroup::generateList();

		if (count($shopper_groups) <= 0)
		{
			$shopper_groups = array();
		}

		$tmp                              = array();
		$tmp[]                            = JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$new_shopper_group_get_value_from = array_merge($tmp, $shopper_groups);

		$lists['new_shopper_group_get_value_from'] = JHtml::_('select.genericlist', $new_shopper_group_get_value_from,
			'new_shopper_group_get_value_from', 'class="form-control" ', 'value',
			'text', $this->config->get('NEW_SHOPPER_GROUP_GET_VALUE_FROM')
		);
		$lists['accessory_product_in_lightbox']    = JHtml::_('redshopselect.booleanlist', 'accessory_product_in_lightbox',
			'class="form-control" ', $this->config->get('ACCESSORY_PRODUCT_IN_LIGHTBOX')
		);

		$lists['webpack_enable_sms']         = JHtml::_('redshopselect.booleanlist', 'webpack_enable_sms', 'class="form-control" size="1"', $this->config->get('WEBPACK_ENABLE_SMS'));
		$lists['webpack_enable_email_track'] = JHtml::_('redshopselect.booleanlist', 'webpack_enable_email_track',
			'class="form-control" size="1"', $this->config->get('WEBPACK_ENABLE_EMAIL_TRACK')
		);

		$q = "SELECT  country_3_code as value,country_name as text,country_jtext from #__redshop_country ORDER BY country_name ASC";
		$db->setQuery($q);
		$countries = $db->loadObjectList();
		$countries = RedshopHelperUtility::convertLanguageString($countries);

		$q = "SELECT  stockroom_id as value,stockroom_name as text from #__redshop_stockroom ORDER BY stockroom_name ASC";
		$db->setQuery($q);
		$stockroom = $db->loadObjectList();

		$country_list = explode(',', $this->config->get('COUNTRY_LIST'));

		$tmp                                       = array();
		$tmp[]                                     = JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$economic_accountgroup                     = RedshopHelperUtility::getEconomicAccountGroup();
		$economic_accountgroup                     = array_merge($tmp, $economic_accountgroup);
		$lists['default_economic_account_group']   = JHtml::_('select.genericlist', $economic_accountgroup,
			'default_economic_account_group', 'class="form-control" size="1" ',
			'value', 'text', $this->config->get('DEFAULT_ECONOMIC_ACCOUNT_GROUP')
		);
		$tmpoption                                 = array();
		$tmpoption[]                               = JHtml::_('select.option', 0, JText::_('COM_REDSHOP_NO'));
		$tmpoption[]                               = JHtml::_('select.option', 1, JText::_('COM_REDSHOP_ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC_LBL'));
		$tmpoption[]                               = JHtml::_('select.option', 2, JText::_('COM_REDSHOP_ATTRIBUTE_PLUS_PRODUCT_IN_ECONOMIC_LBL'));
		$lists['attribute_as_product_in_economic'] = JHtml::_('select.genericlist', $tmpoption,
			'attribute_as_product_in_economic', 'class="form-control" size="1" ',
			'value', 'text', $this->config->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC')
		);

		$lists['detail_error_message_on'] = JHtml::_('redshopselect.booleanlist', 'detail_error_message_on', 'class="form-control" ', $this->config->get('DETAIL_ERROR_MESSAGE_ON'));

		$lists['newsletters']   = JHtml::_('select.genericlist', $newsletters, 'default_newsletter',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('DEFAULT_NEWSLETTER')
		);
		$lists['currency_data'] = JHtml::_('select.genericlist', $currency_data, 'currency_code',
			'class="form-control" size="1" onchange="changeRedshopCurrencyList(this);"', 'value', 'text', $this->config->get('CURRENCY_CODE')
		);

		$lists['use_encoding']        = JHtml::_('redshopselect.booleanlist', 'use_encoding', 'class="form-control" ', $this->config->get('USE_ENCODING'));
		$lists['required_vat_number'] = JHtml::_('redshopselect.booleanlist', 'required_vat_number', 'class="form-control" ', $this->config->get('REQUIRED_VAT_NUMBER'));

		$lists['coupons_enable']           = JHtml::_('redshopselect.booleanlist', 'coupons_enable', 'class="form-control" ', $this->config->get('COUPONS_ENABLE'));
		$lists['vouchers_enable']          = JHtml::_('redshopselect.booleanlist', 'vouchers_enable', 'class="form-control" ', $this->config->get('VOUCHERS_ENABLE'));
		$lists['manufacturer_mail_enable'] = JHtml::_('redshopselect.booleanlist', 'manufacturer_mail_enable', 'class="form-control" ', $this->config->get('MANUFACTURER_MAIL_ENABLE'));

		$lists['apply_voucher_coupon_already_discount'] = JHtml::_('redshopselect.booleanlist', 'apply_voucher_coupon_already_discount', 'class="form-control" ', $this->config->get('APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT'));

		$lists['supplier_mail_enable'] = JHtml::_('redshopselect.booleanlist', 'supplier_mail_enable', 'class="form-control" ', $this->config->get('SUPPLIER_MAIL_ENABLE'));

		$lists['create_account_checkbox']   = JHtml::_('redshopselect.booleanlist', 'create_account_checkbox', 'class="form-control"', $this->config->get('CREATE_ACCOUNT_CHECKBOX'));
		$lists['show_email_verification']   = JHtml::_('redshopselect.booleanlist', 'show_email_verification', 'class="form-control"', $this->config->get('SHOW_EMAIL_VERIFICATION'));
		$lists['quantity_text_display']     = JHtml::_('redshopselect.booleanlist', 'quantity_text_display', 'class="form-control"', $this->config->get('QUANTITY_TEXT_DISPLAY'));
		$lists['enable_sef_product_number'] = JHtml::_('redshopselect.booleanlist', 'enable_sef_product_number', 'class="form-control"', $this->config->get('ENABLE_SEF_PRODUCT_NUMBER'));

		$lists['enable_sef_number_name'] = JHtml::_('redshopselect.booleanlist', 'enable_sef_number_name', 'class="form-control"', $this->config->get('ENABLE_SEF_NUMBER_NAME'), 'COM_REDSHOP_NAME', 'COM_REDSHOP_ID');
		$lists['category_in_sef_url']    = JHtml::_('redshopselect.booleanlist', 'category_in_sef_url', 'class="form-control"', $this->config->get('CATEGORY_IN_SEF_URL'));

		$lists['autogenerated_seo']        = JHtml::_('redshopselect.booleanlist', 'autogenerated_seo', 'class="form-control"', $this->config->get('AUTOGENERATED_SEO'));
		$lists['shop_country']             = JHtml::_('select.genericlist', $countries, 'shop_country', 'class="form-control" size="1" ', 'value', 'text', $this->config->get('SHOP_COUNTRY'));
		$lists['default_shipping_country'] = JHtml::_('select.genericlist', $countries, 'default_shipping_country',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('DEFAULT_SHIPPING_COUNTRY')
		);

		// Default_shipping_country
		$lists['show_shipping_in_cart']      = JHtml::_('redshopselect.booleanlist', 'show_shipping_in_cart', 'class="form-control"', $this->config->get('SHOW_SHIPPING_IN_CART'));
		$lists['discount_mail_send']         = JHtml::_('redshopselect.booleanlist', 'discount_mail_send', 'class="form-control"', $this->config->get('DISCOUNT_MAIL_SEND'));
		$lists['special_discount_mail_send'] = JHtml::_('redshopselect.booleanlist', 'special_discount_mail_send', 'class="form-control"', $this->config->get('SPECIAL_DISCOUNT_MAIL_SEND'));
		$lists['economic_integration']       = JHtml::_('redshopselect.booleanlist', 'economic_integration', 'class="form-control"', $this->config->get('ECONOMIC_INTEGRATION'));
		$discoupon_percent_or_total          = array(JHtml::_('select.option', 0, JText::_('COM_REDSHOP_TOTAL')),
			JHtml::_('select.option', 1, JText::_('COM_REDSHOP_PERCENTAGE'))
		);
		$lists['discoupon_percent_or_total'] = JHtml::_('select.genericlist', $discoupon_percent_or_total,
			'discoupon_percent_or_total', 'class="form-control" size="1"',
			'value', 'text', $this->config->get('DISCOUPON_PERCENT_OR_TOTAL')
		);
		$lists['use_stockroom']              = JHtml::_('redshopselect.booleanlist', 'use_stockroom', 'class="form-control" size="1"', $this->config->get('USE_STOCKROOM'));
		$lists['use_blank_as_infinite']      = JHtml::_('redshopselect.booleanlist', 'use_blank_as_infinite', 'class="form-control" size="1"', $this->config->get('USE_BLANK_AS_INFINITE'));

		$lists['allow_pre_order']         = JHtml::_('redshopselect.booleanlist', 'allow_pre_order', 'class="form-control" size="1"', $this->config->get('ALLOW_PRE_ORDER'));
		$lists['onestep_checkout_enable'] = JHtml::_('redshopselect.booleanlist', 'onestep_checkout_enable', 'class="form-control" size="1"', $this->config->get('ONESTEP_CHECKOUT_ENABLE'));
		$lists['ssl_enable_in_checkout']  = JHtml::_('redshopselect.booleanlist', 'ssl_enable_in_checkout', 'class="form-control" size="1"', $this->config->get('SSL_ENABLE_IN_CHECKOUT'));
		$lists['twoway_related_product']  = JHtml::_('redshopselect.booleanlist', 'twoway_related_product', 'class="form-control" size="1"', $this->config->get('TWOWAY_RELATED_PRODUCT'));

		// For child product opttion
		$chilproduct_data                       = RedshopHelperUtility::getChildProductOption();
		$lists['childproduct_dropdown']         = JHtml::_('select.genericlist', $chilproduct_data, 'childproduct_dropdown',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('CHILDPRODUCT_DROPDOWN')
		);
		$lists['purchase_parent_with_child']    = JHtml::_('redshopselect.booleanlist', 'purchase_parent_with_child',
			'class="form-control" size="1"', $this->config->get('PURCHASE_PARENT_WITH_CHILD')
		);
		$lists['product_hover_image_enable']    = JHtml::_('redshopselect.booleanlist', 'product_hover_image_enable',
			'class="form-control" size="1"', $this->config->get('PRODUCT_HOVER_IMAGE_ENABLE')
		);
		$lists['additional_hover_image_enable'] = JHtml::_('redshopselect.booleanlist', 'additional_hover_image_enable',
			'class="form-control" size="1"', $this->config->get('ADDITIONAL_HOVER_IMAGE_ENABLE')
		);
		$lists['ssl_enable_in_backend']         = JHtml::_('redshopselect.booleanlist', 'ssl_enable_in_backend', 'class="form-control" size="1"', $this->config->get('SSL_ENABLE_IN_BACKEND'));
		$lists['use_tax_exempt']                = JHtml::_('redshopselect.booleanlist', 'use_tax_exempt', 'class="form-control" size="1"', $this->config->get('USE_TAX_EXEMPT'));
		$lists['tax_exempt_apply_vat']          = JHtml::_('redshopselect.booleanlist', 'tax_exempt_apply_vat', 'class="form-control" size="1"', $this->config->get('TAX_EXEMPT_APPLY_VAT'));
		$lists['couponinfo']                    = JHtml::_('redshopselect.booleanlist', 'couponinfo', 'class="form-control" size="1"', $this->config->get('COUPONINFO'));
		$lists['my_tags']                       = JHtml::_('redshopselect.booleanlist', 'my_tags', 'class="form-control" size="1"', $this->config->get('MY_TAGS'));
		$lists['my_wishlist']                   = JHtml::_('redshopselect.booleanlist', 'my_wishlist', 'class="form-control" size="1"', $this->config->get('MY_WISHLIST'));
		$lists['compare_products']              = JHtml::_('redshopselect.booleanlist', 'compare_products', 'class="form-control" size="1"', $this->config->get('COMPARE_PRODUCTS'));
		$lists['country_list']                  = JHtml::_('select.genericlist', $countries, 'country_list[]', 'class="form-control" multiple="multiple" size="5"',
			'value', 'text', $country_list
		);
		$lists['product_detail_is_lightbox']    = JHtml::_('redshopselect.booleanlist', 'product_detail_is_lightbox',
			'class="form-control" size="1"', $this->config->get('PRODUCT_DETAIL_IS_LIGHTBOX')
		);
		$lists['new_customer_selection']        = JHtml::_('redshopselect.booleanlist', 'new_customer_selection', 'class="form-control" size="1"', $this->config->get('NEW_CUSTOMER_SELECTION'));
		$lists['ajax_cart_box']                 = JHtml::_('redshopselect.booleanlist', 'ajax_cart_box', 'class="form-control" size="1"', $this->config->get('AJAX_CART_BOX'));
		$lists['is_product_reserve']            = JHtml::_('redshopselect.booleanlist', 'is_product_reserve', 'class="form-control" size="1"', $this->config->get('IS_PRODUCT_RESERVE'));
		$lists['product_is_lightbox']           = JHtml::_('redshopselect.booleanlist', 'product_is_lightbox', 'class="form-control" size="1"', $this->config->get('PRODUCT_IS_LIGHTBOX'));
		$lists['product_addimg_is_lightbox']    = JHtml::_('redshopselect.booleanlist', 'product_addimg_is_lightbox',
			'class="form-control" size="1"', $this->config->get('PRODUCT_ADDIMG_IS_LIGHTBOX')
		);
		$lists['cat_is_lightbox']               = JHtml::_('redshopselect.booleanlist', 'cat_is_lightbox', 'class="form-control" size="1"', $this->config->get('CAT_IS_LIGHTBOX'));
		$lists['default_stockroom']             = JHtml::_('select.genericlist', $stockroom, 'default_stockroom',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('DEFAULT_STOCKROOM')
		);
		$lists['portalshop']                    = JHtml::_('redshopselect.booleanlist', 'portal_shop', 'class="form-control" size="1"', $this->config->get('PORTAL_SHOP'));

		$imageSizeSwapping                = array();
		$imageSizeSwapping[]              = JHtml::_('select.option', 0, JText::_('COM_REDSHOP_CONFIG_NO_PROPORTIONAL_RESIZED'));
		$imageSizeSwapping[]              = JHtml::_('select.option', 1, JText::_('COM_REDSHOP_CONFIG_PROPORTIONAL_RESIZED'));
		$imageSizeSwapping[]              = JHtml::_('select.option', 2, JText::_('COM_REDSHOP_CONFIG_PROPORTIONAL_RESIZED_AND_CROP'));
		$lists['use_image_size_swapping'] = JHtml::_('select.genericlist', $imageSizeSwapping,
			'use_image_size_swapping', 'class="form-control" size="1" ',
			'value', 'text', $this->config->get('USE_IMAGE_SIZE_SWAPPING')
		);

		$lists['apply_vat_on_discount']   = JHtml::_('redshopselect.booleanlist', 'apply_vat_on_discount', 'class="form-control" size="1"', $this->config->get('APPLY_VAT_ON_DISCOUNT'), $yes = JText::_('COM_REDSHOP_BEFORE_DISCOUNT'), $no = JText::_('COM_REDSHOP_AFTER_DISCOUNT'));
		$lists['auto_scroll_wrapper']     = JHtml::_('redshopselect.booleanlist', 'auto_scroll_wrapper', 'class="form-control" size="1"', $this->config->get('AUTO_SCROLL_WRAPPER'));
		$lists['allow_multiple_discount'] = JHtml::_('redshopselect.booleanlist', 'allow_multiple_discount', 'class="form-control" size="1"', $this->config->get('ALLOW_MULTIPLE_DISCOUNT'));
		$lists['show_product_detail']     = JHtml::_('redshopselect.booleanlist', 'show_product_detail', 'class="form-control" size="1"', $this->config->get('SHOW_PRODUCT_DETAIL'));
		$lists['compare_template_id']     = JHtml::_('select.genericlist', $compare_template, 'compare_template_id',
			'class="form-control" size="1" ', 'template_id', 'template_name', $this->config->get('COMPARE_TEMPLATE_ID')
		);

		$lists['show_terms_and_conditions'] = JHtml::_('redshopselect.booleanlist', 'show_terms_and_conditions',
			'class="form-control" size="1"', $this->config->get('SHOW_TERMS_AND_CONDITIONS'), $yes = JText::_('COM_REDSHOP_SHOW_PER_USER'),
			$no = JText::_('COM_REDSHOP_SHOW_PER_ORDER')
		);

		$lists['rating_review_login_required'] = JHtml::_('redshopselect.booleanlist', 'rating_review_login_required',
			'class="form-control" size="1"', $this->config->get('RATING_REVIEW_LOGIN_REQUIRED')
		);

		$product_comparison   = array();
		$product_comparison[] = JHtml::_('select.option', '', JText::_('COM_REDSHOP_SELECT'));
		$product_comparison[] = JHtml::_('select.option', 'category', JText::_('COM_REDSHOP_CATEGORY'));
		$product_comparison[] = JHtml::_('select.option', 'global', JText::_('COM_REDSHOP_GLOBAL'));

		$lists['product_comparison_type'] = JHtml::_('select.genericlist', $product_comparison, 'product_comparison_type',
			'class="form-control" size="1"', 'value', 'text', $this->config->get('PRODUCT_COMPARISON_TYPE')
		);
		$lists['newsletter_enable']       = JHtml::_('redshopselect.booleanlist', 'newsletter_enable', 'class="form-control" size="1"', $this->config->get('NEWSLETTER_ENABLE'));
		$lists['newsletter_confirmation'] = JHtml::_('redshopselect.booleanlist', 'newsletter_confirmation', 'class="form-control" size="1"', $this->config->get('NEWSLETTER_CONFIRMATION'));

		$lists['watermark_category_image']            = JHtml::_('redshopselect.booleanlist', 'watermark_category_image',
			'class="form-control" size="1"', $this->config->get('WATERMARK_CATEGORY_IMAGE')
		);
		$lists['watermark_category_thumb_image']      = JHtml::_('redshopselect.booleanlist', 'watermark_category_thumb_image',
			'class="form-control" size="1"', $this->config->get('WATERMARK_CATEGORY_THUMB_IMAGE')
		);
		$lists['watermark_product_image']             = JHtml::_('redshopselect.booleanlist', 'watermark_product_image', 'class="form-control" size="1"', $this->config->get('WATERMARK_PRODUCT_IMAGE'));
		$lists['watermark_product_thumb_image']       = JHtml::_('redshopselect.booleanlist', 'watermark_product_thumb_image',
			'class="form-control" size="1"', $this->config->get('WATERMARK_PRODUCT_THUMB_IMAGE')
		);
		$lists['watermark_product_additional_image']  = JHtml::_('redshopselect.booleanlist', 'watermark_product_additional_image',
			'class="form-control" size="1"', $this->config->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE')
		);
		$lists['watermark_cart_thumb_image']          = JHtml::_('redshopselect.booleanlist', 'watermark_cart_thumb_image',
			'class="form-control" size="1"', $this->config->get('WATERMARK_CART_THUMB_IMAGE')
		);
		$lists['watermark_giftcart_image']            = JHtml::_('redshopselect.booleanlist', 'watermark_giftcart_image',
			'class="form-control" size="1"', $this->config->get('WATERMARK_GIFTCART_IMAGE')
		);
		$lists['watermark_giftcart_thumb_image']      = JHtml::_('redshopselect.booleanlist', 'watermark_giftcart_thumb_image',
			'class="form-control" size="1"', $this->config->get('WATERMARK_GIFTCART_THUMB_IMAGE')
		);
		$lists['watermark_manufacturer_thumb_image']  = JHtml::_('redshopselect.booleanlist', 'watermark_manufacturer_thumb_image',
			'class="form-control" size="1"', $this->config->get('WATERMARK_MANUFACTURER_THUMB_IMAGE')
		);
		$lists['watermark_manufacturer_image']        = JHtml::_('redshopselect.booleanlist', 'watermark_manufacturer_image',
			'class="form-control" size="1"', $this->config->get('WATERMARK_MANUFACTURER_IMAGE')
		);
		$lists['clickatell_enable']                   = JHtml::_('redshopselect.booleanlist', 'clickatell_enable', 'class="form-control" size="1"', $this->config->get('CLICKATELL_ENABLE'));
		$lists['quotation_mode']                      = JHtml::_('redshopselect.booleanlist', 'default_quotation_mode', 'class="form-control" size="1"',
			$this->config->get('DEFAULT_QUOTATION_MODE_PRE'), $yes = JText::_('COM_REDSHOP_ON'),
			$no = JText::_('COM_REDSHOP_OFF')
		);
		$lists['wanttoshowattributeimage']            = JHtml::_('redshopselect.booleanlist', 'wanttoshowattributeimage',
			'class="form-control" size="1"', $this->config->get('WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART')
		);
		$lists['show_quotation_price']                = JHtml::_('redshopselect.booleanlist', 'show_quotation_price',
			'class="form-control" size="1"', $this->config->get('SHOW_QUOTATION_PRICE')
		);
		$lists['display_out_of_stock_attribute_data'] = JHtml::_('redshopselect.booleanlist', 'display_out_of_stock_attribute_data',
			'class="form-control"', $this->config->get('DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA')
		);

		$lists['category_tree_in_sef_url'] = JHtml::_('redshopselect.booleanlist', 'category_tree_in_sef_url', 'class="form-control"', $this->config->get('CATEGORY_TREE_IN_SEF_URL'));
		$lists['statistics_enable']        = JHtml::_('redshopselect.booleanlist', 'statistics_enable', 'class="form-control" size="1"', $this->config->get('STATISTICS_ENABLE'));
		$orderstatus                       = $model->getOrderstatus();
		$tmp                               = array();
		$tmp[]                             = JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$orderstatus                       = array_merge($tmp, $orderstatus);
		$lists['clickatell_order_status']  = JHtml::_('select.genericlist', $orderstatus, 'clickatell_order_status',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('CLICKATELL_ORDER_STATUS')
		);

		$menuitem           = array();
		$menuitem[0]        = new stdClass;
		$menuitem[0]->value = 0;
		$menuitem[0]->text  = JText::_('COM_REDSHOP_SELECT');
		$q                  = "SELECT m.id,m.title AS name,mt.title FROM #__menu AS m "
			. "LEFT JOIN #__menu_types AS mt ON mt.menutype=m.menutype "
			. "WHERE m.published=1 "
			. "ORDER BY m.menutype";
		$db->setQuery($q);
		$menuitemlist = $db->loadObjectList();

		for ($i = 0, $in = count($menuitemlist); $i < $in; $i++)
		{
			$menuitem[$i + 1]        = new stdClass;
			$menuitem[$i + 1]->value = $menuitemlist[$i]->id;
			$menuitem[$i + 1]->text  = $menuitemlist[$i]->name;
		}

		$lists['url_after_portal_login']  = JHtml::_('select.genericlist', $menuitem, 'portal_login_itemid',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('PORTAL_LOGIN_ITEMID')
		);
		$lists['url_after_portal_logout'] = JHtml::_('select.genericlist', $menuitem, 'portal_logout_itemid',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('PORTAL_LOGOUT_ITEMID')
		);

		$default_vat_group = $model->getVatGroup();
		$tmp               = array();
		$tmp[]             = JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$default_vat_group = array_merge($tmp, $default_vat_group);

		$tmp                 = array();
		$tmp[]               = JHtml::_('select.option', '', JText::_('COM_REDSHOP_SELECT'));
		$default_vat_country = array_merge($tmp, $countries);

		$default_customer_register_type          = array();
		$default_customer_register_type[]        = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$default_customer_register_type[]        = JHtml::_('select.option', '1', JText::_('COM_REDSHOP_PRIVATE'));
		$default_customer_register_type[]        = JHtml::_('select.option', '2', JText::_('COM_REDSHOP_COMPANY'));
		$lists['default_customer_register_type'] = JHtml::_('select.genericlist', $default_customer_register_type,
			'default_customer_register_type', 'class="form-control" ', 'value', 'text', $this->config->get('DEFAULT_CUSTOMER_REGISTER_TYPE')
		);

		$checkoutLoginRegisterSwitcher             = array();
		$checkoutLoginRegisterSwitcher[]           = JHtml::_('select.option', 'tabs', JText::_('COM_REDSHOP_CONFIG_TABS'));
		$checkoutLoginRegisterSwitcher[]           = JHtml::_('select.option', 'sliders', JText::_('COM_REDSHOP_CONFIG_SLIDERS'));
		$lists['checkout_login_register_switcher'] = JHtml::_('select.genericlist', $checkoutLoginRegisterSwitcher,
			'checkout_login_register_switcher', 'class="form-control" ', 'value', 'text', $this->config->get('CHECKOUT_LOGIN_REGISTER_SWITCHER')
		);

		$addtocart_behaviour          = array();
		$addtocart_behaviour[]        = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_SELECT'));
		$addtocart_behaviour[]        = JHtml::_('select.option', '1', JText::_('COM_REDSHOP_DIRECT_TO_CART'));
		$addtocart_behaviour[]        = JHtml::_('select.option', '2', JText::_('COM_REDSHOP_STAY_ON_CURRENT_VIEW'));
		$lists['addtocart_behaviour'] = JHtml::_('select.genericlist', $addtocart_behaviour, 'addtocart_behaviour',
			'class="form-control" ', 'value', 'text', $this->config->get('ADDTOCART_BEHAVIOUR')
		);

		$allow_customer_register_type          = array();
		$allow_customer_register_type[]        = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_BOTH'));
		$allow_customer_register_type[]        = JHtml::_('select.option', '1', JText::_('COM_REDSHOP_PRIVATE'));
		$allow_customer_register_type[]        = JHtml::_('select.option', '2', JText::_('COM_REDSHOP_COMPANY'));
		$lists['allow_customer_register_type'] = JHtml::_('select.genericlist', $allow_customer_register_type,
			'allow_customer_register_type', 'class="form-control" ', 'value', 'text',
			$this->config->get('ALLOW_CUSTOMER_REGISTER_TYPE')
		);

		// Optional shipping address select box
		$lists['optional_shipping_address'] = JHtml::_('redshopselect.booleanlist', 'optional_shipping_address', 'class="form-control" ', $this->config->get('OPTIONAL_SHIPPING_ADDRESS'));
		$lists['shipping_method_enable']    = JHtml::_('redshopselect.booleanlist', 'shipping_method_enable', 'class="form-control" ', $this->config->get('SHIPPING_METHOD_ENABLE'));

		$lists['default_vat_group'] = JHtml::_('select.genericlist', $default_vat_group, 'default_vat_group',
			'class="form-control" ', 'value', 'text', $this->config->get('DEFAULT_VAT_GROUP')
		);

		$vat_based_on          = array();
		$vat_based_on[]        = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_WEBSHOP_MODE'));
		$vat_based_on[]        = JHtml::_('select.option', '1', JText::_('COM_REDSHOP_CUSTOMER_MODE'));
		$vat_based_on[]        = JHtml::_('select.option', '2', JText::_('COM_REDSHOP_EU_MODE'));
		$lists['vat_based_on'] = JHtml::_('select.genericlist', $vat_based_on, 'vat_based_on', 'class="form-control" ', 'value', 'text', $this->config->get('VAT_BASED_ON'));

		$lists['default_vat_country'] = JHtml::_('select.genericlist', $default_vat_country, 'default_vat_country',
			'class="form-control" onchange="changeStateList();"', 'value', 'text', $this->config->get('DEFAULT_VAT_COUNTRY')
		);

		$country_list_name     = 'default_vat_country';
		$state_list_name       = 'default_vat_state';
		$selected_country_code = $this->config->get('DEFAULT_VAT_COUNTRY');
		$selected_state_code   = $this->config->get('DEFAULT_VAT_STATE');

		if (empty($selected_state_code))
		{
			$selected_state_code = "originalPos";
		}
		else
		{
			$selected_state_code = "'" . $selected_state_code . "'";
		}

		$db->setQuery("SELECT c.id, c.country_3_code, s.state_name, s.state_2_code
						FROM #__redshop_country c
						LEFT JOIN #__redshop_state s
						ON c.id=s.country_id OR s.country_id IS NULL
						ORDER BY c.id, s.state_name");
		$states = $db->loadObjectList();

		// Build the State lists for each Country
		$script       = "<script language=\"javascript\" type=\"text/javascript\">//<![CDATA[\n";
		$script       .= "<!--\n";
		$script       .= "var originalOrder = '1';\n";
		$script       .= "var originalPos = '$selected_country_code';\n";
		$script       .= "var states = new Array();	// array in the format [key,value,text]\n";
		$i            = 0;
		$prev_country = '';

		for ($j = 0, $jn = count($states); $j < $jn; $j++)
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

		$script                     .= "function changeStateList()
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

						if(window.jQuery){
							jQuery(\"#" . $state_list_name . "\").trigger(\"liszt:updated\");
		  				}
				 	}
				 	writeDynaList( 'class=\"form-control\" name=\"default_vat_state\" size=\"1\" id=\"default_vat_state\"',
				 	states, originalPos, originalPos, $selected_state_code );
					//-->
					//]]></script>";
		$lists['default_vat_state'] = $script;

		$shopper_Group_private = $model->getShopperGroupPrivate();

		$tmp   = array();
		$tmp[] = JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$tmp   = array_merge($tmp, $shopper_Group_private);

		$lists['shopper_group_default_private'] = JHtml::_('select.genericlist', $tmp, 'shopper_group_default_private',
			'class="form-control" ', 'value', 'text', $this->config->get('SHOPPER_GROUP_DEFAULT_PRIVATE')
		);

		$shopper_Group_company                  = $model->getShopperGroupCompany();
		$tmp                                    = array();
		$tmp[]                                  = JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$tmp                                    = array_merge($tmp, $shopper_Group_company);
		$lists['shopper_group_default_company'] = JHtml::_('select.genericlist', $tmp, 'shopper_group_default_company',
			'class="form-control" ', 'value', 'text', $this->config->get('SHOPPER_GROUP_DEFAULT_COMPANY')
		);

		$tmp                                         = array();
		$tmp[]                                       = JHtml::_('select.option', 0, JText::_('COM_REDSHOP_SELECT'));
		$tmp                                         = array_merge($tmp, $shopper_Group_private, $shopper_Group_company);
		$lists['shopper_group_default_unregistered'] = JHtml::_('select.genericlist', $tmp, 'shopper_group_default_unregistered',
			'class="form-control" ', 'value', 'text', $this->config->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED')
		);

		$register_methods         = array();
		$register_methods[]       = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_REGISTER_WITH_ACCOUNT_CREATION'));
		$register_methods[]       = JHtml::_('select.option', '1', JText::_('COM_REDSHOP_REGISTER_WITHOUT_ACCOUNT_CREATION'));
		$register_methods[]       = JHtml::_('select.option', '2', JText::_('COM_REDSHOP_REGISTER_ACCOUNT_OPTIONAL'));
		$register_methods[]       = JHtml::_('select.option', '3', JText::_('COM_REDSHOP_REGISTER_ACCOUNT_SILENT'));
		$lists['register_method'] = JHtml::_('select.genericlist', $register_methods, 'register_method',
			'class="form-control" id="register_method"', 'value', 'text', $this->config->get('REGISTER_METHOD')
		);

		$lists['product_template']              = JHtml::_('select.genericlist', $product_template, 'default_product_template',
			'class="form-control" size="1" ', 'template_id', 'template_name', $this->config->get('PRODUCT_TEMPLATE')
		);
		$lists['ajax_detail_template']          = JHtml::_('select.genericlist', $ajax_detail_template, 'default_ajax_detailbox_template',
			'class="form-control" size="1" ', 'template_id', 'template_name', $this->config->get('DEFAULT_AJAX_DETAILBOX_TEMPLATE')
		);
		$lists['category_template']             = JHtml::_('select.genericlist', $category_template, 'default_category_template',
			'class="form-control" size="1" ', 'template_id', 'template_name', $this->config->get('CATEGORY_TEMPLATE')
		);
		$lists['default_categorylist_template'] = JHtml::_('select.genericlist', $categorylist_template, 'default_categorylist_template',
			'class="form-control" size="1" ', 'template_id', 'template_name', $this->config->get('DEFAULT_CATEGORYLIST_TEMPLATE')
		);
		$lists['manufacturer_template']         = JHtml::_('select.genericlist', $manufacturer_template, 'default_manufacturer_template',
			'class="form-control" size="1" ', 'template_id', 'template_name', $this->config->get('MANUFACTURER_TEMPLATE')
		);
		$lists['show_price']                    = JHtml::_('redshopselect.booleanlist', 'show_price', 'class="form-control" size="1"', $this->config->get('SHOW_PRICE_PRE'));

		$lists['use_as_catalog']                      = JHtml::_('redshopselect.booleanlist', 'use_as_catalog', 'class="form-control" size="1"', $this->config->get('PRE_USE_AS_CATALOG', 0));
		$lists['show_tax_exempt_infront']             = JHtml::_('redshopselect.booleanlist', 'show_tax_exempt_infront',
			'class="form-control" size="1"', $this->config->get('SHOW_TAX_EXEMPT_INFRONT')
		);
		$lists['individual_add_to_cart_enable']       = JHtml::_('redshopselect.booleanlist', 'individual_add_to_cart_enable',
			'class="form-control" size="1"', $this->config->get('INDIVIDUAL_ADD_TO_CART_ENABLE'),
			JText::_('COM_REDSHOP_INDIVIDUAL_ADD_TO_CART_PER_PROPERTY'),
			JText::_('COM_REDSHOP_ADD_TO_CART_PER_PRODUCT')
		);
		$lists['accessory_as_product_in_cart_enable'] = JHtml::_('redshopselect.booleanlist', 'accessory_as_product_in_cart_enable',
			'class="form-control" size="1"', $this->config->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE')
		);
		$lists['use_product_outofstock_image']        = JHtml::_('redshopselect.booleanlist', 'use_product_outofstock_image',
			'class="form-control" size="1"', $this->config->get('USE_PRODUCT_OUTOFSTOCK_IMAGE')
		);
		$lists['enable_address_detail_in_shipping']   = JHtml::_('redshopselect.booleanlist', 'enable_address_detail_in_shipping',
			'class="form-control" size="1"', $this->config->get('ENABLE_ADDRESS_DETAIL_IN_SHIPPING')
		);

		$lists['send_mail_to_customer'] = JHtml::_('redshopselect.booleanlist', 'send_mail_to_customer', 'class="form-control" size="1"', $this->config->get('SEND_MAIL_TO_CUSTOMER'));

		$bookinvoice                     = array();
		$bookinvoice[]                   = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_DIRECTLY_BOOK'));
		$bookinvoice[]                   = JHtml::_('select.option', '1', JText::_('COM_REDSHOP_MANUALLY_BOOK'));
		$bookinvoice[]                   = JHtml::_('select.option', '2', JText::_('COM_REDSHOP_BOOK_ON_ORDER_STATUS'));
		$lists['economic_invoice_draft'] = JHtml::_('select.genericlist', $bookinvoice, 'economic_invoice_draft',
			'class="form-control"', 'value', 'text',
			$this->config->get('ECONOMIC_INVOICE_DRAFT')
		);

		$bookInvoiceNumbers                    = array(
			JHtml::_('select.option', '0', JText::_('COM_REDSHOP_SAME_AS_ORDER_NUMBER')),
			JHtml::_('select.option', '1', JText::_('COM_REDSHOP_SEQUENTIALLY_IN_ECONOMIC_NO_MATCH_UP_WITH_ORDER_NUMBER'))
		);
		$lists['economic_book_invoice_number'] = JHtml::_('select.genericlist', $bookInvoiceNumbers, 'economic_book_invoice_number',
			'class="form-control"', 'value', 'text',
			$this->config->get('ECONOMIC_BOOK_INVOICE_NUMBER')
		);

		// NEXT-PREVIOUS LINK
		$link_type                   = array();
		$link_type[]                 = JHtml::_('select.option', '0', JText::_('COM_REDSHOP_DEFAULT_LINK'));
		$link_type[]                 = JHtml::_('select.option', '1', JText::_('COM_REDSHOP_CUSTOM_LINK'));
		$link_type[]                 = JHtml::_('select.option', '2', JText::_('COM_REDSHOP_IMAGE_LINK'));
		$lists['next_previous_link'] = JHtml::_('select.genericlist', $link_type, 'next_previous_link',
			'class="form-control" ', 'value', 'text', $this->config->get('DEFAULT_LINK_FIND')
		);

		$order_data                                            = RedshopHelperUtility::getOrderByList();
		$lists['default_product_ordering_method']              = JHtml::_('select.genericlist', $order_data, 'default_product_ordering_method',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('DEFAULT_PRODUCT_ORDERING_METHOD')
		);
		$lists['default_manufacturer_product_ordering_method'] = JHtml::_('select.genericlist', $order_data,
			'default_manufacturer_product_ordering_method', 'class="form-control" size="1" ', 'value',
			'text', $this->config->get('DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD')
		);

		$order_data                                 = RedshopHelperUtility::getRelatedOrderByList();
		$lists['default_related_ordering_method']   = JHtml::_('select.genericlist', $order_data, 'default_related_ordering_method',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('DEFAULT_RELATED_ORDERING_METHOD')
		);
		$order_data                                 = RedshopHelperUtility::getAccessoryOrderByList();
		$lists['default_accessory_ordering_method'] = JHtml::_('select.genericlist', $order_data, 'default_accessory_ordering_method',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('DEFAULT_ACCESSORY_ORDERING_METHOD')
		);

		$lists['shipping_after'] = $extra_field->rs_booleanlist(
			'shipping_after',
			'class="form-control"',
			$this->config->get('SHIPPING_AFTER', 'total'),
			JText::_('COM_REDSHOP_TOTAL'),
			JText::_('COM_REDSHOP_SUBTOTAL_LBL'),
			'',
			'total',
			'subtotal'
		);

		$lists['payment_calculation_on'] = $extra_field->rs_booleanlist(
			'payment_calculation_on',
			'class="form-control"',
			$this->config->get('PAYMENT_CALCULATION_ON', 'total'),
			JText::_('COM_REDSHOP_TOTAL'),
			JText::_('COM_REDSHOP_SUBTOTAL_LBL'),
			'',
			'total',
			'subtotal'
		);

		$lists['calculate_vat_on'] = $extra_field->rs_booleanlist(
			'calculate_vat_on',
			'class="form-control"',
			$this->config->get('CALCULATE_VAT_ON', 'BT'),
			JText::_('COM_REDSHOP_BILLING_ADDRESS_LBL'),
			JText::_('COM_REDSHOP_SHIPPING_ADDRESS_LBL'),
			'',
			'BT',
			'ST'
		);

		$order_data           = array();
		$order_data[0]        = new stdClass;
		$order_data[0]->value = "c.name ASC";
		$order_data[0]->text  = JText::_('COM_REDSHOP_CATEGORY_NAME');

		$order_data[1]        = new stdClass;
		$order_data[1]->value = "c.id DESC";
		$order_data[1]->text  = JText::_('COM_REDSHOP_NEWEST');

		$order_data[2]        = new stdClass;
		$order_data[2]->value = "c.ordering ASC";
		$order_data[2]->text  = JText::_('COM_REDSHOP_ORDERING');

		$lists['default_category_ordering_method'] = JHtml::_('select.genericlist', $order_data, 'default_category_ordering_method',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('DEFAULT_CATEGORY_ORDERING_METHOD')
		);

		$order_data                                    = RedshopHelperUtility::getManufacturerOrderByList();
		$lists['default_manufacturer_ordering_method'] = JHtml::_('select.genericlist', $order_data, 'default_manufacturer_ordering_method',
			'class="form-control" size="1" ', 'value', 'text', $this->config->get('DEFAULT_MANUFACTURER_ORDERING_METHOD')
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

		$lists['currency_symbol_position'] = JHtml::_('select.genericlist', $symbol_position, 'currency_symbol_position',
			'class="form-control" ', 'value', 'text', $this->config->get('CURRENCY_SYMBOL_POSITION')
		);

		$default_dateformat          = $config->getDateFormat();
		$lists['default_dateformat'] = JHtml::_('select.genericlist', $default_dateformat, 'default_dateformat',
			'class="form-control" ', 'value', 'text', $this->config->get('DEFAULT_DATEFORMAT')
		);

		$lists['discount_enable']         = JHtml::_('redshopselect.booleanlist', 'discount_enable', 'class="form-control" ', $this->config->get('DISCOUNT_ENABLE'));
		$lists['invoice_mail_enable']     = JHtml::_('redshopselect.booleanlist', 'invoice_mail_enable', 'class="form-control"', $this->config->get('INVOICE_MAIL_ENABLE'));
		$lists['wishlist_login_required'] = JHtml::_('redshopselect.booleanlist', 'wishlist_login_required', 'class="form-control"', $this->config->get('WISHLIST_LOGIN_REQUIRED'));
		$lists['wishlist_list'] = JHtml::_('redshopselect.booleanlist', 'wishlist_list', 'class="form-control"', $this->config->get('WISHLIST_LIST'));

		$invoice_mail_send_option           = array();
		$invoice_mail_send_option[0]        = new stdClass;
		$invoice_mail_send_option[0]->value = 0;
		$invoice_mail_send_option[0]->text  = JText::_('COM_REDSHOP_NONE');

		$invoice_mail_send_option[1]        = new stdClass;
		$invoice_mail_send_option[1]->value = 1;
		$invoice_mail_send_option[1]->text  = JText::_('COM_REDSHOP_ADMINISTRATOR');

		$invoice_mail_send_option[2]        = new stdClass;
		$invoice_mail_send_option[2]->value = 2;
		$invoice_mail_send_option[2]->text  = JText::_('COM_REDSHOP_CUSTOMER');

		$invoice_mail_send_option[3]        = new stdClass;
		$invoice_mail_send_option[3]->value = 3;
		$invoice_mail_send_option[3]->text  = JText::_('COM_REDSHOP_BOTH');

		$lists['invoice_mail_send_option'] = JHtml::_(
			'redshopselect.radiolist',
			$invoice_mail_send_option,
			'invoice_mail_send_option',
			'',
			'value',
			'text',
			$this->config->get('INVOICE_MAIL_SEND_OPTION')
		);

		$order_mail_after           = array();
		$order_mail_after[0]        = new stdClass;
		$order_mail_after[0]->value = 0;
		$order_mail_after[0]->text  = JText::_('COM_REDSHOP_ORDER_MAIL_BEFORE_PAYMENT');

		$order_mail_after[1]        = new stdClass;
		$order_mail_after[1]->value = 1;
		$order_mail_after[1]->text  = JText::_('COM_REDSHOP_ORDER_MAIL_AFTER_PAYMENT_BUT_SEND_BEFORE_ADMINISTRATOR');

		$order_mail_after[2]        = new stdClass;
		$order_mail_after[2]->value = 2;
		$order_mail_after[2]->text  = JText::_('COM_REDSHOP_ORDER_MAIL_AFTER_PAYMENT');

		$lists['order_mail_after'] = JHtml::_(
			'select.genericlist',
			$order_mail_after,
			'order_mail_after',
			' class="form-control"',
			'value',
			'text',
			$this->config->get('ORDER_MAIL_AFTER')
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

		$lists['discount_type'] = JHtml::_('select.genericlist', $discount_type, 'discount_type',
			'class="form-control" ', 'value', 'text', $this->config->get('DISCOUNT_TYPE')
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

		$option[5]        = new stdClass;
		$option[5]->value = 'l';
		$option[5]->text  = JText::_('COM_REDSHOP_LITER');

		$option[5]        = new stdClass;
		$option[5]->value = 'ml';
		$option[5]->text  = JText::_('COM_REDSHOP_MILLILITER');

		$lists['default_volume_unit'] = JHtml::_('select.genericlist', $option, 'default_volume_unit',
			'class="form-control" ', 'value', 'text', $this->config->get('DEFAULT_VOLUME_UNIT')
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

		$lists['default_weight_unit'] = JHtml::_('select.genericlist', $option, 'default_weight_unit',
			'class="form-control" ', 'value', 'text', $this->config->get('DEFAULT_WEIGHT_UNIT')
		);
		unset($option);

		$lists['postdk_integration']         = JHtml::_('redshopselect.booleanlist', 'postdk_integration', 'class="form-control" size="1"', $this->config->get('POSTDK_INTEGRATION'));
		$lists['send_catalog_reminder_mail'] = JHtml::_('redshopselect.booleanlist', 'send_catalog_reminder_mail', 'class="form-control" size="1"', $this->config->get('SEND_CATALOG_REMINDER_MAIL'));

		$lists['load_redshop_style'] = JHtml::_('redshopselect.booleanlist', 'load_redshop_style', 'class="form-control" size="1"', $this->config->get('LOAD_REDSHOP_STYLE'));

		$lists['enable_stockroom_notification'] = JHtml::_('redshopselect.booleanlist', 'enable_stockroom_notification', 'class="form-control" size="1"', $this->config->get('ENABLE_STOCKROOM_NOTIFICATION'));

		$lists['inline_editing'] = JHtml::_(
			'redshopselect.booleanlist', 'inline_editing', 'class="form-control" size="1"', $this->config->get('INLINE_EDITING')
		);

		$lists['currency_libraries'] = JHtml::_('redshopselect.booleanlist', 'currency_libraries',
			'class="form-control" size="1"', $this->config->get('CURRENCY_LIBRARIES'), $yes = JText::_('COM_REDSHOP_CURRENCY_LIBRARIES_LAYER'),
			$no = JText::_('COM_REDSHOP_CURRENCY_LIBRARIES_ECB')
		);

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
		$this->request_url          = JUri::getInstance()->toString();
		$this->tabmenu              = $this->getTabMenu();

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


	/**
	 * Tab Menu
	 *
	 * @return  object  Tab menu
	 *
	 * @since   1.7
	 */
	private function getTabMenu()
	{
		$app                 = JFactory::getApplication();
		$selectedTabPosition = $app->getUserState('com_redshop.configuration.selectedTabPosition', 'general');

		$tabMenu = RedshopAdminMenu::getInstance()->init();
		$tabMenu->section('tab')
			->title('COM_REDSHOP_GENERAL_CONFIGURATION')
			->addItem(
				'#general',
				'COM_REDSHOP_GENERAL_CONFIGURATION',
				($selectedTabPosition == 'general') ? true : false,
				'general'
			)->addItem(
				'#user',
				'COM_REDSHOP_USER',
				($selectedTabPosition == 'user') ? true : false,
				'user'
			)->addItem(
				'#cattab',
				'COM_REDSHOP_CATEGORY_TAB',
				($selectedTabPosition == 'cattab') ? true : false,
				'cattab'
			)->addItem(
				'#manufacturertab',
				'COM_REDSHOP_REDMANUFACTURER_TAB',
				($selectedTabPosition == 'manufacturertab') ? true : false,
				'manufacturertab'
			)->addItem(
				'#producttab',
				'COM_REDSHOP_PRODUCT_TAB',
				($selectedTabPosition == 'producttab') ? true : false,
				'producttab'
			)->addItem(
				'#featuretab',
				'COM_REDSHOP_FEATURE_TAB',
				($selectedTabPosition == 'featuretab') ? true : false,
				'featuretab'
			)->addItem(
				'#pricetab',
				'COM_REDSHOP_PRICE_TAB',
				($selectedTabPosition == 'pricetab') ? true : false,
				'pricetab'
			)->addItem(
				'#carttab',
				'COM_REDSHOP_CART_TAB',
				($selectedTabPosition == 'carttab') ? true : false,
				'carttab'
			)->addItem(
				'#ordertab',
				'COM_REDSHOP_ORDER_TAB',
				($selectedTabPosition == 'ordertab') ? true : false,
				'ordertab'
			)->addItem(
				'#newslettertab',
				'COM_REDSHOP_NEWSLETTER_TAB',
				($selectedTabPosition == 'newslettertab') ? true : false,
				'newslettertab'
			)->addItem(
				'#integration',
				'COM_REDSHOP_INTEGRATION',
				($selectedTabPosition == 'integration') ? true : false,
				'integration'
			)->addItem(
				'#seo',
				'COM_REDSHOP_SEO',
				($selectedTabPosition == 'seo') ? true : false,
				'seo'
			)->addItem(
				'#dashboard',
				'COM_REDSHOP_DASHBOARD',
				($selectedTabPosition == 'dashboard') ? true : false,
				'dashboard'
			)->addItem(
				'#redshopabout',
				'COM_REDSHOP_ABOUT',
				($selectedTabPosition == 'redshopabout') ? true : false,
				'redshopabout'
			);

		return $tabMenu;
	}
}
