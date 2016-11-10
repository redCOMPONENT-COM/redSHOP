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

	
	public static $countryIdDropDown = "//*[@id='select2-chosen-1']";

	public static $stateName = "#jform_state_name";

	public static $stateTwoCode = "#jform_state_2_code";

	public static $stateThreeCode = "#jform_state_3_code";

	public static $checkAll = "//tbody/tr[1]/td[2]/div";

	public static $stateResultRow = "//tbody/tr[1]/td[3]/a";

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
