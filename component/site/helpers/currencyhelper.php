<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Currency\Currency;
use Redshop\Currency\CurrencyLayer;

/**
 * price converter
 *
 * @since  2.5
 *
 * @deprecated  2.0.6  Use Redshop\Currency\Currency instead.
 */
class CurrencyHelper
{
	/**
	 * Returns the CurrencyHelper object, only creating it
	 * if it doesn't already exist.
	 *
	 * @return  CurrencyHelper  The CurrencyHelper object
	 *
	 * @since   1.6
	 *
	 * @deprecated  2.0.6  Use Redshop\Currency\Currency instead.
	 */
	public static function getInstance()
	{
		return Currency::getInstance();
	}

	/**
	 * Initializes the global currency converter array
	 *
	 * @return  mixed
	 *
	 * @deprecated  2.0.6  Use Redshop\Currency\Currency instead.
	 */
	public function init()
	{
		return Currency::getInstance()->init();
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
	 * @deprecated  2.0.6  Use Redshop\Currency\Currency instead.
	 */
	public function convert($amountA, $currA = '', $currB = '')
	{
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
	 * @return  int     Currency Numeric Code
	 *
	 * @deprecated  2.0.6  Use Redshop\Currency\Currency instead.
	 */
	public function get_iso_code($code)
	{
		return Currency::getInstance()->getISOCode($code);
	}
}
