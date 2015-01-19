<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class GiftCardManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class GiftCardManagerPage
{
	public static $URL = '/administrator/index.php?option=com_redshop&view=giftcard';

	public static $giftCardName = "//input[@id='giftcard_name']";

	public static $giftCardPrice = "//input[@id='giftcard_price']";

	public static $giftCardValue = "//input[@id='giftcard_value']";

	public static $giftCardValidity = "//input[@id='giftcard_validity']";

	public static $giftCardResultRow = "//div[@id='editcell']/table/tbody/tr[1]";

	public static $firstResult = "//input[@id='cb0']";

	public static $giftCardState = "//div[@id='editcell']/table/tbody/tr[1]//td[9]//a";
}
