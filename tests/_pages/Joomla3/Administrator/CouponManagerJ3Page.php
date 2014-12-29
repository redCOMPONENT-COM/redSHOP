<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
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
	public static $URL = '/administrator/index.php?option=com_redshop&view=coupon';

	public static $couponCode = "//input[@id='coupon_code']";

	public static $couponValue = "//input[@id='coupon_value']";

	public static $couponLeft = "//input[@id='coupon_left']";

	public static $couponValueInDropDown = "//div[@id='percent_or_total_chzn']/a";

	public static $couponTypeDropDown = "//div[@id='coupon_type_chzn']/a";

	public static $selectFirst = "//input[@id='cb0']";

	public static $firstResultRow = "//div[@id='editcell']//table[2]//tbody/tr[1]";

	/**
	 * Function to get path for CouponValueIn
	 *
	 * @param   String  $couponValue  Value of the Coupon
	 *
	 * @return string
	 */
	public function couponValueIn($couponValue)
	{
		$path = "//div[@id='percent_or_total_chzn']/div/ul/li[contains(text(), '" . $couponValue . "')]";

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
		$path = "//div[@id='coupon_type_chzn']/div/ul/li[contains(text(), '" . $couponType . "')]";

		return $path;
	}
}
