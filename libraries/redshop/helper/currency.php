<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Currency\Currency;
use Redshop\Currency\CurrencyLayer;

/**
 * Class Redshop Helper for Currency
 *
 * @since  2.0.6
 */
class RedshopHelperCurrency
{
	/**
	 * Initializes the global currency converter array
	 *
	 * @return  mixed
	 *
	 * @since   2.0.6
	 *
	 * @throws  Exception
	 */
	public static function init()
	{
		Currency::getInstance()->init();
	}

	/**
	 * Convert currency
	 *
	 * @param   float   $amountA  Amount to convert
	 * @param   string  $currA    Base Currency code
	 * @param   string  $currB    Currency code in which need amount to be converted
	 *
	 * @return  float             Converted amount
	 *
	 * @since  2.0.6
	 */
	public static function convert($amountA, $currA = '', $currB = '')
	{
		JPluginHelper::importPlugin('redshop_product');
		$result = RedshopHelperUtility::getDispatcher()->trigger('onConverCurrency', array(&$amountA, $currA, $currB));

		if (in_array(true, $result, true))
		{
			return $amountA;
		}

		if (Redshop::getConfig()->get('CURRENCY_LIBRARIES') == 1)
		{
			return CurrencyLayer::getInstance()->convert($amountA, $currA, $currB);
		}
		else
		{
			return Currency::getInstance()->convert($amountA, $currA, $currB);
		}
	}

	/**
	 * Method to get Currency Numeric code / ISO code
	 *
	 * @param   string  $code  Currency Code
	 *
	 * @todo    Add numeric code into table #_redshop_currency "redSHOP Currency Detail"
	 *
	 * @return  string         Currency Numeric Code
	 *
	 * @since   2.0.6  Use Redshop\Currency\Currency instead.
	 */
	public static function getISOCode($code)
	{
		return Currency::getInstance()->getISOCode($code);
	}
}
