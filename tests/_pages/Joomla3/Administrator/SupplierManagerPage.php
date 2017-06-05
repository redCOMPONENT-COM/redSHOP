<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class SupplierManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class SupplierManagerPage
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=suppliers';

    public static $checkAllSupplier = "//input[@onclick='Joomla.checkAll(this)']";

    public static $supplierNameField = ['id' => "jform_name"];

    public static $supplierEmailId = ['id' => "jform_email"];

    public static $supplierSuccessMessage = "Item successfully saved.";


    public static $supplierResultRow = "//table[contains(@class, 'adminlist')]/tbody/tr[1]";

    public static  $searchField = ['id' => 'filter_search'];

    public static $fieldMissing="Field required: Name";

    public static $fieldEmailInvalid="Invalid field:  Supplier Email ";

    public static $supplierResultName="//div[@class='table-responsive']/table/tbody/tr/td[2]";

    public static $supplierStatePath = "//div[@class='table-responsive']/table/tbody/tr/td[4]/a";


}
