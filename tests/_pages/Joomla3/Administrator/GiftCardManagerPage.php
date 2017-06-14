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
    public static $URL = '/administrator/index.php?option=com_redshop&view=giftcards';

    public static $giftCardName = "//input[@id='jform_giftcard_name']";

    public static $giftCardPrice = "//input[@id='jform_giftcard_price']";

    public static $giftCardValue = "//input[@id='jform_giftcard_value']";

    public static $giftCardValidity = "//input[@id='jform_giftcard_validity']";

    public static $giftCardResultRow = "//table[@id='articleList']/tbody/tr[1]";

    public static $firstResult = "//input[@id='cb0']";

    public static $giftCardState = "//table[@id='articleList']/tbody/tr[1]//td[2]//a";
    public static $errorPath = "//div[@id='system-message-container']/div/div";
}
