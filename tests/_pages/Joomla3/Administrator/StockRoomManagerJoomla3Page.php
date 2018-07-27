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
 * @since  2.4
 */

class StockRoomManagerJoomla3Page
{
    /**
     * @var string
     */
    public static $URL = '/administrator/index.php?option=com_redshop&view=stockroom';

    /**
     * @var string
     */
    public static $stockRoomName = "//input[@id='stockroom_name']";

    /**
     * @var string
     */
    public static $minimumStockAmount = "//input[@id='min_stock_amount']";

    /**
     * @var string
     */
    public static $stockRoomSuccessMessage = 'Stockroom Detail Saved';

    /**
     * @var string
     */
    public static $firstResultRow = "//div[@id='editcell']/table/tbody/tr[1]";

    /**
     * @var string
     */
    public static $selectFirst = "//input[@id='cb0']";

    /**
     * @var string
     */
    public static $stockRoomStatePath = "//div[@id='editcell']//table//tbody/tr[1]/td[6]/a";

    //button

    /**
     * @var string
     */
    public static $newButton = "New";

    /**
     * @var array
     */
    public static $saveButton = ['xpath' => "//button[@onclick=\"Joomla.submitbutton('apply');\"]"];

    /**
     * @var string
     */
    public static $unpublishButton = "Unpublish";

    /**
     * @var string
     */
    public static $publishButton = "Publish";

    /**
     * @var string
     */
    public static $saveCloseButton = "Save & Close";

    /**
     * @var string
     */
    public static $deleteButton = "Delete";

    /**
     * @var string
     */
    public static $editButton = "Edit";

    /**
     * @var string
     */
    public static $saveNewButton = "Save & New";

    /**
     * @var string
     */
    public static $cancelButton = "Cancel";

    /**
     * @var string
     */
    public static $checkInButton = "Check-in";

    /**
     * @var string
     */
    public static $closeButton = "Close";

    //selector

    /**
     * @var string
     */
    public static $selectorSuccess = '.alert-success';

    /**
     * @var string
     */
    public static $selectorError = '.alert-danger';

    /**
     * @var string
     */
    public static $selectorNamePage = '.page-title';
}
