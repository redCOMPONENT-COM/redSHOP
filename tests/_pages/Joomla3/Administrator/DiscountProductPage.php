<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class DiscountProductPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class DiscountProductPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePage = "Product price discounts";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=discount_products';

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldAmount = "#jform_amount";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldCondition = "#jform_condition";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldDiscountType = "#jform_discount_type";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldDiscountAmount = ['name' => 'jform[discount_amount]'];

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
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldCategory = "#jform_category_ids";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $inputCategoryID = "#s2id_autogen1";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $fieldShopperGroup = "#jform_shopper_group";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageErrorStartDateHigherEndDate = 'Oops! Discount start date is equal or higher than end date.';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageErrorAmountZero = 'Oops! Discount amount must be greater than 0';

	/**
	 * @var string
	 * @since 2.1.2
	 */
	public static $deleteSuccess = '1 item successfully deleted';
}
