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

	public static $amount = "#amount";

	public static $discountSuccessMessage = 'Discount Detail Saved';

	public static $discountAmount = "//input[@id='discount_amount']";

	public static $discountTypeDropDown = "//*[@id='select2-chosen-1']";

	public static $discountType = "//*[@id='select2-chosen-2']";

	public static $shopperGroupDropDown = "//*[@id='s2id_shopper_group_id']/ul";

	public static $firstResultRow = "//div[@id='editcell']/table/tbody/tr[1]";

	public static $selectFirst = "//tbody/tr[1]/td[2]/div";

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
		$path = "//*[@id='select2-drop']/ul/li[2]";

		return $path;
	}

	public static function getCurrencyCode()
	{
		require_once REDSHOP_CONFIG_PATH;

		$redshopConfig = new \RedshopConfig;

		return $redshopConfig->REDCURRENCY_SYMBOL;
	}
}
