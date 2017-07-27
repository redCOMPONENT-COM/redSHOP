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
	public static $URL = '/administrator/index.php?option=com_redshop&view=voucher';

	public static $voucherCode = "#voucher_code";

	public static $voucherAmount = "#voucher_amount";

	public static $voucherLeft = "#voucher_left";

	public static $voucherCheck = "#cb0";

	public static $voucherResultRow = "//div[@id='editcell']/div[2]/table/tbody/tr[1]";

	public static $voucherStatePath = "//div[@id='editcell']/div[2]/table/tbody/tr/td[9]/a";

    //div[@class='table-responsive']/table/tbody/tr/td[9]/a

	public static $voucherSearchField = "#filter";
}
