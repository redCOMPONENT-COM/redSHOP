<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2012 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class WrapperManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class WrapperManagerJoomla3Page
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=wrapper';

	public static $wrapperCreateSuccessMessage = "Wrapping detail saved";

	public static $firstResultRow = "//div[@id='editcell']//table[1]//tbody/tr[1]";

	public static $selectFirst = "//input[@id='cb0']";

	public static $wrapperStatePath = "//div[@id='editcell']//table[1]//tbody/tr[1]/td[7]/a";

	public static $wrapperDeleteSuccessMessage = "Wrapping detail deleted successfully";

	public static $wrapperName = "//input[@id='wrapper_name']";

	public static $wrapperPrice = "//input[@id='wrapper_price']";

	public static $categoryDropDown = "//div[@id='categoryid_chzn']/ul/li/input";


	/**
	 * Function to get path for Category Drop Down while Creating Wrappers
	 *
	 * @param   String  $categoryName  Name of the Category
	 *
	 * @return string
	 */
	public function category($categoryName)
	{
		$path = "//div[@id='categoryid_chzn']/div/ul/li[contains(text(), '" . $categoryName . "')]";

		return $path;
	}
}
