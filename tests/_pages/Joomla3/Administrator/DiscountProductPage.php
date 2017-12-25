<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class DiscountProductPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.1.0
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
	public static $fieldAmount = ['id' => 'jform_amount'];

	/**
	 * @var array
	 */
	public static $fieldCondition = ['id' => 'jform_condition'];

	/**
	 * @var array
	 */
	public static $fieldDiscountType = ['id' => 'jform_discount_type'];

	/**
	 * @var array
	 */
	public static $fieldDiscountAmount = ['name' => 'jform[discount_amount]'];

	/**
	 * @var array
	 */
	public static $fieldStartDate = ['id' => 'jform_start_date'];

	/**
	 * @var array
	 */
	public static $fieldEndDate = ['id' => 'jform_end_date'];

	/**
	 * @var array
	 */
	public static $fieldCategory = ['id' => 'jform_category_ids'];

	/**
	 * @var array
	 */
	public static $fieldShopperGroup = ['id' => 'jform_shopper_group'];
}
