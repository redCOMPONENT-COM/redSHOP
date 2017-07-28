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

    //name page
    public static $namePageManagement = 'Gift Card Management';

    public static $URL = '/administrator/index.php?option=com_redshop&view=giftcards';

    public static $URLNew = '/administrator/index.php?option=com_redshop&view=giftcard&layout=edit';

    public static $URLEdit = '/administrator/index.php?option=com_redshop&view=giftcard&layout=edit&giftcard_id=';

    public static $giftCardName = "//input[@id='jform_giftcard_name']";

    public static $giftCardPrice = "//input[@id='jform_giftcard_price']";

    public static $giftCardValue = "//input[@id='jform_giftcard_value']";

    public static $giftCardValidity = "//input[@id='jform_giftcard_validity']";

    public static $giftCardResultRow = "//table[@id='articleList']/tbody/tr[2]";

    public static $firstResult = "//input[@id='cb0']";

    public static $giftCardState = "//table[@id='articleList']/tbody/tr[1]//td[2]//a";

    public static $errorPath = "//div[@id='system-message-container']/div/div";

    public static $checkAllCart = "//input[@onclick='Joomla.checkAll(this)']";

    public static $getCartStatus = ['xpath' => "//div[@class='table-responsive']/table/tbody/tr/td[2]"];

    public static $errorValid = ['xpath' => "//div[@id='system-message-container']/div/div"];

    public static $xpathMessage=['xpath'=>"//div[@id='system-message-container']/div/div/div"];

    public static $getGiftCard = ['xpath' => "//div[@class='table-responsive']/table/tbody/tr/td[1]"];

    public static $giftCardId =['xpath' => "//div[@class='table-responsive']/table/tbody/tr/td[9]"];

    //button
    public static $newButton = "New";

    public static $saveButton = "Save";

    public static $unpublishButton = "Unpublish";

    public static $publishButton = "Publish";

    public static $saveCloseButton = "Save & Close";

    public static $deleteButton = "Delete";

    public static $editButton = "Edit";

    public static $saveNewButton = "Save & New";

    public static $cancelButton = "Cancel";

    public static $checkInButton = "Check-in";

    public static $closeButton = "Close";


    //selector
    public static $selectorSuccess = '.alert-success';

    public static $selectorError = '.alert-danger';

    public static $selectorNamePage = '.page-title';


    //message
    public static $messageSaveSuccess = "Item saved.";

    public static $messageError = "Error";

    public static $messageSuccess = "Message";

//    public static $messageSuccessPublishAll="";

    public static $messageSuccessUnpublish="items successfully unpublished";

    public static $messageUnpublishSuccess="1 item successfully unpublished";

    public static $messagePublishSuccess="1 item successfully published";

    public static $messageDeleteSuccess = "1 item successfully deleted";

    public static $messageInvalidName = 'Invalid field: Gift Card Name';

    public static $messageInvalidPrice = 'Invalid field:  Gift Card Price ';

    public static $messageInvalidGiftCart = 'Invalid field:  Gift Card Value ';

    public static $messageInvalidCart = 'Invalid field:  Gift Card Validity';
}
