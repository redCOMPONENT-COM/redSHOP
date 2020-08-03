<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class StockRoomManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class StockRoomManagerJoomla3Page extends AdminJ3Page
{
	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = 'administrator/index.php?option=com_redshop&view=stockrooms';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $stockRoomName = "//input[@id='jform_name']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $minimumStockAmount = "//input[@id='jform_min_stock_amount']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $stockRoomSuccessMessage = 'Item saved.';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $firstResultRow = "//table[@id='table-stockrooms']/tbody/tr[1]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $selectFirst = "//input[@id='cb0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $stockRoomStatePath = "//table[@id='table-stockrooms']//tbody/tr[1]/td[7]/a";

	//button

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $newButton = "New";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $saveButton = "//button[@onclick=\"Joomla.submitbutton('stockroom.apply');\"]";

	//selector

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $selectorSuccess = '.alert-success';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $selectorError = '.alert-danger';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $selectorNamePage = '.page-title';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $deleteMessage = "successfully deleted";
}
