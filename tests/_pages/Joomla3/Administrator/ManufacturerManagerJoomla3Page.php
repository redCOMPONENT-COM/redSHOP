<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class ManufacturerManagerJoomla3Page
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class ManufacturerManagerJoomla3Page
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=manufacturer';

    public static $detailsTab = "//a[contains(text(), 'Details')]";

    public static $manufacturerName = "//input[@id='manufacturer_name']";

    public static $manufacturerSuccessMessage = 'Manufacturer Detail Saved';

    public static $firstResultRow = "//div[@id='editcell']//table[2]//tbody/tr[1]";

    public static $selectFirst = "//input[@id='cb0']";

    public static $manufacturerStatePath = "//div[@id='editcell']//table[2]//tbody/tr[1]/td[7]/a";
}
