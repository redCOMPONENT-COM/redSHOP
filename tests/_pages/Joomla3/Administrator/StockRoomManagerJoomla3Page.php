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

class StockRoomManagerJoomla3Page extends AdminJ3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=stockroom';

	public static $stockRoomName = "//input[@id='stockroom_name']";

	public static $minimumStockAmount = "//input[@id='min_stock_amount']";

	public static $stockRoomSuccessMessage = 'Stockroom Detail Saved';

	public static $firstResultRow = "//tr[@class='row0']";

	public static $selectFirst = "//input[@id='cb0']";

	public static $stockRoomStatePath = "//tr[@class='row0']/td[6]/a";

	public static $stockRoomSearch = "//input[@id='filter']";

}