<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CouponManagerJ3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class CouponManagerJ3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=coupons';

	public static $couponCode = "//input[@id='jform_coupon_code']";

	public static $couponValue = "//input[@id='jform_coupon_value']";

	public static $couponLeft = "//input[@id='jform_coupon_left']";

	public static $couponValueInDropDown = "//div[@id='s2id_jform_percent_or_total']/a";

	public static $couponTypeDropDown = "//div[@id='jform_coupon_type_chzn']/a";

	public static $selectFirst = "//input[@id='cb0']/following-sibling::ins";

	public static $searchField = "//input[@id='filter_search']";

	public static $searchButton = "//input[@value='Search']";

	public static $couponResultRow = "//form[@id='adminForm']/table/tbody/tr[1]";

	/**
	 * Function to get path for CouponValueIn
	 *
	 * @param   String  $couponValue  Value of the Coupon
	 *
	 * @return string
	 */
	public function couponValueIn($couponValue)
	{
		$path = "//div[@id='select2-drop']//ul//li//div[contains(text(), '" . $couponValue . "')]";

		return $path;
	}

	/**
	 * Function to get path for CouponType
	 *
	 * @param   String  $couponType  Value of the Coupon
	 *
	 * @return string
	 */
	public function couponType($couponType)
	{
		$path = "//div[@id='jform_coupon_type_chzn']/div/ul/li[contains(text(), '" . $couponType . "')]";

		return $path;
	}
}
