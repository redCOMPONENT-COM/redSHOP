<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class VoucherManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  2.4
 */
class VoucherManagerPage extends AdminJ3Page
{
    //name page

    /**
     * @var string
     */
    public static $namePageManagement = 'Voucher Management';

    /**
     * @var string
     */
    public static $URL = '/administrator/index.php?option=com_redshop&view=vouchers';

    /**
     * @var string
     */
    public static $URLEdit = '/administrator/index.php?option=com_redshop&task=voucher.edit&id=';

    /**
     * @var string
     */
    public static $voucherCode = "#jform_code";

    /**
     * @var string
     */
    public static $voucherAmount = "#jform_amount";

    /**
     * @var string
     */
    public static $voucherStartDate = "#jform_start_date";

    /**
     * @var string
     */
    public static $voucherEndDate = "#jform_end_date";

    /**
     * @var string
     */
    public static $voucherLeft = "#jform_voucher_left";

    /**
     * @var string
     */
    public static $voucherCheck = "#cb0";

    /**
     * @var string
     */
    public static $voucherResultRow = "//table[contains(@class, 'adminlist')]/tbody/tr[1]";

    /**
     * @var string
     */
    public static $voucherStatePath = "//div[@class='table-responsive']/table/tbody/tr/td[10]/a";

    /**
     * @var string
     */
    public static $voucherId = "//div[@class='table-responsive']/tbody/tr[1]/td[12]";

    /**
     * @var string
     */
    public static $voucherSearchField = "#filter_search";

    /**
     * @var string
     */
    public static $xPathStatus = "//div[@class='table-responsive']/table/tbody/tr/td[10]/a";

    /**
     * @var string
     */
    public static $xPathInvalid = "//div[@id='system-message-container']/div/div";

    /**
     * @var string
     */
    public static $fillProduct = "#s2id_autogen1";

    /**
     * @var array
     */
    public static $waitProduct = ['css' => 'span.select2-match'];

    /**
     * @var string
     */
    public static $messageContainer = "#system-message-container";

    /**
     * @var string
     */
    public static $searchField = "#filter_search";

    //button

    /**
     * @var string
     */
    public static $newButton = "New";

    /**
     * @var string
     */
    public static $saveButton = "Save";

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

    /**
     * @var string
     */
    public static $headPageName = "//h1";


    //message

    /**
     * @var string
     */
    public static $messageSaveSuccess = "Item saved.";

    /**
     * @var string
     */
    public static $messageError = "Error";

    /**
     * @var string
     */
    public static $messageSuccess = "Message";

    /**
     * @var string
     */
	public static $messageDeletedOneSuccess = "1 item successfully deleted";

    /**
     * @var string
     */
    public static $invalidCode = 'Field required: Code';

    /**
     * @var string
     */
    public static $invalidProduct = 'Invalid field:  Voucher ProductManagement';

    /**
     * @var string
     */
    public static $messagePublishSuccess = '1 item successfully published';

    /**
     * @var string
     */
    public static $messageUnpublishSuccess = '1 item successfully unpublished';
}
