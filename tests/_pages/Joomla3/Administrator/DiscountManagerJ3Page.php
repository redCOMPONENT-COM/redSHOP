<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class DiscountManagerJ3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class DiscountManagerJ3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=discount';

	public static $name = "//input[@id='name']";

	public static $amount = "//input[@id='amount']";

	public static $discountSuccessMessage = 'Discount Detail Saved';

	public static $discountAmount = "//input[@id='discount_amount']";

	public static $discountTypeDropDown = "//div[@id='discount_type_chzn']/a";

	public static $shopperGroupDropDown = "//div[@id='shopper_group_id_chzn']/ul/li/input";

	public static $firstResultRow = "//div[@id='editcell']/table/tbody/tr[1]";

	public static $selectFirst = "//input[@id='cb0']";

	public static $discountStatePath = "//div[@id='editcell']/table/tbody/tr/td[7]/a";

	/**
	 * Function to get the path for Discount Type
	 *
	 * @param   String  $discountType  Type of Discount
	 *
	 * @return string
	 */
	public function discountType($discountType)
	{
		$path = "//div[@id='discount_type_chzn']/div/ul/li[contains(text(), '" . $discountType . "')]";

		return $path;
	}

	/**
	 * Function to get the Path for Shopper Group
	 *
	 * @param   String  $groupType  Type of the Group
	 *
	 * @return string
	 */
	public function shopperGroup($groupType)
	{
		$path = "//div[@id='shopper_group_id_chzn']/div/ul/li[contains(text(), '" . $groupType . "')]";

		return $path;
	}

	public static function getCurrencyCode()
	{
		return "DKK ";
	}
}
