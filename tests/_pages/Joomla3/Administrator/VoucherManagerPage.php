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
class VoucherManagerPage extends AdminJ3Page
{

    //name pag
    public static $namePageManagement = 'Voucher Management';

    public static $URL = '/administrator/index.php?option=com_redshop&view=vouchers';

    public static $URLEdit = '/administrator/index.php?option=com_redshop&task=voucher.edit&id=';

    public static $voucherCode = "#jform_code";

    public static $voucherAmount = "#jform_amount";

    public static $voucherStartDate = "#jform_start_date";

    public static $voucherEndDate = "#jform_end_date";

    public static $voucherLeft = "#jform_voucher_left";

    public static $voucherCheck = "#cb0";

    public static $voucherResultRow = "//div[@class='table-responsive']/table/tbody/tr";

	public static $voucherStatePath = "//table[@id='table-vouchers']/tbody/tr/td[10]/a";


    public static $voucherId = "//div[@class='table-responsive']/tbody/tr[1]/td[12]";

    public static $voucherSearchField = "#filter_search";

    public static $xPathStatus = ['xpath' => "//table[@id='table-vouchers']/tbody/tr/td[10]/a"];

    public static $xPathInvalid = ['xpath' => "//div[@id='system-message-container']/div/div"];

    public static $fillProduct = ['id' => 's2id_autogen1'];

    public static $waitProduct = ['css' => 'span.select2-match'];

    public static $messageContainer = ['id' => 'system-message-container'];

	public static $searchField = ['id' => 'filter_search'];

    //selector
    public static $selectorSuccessHead='.alert alert-success alert-dismissible';

    //message
    public static $messageError = "Error";

    public static $messageSuccess = "Message";

	public static $messageDeletedOneSuccess = "1 item successfully deleted";

    public static $invalidCode = 'Field required: Code';

    public static $invalidProduct = 'Invalid field:  Voucher Products';

    public static $messagePublishSuccess = '1 item successfully published';

    public static $messageUnpublishSuccess = '1 item successfully unpublished';
}
