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
class StatePage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=states';

	/**
	 * @var string
	 */
	public static $namePage = 'State Management';

	/**
	 * @var string
	 */
	public static $fieldCountryDropdown = "//div[@id='s2id_jform_country_id']/a";

	/**
	 * @var string
	 */
	public static $fieldCountrySearch = "//input[@id='s2id_autogen1_search']";

	/**
	 * @var array
	 */
	public static $fieldName = ['id' => 'jform_state_name'];

	/**
	 * @var array
	 */
	public static $fieldTwoCode = ['id' => 'jform_state_2_code'];

	/**
	 * @var array
	 */
	public static $fieldThreeCode = ['id' => 'jform_state_3_code'];

	/**
	 * @var string
	 */
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
		$path = "//span[contains(text(), '" . $countryName . "')]";

		// $path = "//ul[@class='select2-results']/li/div[@class='select2-result-label' ][contains(text(), '" . $countryName . "')]";

		return $path;
	}
}
