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
	public static $namePage = 'Country Management';
	public static $headPage = ['xpath' => "//h1"];

	public static $searchField = ['id' => 'filter_search'];

	public static $URL = '/administrator/index.php?option=com_redshop&view=countries';

	public static $countryName = "#jform_country_name";
	public static $countryTwoCode = "#jform_country_2_code";
	public static $countryThreeCode = "#jform_country_3_code";
	public static $country = "#jform_country_jtext";

	public static $countryCheck = "//input[@id='cb0']";

	public static $countryResultRow = "//table[contains(@class, 'adminlist')]/tbody/tr[1]";

	public static $newButton = "New";
	public static $saveCloseButton = "Save & Close";
	public static $deleteButton = "Delete";

	public static $itemSaveSuccessMessage = "Item saved.";
	public static $messageDeletedOneSuccess = "1 item successfully deleted";

	public static $selectorSuccess = ".alert-success";
}
