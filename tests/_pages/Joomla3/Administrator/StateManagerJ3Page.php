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
	public static $URL = '/administrator/index.php?option=com_redshop&view=state';

	public static $countryIdDropDown = "//div[@id='country_id_chzn']/a";

	public static $stateName = "#state_name";

	public static $stateTwoCode = "#state_2_code";

	public static $stateThreeCode = "#state_3_code";

	public static $checkAll = "//input[@onclick='Joomla.checkAll(this)']";

	public static $stateResultRow = "//form[@id='adminForm']/table[2]/tbody/tr[1]";

	public static $searchField = "//input[@id='country_main_filter']";

	public static $searchButton = "//input[@value='Search']";

	/**
	 * Function to get the CountryID Path
	 *
	 * @param   string  $countryName  Name of the Country
	 *
	 * @return string
	 */
	public function countryID($countryName)
	{
		$path = "//div[@id='country_id_chzn']/div/ul/li[contains(text(), '" . $countryName . "')]";

		return $path;
	}
}
