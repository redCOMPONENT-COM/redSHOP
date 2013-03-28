<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$doc    = JFactory::getDocument();
$tmpl   = JRequest::getCmd('tmpl');
$view   = JRequest::getCmd('view');
$layout = JRequest::getCmd('layout');
$for    = JRequest::getWord("for", false);

if ($tmpl == 'component' && !$for)
{
	$doc->addStyleDeclaration('html { overflow:scroll; }');
}

// 	Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/configuration.php';

$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

require_once JPATH_SITE . '/components/com_redshop/helpers/currency.php';
$session = JFactory::getSession('product_currency');

$post   = JRequest::get('POST');
$Itemid = JRequest::getVar('Itemid');
require_once JPATH_SITE . '/components/com_redshop/helpers/helper.php';
$redhelper   = new redhelper ();
$cart_Itemid = $redhelper->getCartItemid($Itemid);

if ($cart_Itemid == "" || $cart_Itemid == 0)
{
	$cItemid   = $redhelper->getItemid();
	$tmpItemid = $cItemid;
}
else
{
	$tmpItemid = $cart_Itemid;
}

if (isset($post['product_currency']))
	$session->set('product_currency', $post['product_currency']);

$currency_symbol  = REDCURRENCY_SYMBOL;
$currency_convert = 1;

