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
class OrderManagerPage extends AdminJ3Page
{
    public static $URL = '/administrator/index.php?option=com_redshop&view=order';


    public static $userId = "//div[@id='s2id_user_id']/a";

    public static $userSearch = "//input[@id='s2id_autogen1_search']";

    public static $address = ['id' => 'address'];

    public static $zipcode = ['id' => 'zipcode'];

    /**
     * @var string
     */
    public static $fistName = ['id' => 'firstname'];

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

    public static $deleteFirst = ['xpath' => '//input[@id=\'cb0\']'];

    public static $nameXpath = ['xpath' => '//td[4]/a'];
    
    //button
    public static $buttonSavePay = "Save + Pay";

    //selector
    public static $messageSaveSuccess = "Order Status Successfully Saved For Order Number 1";

    public static $messageDeleteSuccess = "Order detail deleted successfully";

    public function returnSearch($userName)
    {
        $path = ['xpath' => "//span[contains(text(), '" . $userName . "')]"];
        return $path;
    }

}
