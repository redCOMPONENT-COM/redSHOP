<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class StateManagerJ3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class StateManagerJ3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=states';
	public static $namePage = 'State Management';

	public static $editUrl = '/administrator/index.php?option=com_redshop&view=state&layout=edit';

	public static $countryIdDropDown = "//div[@id='s2id_jform_country_id']/a";

	public static $countrySearchInputField = "//input[@id='s2id_autogen1_search']";

	public static $stateName = "#jform_state_name";

	public static $stateTwoCode = "#jform_state_2_code";

	public static $stateThreeCode = "#jform_state_3_code";

	public static $checkAll = "//input[@id='cb0']";

	public static $stateResultRow = "//table[contains(@class, 'adminlist')]/tbody/tr[1]";

	public static $searchField = "//input[@id='filter_search']";

	public static $searchButton = "//input[@value='Search']";
	public static $saveCloseButton = "Save & Close";
	public static $deleteButton = "Delete";

	public static $headPage = ['xpath' => "//h1"];
	public static $selectorSuccess = ".alert-success";

	public static $itemSaveSuccessMessage = "Item saved.";
	public static $messageDeletedOneSuccess = "1 item successfully deleted";

	/**
	 * Function to get the CountryID Path
	 *
	 * @param   string  $countryName  Name of the Country
	 *
	 * @return string
	 */
	public function countryID($countryName)
	{
		$path = "//span[contains(text(), '" . $countryName . "')]";

		//$path = "//ul[@class='select2-results']/li/div[@class='select2-result-label' ][contains(text(), '" . $countryName . "')]";

		return $path;
	}
}
