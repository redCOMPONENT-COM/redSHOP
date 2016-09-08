<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Class RedshopHelperJs
 *
 * @since  1.5
 */
class RedshopHelperJs
{
	/**
	 * Init redshop js
	 *
	 * @return void
	 */
	public static function init()
	{
		$doc     = JFactory::getDocument();
		$tmpl    = JRequest::getCmd('tmpl');
		$view    = JRequest::getCmd('view');
		$layout  = JRequest::getCmd('layout');
		$session = JFactory::getSession();

		$post   = JRequest::get('POST');
		$redhelper   = redhelper::getInstance();
		$cart_Itemid = $redhelper->getCartItemid();

		if ($cart_Itemid == "" || $cart_Itemid == 0)
		{
			$cItemid   = $redhelper->getItemid();
			$tmpItemid = $cItemid;
		}
		else
		{
			$tmpItemid = $cart_Itemid;
		}

		$currency_symbol  = REDCURRENCY_SYMBOL;
		$currency_convert = 1;

		if (isset($post['product_currency']))
		{
			$session->set('product_currency', $post['product_currency']);
		}

		if ($session->get('product_currency'))
		{
			$currency_symbol  = $session->get('product_currency');
			$convertPrice     = CurrencyHelper::getInstance();
			$currency_convert = round($convertPrice->convert(1), 2);
		}

		$token = JSession::getFormToken();

		// Prepare dynamic variables to add them in javascript stack
		$dynamicVars = array(
			'SITE_URL'                          => JURI::root(),
			'AJAX_TOKEN'                        => $token,
			'AJAX_BASE_URL'                     => JRoute::_('index.php?tmpl=component&option=com_redshop&' . $token . '=1', false),
			'AJAX_CART_BOX'                     => AJAX_CART_BOX,
			'REDSHOP_VIEW'                      => $view,
			'REDSHOP_LAYOUT'                    => $layout,
			'CURRENCY_SYMBOL_CONVERT'           => $currency_symbol,
			'CURRENCY_CONVERT'                  => $currency_convert,
			'PRICE_SEPERATOR'                   => PRICE_SEPERATOR,
			'CURRENCY_SYMBOL_POSITION'          => CURRENCY_SYMBOL_POSITION,
			'PRICE_DECIMAL'                     => PRICE_DECIMAL,
			'THOUSAND_SEPERATOR'                => THOUSAND_SEPERATOR,
			'USE_STOCKROOM'                     => USE_STOCKROOM,
			'USE_AS_CATALOG'                    => USE_AS_CATALOG,
			'AJAX_CART_DISPLAY_TIME'            => AJAX_CART_DISPLAY_TIME,
			'SHOW_PRICE'                        => SHOW_PRICE,
			'BASE_TAX'                          => producthelper::getInstance()->getProductTax(0, 1),
			'DEFAULT_QUOTATION_MODE'            => DEFAULT_QUOTATION_MODE,
			'PRICE_REPLACE'                     => PRICE_REPLACE,
			'ALLOW_PRE_ORDER'                   => ALLOW_PRE_ORDER,
			'ATTRIBUTE_SCROLLER_THUMB_WIDTH'    => ATTRIBUTE_SCROLLER_THUMB_WIDTH,
			'ATTRIBUTE_SCROLLER_THUMB_HEIGHT'   => ATTRIBUTE_SCROLLER_THUMB_HEIGHT,
			'PRODUCT_DETAIL_IS_LIGHTBOX'        => PRODUCT_DETAIL_IS_LIGHTBOX,
			'REQUIRED_VAT_NUMBER'               => REQUIRED_VAT_NUMBER,
			'NOOF_SUBATTRIB_THUMB_FOR_SCROLLER' => NOOF_SUBATTRIB_THUMB_FOR_SCROLLER,
			'SHOW_QUOTATION_PRICE'              => SHOW_QUOTATION_PRICE,
			'AJAX_DETAIL_BOX_WIDTH'             => AJAX_DETAIL_BOX_WIDTH,
			'AJAX_DETAIL_BOX_HEIGHT'            => AJAX_DETAIL_BOX_HEIGHT,
			'AJAX_BOX_WIDTH'                    => AJAX_BOX_WIDTH,
			'AJAX_BOX_HEIGHT'                   => AJAX_BOX_HEIGHT,
			'PRICE_REPLACE_URL'                 => PRICE_REPLACE_URL,
			'ZERO_PRICE_REPLACE_URL'            => ZERO_PRICE_REPLACE_URL,
			'ZERO_PRICE_REPLACE'                => ZERO_PRICE_REPLACE
		);

		// Now looping to add dynamic vars into javascript stack
		foreach ($dynamicVars as $key => $value)
		{
			RedshopHelperConfig::script($key, $value);
		}

		// Prepare language string to add in javascript store
		$languages = array(
			'COM_REDSHOP_YOUR_MUST_PROVIDE_A_FIRSTNAME',
			'COM_REDSHOP_PRODUCT_OUTOFSTOCK_MESSAGE',
			'COM_REDSHOP_PREORDER_PRODUCT_OUTOFSTOCK_MESSAGE',
			'COM_REDSHOP_PASSWORD_MIN_CHARACTER_LIMIT',
			'COM_REDSHOP_YOUR_MUST_PROVIDE_A_VALID_PHONE',
			'COM_REDSHOP_IS_REQUIRED',
			'COM_REDSHOP_ENTER_NUMBER',
			'COM_REDSHOP_PLEASE_ENTER_COMPANY_NAME',
			'COM_REDSHOP_YOUR_MUST_PROVIDE_A_LASTNAME',
			'COM_REDSHOP_YOUR_MUST_PROVIDE_A_ADDRESS',
			'COM_REDSHOP_YOUR_MUST_PROVIDE_A_ZIP',
			'COM_REDSHOP_YOUR_MUST_PROVIDE_A_CITY',
			'COM_REDSHOP_YOUR_MUST_PROVIDE_A_PHONE',
			'COM_REDSHOP_YOU_MUST_PROVIDE_LOGIN_NAME',
			'COM_REDSHOP_PROVIDE_EMAIL_ADDRESS',
			'COM_REDSHOP_EMAIL_NOT_MATCH',
			'COM_REDSHOP_PASSWORD_NOT_MATCH',
			'COM_REDSHOP_NOT_AVAILABLE',
			'COM_REDSHOP_PLEASE_INSERT_HEIGHT',
			'COM_REDSHOP_PLEASE_INSERT_WIDTH',
			'COM_REDSHOP_PLEASE_INSERT_DEPTH',
			'COM_REDSHOP_PLEASE_INSERT_RADIUS',
			'COM_REDSHOP_PLEASE_INSERT_UNIT',
			'COM_REDSHOP_THIS_FIELD_IS_REQUIRED',
			'COM_REDSHOP_SELECT_SUBSCRIPTION_PLAN',
			'COM_REDSHOP_USERNAME_MIN_CHARACTER_LIMIT',
			'COM_REDSHOP_EAN_MIN_CHARACTER_LIMIT',
			'COM_REDSHOP_AVAILABLE_STOCK'
		);

		// Now looping to add language strings into javascript store
		foreach ($languages as $value)
		{
			JText::script($value);
		}

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
	}
}
