<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class StockRoomManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */

class StockRoomManagerJoomla3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=stockroom';

	public static $stockRoomName = "//input[@id='stockroom_name']";

	public static $minimumStockAmount = "//input[@id='min_stock_amount']";

	public static $stockRoomSuccessMessage = 'Stockroom Detail Saved';

	public static $firstResultRow = "//tbody/tr[1]/td[3]/a";

	public static $selectFirst = "//tbody/tr[1]/td[2]/div";

	public static $stockRoomStatePath = "//div[@id='editcell']//table//tbody/tr[1]/td[6]/a";
}
