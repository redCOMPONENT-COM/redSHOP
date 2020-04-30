<?php
/**
 * @package     redSHOP
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2020 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class VoucherManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4.0
 */
class VoucherManagerPage extends AdminJ3Page
{
	//name page

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $namePageManagement = 'Voucher Management';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URL = '/administrator/index.php?option=com_redshop&view=vouchers';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $URLEdit = '/administrator/index.php?option=com_redshop&task=voucher.edit&id=';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $voucherCode = "#jform_code";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $voucherAmount = "#jform_amount";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $voucherStartDate = "#jform_start_date";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $voucherEndDate = "#jform_end_date";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $voucherLeft = "#jform_voucher_left";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $voucherCheck = "#cb0";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $voucherResultRow = "//table[contains(@class, 'adminlist')]/tbody/tr[1]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $voucherStatePath = "//div[@class='table-responsive']/table/tbody/tr/td[10]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $voucherId = "//div[@class='table-responsive']/tbody/tr[1]/td[12]";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $voucherSearchField = "#filter_search";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $xPathStatus = "//div[@class='table-responsive']/table/tbody/tr/td[10]/a";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $xPathInvalid = "//div[@id='system-message-container']/div/div";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $fillProduct = "#s2id_autogen1";

	/**
	 * @var array
	 * @since 1.4.0
	 */
	public static $waitProduct = ['css' => 'span.select2-match'];

	//message

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageSaveSuccess = "Item saved.";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageError = "Error";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageSuccess = "Message";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageDeletedOneSuccess = "1 item successfully deleted";

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $invalidCode = 'Field required: Code';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $invalidProduct = 'Invalid field:  Voucher ProductManagement';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messagePublishSuccess = '1 item successfully published';

	/**
	 * @var string
	 * @since 1.4.0
	 */
	public static $messageUnpublishSuccess = '1 item successfully unpublished';
}
