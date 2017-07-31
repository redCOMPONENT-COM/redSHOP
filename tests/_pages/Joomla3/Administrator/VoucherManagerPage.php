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
 * @since  1.4
 */
class VoucherManagerPage
{

    //name pag
    public static $namePageManagement = 'Voucher Management';

    public static $URL = '/administrator/index.php?option=com_redshop&view=voucher';

    public static $URLEdit = '/administrator/index.php?option=com_redshop&view=voucher_detail&task=edit&cid[]=';

    public static $voucherCode = "#voucher_code";

    public static $voucherAmount = "#voucher_amount";

    public static $voucherStartDate = "#start_date";

    public static $voucherEndDate = "#end_date";

    public static $voucherLeft = "#voucher_left";

    public static $voucherCheck = "#cb0";

    public static $voucherResultRow = "//div[@id='editcell']/div[2]/table/tbody/tr[1]";

    public static $voucherStatePath = "//div[@id='editcell']/div[2]/table/tbody/tr/td[9]/a";

    public static $voucherId = "//div[@id='editcell']/div[2]/table/tbody/tr/td[10]";

    public static $filter = ['id' => 'filter'];
    public static $voucherSearchField = "#filter";

    public static $xPathStatus = ['xpath' => "//div[@class='table-responsive']/table/tbody/tr/td[9]/a"];


    public static $xPathInvalid = ['xpath' => "//div[@id='system-message-container']/div/div"];

    public static $fillProduct = ['id' => 's2id_autogen1'];

    public static $waitProduct = ['css' => 'span.select2-match'];

    public static $messageContainer = ['id' => 'system-message-container'];



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

    public static $headPageName = ['xpath' => "//h1"];


    //message
    public static $messageSaveSuccess = "Item saved.";

    public static $messageError = "Error";

    public static $messageSuccess = "Message";

    public static $messageDeleteSuccess = "1 item successfully deleted";

    public static $invalidCode = 'Invalid field:  Voucher Code:';

    public static $invalidProduct = 'Invalid field:  Voucher Products';

    public static $messageSaveSuccessVocher = 'Voucher details saved';

    public static $messagePublishSuccess = 'Voucher detail published successfully';

    public static $messageUnpublishSuccess = 'Voucher detail unpublished successfully';
}
