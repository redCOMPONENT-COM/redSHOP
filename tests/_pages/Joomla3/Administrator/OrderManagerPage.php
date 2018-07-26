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
 * @since  2.4
 */
class OrderManagerPage extends AdminJ3Page
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=order';


    public static $userId = "//div[@id='s2id_user_id']/a";

    public static $userSearch = "//input[@id='s2id_autogen1_search']";

    public static $address = "#address";

    public static $zipcode = "#zipcode";

    /**
     * @var string
     */
    public static $fistName = "#firstname";

    public static $city = "#city";

    public static $phone = "#phone";

    public static $close = "#toolbar-cancel";


    public static $filter = "#filter";

    public static $applyUser = "#toolbar-apply";


    public static $productId = "#s2id_product1";

    public static $productsSearch = "#s2id_autogen2_search";

    public static $quanlityFirst = "#quantityproduct1";

    public static $quantityp1 = "#quantity";

    public static $nameProductSuccess = "#order_product_detail_3";

    public static $statusOrder = "#s2id_status";

    public static $statusSearch = "#s2id_autogen2_search";

    public static $statusPaymentStatus = "#s2id_order_paymentstatus";

    public static $statusPaymentSearch = "#s2id_autogen3_search";

    public static $nameButtonStatus = ['name' => 'order_status'];

    public static $deleteFirst = "//input[@id=\'cb0\']";

    public static $nameXpath = "//td[4]/a'";
    
    //button
    public static $buttonSavePay = "Save + Pay";

    //selector
    public static $messageSaveSuccess = "Order Status Successfully Saved For Order Number 1";

    public static $messageDeleteSuccess = "Order detail deleted successfully";

    public function returnSearch($userName)
    {
        $path = "//span[contains(text(), '" . $userName . "')]";
        return $path;
    }

}
