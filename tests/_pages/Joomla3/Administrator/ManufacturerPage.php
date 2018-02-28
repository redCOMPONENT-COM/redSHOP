<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManufacturerManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class ManufacturerPage extends AdminJ3Page
{
	/**
	 * @var string
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=manufacturers';

	/**
	 * @var string
	 */
	public static $detailsTab = "//a[contains(text(), 'Details')]";

	/**
	 * @var array
	 */
	public static $manufacturerName = ['id' => 'jform_name'];

	/**
	 * @var string
	 */
	public static $manufacturerSuccessMessage = 'Item saved';

	/**
	 * @var string
	 */
	public static $firstResultRow = "//div[@id='editcell']//table[2]//tbody/tr[1]";

	/**
	 * @var string
	 */
	public static $selectFirst = "//input[@id='cb0']";

	/**
	 * @var string
	 */
	public static $manufacturerStatePath = ['xpath' => '//a[contains(@class, \'btn-state-item\')]'];

	/**
	 * @var array
	 */
	public static $emailManufacture = ['id' => 'jform_email'];

	/**
	 * @var array
	 */
	public static $productPerPage = ['id' => 'jform_product_per_page'];

	/**
	 * @var array
	 */
	public static $fieldTemplate = ['id' => 'jform_template_id'];

	/**
	 * @var string
	 */
	public static $templateSection = 'manufacturer_products';
}
