<?php
/**
 * @package     RedSHOP.Library
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

use Redshop\Currency\Currency;

/**
 * Class Redshop Helper for Currency
 *
 * @since  __DEPLOY_VERSION__
 */
class RedshopHelperCurrency
{
	/**
	 * Initializes the global currency converter array
	 *
	 * @return  mixed
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public static function init()
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
	 * @since  __DEPLOY_VERSION__
	 */
	public static function convert($amountA, $currA = '', $currB = '')
	{
		return Currency::getInstance()->convert($amountA, $currA, $currB);
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
	 * @since   __DEPLOY_VERSION__  Use Redshop\Currency\Currency instead.
	 */
	public static function getISOCode($code)
	{
		return Currency::getInstance()->getISOCode($code);
	}
}
