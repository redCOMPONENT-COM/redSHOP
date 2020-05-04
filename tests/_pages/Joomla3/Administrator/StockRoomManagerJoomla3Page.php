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
	public static $URL = '/administrator/index.php?option=com_redshop&view=stockroom';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $stockRoomName = "//input[@id='stockroom_name']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $minimumStockAmount = "//input[@id='min_stock_amount']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $stockRoomSuccessMessage = 'Stockroom Detail Saved';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $firstResultRow = "//div[@id='editcell']/table/tbody/tr[1]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $selectFirst = "//input[@id='cb0']";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $stockRoomStatePath = "//div[@id='editcell']//table//tbody/tr[1]/td[6]/a";

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
	public static $saveButton = "//button[@onclick=\"Joomla.submitbutton('apply');\"]";

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
	public static $deleteMessage = "Stockroom Detail Deleted Successfully";
}
