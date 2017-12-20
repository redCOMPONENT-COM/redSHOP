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

	public static $discountTypeDropDown = "//div[@id='discount_type_chzn']/a";

	public static $shopperGroupDropDown = "//div[@id='shopper_group_id_chzn']/ul/li/input";

	public static $selectFirst = "//input[@id='cb0']";

	public static $startDate = "//input[@id='start_date']";

	public static $endDate = "//input[@id='end_date']";

	public static $discountState = ['xpath' => '//tr/td[8]/a'];

	public static $discountCheckBox = ['xpath' =>'//tr/td[2]'];


	//selctor

	public static $discountType = ['id' => "s2id_discount_type"];

	public static $discountTypeSearch = ['id' => "s2id_autogen2_search"];

	public static $conditionId=['id'=>'s2id_condition'];

	public static $conditionSearch=['id'=>'s2id_autogen1_search'];

	public static $searchResults = ['id' => "select2-results-2"];

	public static $searchShopperId = ['id' => "s2id_shopper_group_id"];
	
	public static $searchShopperField = ['id' => 's2id_autogen3'];

	public static $saveSuccess = ['id' => 'system-message-container'];

	public static $filter = ['id' => 'name_filter'];

	//message

	public static $messageSaveSuccess = "Discount Detail Saved";

	public static $messageUnpublishSuccess = "Discount Detail UnPublished Successfully";

	public static $messagePublishSuccess = "Discount Detail Published Successfully";

	public static $messageDeleteSuccess = "Discount Detail Deleted Successfully";


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