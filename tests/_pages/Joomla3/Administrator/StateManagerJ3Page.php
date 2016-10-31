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

	public static $editUrl = '/administrator/index.php?option=com_redshop&view=state&layout=edit';

	public static $countryIdDropDown = "//div[@id='country_id_chzn']/a";

	public static $stateName = "#jform_state_name";

	public static $stateTwoCode = "#jform_state_2_code";

	public static $stateThreeCode = "#jform_state_3_code";

	public static $checkAll = "//input[@onclick='Joomla.checkAll(this)']";

	public static $stateResultRow = "//form[@id='adminForm']/table/tbody/tr[1]";

	public static $searchField = "//input[@id='filter_search']";

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
