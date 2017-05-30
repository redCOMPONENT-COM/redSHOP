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
	public static $manufactureEmail="//input[@id='manufacturer_email]";

	public static $manufacturerSuccessMessage = 'Manufacturer Detail Saved';

    public static $manufacturerDeleteSuccessMessage = 'Manufacturer Detail Deleted Successfully';

	public static $manufactureCoPySuccess="Manufacturer Detail Copied";
	//	public static $firstResultRow = "//div[@id='editcell']//table[2]//tbody/tr[1]";

    public static $firstResultRow = "//div[@class='table-responsive']/table/tbody/tr/td[2]";

	public static $selectFirst = "//input[@id='cb0']";

	public static $manufacturerStatePath = "//div[@id='editcell']//table[2]//tbody/tr[1]/td[7]/a";

	public static $NoPage = ['id' => "product_per_page"];
    public static $checkAllManufacturer ="//input[@onclick='Joomla.checkAll(this)']";
    public static $manufacturerUnpublishMessage="Manufacturer Detail UnPublished Successfully";

    public static $manufacturerPublishMessage="Manufacturer Detail Published Successfully";

}
