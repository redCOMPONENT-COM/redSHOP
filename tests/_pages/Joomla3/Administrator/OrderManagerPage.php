<?php
/**
 * @package     RedShop
 * @subpackage  Page Class
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * Class OrderManagerPage
 *
 * @link   http://codeception.com/docs/07-AdvancedUsage#PageObjects
 *
 * @since  1.4
 */
class OrderManagerPage
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=order';


    public static $userId = "//div[@id='s2id_user_id']/a";

    public static $userSearch = ".//*[@id='s2id_autogen1_search']";

    public static $address = ['id' => 'address'];

    public static $zipcode = ['id' => 'zipcode'];

    public static $city = ['id' => 'city'];

    public static $phone = ['id' => 'phone'];

    public static $close = ['id' => 'toolbar-cancel'];


    public static $filter = ['id' => 'filter'];

    public static $applyUser = ['id' => 'toolbar-apply'];


    public static $productId = ['id' => 's2id_product1'];

    public static $productsSearch = ['id' => 's2id_autogen2_search'];

    public static $quanlityFirst = ['id' => 'quantityproduct1'];

    public static $quantityp1 = ['id' => 'quantity'];

    public static $nameProductSuccess = ['id' => 'order_product_detail_3'];

    public static $statusOrder = ['id' => 's2id_status'];

    public static $statusSearch = ['id' => 's2id_autogen2_search'];

    public static $statusPaymentStatus = ['id' => 's2id_order_paymentstatus'];

    public static $statusPaymentSearch = ['id' => 's2id_autogen3_search'];

    public static $nameButtonStatus = ['name' => 'order_status'];

    public static $nameUser = ['xpath' => "//div[@class='table-responsive']/table/tbody/tr/td[4]/a"];

    public static $saveSuccess = "//div[@id='system-message']/div[2]/div/p";


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

    public static $savePay = "Save + Pay";

    public static $closeButton="Close";

    //selector

    public static $selectorSuccess = '.alert-success';

    public static $selectorError = '.alert-danger';

    public static $selectorNamePage = '.page-title';


    public static $messageSaveSuccess = "Order Status Successfully Saved For Order Number 1";

    public static $messageDeleteSuccess = "Order detail deleted successfully";

    public function returnSearch($userName)
    {
        $path = ['xpath' => "//span[contains(text(), '" . $userName . "')]"];
        return $path;
    }

}
