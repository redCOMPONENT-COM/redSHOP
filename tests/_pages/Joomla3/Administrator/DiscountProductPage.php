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
	public static $fieldCondition = ['name' => 'jform[condition]'];

	/**
	 * @var array
	 */
	public static $fieldDiscountType = ['name' => 'jform[discount_type]'];

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




	public static $namePageDetail = "Discount";
	//Message when change success
	public static $messageUnpublishSuccess = "Discount Detail UnPublished Successfully";

	public static $messagePublishSuccess = "Discount Detail Published Successfully";

	public static $messageDeleteSuccess = "Discount Detail Deleted Successfully";

	public static $messageSaveDiscountSuccess = "Discount Detail Saved";

	public static $namePageDiscount = "Discount Page New";

	//selector
	public static $selectorSuccess = '.alert-success';

	public static $pageTitle = '.page-title';

	//page discount detail

	public static $conditionSearch = ['id' => "s2id_autogen1_search"];

	public static $discountTypeSearch = ['id' => "s2id_autogen2_search"];

	public static $categoryInput = ['xpath' => "//div[@id='s2id_category_ids']//ul/li//input"];



	public static $shopperGroupInput = ['xpath' => "//div[@id='s2id_shopper_group_id']//ul/li//input"];

	//Button

	public static $newButton = "New";

	public static $saveButton = "Save";

	public static $unpublishButton = "Unpublish";

	public static $publishButton = "Publish";

	public static $saveCloseButton = "Save & Close";

	public static $deleteButton = "Delete";

	public static $editButton = "Edit";

	public function returnType($condition)
	{
		$path = "//span[contains(text(), '" . $condition . "')]";
		return $path;
	}
}
