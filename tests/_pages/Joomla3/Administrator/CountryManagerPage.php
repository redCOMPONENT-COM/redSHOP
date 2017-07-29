<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CountryManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class CountryManagerPage
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=countries';

	public static $countryName = "#jform_country_name";

	public static $countryTwoCode = "#jform_country_2_code";

	public static $countryThreeCode = "#jform_country_3_code";

	public static $country = "#jform_country_jtext";

	public static $countryCheck = "//input[@id='cb0']";

	public static $countryResultRow = "//table[contains(@class, 'adminlist')]/tbody/tr[1]";
}
