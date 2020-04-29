<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CouponManagerJ3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class CouponPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = 'Coupon Management';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=coupons';

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $idFromCode = "#jform_code";
	
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $selectFirst = "//input[@id='cb0']";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $selectValueCoupon =  "//a[contains(concat(' ', @class, ' '), 'btn-edit-item')]";

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $messageFail = 'Save failed with the following error: Start date must be sooner or equal to end date';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $couponType = '//input[@name="jform[type]"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $startDateField = '#jform_start_date';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $endDateField = '#jform_end_date';
}
