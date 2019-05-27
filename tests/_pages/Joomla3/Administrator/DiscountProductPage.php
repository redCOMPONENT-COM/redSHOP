<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
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
	 */
	public static $namePage = "Product price discounts";

	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=discount_products';

	/**
	 * @var array
	 */
	public static $fieldAmount = "#jform_amount";

	/**
	 * @var array
	 */
	public static $fieldCondition = "#jform_condition";

	/**
	 * @var array
	 */
	public static $fieldDiscountType = "#jform_discount_type";

	/**
	 * @var array
	 */
	public static $fieldDiscountAmount = ['name' => 'jform[discount_amount]'];

	/**
	 * @var array
	 */
	public static $fieldStartDate = "#jform_start_date";

	/**
	 * @var array
	 */
	public static $fieldEndDate = "#jform_end_date";

	/**
	 * @var array
	 */
	public static $fieldCategory = "#jform_category_ids";

	/**
	 * @var string
	 */
	public static $inputCategoryID = "#s2id_autogen1";

	/**
	 * @var array
	 */
	public static $fieldShopperGroup = "#jform_shopper_group";

	/**
	 * @var string
	 */
	public static $messageErrorStartDateHigherEndDate = 'Oops! Discount start date is equal or higher than end date.';

	/**
	 * @var string
	 */
	public static $messageErrorAmountZero = 'Oops! Discount amount must be greater than 0';
}
