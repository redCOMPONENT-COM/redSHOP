<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class QuotationManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class QuotationManagerPage
{

    //page name
    public static $pageManagementName = "User Management";

    public static $URL = '/administrator/index.php?option=com_redshop&view=quotation';


    public static $userId = ['id' => 's2id_user_id'];

    public static $userSearch = ['id' => 's2id_autogen1_search'];

    public static $productId = ['id' => 's2id_product1'];

    public static $productsSearch = ['id' => 's2id_autogen2_search'];

    public static $quanlityFirst = ['id' => 'quantityproduct1'];

    public static $quantityp1 = ['id' => 'quantityp1'];


    public static $quotationId = "//div[@id='editcell']/div[2]/table/tbody/tr[1]/td[3]/a";
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


    //selector

    public static $selectorSuccess = '.alert-success';

    public static $selectorError = '.alert-danger';

    public static $selectorNamePage = '.page-title';


    //message
    public static $messageSaveSuccess = "Quotation detail saved";

    public static $messageDeleteSuccess = "Quotation detail deleted successfully";


    public function xPathSearch($userName)
    {
        $path = ['xpath' => "//span[contains(text(), '" . $userName . "')]"];
        return $path;
    }
}