$script = "
		window.site_url = '" . JURI::root() . "';
		window.AJAX_CART_BOX = '" . AJAX_CART_BOX . "';
		window.REDSHOP_VIEW = '" . $view . "';
		window.REDSHOP_LAYOUT = '" . $layout . "';
		window.DEFAULT_CUSTOMER_REGISTER_TYPE = '" . DEFAULT_CUSTOMER_REGISTER_TYPE . "';
		window.AJAX_CART_URL = '" . JRoute::_('index.php?option=com_redshop&view=cart&Itemid=' . $tmpItemid, false) . "';
		window.REDCURRENCY_SYMBOL = '" . REDCURRENCY_SYMBOL . "';
		window.CURRENCY_SYMBOL_CONVERT = '" . $currency_symbol . "';
		window.CURRENCY_CONVERT = '" . $currency_convert . "';
		window.PRICE_SEPERATOR = '" . PRICE_SEPERATOR . "';
		window.COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE = '" . JText::_('COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE') . "';
		window.COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE = '" . JText::_('COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE') . "';
		window.CURRENCY_SYMBOL_POSITION = '" . CURRENCY_SYMBOL_POSITION . "';
		window.PRICE_DECIMAL = '" . PRICE_DECIMAL . "';
		window.COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT = '" . JText::_('COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT') . "';
		window.THOUSAND_SEPERATOR = '" . THOUSAND_SEPERATOR . "';
		window.COM_REDSHOP_VIEW_CART = '" . JText::_('COM_REDSHOP_VIEW_CART') . "';
		window.COM_REDSHOP_CONTINUE_SHOPPING = '" . JText::_('COM_REDSHOP_CONTINUE_SHOPPING') . "';
		window.COM_REDSHOP_YOUR_MUST_PROVIDE_A_VALID_PHONE = '" . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_VALID_PHONE') . "';
		window.COM_REDSHOP_CART_SAVE = '" . JText::_('COM_REDSHOP_CART_SAVE') . "';
		window.COM_REDSHOP_IS_REQUIRED = '" . JText::_('COM_REDSHOP_IS_REQUIRED') . "';
		window.COM_REDSHOP_ENTER_NUMBER = '" . JText::_('COM_REDSHOP_ENTER_NUMBER') . "';
		window.USE_STOCKROOM = '" . USE_STOCKROOM . "';
		window.USE_AS_CATALOG = '" . USE_AS_CATALOG . "';
		window.AJAX_CART_DISPLAY_TIME = '" . AJAX_CART_DISPLAY_TIME . "';
		window.SHOW_PRICE = '" . SHOW_PRICE . "';
		window.DEFAULT_QUOTATION_MODE = '" . DEFAULT_QUOTATION_MODE . "';
		window.PRICE_REPLACE = '" . PRICE_REPLACE . "';
		window.PRICE_REPLACE_URL = '" . PRICE_REPLACE_URL . "';
		window.ZERO_PRICE_REPLACE = '" . ZERO_PRICE_REPLACE . "';
		window.ZERO_PRICE_REPLACE_URL = '" . ZERO_PRICE_REPLACE_URL . "';
		window.OPTIONAL_SHIPPING_ADDRESS = '" . OPTIONAL_SHIPPING_ADDRESS . "';
		window.SHIPPING_METHOD_ENABLE = '" . SHIPPING_METHOD_ENABLE . "';
		window.PRODUCT_ADDIMG_IS_LIGHTBOX = '" . PRODUCT_ADDIMG_IS_LIGHTBOX . "';
		window.ALLOW_PRE_ORDER = '" . ALLOW_PRE_ORDER . "';
		window.ATTRIBUTE_SCROLLER_THUMB_WIDTH = '" . ATTRIBUTE_SCROLLER_THUMB_WIDTH . "';
		window.ATTRIBUTE_SCROLLER_THUMB_HEIGHT = '" . ATTRIBUTE_SCROLLER_THUMB_HEIGHT . "';
		window.PRODUCT_DETAIL_IS_LIGHTBOX = '" . PRODUCT_DETAIL_IS_LIGHTBOX . "';
		window.REQUIRED_VAT_NUMBER = '" . REQUIRED_VAT_NUMBER . "';
		window.COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME = '" . JText::_('COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME', true) . "';
		window.COM_REDSHOP_YOUR_MUST_PROVIDE_A_FIRSTNAME = '" . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_FIRSTNAME', true) . "';
		window.COM_REDSHOP_YOUR_MUST_PROVIDE_A_LASTNAME = '" . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_LASTNAME', true) . "';
		window.COM_REDSHOP_YOUR_MUST_PROVIDE_A_ADDRESS = '" . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_ADDRESS', true) . "';
		window.COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP = '" . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP', true) . "';
		window.COM_REDSHOP_YOUR_MUST_PROVIDE_A_CITY = '" . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_CITY', true) . "';
		window.COM_REDSHOP_YOUR_MUST_PROVIDE_A_PHONE = '" . JText::_('COM_REDSHOP_YOUR_MUST_PROVIDE_A_PHONE', true) . "';
		window.COM_REDSHOP_THIS_FIELD_REQUIRED = '" . JText::_('COM_REDSHOP_THIS_FIELD_REQUIRED', true) . "';
		window.COM_REDSHOP_THIS_FIELD_REMOTE = '" . JText::_('COM_REDSHOP_THIS_FIELD_REMOTE', true) . "';
		window.COM_REDSHOP_THIS_FIELD_URL= '" . JText::_('COM_REDSHOP_THIS_FIELD_URL', true) . "';
		window.COM_REDSHOP_THIS_FIELD_DATE= '" . JText::_('COM_REDSHOP_THIS_FIELD_DATE', true) . "';
		window.COM_REDSHOP_THIS_FIELD_DATEISO= '" . JText::_('COM_REDSHOP_THIS_FIELD_DATEISO', true) . "';
		window.COM_REDSHOP_THIS_FIELD_NUMBER= '" . JText::_('COM_REDSHOP_THIS_FIELD_NUMBER', true) . "';
		window.COM_REDSHOP_THIS_FIELD_DIGITS= '" . JText::_('COM_REDSHOP_THIS_FIELD_DIGITS', true) . "';
		window.COM_REDSHOP_THIS_FIELD_CREDITCARD= '" . JText::_('COM_REDSHOP_THIS_FIELD_CREDITCARD', true) . "';
		window.COM_REDSHOP_THIS_FIELD_EQUALTO= '" . JText::_('COM_REDSHOP_THIS_FIELD_EQUALTO', true) . "';
		window.COM_REDSHOP_THIS_FIELD_ACCEPT= '" . JText::_('COM_REDSHOP_THIS_FIELD_ACCEPT', true) . "';
		window.COM_REDSHOP_THIS_FIELD_MAXLENGTH= '" . JText::_('COM_REDSHOP_THIS_FIELD_MAXLENGTH', true) . "';
		window.COM_REDSHOP_THIS_FIELD_MINLENGTH= '" . JText::_('COM_REDSHOP_THIS_FIELD_MINLENGTH', true) . "';
		window.COM_REDSHOP_THIS_FIELD_RANGELENGTH= '" . JText::_('COM_REDSHOP_THIS_FIELD_RANGELENGTH', true) . "';
		window.COM_REDSHOP_THIS_FIELD_RANGE= '" . JText::_('COM_REDSHOP_THIS_FIELD_RANGE', true) . "';
		window.COM_REDSHOP_THIS_FIELD_MAX= '" . JText::_('COM_REDSHOP_THIS_FIELD_MAX', true) . "';
		window.COM_REDSHOP_THIS_FIELD_MIN= '" . JText::_('COM_REDSHOP_THIS_FIELD_MIN', true) . "';
		window.COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME = '" . JText::_('COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME', true) . "';
		window.COM_REDSHOP_PROVIDE_EMAIL_ADDRESS = '" . JText::_('COM_REDSHOP_PROVIDE_EMAIL_ADDRESS', true) . "';
		window.COM_REDSHOP_EMAIL_NOT_MATCH = '" . JText::_('COM_REDSHOP_EMAIL_NOT_MATCH', true) . "';
		window.COM_REDSHOP_PASSWORD_NOT_MATCH = '" . JText::_('COM_REDSHOP_PASSWORD_NOT_MATCH', true) . "';
		window.NOOF_SUBATTRIB_THUMB_FOR_SCROLLER = '" . NOOF_SUBATTRIB_THUMB_FOR_SCROLLER . "';
		window.COM_REDSHOP_NOT_AVAILABLE = '" . JText::_('COM_REDSHOP_NOT_AVAILABLE', true) . "';
		window.COM_REDSHOP_PLEASE_INSERT_HEIGHT = '" . JText::_('COM_REDSHOP_PLEASE_INSERT_HEIGHT', true) . "';
		window.COM_REDSHOP_PLEASE_INSERT_WIDTH = '" . JText::_('COM_REDSHOP_PLEASE_INSERT_WIDTH', true) . "';
		window.COM_REDSHOP_PLEASE_INSERT_DEPTH = '" . JText::_('COM_REDSHOP_PLEASE_INSERT_DEPTH', true) . "';
		window.COM_REDSHOP_PLEASE_INSERT_RADIUS = '" . JText::_('COM_REDSHOP_PLEASE_INSERT_RADIUS', true) . "';
		window.COM_REDSHOP_PLEASE_INSERT_UNIT = '" . JText::_('COM_REDSHOP_PLEASE_INSERT_UNIT', true) . "';
		window.COM_REDSHOP_THIS_FIELD_IS_REQUIRED = '" . JText::_('COM_REDSHOP_THIS_FIELD_IS_REQUIRED', true) . "';
		window.COM_REDSHOP_SELECT_SUBSCRIPTION_PLAN = '" . JText::_('COM_REDSHOP_SELECT_SUBSCRIPTION_PLAN', true) . "';
	    window.COM_REDSHOP_USERNAME_MIN_CHARACTER_LIMIT = '" . JText::_('COM_REDSHOP_USERNAME_MIN_CHARACTER_LIMIT', true) . "';
		window.CREATE_ACCOUNT_CHECKBOX = '" . CREATE_ACCOUNT_CHECKBOX . "';
		window.USE_TAX_EXEMPT = '" . USE_TAX_EXEMPT . "';
		window.SHOW_EMAIL_VERIFICATION = '" . SHOW_EMAIL_VERIFICATION . "';
		window.SHOW_QUOTATION_PRICE = '" . SHOW_QUOTATION_PRICE . "';
		window.AJAX_DETAIL_BOX_WIDTH = '" . AJAX_DETAIL_BOX_WIDTH . "';
		window.AJAX_DETAIL_BOX_HEIGHT = '" . AJAX_DETAIL_BOX_HEIGHT . "';
		window.AJAX_BOX_WIDTH = '" . AJAX_BOX_WIDTH . "';
		window.AJAX_BOX_HEIGHT = '" . AJAX_BOX_HEIGHT . "';
	";
$doc->addScriptDeclaration($script);

if ($view == 'product')
{
	if (is_file(REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox/' . PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE))
	{
		$slimboxCloseButton = "#sbox-btn-close {background: transparent url( \"" . REDSHOP_FRONT_IMAGES_ABSPATH . "slimbox/" . PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE . "\" ) no-repeat center;}";
	}
	else
	{
		$slimboxCloseButton = "#sbox-btn-close {background: transparent url( \"" . REDSHOP_FRONT_IMAGES_ABSPATH . "slimbox/closelabel.gif\" ) no-repeat center;}";
	}

	$doc->addStyleDeclaration($slimboxCloseButton);
}
