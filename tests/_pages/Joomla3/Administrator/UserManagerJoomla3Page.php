<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class UserManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */

class UserManagerJoomla3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=user';

	public static $generalUserInformationTab = "//a[contains(text(), 'General User Information')]";

	public static $billingInformationTab = "//*[@id='user-pane']/dt[3]/span/h3/a";

	public static $userName = "//input[@id='username']";

	public static $newPassword = "//input[@id='password']";

	public static $confirmNewPassword = "//input[@id='password2']";

	public static $email = "//input[@id='email']";

	public static $groupRadioButton = "//form[@id='adminForm']/div[1]/dd[1]/div/fieldset/table/tbody/tr[6]/td[2]";

	public static $shopperGroupDropDown = "//*[@id='select2-chosen-1']";

	public static $firstName = "//input[@id='firstname']";

	public static $lastName = "//input[@id='lastname']";

	public static $userSuccessMessage = 'User detail saved';

	public static $firstResultRow = "//tbody/tr[1]/td[3]/a";

	public static $selectFirst = "//tbody/tr[1]/td[2]/div";

	/**
	 * Function to get the path for Shopper Group
	 *
	 * @param   String  $shopperGroup  Group of Shopper
	 *
	 * @return string
	 */
	public function shopperGroup($shopperGroup)
	{
		$path = "//div[@id='shopper_group_id_chzn']/div/ul/li[contains(text(), '" . $shopperGroup . "')]";

		return $path;
	}
}
