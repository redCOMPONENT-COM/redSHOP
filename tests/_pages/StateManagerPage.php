<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class StateManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class StateManagerPage
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=state';

	public static $countryId = "#country_id";

	public static $stateName = "#state_name";

	public static $stateTwoCode = "#state_2_code";

	public static $stateThreeCode = "#state_3_code";

	public static $checkAll = "//input[@onclick='checkAll(1)']";

	public static $stateResultRow = "//form[@id='adminForm']/table[2]/tbody/tr[1]";

	public static $searchField = "//input[@id='country_main_filter']";

	public static $searchButton = "//input[@value='Search']";
}
