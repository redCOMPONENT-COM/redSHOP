<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class StateManagerJ3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class StatePage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=states';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = 'State Management';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldCountryDropdown = "//div[@id='s2id_jform_country_id']/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldCountrySearch = "//input[@id='s2id_autogen1_search']";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldName = "#jform_state_name";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldTwoCode = "#jform_state_2_code";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldThreeCode = "#jform_state_3_code";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $searchButton = "//input[@value='Search']";

	/**
	 * Function to get the CountryID Path
	 *
	 * @param   string  $countryName  Name of the Country
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function countryID($countryName)
	{
		$path = "//span[contains(text(), '" . $countryName . "')]";

		// $path = "//ul[@class='select2-results']/li/div[@class='select2-result-label' ][contains(text(), '" . $countryName . "')]";

		return $path;
	}
}
