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
	public static $idFromCode = ['id' => 'jform_code'];
	
	/**
	 * @var string
	 */
	public static $selectFirst = "//input[@id='cb0']";

	/**
	 * @var array
	 */
	public static $selectValueCoupon =  ['xpath' => '//a[contains(concat(\' \', @class, \' \'), \'btn-edit-item \')]'];
}
