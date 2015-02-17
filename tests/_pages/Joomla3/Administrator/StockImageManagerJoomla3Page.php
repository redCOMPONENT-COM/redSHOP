<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class StockImageManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */

class StockImageManagerJoomla3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=stockimage';

	public static $stockImageToolTip = "//input[@id='stock_amount_image_tooltip']";

	public static $stockAmountDropDown = "//div[@id='stock_option_chzn']/a";

	public static $stockQuantity = "//input[@id='stock_quantity']";

	public static $stockImageSuccessMessage = 'Stock Amount Image saved';

	public static $firstResultRow = "//div[@id='editcell']//table[1]//tbody/tr[1]";

	public static $selectFirst = "//input[@id='cb0']";

	/**
	 * Function to get the path for Section
	 *
	 * @param   String  $option  Option for the
	 *
	 * @return string
	 */
	public function stockAmount($option)
	{
		$path = "//div[@id='stock_option_chzn']/div/ul/li[text() = '" . $option . "']";

		return $path;
	}
}
