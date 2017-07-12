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

    //name page
    public static $namePage = "Supplier Management";

    public static $URL = '/administrator/index.php?option=com_redshop&view=suppliers';

    public static $supplierNameField = ['id' => "jform_name"];

    public static $supplierEmailId = ['id' => "jform_email"];

    public static $supplierSuccessMessage = "Item saved.";


    public static $supplierResultRow = "//table[contains(@class, 'adminlist')]/tbody/tr[1]";

    public static $searchField = ['id' => 'filter_search'];

    public static $fieldMissing = "Field required: Name";

    public static $fieldEmailInvalid = "Invalid field:  Supplier Email ";

    public static $supplierResultName = "//div[@class='table-responsive']/table/tbody/tr/td[2]";

    public static $supplierStatePath = "//div[@class='table-responsive']/table/tbody/tr/td[6]/a";

    public static $headPage = ['xpath' => "//h1"];

    //Selector

    public static $selectorSuccess = ".alert-success";

    public static $selectorMissing = '.alert-danger';

    public static $selectorPageTitle='.page-title';


    //message

    public static $messageDeletedOneSuccess = "1 item successfully deleted";

    public static $messageHead = "Message";


    //Button

    public static $saveCloseButton = "Save & Close";

    public static $saveButton = "Save";

    public static $deleteButton = "Delete";

    public static $newButton = "New";

    public static $saveButon = "Save";

    public static $cancelButton = "Cancel";

    public static $publish = "Publish";

    public static $unpublish = "Unpublish";

    public static $editButton = "Edit";

    public static $resetButton = "Reset";

    public static $checkinButton = "Check-in";

    public static $closeButton = "Close";

}
