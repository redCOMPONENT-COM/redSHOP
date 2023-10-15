<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

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
        $lang = JFactory::getLanguage();
        $lang->load('com_content', JPATH_ADMINISTRATOR, $lang->getTag(), true);
        $db = JFactory::getDbo();

        $document = JFactory::getDocument();
        $layout   = JFactory::getApplication()->input->getCmd('layout', '');

        if ($layout == "resettemplate") {
            $tpl = "resettemplate";
        }

        $document->setTitle(Text::_('COM_REDSHOP_CONFIG'));
        HTMLHelper::script('com_redshop/redshop.validation.min.js', ['relative' => true]);

        /** @var RedshopModelConfiguration $model */
        $model         = $this->getModel('configuration');
        $currency_data = $model->getCurrencies();

        $this->config = $model->getData();
        $lists        = array();

        // Load payment languages
        RedshopHelperPayment::loadLanguages(true);
        RedshopHelperShipping::loadLanguages(true);
        RedshopHelperModule::loadLanguages();

        JToolbarHelper::title(Text::_('COM_REDSHOP_CONFIG'), 'equalizer redshop_icon-48-settings');
        JToolbarHelper::save();
        JToolbarHelper::apply();
        JToolbarHelper::cancel();

        $this->setLayout('default');

        $newsletters = $model->getnewsletters();

        $templates          = array();
        $templates[0]       = new stdClass;
        $templates[0]->id   = 0;
        $templates[0]->name = Text::_('COM_REDSHOP_SELECT');

        $product_template      = RedshopHelperTemplate::getTemplate("product");
        $compare_template      = RedshopHelperTemplate::getTemplate("compare_product");
        $category_template     = RedshopHelperTemplate::getTemplate("category");
        $categorylist_template = RedshopHelperTemplate::getTemplate("frontpage_category");
        $manufacturer_template = RedshopHelperTemplate::getTemplate("manufacturer_products");
        $ajax_detail_template  = RedshopHelperTemplate::getTemplate("ajax_cart_detail_box");

        $product_template      = array_merge($templates, $product_template);
        $compare_template      = array_merge($templates, $compare_template);
        $category_template     = array_merge($templates, $category_template);
        $categorylist_template = array_merge($templates, $categorylist_template);
        $manufacturer_template = array_merge($templates, $manufacturer_template);
        $ajax_detail_template  = array_merge($templates, $ajax_detail_template);

        $shopper_groups = Redshop\Helper\ShopperGroup::generateList();

        if (count($shopper_groups) <= 0) {
            $shopper_groups = array();
        }

        $tmp                              = array();
        $tmp[]                            = HTMLHelper::_('select.option', 0, Text::_('COM_REDSHOP_SELECT'));
        $new_shopper_group_get_value_from = array_merge($tmp, $shopper_groups);

        $lists['new_shopper_group_get_value_from'] = HTMLHelper::_(
            'select.genericlist',
            $new_shopper_group_get_value_from,
            'new_shopper_group_get_value_from',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('NEW_SHOPPER_GROUP_GET_VALUE_FROM')
        );
        $lists['accessory_product_in_lightbox']    = HTMLHelper::_(
            'redshopselect.booleanlist',
            'accessory_product_in_lightbox',
            'class="form-control" ',
            $this->config->get('ACCESSORY_PRODUCT_IN_LIGHTBOX')
        );

        $lists['webpack_enable_sms']         = HTMLHelper::_(
            'redshopselect.booleanlist',
            'webpack_enable_sms',
            'class="form-control" size="1"',
            $this->config->get('WEBPACK_ENABLE_SMS')
        );
        $lists['webpack_enable_email_track'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'webpack_enable_email_track',
            'class="form-control" size="1"',
            $this->config->get('WEBPACK_ENABLE_EMAIL_TRACK')
        );

        $q = $db->getQuery(true)
            ->select($db->qn('country_3_code', 'value'))
            ->select($db->qn('country_name', 'text'))
            ->select($db->qn('country_jtext'))
            ->from($db->qn('#__redshop_country'))
            ->order($db->qn('country_name') . ' ASC');
        $db->setQuery($q);

        $countries = $db->loadObjectList();
        $countries = RedshopHelperUtility::convertLanguageString($countries);

        $q = $db->getQuery(true)
            ->select($db->qn('id', 'value'))
            ->select($db->qn('name', 'text'))
            ->from($db->qn('#__redshop_stockroom'))
            ->order($db->qn('name') . ' ASC');
        $db->setQuery($q);

        $stockroom    = $db->loadObjectList();
        $country_list = explode(',', $this->config->get('COUNTRY_LIST'));

        $tmp                                       = array();
        $tmp[]                                     = HTMLHelper::_('select.option', 0, Text::_('COM_REDSHOP_SELECT'));
        $economic_accountgroup                     = RedshopHelperUtility::getEconomicAccountGroup();
        $economic_accountgroup                     = array_merge($tmp, $economic_accountgroup);
        $lists['default_economic_account_group']   = HTMLHelper::_(
            'select.genericlist',
            $economic_accountgroup,
            'default_economic_account_group',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('DEFAULT_ECONOMIC_ACCOUNT_GROUP')
        );
        $tmpoption                                 = array();
        $tmpoption[]                               = HTMLHelper::_('select.option', 0, Text::_('COM_REDSHOP_NO'));
        $tmpoption[]                               = HTMLHelper::_(
            'select.option',
            1,
            Text::_('COM_REDSHOP_ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC_LBL')
        );
        $tmpoption[]                               = HTMLHelper::_(
            'select.option',
            2,
            Text::_(
                'COM_REDSHOP_ATTRIBUTE_PLUS_PRODUCT_IN_ECONOMIC_LBL'
            )
        );
        $lists['attribute_as_product_in_economic'] = HTMLHelper::_(
            'select.genericlist',
            $tmpoption,
            'attribute_as_product_in_economic',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC')
        );

        $lists['detail_error_message_on'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'detail_error_message_on',
            'class="form-control" ',
            $this->config->get('DETAIL_ERROR_MESSAGE_ON')
        );

        $lists['newsletters']   = HTMLHelper::_(
            'select.genericlist',
            $newsletters,
            'default_newsletter',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('DEFAULT_NEWSLETTER')
        );
        $lists['currency_data'] = HTMLHelper::_(
            'select.genericlist',
            $currency_data,
            'currency_code',
            'class="form-control" size="1" onchange="changeRedshopCurrencyList(this);"',
            'value',
            'text',
            $this->config->get('CURRENCY_CODE')
        );

        $lists['use_encoding']        = HTMLHelper::_(
            'redshopselect.booleanlist',
            'use_encoding',
            'class="form-control" ',
            $this->config->get('USE_ENCODING')
        );
        $lists['required_vat_number'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'required_vat_number',
            'class="form-control" ',
            $this->config->get('REQUIRED_VAT_NUMBER')
        );

        $lists['coupons_enable']           = HTMLHelper::_(
            'redshopselect.booleanlist',
            'coupons_enable',
            'class="form-control" ',
            $this->config->get('COUPONS_ENABLE')
        );
        $lists['vouchers_enable']          = HTMLHelper::_(
            'redshopselect.booleanlist',
            'vouchers_enable',
            'class="form-control" ',
            $this->config->get('VOUCHERS_ENABLE')
        );
        $lists['manufacturer_mail_enable'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'manufacturer_mail_enable',
            'class="form-control" ',
            $this->config->get('MANUFACTURER_MAIL_ENABLE')
        );

        $lists['apply_voucher_coupon_already_discount'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'apply_voucher_coupon_already_discount',
            'class="form-control" ',
            $this->config->get(
                'APPLY_VOUCHER_COUPON_ALREADY_DISCOUNT'
            )
        );

        $lists['supplier_mail_enable'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'supplier_mail_enable',
            'class="form-control" ',
            $this->config->get('SUPPLIER_MAIL_ENABLE')
        );

        $lists['create_account_checkbox']   = HTMLHelper::_(
            'redshopselect.booleanlist',
            'create_account_checkbox',
            'class="form-control"',
            $this->config->get('CREATE_ACCOUNT_CHECKBOX')
        );
        $lists['show_email_verification']   = HTMLHelper::_(
            'redshopselect.booleanlist',
            'show_email_verification',
            'class="form-control"',
            $this->config->get('SHOW_EMAIL_VERIFICATION')
        );
        $lists['quantity_text_display']     = HTMLHelper::_(
            'redshopselect.booleanlist',
            'quantity_text_display',
            'class="form-control"',
            $this->config->get('QUANTITY_TEXT_DISPLAY')
        );
        $lists['enable_sef_product_number'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'enable_sef_product_number',
            'class="form-control"',
            $this->config->get('ENABLE_SEF_PRODUCT_NUMBER')
        );

        $lists['enable_sef_number_name'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'enable_sef_number_name',
            'class="form-control"',
            $this->config->get('ENABLE_SEF_NUMBER_NAME'),
            'COM_REDSHOP_NAME',
            'COM_REDSHOP_ID'
        );
        $lists['category_in_sef_url']    = HTMLHelper::_(
            'redshopselect.booleanlist',
            'category_in_sef_url',
            'class="form-control"',
            $this->config->get('CATEGORY_IN_SEF_URL')
        );

        $lists['autogenerated_seo']        = HTMLHelper::_(
            'redshopselect.booleanlist',
            'autogenerated_seo',
            'class="form-control"',
            $this->config->get('AUTOGENERATED_SEO')
        );
        $lists['shop_country']             = HTMLHelper::_(
            'select.genericlist',
            $countries,
            'shop_country',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('SHOP_COUNTRY')
        );
        $lists['default_shipping_country'] = HTMLHelper::_(
            'select.genericlist',
            $countries,
            'default_shipping_country',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('DEFAULT_SHIPPING_COUNTRY')
        );

        // Default_shipping_country
        $lists['show_shipping_in_cart']      = HTMLHelper::_(
            'redshopselect.booleanlist',
            'show_shipping_in_cart',
            'class="form-control"',
            $this->config->get('SHOW_SHIPPING_IN_CART')
        );
        $lists['discount_mail_send']         = HTMLHelper::_(
            'redshopselect.booleanlist',
            'discount_mail_send',
            'class="form-control"',
            $this->config->get('DISCOUNT_MAIL_SEND')
        );
        $lists['special_discount_mail_send'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'special_discount_mail_send',
            'class="form-control"',
            $this->config->get('SPECIAL_DISCOUNT_MAIL_SEND')
        );
        $lists['economic_integration']       = HTMLHelper::_(
            'redshopselect.booleanlist',
            'economic_integration',
            'class="form-control"',
            $this->config->get('ECONOMIC_INTEGRATION')
        );
        $discoupon_percent_or_total          = array(
            HTMLHelper::_('select.option', 0, Text::_('COM_REDSHOP_TOTAL')),
            HTMLHelper::_('select.option', 1, Text::_('COM_REDSHOP_PERCENTAGE'))
        );
        $lists['discoupon_percent_or_total'] = HTMLHelper::_(
            'select.genericlist',
            $discoupon_percent_or_total,
            'discoupon_percent_or_total',
            'class="form-control" size="1"',
            'value',
            'text',
            $this->config->get('DISCOUPON_PERCENT_OR_TOTAL')
        );
        $lists['use_stockroom']              = HTMLHelper::_(
            'redshopselect.booleanlist',
            'use_stockroom',
            'class="form-control" size="1"',
            $this->config->get('USE_STOCKROOM')
        );
        $lists['use_blank_as_infinite']      = HTMLHelper::_(
            'redshopselect.booleanlist',
            'use_blank_as_infinite',
            'class="form-control" size="1"',
            $this->config->get('USE_BLANK_AS_INFINITE')
        );

        $lists['allow_pre_order']         = HTMLHelper::_(
            'redshopselect.booleanlist',
            'allow_pre_order',
            'class="form-control" size="1"',
            $this->config->get('ALLOW_PRE_ORDER')
        );
        $lists['onestep_checkout_enable'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'onestep_checkout_enable',
            'class="form-control" size="1"',
            $this->config->get('ONESTEP_CHECKOUT_ENABLE')
        );
        $lists['ssl_enable_in_checkout']  = HTMLHelper::_(
            'redshopselect.booleanlist',
            'ssl_enable_in_checkout',
            'class="form-control" size="1"',
            $this->config->get('SSL_ENABLE_IN_CHECKOUT')
        );
        $lists['twoway_related_product']  = HTMLHelper::_(
            'redshopselect.booleanlist',
            'twoway_related_product',
            'class="form-control" size="1"',
            $this->config->get('TWOWAY_RELATED_PRODUCT')
        );

        // For child product option
        $chilproduct_data                       = RedshopHelperUtility::getChildProductOption();
        $lists['childproduct_dropdown']         = HTMLHelper::_(
            'select.genericlist',
            $chilproduct_data,
            'childproduct_dropdown',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('CHILDPRODUCT_DROPDOWN')
        );
        $lists['purchase_parent_with_child']    = HTMLHelper::_(
            'redshopselect.booleanlist',
            'purchase_parent_with_child',
            'class="form-control" size="1"',
            $this->config->get('PURCHASE_PARENT_WITH_CHILD')
        );
        $lists['product_hover_image_enable']    = HTMLHelper::_(
            'redshopselect.booleanlist',
            'product_hover_image_enable',
            'class="form-control" size="1"',
            $this->config->get('PRODUCT_HOVER_IMAGE_ENABLE')
        );
        $lists['asterisk_postion']              = HTMLHelper::_(
            'redshopselect.booleanlist',
            'asterisk_postion',
            'class="form-control" size="1"',
            $this->config->get('ASTERISK_POSITION'),
            $yes = Text::_('COM_REDSHOP_RIGHT'),
            $no = Text::_('COM_REDSHOP_LEFT')
        );
        $lists['additional_hover_image_enable'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'additional_hover_image_enable',
            'class="form-control" size="1"',
            $this->config->get('ADDITIONAL_HOVER_IMAGE_ENABLE')
        );
        $lists['ssl_enable_in_backend']         = HTMLHelper::_(
            'redshopselect.booleanlist',
            'ssl_enable_in_backend',
            'class="form-control" size="1"',
            $this->config->get('SSL_ENABLE_IN_BACKEND')
        );
        $lists['use_tax_exempt']                = HTMLHelper::_(
            'redshopselect.booleanlist',
            'use_tax_exempt',
            'class="form-control" size="1"',
            $this->config->get('USE_TAX_EXEMPT')
        );
        $lists['tax_exempt_apply_vat']          = HTMLHelper::_(
            'redshopselect.booleanlist',
            'tax_exempt_apply_vat',
            'class="form-control" size="1"',
            $this->config->get('TAX_EXEMPT_APPLY_VAT')
        );
        $lists['couponinfo']                    = HTMLHelper::_(
            'redshopselect.booleanlist',
            'couponinfo',
            'class="form-control" size="1"',
            $this->config->get('COUPONINFO')
        );
        $lists['my_tags']                       = HTMLHelper::_(
            'redshopselect.booleanlist',
            'my_tags',
            'class="form-control" size="1"',
            $this->config->get('MY_TAGS')
        );
        $lists['my_wishlist']                   = HTMLHelper::_(
            'redshopselect.booleanlist',
            'my_wishlist',
            'class="form-control" size="1"',
            $this->config->get('MY_WISHLIST')
        );
        $lists['compare_products']              = HTMLHelper::_(
            'redshopselect.booleanlist',
            'compare_products',
            'class="form-control" size="1"',
            $this->config->get('COMPARE_PRODUCTS')
        );
        $lists['country_list']                  = HTMLHelper::_(
            'select.genericlist',
            $countries,
            'country_list[]',
            'class="form-control" multiple="multiple" size="5"',
            'value',
            'text',
            $country_list
        );
        $lists['product_detail_is_lightbox']    = HTMLHelper::_(
            'redshopselect.booleanlist',
            'product_detail_is_lightbox',
            'class="form-control" size="1"',
            $this->config->get('PRODUCT_DETAIL_IS_LIGHTBOX')
        );
        $lists['new_customer_selection']        = HTMLHelper::_(
            'redshopselect.booleanlist',
            'new_customer_selection',
            'class="form-control" size="1"',
            $this->config->get('NEW_CUSTOMER_SELECTION')
        );
        $lists['ajax_cart_box']                 = HTMLHelper::_(
            'redshopselect.booleanlist',
            'ajax_cart_box',
            'class="form-control" size="1"',
            $this->config->get('AJAX_CART_BOX')
        );
        $lists['enable_clear_user_info']        = HTMLHelper::_(
            'redshopselect.booleanlist',
            'enable_clear_user_info',
            'class="form-control" size="1"',
            $this->config->get('ENABLE_CLEAR_USER_INFO')
        );
        $lists['is_product_reserve']            = HTMLHelper::_(
            'redshopselect.booleanlist',
            'is_product_reserve',
            'class="form-control" size="1"',
            $this->config->get('IS_PRODUCT_RESERVE')
        );
        $lists['product_is_lightbox']           = HTMLHelper::_(
            'redshopselect.booleanlist',
            'product_is_lightbox',
            'class="form-control" size="1"',
            $this->config->get('PRODUCT_IS_LIGHTBOX')
        );
        $lists['product_addimg_is_lightbox']    = HTMLHelper::_(
            'redshopselect.booleanlist',
            'product_addimg_is_lightbox',
            'class="form-control" size="1"',
            $this->config->get('PRODUCT_ADDIMG_IS_LIGHTBOX')
        );
        $lists['cat_is_lightbox']               = HTMLHelper::_(
            'redshopselect.booleanlist',
            'cat_is_lightbox',
            'class="form-control" size="1"',
            $this->config->get('CAT_IS_LIGHTBOX')
        );
        $lists['default_stockroom']             = HTMLHelper::_(
            'select.genericlist',
            $stockroom,
            'default_stockroom',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('DEFAULT_STOCKROOM')
        );
        $lists['portalshop']                    = HTMLHelper::_(
            'redshopselect.booleanlist',
            'portal_shop',
            'class="form-control" size="1"',
            $this->config->get('PORTAL_SHOP')
        );

        // Default checkout required
        $lists['required_postal_code']  = HTMLHelper::_(
            'redshopselect.booleanlist',
            'required_postal_code',
            'class="form-control" size="1"',
            $this->config->get('REQUIRED_POSTAL_CODE')
        );
        $lists['required_ean_number']   = HTMLHelper::_(
            'redshopselect.booleanlist',
            'required_ean_number',
            'class="form-control" size="1"',
            $this->config->get('REQUIRED_EAN_NUMBER')
        );
        $lists['required_address']      = HTMLHelper::_(
            'redshopselect.booleanlist',
            'required_address',
            'class="form-control" size="1"',
            $this->config->get('REQUIRED_ADDRESS')
        );
        $lists['required_country_code'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'required_country_code',
            'class="form-control" size="1"',
            $this->config->get('REQUIRED_COUNTRY_CODE')
        );
        $lists['required_phone']        = HTMLHelper::_(
            'redshopselect.booleanlist',
            'required_phone',
            'class="form-control" size="1"',
            $this->config->get('REQUIRED_PHONE')
        );

        $imageSizeSwapping                = array();
        $imageSizeSwapping[]              = HTMLHelper::_(
            'select.option',
            0,
            Text::_('COM_REDSHOP_CONFIG_NO_PROPORTIONAL_RESIZED')
        );
        $imageSizeSwapping[]              = HTMLHelper::_(
            'select.option',
            1,
            Text::_('COM_REDSHOP_CONFIG_PROPORTIONAL_RESIZED')
        );
        $imageSizeSwapping[]              = HTMLHelper::_(
            'select.option',
            2,
            Text::_('COM_REDSHOP_CONFIG_PROPORTIONAL_RESIZED_AND_CROP')
        );
        $lists['use_image_size_swapping'] = HTMLHelper::_(
            'select.genericlist',
            $imageSizeSwapping,
            'use_image_size_swapping',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('USE_IMAGE_SIZE_SWAPPING')
        );

        $lists['apply_vat_on_discount']   = HTMLHelper::_(
            'redshopselect.booleanlist',
            'apply_vat_on_discount',
            'class="form-control" size="1"',
            $this->config->get('APPLY_VAT_ON_DISCOUNT'),
            $yes = Text::_('COM_REDSHOP_BEFORE_DISCOUNT'),
            $no = Text::_('COM_REDSHOP_AFTER_DISCOUNT')
        );
        $lists['auto_scroll_wrapper']     = HTMLHelper::_(
            'redshopselect.booleanlist',
            'auto_scroll_wrapper',
            'class="form-control" size="1"',
            $this->config->get('AUTO_SCROLL_WRAPPER')
        );
        $lists['allow_multiple_discount'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'allow_multiple_discount',
            'class="form-control" size="1"',
            $this->config->get('ALLOW_MULTIPLE_DISCOUNT')
        );
        $lists['show_product_detail']     = HTMLHelper::_(
            'redshopselect.booleanlist',
            'show_product_detail',
            'class="form-control" size="1"',
            $this->config->get('SHOW_PRODUCT_DETAIL')
        );
        $lists['compare_template_id']     = HTMLHelper::_(
            'select.genericlist',
            $compare_template,
            'compare_template_id',
            'class="form-control" size="1" ',
            'id',
            'name',
            $this->config->get('COMPARE_TEMPLATE_ID')
        );

        $lists['show_terms_and_conditions'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'show_terms_and_conditions',
            'class="form-control" size="1"',
            $this->config->get('SHOW_TERMS_AND_CONDITIONS'),
            $yes = Text::_('COM_REDSHOP_SHOW_PER_USER'),
            $no = Text::_('COM_REDSHOP_SHOW_PER_ORDER')
        );

        $lists['rating_review_login_required'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'rating_review_login_required',
            'class="form-control" size="1"',
            $this->config->get('RATING_REVIEW_LOGIN_REQUIRED')
        );

        $product_comparison   = array();
        $product_comparison[] = HTMLHelper::_('select.option', '', Text::_('COM_REDSHOP_SELECT'));
        $product_comparison[] = HTMLHelper::_('select.option', 'category', Text::_('COM_REDSHOP_CATEGORY'));
        $product_comparison[] = HTMLHelper::_('select.option', 'global', Text::_('COM_REDSHOP_GLOBAL'));

        $lists['product_comparison_type'] = HTMLHelper::_(
            'select.genericlist',
            $product_comparison,
            'product_comparison_type',
            'class="form-control" size="1"',
            'value',
            'text',
            $this->config->get('PRODUCT_COMPARISON_TYPE')
        );
        $lists['newsletter_enable']       = HTMLHelper::_(
            'redshopselect.booleanlist',
            'newsletter_enable',
            'class="form-control" size="1"',
            $this->config->get('NEWSLETTER_ENABLE')
        );
        $lists['newsletter_confirmation'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'newsletter_confirmation',
            'class="form-control" size="1"',
            $this->config->get('NEWSLETTER_CONFIRMATION')
        );

        $lists['watermark_category_image']           = HTMLHelper::_(
            'redshopselect.booleanlist',
            'watermark_category_image',
            'class="form-control" size="1"',
            $this->config->get('WATERMARK_CATEGORY_IMAGE')
        );
        $lists['watermark_category_thumb_image']     = HTMLHelper::_(
            'redshopselect.booleanlist',
            'watermark_category_thumb_image',
            'class="form-control" size="1"',
            $this->config->get('WATERMARK_CATEGORY_THUMB_IMAGE')
        );
        $lists['watermark_product_image']            = HTMLHelper::_(
            'redshopselect.booleanlist',
            'watermark_product_image',
            'class="form-control" size="1"',
            $this->config->get('WATERMARK_PRODUCT_IMAGE')
        );
        $lists['watermark_product_thumb_image']      = HTMLHelper::_(
            'redshopselect.booleanlist',
            'watermark_product_thumb_image',
            'class="form-control" size="1"',
            $this->config->get('WATERMARK_PRODUCT_THUMB_IMAGE')
        );
        $lists['watermark_product_additional_image'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'watermark_product_additional_image',
            'class="form-control" size="1"',
            $this->config->get('WATERMARK_PRODUCT_ADDITIONAL_IMAGE')
        );
        $lists['watermark_cart_thumb_image']         = HTMLHelper::_(
            'redshopselect.booleanlist',
            'watermark_cart_thumb_image',
            'class="form-control" size="1"',
            $this->config->get('WATERMARK_CART_THUMB_IMAGE')
        );
        $lists['watermark_giftcart_image']           = HTMLHelper::_(
            'redshopselect.booleanlist',
            'watermark_giftcart_image',
            'class="form-control" size="1"',
            $this->config->get('WATERMARK_GIFTCART_IMAGE')
        );
        $lists['watermark_giftcart_thumb_image']     = HTMLHelper::_(
            'redshopselect.booleanlist',
            'watermark_giftcart_thumb_image',
            'class="form-control" size="1"',
            $this->config->get('WATERMARK_GIFTCART_THUMB_IMAGE')
        );
        $lists['watermark_manufacturer_thumb_image'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'watermark_manufacturer_thumb_image',
            'class="form-control" size="1"',
            $this->config->get('WATERMARK_MANUFACTURER_THUMB_IMAGE')
        );
        $lists['watermark_manufacturer_image']       = HTMLHelper::_(
            'redshopselect.booleanlist',
            'watermark_manufacturer_image',
            'class="form-control" size="1"',
            $this->config->get('WATERMARK_MANUFACTURER_IMAGE')
        );
        $lists['clickatell_enable']                  = HTMLHelper::_(
            'redshopselect.booleanlist',
            'clickatell_enable',
            'class="form-control" size="1"',
            $this->config->get('CLICKATELL_ENABLE')
        );
        $lists['quotation_mode']                     = HTMLHelper::_(
            'redshopselect.booleanlist',
            'default_quotation_mode',
            'class="form-control" size="1"',
            $this->config->get('DEFAULT_QUOTATION_MODE_PRE'),
            $yes = Text::_('COM_REDSHOP_ON'),
            $no = Text::_('COM_REDSHOP_OFF')
        );
        $lists['wanttoshowattributeimage']   = HTMLHelper::_(
            'redshopselect.booleanlist',
            'wanttoshowattributeimage',
            'class="form-control" size="1"',
            $this->config->get('WANT_TO_SHOW_ATTRIBUTE_IMAGE_INCART')
        );
        $lists['show_quotation_price']       = HTMLHelper::_(
            'redshopselect.booleanlist',
            'show_quotation_price',
            'class="form-control" size="1"',
            $this->config->get('SHOW_QUOTATION_PRICE')
        );
        $lists['display_out_of_stock_after'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'display_out_of_stock_after',
            'class="form-control" size="1"',
            $this->config->get('DISPLAY_OUT_OF_STOCK_AFTER')
        );

        $lists['display_out_of_stock_attribute_data'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'display_out_of_stock_attribute_data',
            'class="form-control"',
            $this->config->get('DISPLAY_OUT_OF_STOCK_ATTRIBUTE_DATA')
        );

        $lists['category_tree_in_sef_url'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'category_tree_in_sef_url',
            'class="form-control"',
            $this->config->get('CATEGORY_TREE_IN_SEF_URL')
        );
        $lists['statistics_enable']        = HTMLHelper::_(
            'redshopselect.booleanlist',
            'statistics_enable',
            'class="form-control" size="1"',
            $this->config->get('STATISTICS_ENABLE')
        );
        $orderstatus                       = $model->getOrderstatus();
        $tmp                               = array();
        $tmp[]                             = HTMLHelper::_('select.option', 0, Text::_('COM_REDSHOP_SELECT'));
        $orderstatus                       = array_merge($tmp, $orderstatus);
        $lists['clickatell_order_status']  = HTMLHelper::_(
            'select.genericlist',
            $orderstatus,
            'clickatell_order_status',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('CLICKATELL_ORDER_STATUS')
        );

        $menuitem           = array();
        $menuitem[0]        = new stdClass;
        $menuitem[0]->value = 0;
        $menuitem[0]->text  = Text::_('COM_REDSHOP_SELECT');
        $q                  = "SELECT m.id,m.title AS name,mt.title FROM #__menu AS m "
            . "LEFT JOIN #__menu_types AS mt ON mt.menutype=m.menutype "
            . "WHERE m.published=1 "
            . "ORDER BY m.menutype";
        $db->setQuery($q);
        $menuitemlist = $db->loadObjectList();

        for ($i = 0, $in = count($menuitemlist); $i < $in; $i++) {
            $menuitem[$i + 1]        = new stdClass;
            $menuitem[$i + 1]->value = $menuitemlist[$i]->id;
            $menuitem[$i + 1]->text  = $menuitemlist[$i]->name;
        }

        $lists['url_after_portal_login']  = HTMLHelper::_(
            'select.genericlist',
            $menuitem,
            'portal_login_itemid',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('PORTAL_LOGIN_ITEMID')
        );
        $lists['url_after_portal_logout'] = HTMLHelper::_(
            'select.genericlist',
            $menuitem,
            'portal_logout_itemid',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('PORTAL_LOGOUT_ITEMID')
        );

        $default_vat_group = $model->getVatGroup();
        $tmp               = array();
        $tmp[]             = HTMLHelper::_('select.option', 0, Text::_('COM_REDSHOP_SELECT'));
        $default_vat_group = array_merge($tmp, $default_vat_group);

        $tmp                 = array();
        $tmp[]               = HTMLHelper::_('select.option', '', Text::_('COM_REDSHOP_SELECT'));
        $default_vat_country = array_merge($tmp, $countries);

        $default_customer_register_type          = array();
        $default_customer_register_type[]        = HTMLHelper::_('select.option', '0', Text::_('COM_REDSHOP_SELECT'));
        $default_customer_register_type[]        = HTMLHelper::_('select.option', '1', Text::_('COM_REDSHOP_PRIVATE'));
        $default_customer_register_type[]        = HTMLHelper::_('select.option', '2', Text::_('COM_REDSHOP_COMPANY'));
        $lists['default_customer_register_type'] = HTMLHelper::_(
            'select.genericlist',
            $default_customer_register_type,
            'default_customer_register_type',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('DEFAULT_CUSTOMER_REGISTER_TYPE')
        );

        $addtocart_behaviour          = array();
        $addtocart_behaviour[]        = HTMLHelper::_('select.option', '0', Text::_('COM_REDSHOP_SELECT'));
        $addtocart_behaviour[]        = HTMLHelper::_('select.option', '1', Text::_('COM_REDSHOP_DIRECT_TO_CART'));
        $addtocart_behaviour[]        = HTMLHelper::_('select.option', '2', Text::_('COM_REDSHOP_STAY_ON_CURRENT_VIEW'));
        $lists['addtocart_behaviour'] = HTMLHelper::_(
            'select.genericlist',
            $addtocart_behaviour,
            'addtocart_behaviour',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('ADDTOCART_BEHAVIOUR')
        );

        $allow_customer_register_type          = array();
        $allow_customer_register_type[]        = HTMLHelper::_('select.option', '0', Text::_('COM_REDSHOP_BOTH'));
        $allow_customer_register_type[]        = HTMLHelper::_('select.option', '1', Text::_('COM_REDSHOP_PRIVATE'));
        $allow_customer_register_type[]        = HTMLHelper::_('select.option', '2', Text::_('COM_REDSHOP_COMPANY'));
        $lists['allow_customer_register_type'] = HTMLHelper::_(
            'select.genericlist',
            $allow_customer_register_type,
            'allow_customer_register_type',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('ALLOW_CUSTOMER_REGISTER_TYPE')
        );

        // Optional shipping address select box
        $lists['optional_shipping_address'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'optional_shipping_address',
            'class="form-control" ',
            $this->config->get('OPTIONAL_SHIPPING_ADDRESS')
        );
        $lists['shipping_method_enable']    = HTMLHelper::_(
            'redshopselect.booleanlist',
            'shipping_method_enable',
            'class="form-control" ',
            $this->config->get('SHIPPING_METHOD_ENABLE')
        );

        $lists['default_vat_group'] = HTMLHelper::_(
            'select.genericlist',
            $default_vat_group,
            'default_vat_group',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('DEFAULT_VAT_GROUP')
        );

        $vat_based_on          = array();
        $vat_based_on[]        = HTMLHelper::_('select.option', '0', Text::_('COM_REDSHOP_WEBSHOP_MODE'));
        $vat_based_on[]        = HTMLHelper::_('select.option', '1', Text::_('COM_REDSHOP_CUSTOMER_MODE'));
        $vat_based_on[]        = HTMLHelper::_('select.option', '2', Text::_('COM_REDSHOP_EU_MODE'));
        $lists['vat_based_on'] = HTMLHelper::_(
            'select.genericlist',
            $vat_based_on,
            'vat_based_on',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('VAT_BASED_ON')
        );

        $lists['default_vat_country'] = HTMLHelper::_(
            'select.genericlist',
            $default_vat_country,
            'default_vat_country',
            'class="form-control" onchange="changeStateList();"',
            'value',
            'text',
            $this->config->get('DEFAULT_VAT_COUNTRY')
        );

        $country_list_name     = 'default_vat_country';
        $state_list_name       = 'default_vat_state';
        $selected_country_code = $this->config->get('DEFAULT_VAT_COUNTRY');
        $selected_state_code   = $this->config->get('DEFAULT_VAT_STATE');

        if (empty($selected_state_code)) {
            $selected_state_code = "originalPos";
        } else {
            $selected_state_code = "'" . $selected_state_code . "'";
        }

        $db->setQuery(
            "SELECT c.id, c.country_3_code, s.state_name, s.state_2_code
						FROM #__redshop_country c
						LEFT JOIN #__redshop_state s
						ON c.id=s.country_id OR s.country_id IS NULL
						ORDER BY c.id, s.state_name"
        );
        $states = $db->loadObjectList();

        // Build the State lists for each Country
        $script       = "<script language=\"javascript\" type=\"text/javascript\">//<![CDATA[\n";
        $script .= "<!--\n";
        $script .= "var originalOrder = '1';\n";
        $script .= "var originalPos = '$selected_country_code';\n";
        $script .= "var states = new Array();	// array in the format [key,value,text]\n";
        $i            = 0;
        $prev_country = '';

        for ($j = 0, $jn = count($states); $j < $jn; $j++) {
            $state          = $states[$j];
            $country_3_code = $state->country_3_code;

            if ($state->state_name) {
                if ($prev_country != $country_3_code) {
                    $script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','',' -= " . Text::_(
                        "COM_REDSHOP_SELECT"
                    ) . " =-' );\n";
                }

                $prev_country = $country_3_code;

                // Array in the format [key,value,text]
                $script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','"
                    . $state->state_2_code . "','"
                    . addslashes(Text::_($state->state_name))
                    . "' );\n";
            } else {
                $script .= "states[" . $i++ . "] = new Array( '" . $country_3_code . "','','" . Text::_(
                    "COM_REDSHOP_NONE"
                ) . "' );\n";
            }
        }

        $script .= "window.writeDynaList = function ( selectParams, source, key, orig_key, orig_val, element ) {
		var select = document.createElement('select');
		var params = selectParams.split(' ');

		for (var l = 0; l < params.length; l++) {
			var par = params[l].split('=');

			// make sure the attribute / content can not be used for scripting
			if (par[0].trim().substr(0, 2).toLowerCase() === \"on\"
				|| par[0].trim().toLowerCase() === \"href\") {
				continue;
			}

			select.setAttribute(par[0], par[1].replace(/\\\"/g, ''));
		}

		var hasSelection = key == orig_key, i, selected, item;

		for (i = 0; i < source.length; i++) {
			item = source[i];

			if (item[0] != key) { continue; }

			selected = hasSelection ? orig_val == item[1] : i === 0;

			var el = document.createElement('option');
			el.setAttribute('value', item[1]);
			el.innerText = item[2];

			if (selected) {
				el.setAttribute('selected', 'selected');
			}

			select.appendChild(el);
		}

		if (element) {
			element.appendChild(select);
		} else {
			document.body.appendChild(select);
		}
	};
	function changeStateList()
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
				 	var element = document.querySelector('#default-vat-state-wrapper > .col-md-8');
				 	writeDynaList( 'class=\"form-control\" name=\"default_vat_state\" size=\"1\" id=\"default_vat_state\"',
				 	states, originalPos, originalPos, $selected_state_code, element);
					//-->
					//]]></script>";
        $lists['default_vat_state'] = $script;

        $shopper_Group_private = $model->getShopperGroupPrivate();

        $tmp   = array();
        $tmp[] = HTMLHelper::_('select.option', 0, Text::_('COM_REDSHOP_SELECT'));
        $tmp   = array_merge($tmp, $shopper_Group_private);

        $lists['shopper_group_default_private'] = HTMLHelper::_(
            'select.genericlist',
            $tmp,
            'shopper_group_default_private',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('SHOPPER_GROUP_DEFAULT_PRIVATE')
        );

        $shopper_Group_company                  = $model->getShopperGroupCompany();
        $tmp                                    = array();
        $tmp[]                                  = HTMLHelper::_('select.option', 0, Text::_('COM_REDSHOP_SELECT'));
        $tmp                                    = array_merge($tmp, $shopper_Group_company);
        $lists['shopper_group_default_company'] = HTMLHelper::_(
            'select.genericlist',
            $tmp,
            'shopper_group_default_company',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('SHOPPER_GROUP_DEFAULT_COMPANY')
        );

        $tmp                                         = array();
        $tmp[]                                       = HTMLHelper::_('select.option', 0, Text::_('COM_REDSHOP_SELECT'));
        $tmp                                         = array_merge(
            $tmp,
            $shopper_Group_private,
            $shopper_Group_company ?: [] // Fix scrutinizer.
        );
        $lists['shopper_group_default_unregistered'] = HTMLHelper::_(
            'select.genericlist',
            $tmp,
            'shopper_group_default_unregistered',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('SHOPPER_GROUP_DEFAULT_UNREGISTERED')
        );

        $register_methods         = array();
        $register_methods[]       = HTMLHelper::_(
            'select.option',
            '0',
            Text::_('COM_REDSHOP_REGISTER_WITH_ACCOUNT_CREATION')
        );
        $register_methods[]       = HTMLHelper::_(
            'select.option',
            '1',
            Text::_('COM_REDSHOP_REGISTER_WITHOUT_ACCOUNT_CREATION')
        );
        $register_methods[]       = HTMLHelper::_('select.option', '2', Text::_('COM_REDSHOP_REGISTER_ACCOUNT_OPTIONAL'));
        $register_methods[]       = HTMLHelper::_('select.option', '3', Text::_('COM_REDSHOP_REGISTER_ACCOUNT_SILENT'));
        $lists['register_method'] = HTMLHelper::_(
            'select.genericlist',
            $register_methods,
            'register_method',
            'class="form-control" id="register_method"',
            'value',
            'text',
            $this->config->get('REGISTER_METHOD')
        );

        $lists['product_template']              = HTMLHelper::_(
            'select.genericlist',
            $product_template,
            'default_product_template',
            'class="form-control" size="1" ',
            'id',
            'name',
            $this->config->get('PRODUCT_TEMPLATE')
        );
        $lists['ajax_detail_template']          = HTMLHelper::_(
            'select.genericlist',
            $ajax_detail_template,
            'default_ajax_detailbox_template',
            'class="form-control" size="1" ',
            'id',
            'name',
            $this->config->get('DEFAULT_AJAX_DETAILBOX_TEMPLATE')
        );
        $lists['category_template']             = HTMLHelper::_(
            'select.genericlist',
            $category_template,
            'default_category_template',
            'class="form-control" size="1" ',
            'id',
            'name',
            $this->config->get('CATEGORY_TEMPLATE')
        );
        $lists['default_categorylist_template'] = HTMLHelper::_(
            'select.genericlist',
            $categorylist_template,
            'default_categorylist_template',
            'class="form-control" size="1" ',
            'id',
            'name',
            $this->config->get('DEFAULT_CATEGORYLIST_TEMPLATE')
        );
        $lists['manufacturer_template']         = HTMLHelper::_(
            'select.genericlist',
            $manufacturer_template,
            'default_manufacturer_template',
            'class="form-control" size="1" ',
            'id',
            'name',
            $this->config->get('MANUFACTURER_TEMPLATE')
        );
        $lists['show_price']                    = HTMLHelper::_(
            'redshopselect.booleanlist',
            'show_price',
            'class="form-control" size="1"',
            $this->config->get('SHOW_PRICE_PRE')
        );

        $lists['use_as_catalog']                      = HTMLHelper::_(
            'redshopselect.booleanlist',
            'use_as_catalog',
            'class="form-control" size="1"',
            $this->config->get('PRE_USE_AS_CATALOG', 0)
        );
        $lists['show_tax_exempt_infront']             = HTMLHelper::_(
            'redshopselect.booleanlist',
            'show_tax_exempt_infront',
            'class="form-control" size="1"',
            $this->config->get('SHOW_TAX_EXEMPT_INFRONT')
        );
        $lists['individual_add_to_cart_enable']       = HTMLHelper::_(
            'redshopselect.booleanlist',
            'individual_add_to_cart_enable',
            'class="form-control" size="1"',
            $this->config->get('INDIVIDUAL_ADD_TO_CART_ENABLE'),
            Text::_('COM_REDSHOP_INDIVIDUAL_ADD_TO_CART_PER_PROPERTY'),
            Text::_('COM_REDSHOP_ADD_TO_CART_PER_PRODUCT')
        );
        $lists['enable_performance_mode']             = HTMLHelper::_(
            'redshopselect.booleanlist',
            'enable_performance_mode',
            'class="form-control" size="1"',
            $this->config->get('ENABLE_PERFORMANCE_MODE')
        );
        $lists['accessory_as_product_in_cart_enable'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'accessory_as_product_in_cart_enable',
            'class="form-control" size="1"',
            $this->config->get('ACCESSORY_AS_PRODUCT_IN_CART_ENABLE')
        );
        $lists['use_product_outofstock_image']        = HTMLHelper::_(
            'redshopselect.booleanlist',
            'use_product_outofstock_image',
            'class="form-control" size="1"',
            $this->config->get('USE_PRODUCT_OUTOFSTOCK_IMAGE')
        );
        $lists['enable_address_detail_in_shipping']   = HTMLHelper::_(
            'redshopselect.booleanlist',
            'enable_address_detail_in_shipping',
            'class="form-control" size="1"',
            $this->config->get('ENABLE_ADDRESS_DETAIL_IN_SHIPPING')
        );

        $lists['send_mail_to_customer'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'send_mail_to_customer',
            'class="form-control" size="1"',
            $this->config->get('SEND_MAIL_TO_CUSTOMER')
        );

        $bookinvoice                     = array();
        $bookinvoice[]                   = HTMLHelper::_('select.option', '0', Text::_('COM_REDSHOP_DIRECTLY_BOOK'));
        $bookinvoice[]                   = HTMLHelper::_('select.option', '1', Text::_('COM_REDSHOP_MANUALLY_BOOK'));
        $bookinvoice[]                   = HTMLHelper::_('select.option', '2', Text::_('COM_REDSHOP_BOOK_ON_ORDER_STATUS'));
        $lists['economic_invoice_draft'] = HTMLHelper::_(
            'select.genericlist',
            $bookinvoice,
            'economic_invoice_draft',
            'class="form-control"',
            'value',
            'text',
            $this->config->get('ECONOMIC_INVOICE_DRAFT')
        );

        $bookInvoiceNumbers                    = array(
            HTMLHelper::_('select.option', '0', Text::_('COM_REDSHOP_SAME_AS_ORDER_NUMBER')),
            HTMLHelper::_(
                'select.option',
                '1',
                Text::_('COM_REDSHOP_SEQUENTIALLY_IN_ECONOMIC_NO_MATCH_UP_WITH_ORDER_NUMBER')
            )
        );
        $lists['economic_book_invoice_number'] = HTMLHelper::_(
            'select.genericlist',
            $bookInvoiceNumbers,
            'economic_book_invoice_number',
            'class="form-control"',
            'value',
            'text',
            $this->config->get('ECONOMIC_BOOK_INVOICE_NUMBER')
        );

        // NEXT-PREVIOUS LINK
        $link_type                   = array();
        $link_type[]                 = HTMLHelper::_('select.option', '0', Text::_('COM_REDSHOP_DEFAULT_LINK'));
        $link_type[]                 = HTMLHelper::_('select.option', '1', Text::_('COM_REDSHOP_CUSTOM_LINK'));
        $link_type[]                 = HTMLHelper::_('select.option', '2', Text::_('COM_REDSHOP_IMAGE_LINK'));
        $lists['next_previous_link'] = HTMLHelper::_(
            'select.genericlist',
            $link_type,
            'next_previous_link',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('DEFAULT_LINK_FIND')
        );

        $order_data                                            = RedshopHelperUtility::getOrderByList();
        $lists['default_product_ordering_method']              = HTMLHelper::_(
            'select.genericlist',
            $order_data,
            'default_product_ordering_method',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('DEFAULT_PRODUCT_ORDERING_METHOD')
        );
        $lists['default_manufacturer_product_ordering_method'] = HTMLHelper::_(
            'select.genericlist',
            $order_data,
            'default_manufacturer_product_ordering_method',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('DEFAULT_MANUFACTURER_PRODUCT_ORDERING_METHOD')
        );

        $order_data                                 = RedshopHelperUtility::getRelatedOrderByList();
        $lists['default_related_ordering_method']   = HTMLHelper::_(
            'select.genericlist',
            $order_data,
            'default_related_ordering_method',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('DEFAULT_RELATED_ORDERING_METHOD')
        );
        $order_data                                 = RedshopHelperUtility::getAccessoryOrderByList();
        $lists['default_accessory_ordering_method'] = HTMLHelper::_(
            'select.genericlist',
            $order_data,
            'default_accessory_ordering_method',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('DEFAULT_ACCESSORY_ORDERING_METHOD')
        );

        $lists['shipping_after'] = RedshopHelperExtrafields::rsBooleanList(
            'shipping_after',
            'class="form-control"',
            $this->config->get('SHIPPING_AFTER', 'total'),
            Text::_('COM_REDSHOP_TOTAL'),
            Text::_('COM_REDSHOP_SUBTOTAL_LBL'),
            false,
            'total',
            'subtotal'
        );

        $lists['payment_calculation_on'] = RedshopHelperExtrafields::rsBooleanList(
            'payment_calculation_on',
            'class="form-control"',
            $this->config->get('PAYMENT_CALCULATION_ON', 'total'),
            Text::_('COM_REDSHOP_TOTAL'),
            Text::_('COM_REDSHOP_SUBTOTAL_LBL'),
            false,
            'total',
            'subtotal'
        );

        $lists['calculate_vat_on'] = RedshopHelperExtrafields::rsBooleanList(
            'calculate_vat_on',
            'class="form-control"',
            $this->config->get('CALCULATE_VAT_ON', 'BT'),
            Text::_('COM_REDSHOP_BILLING_ADDRESS_LBL'),
            Text::_('COM_REDSHOP_SHIPPING_ADDRESS_LBL'),
            false,
            'BT',
            'ST'
        );

        $order_data           = array();
        $order_data[0]        = new stdClass;
        $order_data[0]->value = "c.name ASC";
        $order_data[0]->text  = Text::_('COM_REDSHOP_CATEGORY_NAME');

        $order_data[1]        = new stdClass;
        $order_data[1]->value = "c.id DESC";
        $order_data[1]->text  = Text::_('COM_REDSHOP_NEWEST');

        $order_data[2]        = new stdClass;
        $order_data[2]->value = "c.ordering ASC";
        $order_data[2]->text  = Text::_('COM_REDSHOP_ORDERING');

        $lists['default_category_ordering_method'] = HTMLHelper::_(
            'select.genericlist',
            $order_data,
            'default_category_ordering_method',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('DEFAULT_CATEGORY_ORDERING_METHOD')
        );

        $order_data                                    = RedshopHelperUtility::getManufacturerOrderByList();
        $lists['default_manufacturer_ordering_method'] = HTMLHelper::_(
            'select.genericlist',
            $order_data,
            'default_manufacturer_ordering_method',
            'class="form-control" size="1" ',
            'value',
            'text',
            $this->config->get('DEFAULT_MANUFACTURER_ORDERING_METHOD')
        );

        $symbol_position           = array();
        $symbol_position[0]        = new stdClass;
        $symbol_position[0]->value = " ";
        $symbol_position[0]->text  = Text::_('COM_REDSHOP_SELECT');

        $symbol_position[1]        = new stdClass;
        $symbol_position[1]->value = "front";
        $symbol_position[1]->text  = Text::_('COM_REDSHOP_FRONT');

        $symbol_position[2]        = new stdClass;
        $symbol_position[2]->value = "behind";
        $symbol_position[2]->text  = Text::_('COM_REDSHOP_BEHIND');

        $symbol_position[3]        = new stdClass;
        $symbol_position[3]->value = "none";
        $symbol_position[3]->text  = Text::_('COM_REDSHOP_NONE');

        $lists['currency_symbol_position'] = HTMLHelper::_(
            'select.genericlist',
            $symbol_position,
            'currency_symbol_position',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('CURRENCY_SYMBOL_POSITION')
        );

        $optionsDateformat  = RedshopHelperDatetime::getDateFormat();
        $selectedDateformat = $this->config->get('DEFAULT_DATEFORMAT');

        if ((string) $selectedDateformat === '0') {
            $selectedDateformat = 'Y-m-d';
        }

        $lists['default_dateformat'] = HTMLHelper::_(
            'select.genericlist',
            $optionsDateformat,
            'default_dateformat',
            'class="form-control" ',
            'value',
            'text',
            $selectedDateformat
        );

        $lists['discount_enable']         = HTMLHelper::_(
            'redshopselect.booleanlist',
            'discount_enable',
            'class="form-control" ',
            $this->config->get('DISCOUNT_ENABLE')
        );
        $lists['invoice_mail_enable']     = HTMLHelper::_(
            'redshopselect.booleanlist',
            'invoice_mail_enable',
            'class="form-control"',
            $this->config->get('INVOICE_MAIL_ENABLE')
        );
        $lists['wishlist_login_required'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'wishlist_login_required',
            'class="form-control"',
            $this->config->get('WISHLIST_LOGIN_REQUIRED')
        );
        $lists['wishlist_list']           = HTMLHelper::_(
            'redshopselect.booleanlist',
            'wishlist_list',
            'class="form-control"',
            $this->config->get('WISHLIST_LIST')
        );

        // Product general
        $lists['product_default_category']   = HTMLHelper::_(
            'redshopselect.booleanlist',
            'product_default_category',
            'class="form-control" size="1"',
            $this->config->get('PRODUCT_DEFAULT_CATEGORY')
        );
        $lists['show_discontinued_products'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'show_discontinued_products',
            'class="form-control" size="1"',
            $this->config->get('SHOW_DISCONTINUED_PRODUCTS')
        );

        $invoice_mail_send_option           = array();
        $invoice_mail_send_option[0]        = new stdClass;
        $invoice_mail_send_option[0]->value = 0;
        $invoice_mail_send_option[0]->text  = Text::_('COM_REDSHOP_NONE');

        $invoice_mail_send_option[1]        = new stdClass;
        $invoice_mail_send_option[1]->value = 1;
        $invoice_mail_send_option[1]->text  = Text::_('COM_REDSHOP_ADMINISTRATOR');

        $invoice_mail_send_option[2]        = new stdClass;
        $invoice_mail_send_option[2]->value = 2;
        $invoice_mail_send_option[2]->text  = Text::_('COM_REDSHOP_CUSTOMER');

        $invoice_mail_send_option[3]        = new stdClass;
        $invoice_mail_send_option[3]->value = 3;
        $invoice_mail_send_option[3]->text  = Text::_('COM_REDSHOP_BOTH');

        $lists['invoice_mail_send_option'] = HTMLHelper::_(
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
        $order_mail_after[0]->text  = Text::_('COM_REDSHOP_ORDER_MAIL_BEFORE_PAYMENT');

        $order_mail_after[1]        = new stdClass;
        $order_mail_after[1]->value = 1;
        $order_mail_after[1]->text  = Text::_('COM_REDSHOP_ORDER_MAIL_AFTER_PAYMENT_BUT_SEND_BEFORE_ADMINISTRATOR');

        $order_mail_after[2]        = new stdClass;
        $order_mail_after[2]->value = 2;
        $order_mail_after[2]->text  = Text::_('COM_REDSHOP_ORDER_MAIL_AFTER_PAYMENT');

        $lists['order_mail_after'] = HTMLHelper::_(
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
        $discount_type[0]->text  = Text::_('COM_REDSHOP_SELECT');

        $discount_type[1]        = new stdClass;
        $discount_type[1]->value = 1;
        $discount_type[1]->text  = Text::_('COM_REDSHOP_DISCOUNT_OR_VOUCHER_OR_COUPON');

        $discount_type[2]        = new stdClass;
        $discount_type[2]->value = 2;
        $discount_type[2]->text  = Text::_('COM_REDSHOP_DISCOUNT_VOUCHER_OR_COUPON');

        $discount_type[3]        = new stdClass;
        $discount_type[3]->value = 3;
        $discount_type[3]->text  = Text::_('COM_REDSHOP_DISCOUNT_VOUCHER_COUPON');

        $discount_type[4]        = new stdClass;
        $discount_type[4]->value = 4;
        $discount_type[4]->text  = Text::_('COM_REDSHOP_DISCOUNT_VOUCHER_COUPON_MULTIPLE');

        $lists['discount_type'] = HTMLHelper::_(
            'select.genericlist',
            $discount_type,
            'discount_type',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('DISCOUNT_TYPE')
        );

        /*
         * Measurement select boxes
         */
        $option           = array();
        $option[0]        = new stdClass;
        $option[0]->value = 0;
        $option[0]->text  = Text::_('COM_REDSHOP_SELECT');

        $option[1]        = new stdClass;
        $option[1]->value = 'mm';
        $option[1]->text  = Text::_('COM_REDSHOP_MILLIMETER');

        $option[2]        = new stdClass;
        $option[2]->value = 'cm';
        $option[2]->text  = Text::_('COM_REDSHOP_CENTIMETERS');

        $option[3]        = new stdClass;
        $option[3]->value = 'inch';
        $option[3]->text  = Text::_('COM_REDSHOP_INCHES');

        $option[4]        = new stdClass;
        $option[4]->value = 'feet';
        $option[4]->text  = Text::_('COM_REDSHOP_FEET');

        $option[5]        = new stdClass;
        $option[5]->value = 'm';
        $option[5]->text  = Text::_('COM_REDSHOP_METER');

        $option[5]        = new stdClass;
        $option[5]->value = 'l';
        $option[5]->text  = Text::_('COM_REDSHOP_LITER');

        $option[5]        = new stdClass;
        $option[5]->value = 'ml';
        $option[5]->text  = Text::_('COM_REDSHOP_MILLILITER');

        $lists['default_volume_unit'] = HTMLHelper::_(
            'select.genericlist',
            $option,
            'default_volume_unit',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('DEFAULT_VOLUME_UNIT')
        );
        unset($option);

        $option           = array();
        $option[0]        = new stdClass;
        $option[0]->value = 0;
        $option[0]->text  = Text::_('COM_REDSHOP_SELECT');

        $option[1]        = new stdClass;
        $option[1]->value = 'gram';
        $option[1]->text  = Text::_('COM_REDSHOP_GRAM');

        $option[2]        = new stdClass;
        $option[2]->value = 'pounds';
        $option[2]->text  = Text::_('COM_REDSHOP_POUNDS');

        $option[3]        = new stdClass;
        $option[3]->value = 'kg';
        $option[3]->text  = Text::_('COM_REDSHOP_KG');

        $lists['default_weight_unit'] = HTMLHelper::_(
            'select.genericlist',
            $option,
            'default_weight_unit',
            'class="form-control" ',
            'value',
            'text',
            $this->config->get('DEFAULT_WEIGHT_UNIT')
        );
        unset($option);

        $lists['postdk_integration']         = HTMLHelper::_(
            'redshopselect.booleanlist',
            'postdk_integration',
            'class="form-control" size="1"',
            $this->config->get('POSTDK_INTEGRATION')
        );
        $lists['send_catalog_reminder_mail'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'send_catalog_reminder_mail',
            'class="form-control" size="1"',
            $this->config->get('SEND_CATALOG_REMINDER_MAIL')
        );

        $lists['load_redshop_style'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'load_redshop_style',
            'class="form-control" size="1"',
            $this->config->get('LOAD_REDSHOP_STYLE')
        );

        $lists['enable_stockroom_notification'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'enable_stockroom_notification',
            'class="form-control" size="1"',
            $this->config->get('ENABLE_STOCKROOM_NOTIFICATION')
        );

        $lists['inline_editing'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'inline_editing',
            'class="form-control" size="1"',
            $this->config->get('INLINE_EDITING')
        );

        $lists['currency_libraries'] = HTMLHelper::_(
            'redshopselect.booleanlist',
            'currency_libraries',
            'class="form-control" size="1"',
            $this->config->get('CURRENCY_LIBRARIES'),
            $yes = Text::_('COM_REDSHOP_CURRENCY_LIBRARIES_LAYER'),
            $no = Text::_('COM_REDSHOP_CURRENCY_LIBRARIES_ECB')
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
        if (isset($_SERVER['SERVER_SOFTWARE'])) {
            return $_SERVER['SERVER_SOFTWARE'];
        } elseif ($sf = getenv('SERVER_SOFTWARE')) {
            return $sf;
        } else {
            return Text::_('COM_REDSHOP_N_A');
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
                '#performance',
                'COM_REDSHOP_PERFORMANCE',
                ($selectedTabPosition == 'performance') ? true : false,
                'performance'
            )->addItem(
                '#redshopabout',
                'COM_REDSHOP_ABOUT',
                ($selectedTabPosition == 'redshopabout') ? true : false,
                'redshopabout'
            );

        return $tabMenu;
    }
}