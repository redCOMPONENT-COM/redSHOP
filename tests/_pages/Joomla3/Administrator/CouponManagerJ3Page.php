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
	public static $URL = '/administrator/index.php?option=com_redshop&view=coupon';
	public static $couponCode = "//input[@id='coupon_code']";
	public static $couponValue = "//input[@id='coupon_value']";
	public static $couponLeft = "//input[@id='coupon_left']";
	public static $couponValueInDropDown = "//div[@id='s2id_percent_or_total']/a";
	public static $couponTypeDropDown = "//*[@id='select2-drop']/div";
	public static $selectFirst = "//table[contains(@class, 'test-redshop-table')]//tr[contains(@class,'test-redshop-table-row')][1]//td[@class='test-redshop-coupon-checkall']//div//ins[@class='iCheck-helper']";
	// //table[@class='test-redshop-table']//tr[@class='test-redshop-table'][1]//td[@class='test-redshop-coupon-checkall']
	public static $firstResultRow = ['class' => "test-redshop-table-row"];
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
		$path = "//*[@id='select2-results-3']/li[1]";
		return $path;
	}
}