<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class DiscountManagerJ3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class DiscountPage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $namePage = 'Product Discount Management';

	/**
	 * @var string
	 */
	public static $url = '/administrator/index.php?option=com_redshop&view=discounts';

	/**
	 * @var array
	 */
	public static $fieldName = ['id' => 'jform_name'];

	/**
	 * @var array
	 */
	public static $fieldAmount = ['id' => 'jform_amount'];

	/**
	 * @var array
	 */
	public static $fieldDiscountAmount = ['id' => 'jform_discount_amount'];

	/**
	 * @var array
	 */
	public static $fieldDiscountType = ['name' => 'jform[discount_type]'];

	/**
	 * @var array
	 */
	public static $fieldCondition = ['name' => 'jform[condition]'];

	/**
	 * @var array
	 */
	public static $fieldShopperGroup = ['id' => 'jform_shopper_group'];

	/**
	 * @var array
	 */
	public static $fieldStartDate = ['id' => 'jform_start_date'];

	/**
	 * @var array
	 */
	public static $fieldEndDate = ['id' => 'jform_end_date'];

	public static $discountState = ['xpath' => '//tr/td[8]/a'];

	public static $discountCheckBox = ['xpath' =>'//tr/td[2]'];


	/**
	 * Function to get the path for Discount Type
	 *
	 * @param   String $discountType Type of Discount
	 *
	 * @return string
	 */
	public function discountType($discountType)
	{
		$path = "//ul/li[contains(text(), '" . $discountType . "')]";

		return $path;
	}

	/**
	 * Function to get the Path for Shopper Group
	 *
	 * @param   String $groupType Type of the Group
	 *
	 * @return string
	 */
	public function shopperGroup($shopperGroup)
	{
		$path = "//ul/li[contains(text(), '" . $shopperGroup . "')]";

		return $path;
	}

	public static function getCurrencyCode()
	{
		return "DKK ";
	}
}