<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class MassDiscountManagerPage
 * @since 1.4.0
 */
class MassDiscountManagerPage extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=mass_discounts';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URLNew = '/administrator/index.php?option=com_redshop&view=mass_discount&layout=edit';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $name = "#jform_name";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $valueAmount = "#jform_amount";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pathNameProduct = "#s2id_autogen3";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryField = "#s2id_jform_category_id";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $dayStart = "#jform_start_date";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $dayEnd = "#jform_end_date";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $checkFirstItems = "//input[@id='cb0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $MassDiscountFilter = "#filter_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $MassDicountResultRow = "//div[@class='table-responsive']/table/tbody/tr/td[3]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryForm = "//div[@id='s2id_jform_category_id']//ul/li";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $categoryFormInput = "//div[@id='s2id_jform_category_id']//ul/li//input";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $discountForm = "//div[@id='s2id_jform_discount_product']//ul/li";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $discountFormInput = "//div[@id='s2id_jform_discount_product']//ul/li//input";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $dateCalender = '#jform_start_date_img';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $startDateIcon = "#jform_start_date_img";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $endDateIcon = "#jform_end_date_img";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $getToday = "//td[contains(@class, 'selected')]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldStartDate = "#jform_start_date";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldEndDate = "#jform_end_date";

	//page name

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pageEdit = "Mass Discount  Edit";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $pageNew = "product mass discount new";

	//Message

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $saveOneSuccess = "Item saved.";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fieldName = "Field required: Name";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $saveError = "Save failed with the following error";

	/**
	 * Function to get Path for $type in Mass Discount
	 *
	 * @param $type
	 *
	 * @return string
	 * @since 1.4.0
	 */
	public function returnXpath($type)
	{
		$path = "//span[contains(text(), '" . $type . "')]";
		return $path;
	}
}