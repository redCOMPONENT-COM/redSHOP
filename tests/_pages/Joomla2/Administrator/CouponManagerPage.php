<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CouponManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class CouponManagerPage
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=coupon';

	public static $couponCode = "//input[@id='coupon_code']";

	public static $couponValue = "//input[@id='coupon_value']";

	public static $couponLeft = "//input[@id='coupon_left']";

	public static $couponValueIn = "//select[@id='percent_or_total']";

	public static $couponType = "//select[@id='coupon_type']";

	public static $selectFirst = "//input[@id='cb0']";

	public static $firstResultRow = "//div[@id='editcell']//table[2]//tbody/tr[1]";
}
