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
class CouponPage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $namePage = 'Coupon Management';

	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=coupons';

	/**
	 * @var array
	 */
	public static $fieldCode = ['id' => 'jform_code'];

	/**
	 * @var array
	 */
	public static $fieldValue = ['id' => 'jform_value'];

	/**
	 * @var array
	 */
	public static $fieldAmountLeft = ['id' => 'jform_amount_left'];

	/**
	 * @var array
	 */
	public static $fieldType = ['name' => 'jform[type]'];

	/**
	 * @var array
	 */
	public static $fieldEffect = ['name' => 'jform[effect]'];

	public static $selectFirst = "//input[@id='cb0']";

	public static $seclectValueCoupon=['xpath'=>'//td[@class=\'test-redshop-coupon-code\']'];
	
	public static $firstResultRow = ['class' => "test-redshop-table-row"];

	/**
	 * Function to get path for CouponValueIn
	 *
	 * @param   String $couponValue Value of the Coupon
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
	 * @param   String $couponType Value of the Coupon
	 *
	 * @return string
	 */
	public function couponType($couponType)
	{
		$path = "//div[@id='coupon_type_chzn']/div/ul/li[contains(text(), '" . $couponType . "')]";

		return $path;
	}
}
