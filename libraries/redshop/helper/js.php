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
		$input   = JFactory::getApplication()->input;
		$session = JFactory::getSession();

		$view   = $input->getCmd('view');
		$layout = $input->getCmd('layout');
		$config = Redshop::getConfig();
		$post   = $input->post->getArray();

		$currencySymbol  = $config->get('REDCURRENCY_SYMBOL', '');
		$currencyConvert = 1;

		if (isset($post['product_currency']))
		{
			$session->set('product_currency', $post['product_currency']);
		}

		if ($session->get('product_currency'))
		{
			$currencySymbol  = RedshopEntityCurrency::getInstance($session->get('product_currency'))->get('currency_code');
			$currencyConvert = round(RedshopHelperCurrency::convert(1), 2);
		}

		$token = JSession::getFormToken();

		// Prepare dynamic variables to add them in javascript stack
		$dynamicVars = array(
			'SITE_URL'                          => JUri::root(),
			'AJAX_TOKEN'                        => $token,
			'AJAX_BASE_URL'                     => "index.php?tmpl=component&option=com_redshop&" . $token . "=1",
			'AJAX_CART_BOX'                     => $config->get('AJAX_CART_BOX'),
			'REDSHOP_VIEW'                      => $view,
			'REDSHOP_LAYOUT'                    => $layout,
			'CURRENCY_SYMBOL_CONVERT'           => $currencySymbol,
			'CURRENCY_CONVERT'                  => $currencyConvert,
			'PRICE_SEPERATOR'                   => $config->get('PRICE_SEPERATOR'),
			'CURRENCY_SYMBOL_POSITION'          => $config->get('CURRENCY_SYMBOL_POSITION'),
			'PRICE_DECIMAL'                     => $config->get('PRICE_DECIMAL'),
			'THOUSAND_SEPERATOR'                => $config->get('THOUSAND_SEPERATOR', ''),
			'USE_STOCKROOM'                     => $config->get('USE_STOCKROOM'),
			'USE_AS_CATALOG'                    => $config->get('USE_AS_CATALOG'),
			'AJAX_CART_DISPLAY_TIME'            => $config->get('AJAX_CART_DISPLAY_TIME'),
			'SHOW_PRICE'                        => $config->get('SHOW_PRICE'),
			'BASE_TAX'                          => RedshopHelperProduct::getProductTax(0, 1),
			'DEFAULT_QUOTATION_MODE'            => $config->get('DEFAULT_QUOTATION_MODE'),
			'PRICE_REPLACE'                     => $config->get('PRICE_REPLACE'),
			'ALLOW_PRE_ORDER'                   => $config->get('ALLOW_PRE_ORDER'),
			'ATTRIBUTE_SCROLLER_THUMB_WIDTH'    => $config->get('ATTRIBUTE_SCROLLER_THUMB_WIDTH'),
			'ATTRIBUTE_SCROLLER_THUMB_HEIGHT'   => $config->get('ATTRIBUTE_SCROLLER_THUMB_HEIGHT'),
			'PRODUCT_DETAIL_IS_LIGHTBOX'        => $config->get('PRODUCT_DETAIL_IS_LIGHTBOX'),
			'REQUIRED_VAT_NUMBER'               => $config->get('REQUIRED_VAT_NUMBER'),
			'NOOF_SUBATTRIB_THUMB_FOR_SCROLLER' => $config->get('NOOF_SUBATTRIB_THUMB_FOR_SCROLLER'),
			'SHOW_QUOTATION_PRICE'              => $config->get('SHOW_QUOTATION_PRICE'),
			'AJAX_DETAIL_BOX_WIDTH'             => $config->get('AJAX_DETAIL_BOX_WIDTH'),
			'AJAX_DETAIL_BOX_HEIGHT'            => $config->get('AJAX_DETAIL_BOX_HEIGHT'),
			'AJAX_BOX_WIDTH'                    => $config->get('AJAX_BOX_WIDTH'),
			'AJAX_BOX_HEIGHT'                   => $config->get('AJAX_BOX_HEIGHT'),
			'PRICE_REPLACE_URL'                 => $config->get('PRICE_REPLACE_URL'),
			'ZERO_PRICE_REPLACE_URL'            => $config->get('ZERO_PRICE_REPLACE_URL'),
			'ZERO_PRICE_REPLACE'                => $config->get('ZERO_PRICE_REPLACE')
		);

		// Current Shopper Group - Show price with VAT config
		$shopperGroupData = RedshopHelperUser::getShopperGroupDataById(RedshopHelperUser::getShopperGroup(JFactory::getUser()->id));

		$dynamicVars['SHOW_PRICE_WITHOUT_VAT'] = $shopperGroupData ? (int) $shopperGroupData->show_price_without_vat : 0;

		$backwardJS = array();

		// Now looping to add dynamic vars into javascript stack
		foreach ($dynamicVars as $key => $value)
		{
			if (Redshop::getConfig()->get('BACKWARD_COMPATIBLE_JS') == 1)
			{
				$backwardJS[] = 'window.' . $key . ' = "' . $value . '";';
			}

			RedshopHelperConfig::script($key, $value);
		}

		if (Redshop::getConfig()->get('BACKWARD_COMPATIBLE_JS') == 1)
		{
			JFactory::getDocument()->addScriptDeclaration(implode("\n", $backwardJS));
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

		$backwardJS = array();

		// Now looping to add language strings into javascript store
		foreach ($languages as $value)
		{
			JText::script($value);

			if (Redshop::getConfig()->get('BACKWARD_COMPATIBLE_JS') == 1)
			{
				$backwardJS[] = 'window.' . $value . ' = "' . JText::_($value) . '";';
			}
		}

		if (Redshop::getConfig()->get('BACKWARD_COMPATIBLE_JS') == 1)
		{
			JFactory::getDocument()->addScriptDeclaration(implode("\n", $backwardJS));
		}

		if ($view == 'product')
		{
			if (JFile::exists(REDSHOP_FRONT_IMAGES_RELPATH . 'slimbox/' . $config->get('PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE')))
			{
				$slimboxCloseButton = "#sbox-btn-close {background: transparent url( \""
					. REDSHOP_FRONT_IMAGES_ABSPATH . "slimbox/" . $config->get('PRODUCT_DETAIL_LIGHTBOX_CLOSE_BUTTON_IMAGE')
					. "\" ) no-repeat center;}";
			}
			else
			{
				$slimboxCloseButton = "#sbox-btn-close {background: transparent url( \""
					. REDSHOP_FRONT_IMAGES_ABSPATH . "slimbox/closelabel.gif\" ) no-repeat center;}";
			}

			$doc->addStyleDeclaration($slimboxCloseButton);
		}
	}
}
