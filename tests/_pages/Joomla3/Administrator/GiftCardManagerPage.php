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

    public static $URLNew='/administrator/index.php?option=com_redshop&view=giftcard&layout=edit';

    public static $giftCardName = "//input[@id='jform_giftcard_name']";

    public static $giftCardPrice = "//input[@id='jform_giftcard_price']";

    public static $giftCardValue = "//input[@id='jform_giftcard_value']";

    public static $giftCardValidity = "//input[@id='jform_giftcard_validity']";

    public static $giftCardResultRow = "//table[@id='articleList']/tbody/tr[2]";

    public static $firstResult = "//input[@id='cb0']";

    public static $giftCardState = "//table[@id='articleList']/tbody/tr[1]//td[2]//a";

    public static $errorPath = "//div[@id='system-message-container']/div/div";

    public static $checkAllCart = "//input[@onclick='Joomla.checkAll(this)']";


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

    public static $messageDeleteSuccess = "1 item successfully deleted";
}
