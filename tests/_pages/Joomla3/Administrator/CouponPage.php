<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class CouponManagerJ3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
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
	public static $idFromCode = "#jform_code";
	
	/**
	 * @var string
	 */
	public static $selectFirst = "//input[@id='cb0']";

	/**
	 * @var array
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

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $buttonDeleteCoupon = '//div[@id="toolbar-delete"]';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $messageDeleteCouponSuccess = '1 item successfully deleted';

	/**
	 * @var string
	 * @since 2.1.3
	 */
	public static $searchFieldCopon = '//input[@id="filter_search"]';
}
