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
class GiftCardManagerPage extends AdminJ3Page
{

    //name page
    public static $namePageManagement = 'Gift Card Management';

    public static $URL = '/administrator/index.php?option=com_redshop&view=giftcards';

    public static $giftCardCancelButton = ['xpath' => "//button[@onclick=\"Joomla.submitbutton('giftcard.cancel');\"]"];

    public static $giftCardName = "//input[@id='jform_giftcard_name']";

    public static $giftCardPrice = "//input[@id='jform_giftcard_price']";

    public static $giftCardValue = "//input[@id='jform_giftcard_value']";

    public static $giftCardValidity = "//input[@id='jform_giftcard_validity']";

    public static $giftCardResultRow = "//table[@id='articleList']/tbody/tr[1]";

    public static $firstResult = "//input[@id='cb0']";

    public static $getCartStatus = ['xpath'=>"//div[@class='btn-group']/a"];

    public static $errorValid = ['xpath' => "//div[@id='system-message-container']/div/div"];

    public static $getGiftCard = ['xpath' => "//input[@id='cb0']"];

    //message
    public static $messageSuccessUnpublish="items successfully unpublished";

    public static $messageUnpublishSuccess="1 item successfully unpublished";

    public static $messagePublishSuccess="1 item successfully published";

    public static $messageDeleteSuccess = "1 item successfully deleted";

    public static $messageInvalidName = 'Invalid field: Gift Card Name';

    public static $messageInvalidPrice = 'Invalid field:  Gift Card Price ';

    public static $messageInvalidGiftCart = 'Invalid field:  Gift Card Value ';

    public static $messageInvalidCart = 'Invalid field:  Gift Card Validity';
}
