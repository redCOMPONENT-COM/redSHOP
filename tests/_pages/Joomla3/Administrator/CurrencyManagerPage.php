<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CurrencyManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */

class CurrencyManagerPage
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=currency';

	public static $currencyNameField = "//input[@id='currency_name']";

	public static $currencyCodeField = "//input[@id='currency_code']";

	public static $firstResult = "//input[@id='cb0']/following-sibling::ins";

	public static $currencyResultRow = "//form[@id='adminForm']/table/tbody/tr[1]";
}
