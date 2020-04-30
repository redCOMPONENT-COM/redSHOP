<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class DiscountPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class DiscountPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = 'Product Discount Management';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=discounts';

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $saveDiscount = "//button[@onclick=\"Joomla.submitbutton('discount.apply');\"]";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldName = "#jform_name";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldAmount = "#jform_amount";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldDiscountAmount = "#jform_discount_amount";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldDiscountType = ['name' => 'jform[discount_type]'];

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldCondition = ['name' => 'jform[condition]'];

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldShopperGroup = "#jform_shopper_group";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldStartDate = "#jform_start_date";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldEndDate = "#jform_end_date";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $discountStatePath = "//div[@class='table-responsive']/table/tbody/tr/td[11]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageErrorStartDateHigherEndDate = 'Oops! Discount start date is equal or higher than end date.';

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $discountCheckBox = "//tr/td[2]";

	/**
	 * @return string
	 *
	 * @since  2.1.0
	 */
	public static function getCurrencyCode()
	{
		return "DKK ";
	}
}